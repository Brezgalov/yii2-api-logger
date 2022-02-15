<?php

namespace Brezgalov\ApiLogger;

use yii\base\Component;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

class DbLogStorage extends Component implements ILogStorage
{
    /**
     * @var Connection
     */
    public $dbComponent;

    /**
     * @var string
     */
    public $table = 'api_logs';

    /**
     * DbStorage constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if (empty($this->dbComponent)) {
            $this->dbComponent = \Yii::$app->db;
        }
    }

    /**
     * @param ApiLogDto $logDto
     * @return mixed
     * @throws \Exception
     */
    public function storeLog(ApiLogDto $logDto)
    {
        return ArrayHelper::getValue(
            $this->dbComponent->schema->insert($this->table, $logDto->getFields()),
            'id'
        );
    }

    /**
     * @param $id
     * @param ApiLogDto $logDto
     * @return bool
     * @throws \yii\db\Exception
     */
    public function updateLog($id, ApiLogDto $logDto)
    {
        return (bool)$this->dbComponent->createCommand()->update($this->table, $logDto->getFields(), ['id' => $id])->execute();
    }
}