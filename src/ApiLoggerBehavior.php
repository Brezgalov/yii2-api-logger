<?php

namespace Brezgalov\ApiLogger;

use app\dao\models\ServiceLogsDao;
use yii\base\Application;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ApiLoggerBehavior extends Behavior
{
    const DEFAULT_APP_NAME = 'default';

    const TEXT_MAX_LENGTH = 65535;

    /**
     * @var int
     */
    public $responseLogMaxLength = self::TEXT_MAX_LENGTH;

    /**
     * @var ILogStorage
     */
    public $logStorage;

    /**
     * @var string
     */
    public $appName;

    /**
     * @var string
     */
    public $activityIdField = 'activity_id';

    /**
     * log only this urls
     * @var array
     */
    public $onlyUrls = [];

    /**
     * log only NOT this urls
     * @var array
     */
    public $exceptUrls = [];

    /**
     * @var int
     */
    protected $logId;

    /**
     * ServiceLoggerBehavior constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = [])
    {
        $this->appName = ArrayHelper::getValue($_ENV, 'APP_NAME', static::DEFAULT_APP_NAME);

        parent::__construct($config);

        if (is_array($this->logStorage) || is_string($this->logStorage)) {
            $this->logStorage = \Yii::createObject($this->logStorage);
        }
    }

    /**
     * @return string[]
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
            Application::EVENT_AFTER_REQUEST => 'afterRequest'
        ];
    }

    public function beforeRequest()
    {
        $urlParts = explode('?', \Yii::$app->request->getUrl());
        $baseUrl = array_shift($urlParts);

        if ($this->onlyUrls && !in_array($baseUrl, $this->onlyUrls)) {
            return;
        }

        if ($this->exceptUrls && in_array($baseUrl, $this->exceptUrls)) {
            return;
        }

        if (empty($this->logStorage) || !($this->logStorage instanceof ILogStorage)) {
            throw new InvalidConfigException('logStorage misconfigured');
        }

        $input = array_merge(\Yii::$app->request->getQueryParams(), \Yii::$app->request->getBodyParams());

        $activityId = ArrayHelper::getValue($input, $this->activityIdField);

        $logDto = new ApiLogDto();
        $logDto->app_name = $this->appName;
        $logDto->referer = \Yii::$app->request->getReferrer();
        $logDto->url = $baseUrl;
        $logDto->activity_id = $activityId;
        $logDto->input = json_encode($input);
        $logDto->input_at = date('Y-m-d H:i:s');
        $logDto->input_headers = json_encode(\Yii::$app->request->getHeaders()->toArray());

        $this->logId = $this->logStorage->storeLog($logDto);
    }

    public function afterRequest()
    {
        if (empty($this->logId)) {
            return;
        }

        if (empty($this->logStorage) || !($this->logStorage instanceof ILogStorage)) {
            throw new InvalidConfigException('logStorage misconfigured');
        }

        $logDto = new ApiLogDto();

        $controller = \Yii::$app->controller;
        $action = $controller && $controller->action ? $controller->action : null;

        if ($controller) {
            $logDto->controller = $controller->id;
        }

        if ($action) {
            $logDto->action = $action->id;
        }

        $data = \Yii::$app->response->data;

        if (is_array($data)) {
            $data = json_encode($data);
        } elseif (!is_string($data)) {
            $data = (string)$data;
        }

        if (strlen($data) > $this->responseLogMaxLength) {
            $data = mb_substr($data, 0, $this->responseLogMaxLength - 3) . '...';
        }

        $logDto->response_code = (string)\Yii::$app->response->statusCode;
        $logDto->response = $data;
        $logDto->response_at = date('Y-m-d H:i:s');

        $this->logStorage->updateLog($this->logId, $logDto);
    }
}