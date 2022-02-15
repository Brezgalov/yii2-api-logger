<?php

namespace Brezgalov\ApiLogger;

class ApiLogDto
{
    public $app_name;
    public $activity_id;
    public $referer;
    public $url;
    public $input;
    public $input_at;
    public $input_headers;
    public $response_code;
    public $response;
    public $response_at;
    public $controller;
    public $action;

    /**
     * @return array
     */
    public function getFields()
    {
        $fields = [
            'app_name',
            'activity_id',
            'referer',
            'url',
            'input',
            'input_at',
            'input_headers',
            'response_code',
            'response',
            'response_at',
            'controller',
            'action',
        ];

        $res = [];

        foreach ($fields as $field) {
            if (!is_null($this->{$field})) {
                $res[$field] = $this->{$field};
            }
        }

        return $res;
    }
}