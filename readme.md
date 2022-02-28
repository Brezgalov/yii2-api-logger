## Логер использования API

> composer require brezgalov/yii2-api-logger

Из коробки доступно логирование через бд. 
Для хранения логов нужно применить миграцию

    php yii migrate --migrationPath="vendor/brezgalov/yii2-api-logger/migrations"

Подключение логера к приложению

    'bootstrap' => [
        function (\yii\web\Application $app) {
            $app->attachBehavior('apiLogger', [
                'class' => ApiLoggerBehavior::class,
                'logStorage' => [
                    'class' => DbLogStorage::class,
                    'dbComponent' => $app->get('api_logs_db')
                ],
            ]);
        },
        ...
    ],

Логер использует следующие ENV переменные

    APP_NAME