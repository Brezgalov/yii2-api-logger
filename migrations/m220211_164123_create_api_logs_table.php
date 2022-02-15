<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service_logs}}`.
 */
class m220211_164123_create_api_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%api_logs}}', [
            'id' => $this->primaryKey(),
            'app_name' => $this->string(),
            'activity_id' => $this->string(),
            'referer' => $this->string(),
            'url' => $this->string()->notNull(),
            'input' => $this->text(),
            'input_at' => $this->dateTime(),
            'input_headers' => $this->text(),
            'response_code' => $this->string(),
            'response' => $this->text(),
            'response_at' => $this->dateTime(),
            'controller' => $this->string(),
            'action' => $this->string(),
        ]);

        $this->createIndex(
            'api_logs_IDX_app_name',
            '{{%api_logs}}',
            'app_name'
        );

        $this->createIndex(
            'api_logs_IDX_activity_id',
            '{{%api_logs}}',
            'activity_id'
        );

        $this->createIndex(
            'api_logs_IDX_referer',
            '{{%api_logs}}',
            'referer'
        );

        $this->createIndex(
            'api_logs_IDX_url',
            '{{%api_logs}}',
            'url'
        );

        $this->createIndex(
            'api_logs_IDX_response_code',
            '{{%api_logs}}',
            'response_code'
        );

        $this->createIndex(
            'api_logs_IDX_controller',
            '{{%api_logs}}',
            'controller'
        );

        $this->createIndex(
            'api_logs_IDX_action',
            '{{%api_logs}}',
            'action'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%api_logs}}');
    }
}
