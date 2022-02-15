## Логер использования API

> composer require brezgalov/yii2-api-logger

Из коробки доступно логирование через бд. 
Для хранения логов нужно применить миграцию

    php yii migrate --migrationPath="@app/vendor/brezgalov/yii2-api-logger"

Подключение логера к приложению

    function (\yii\web\Application $app) {
            $app->attachBehavior('apiLogger', [
                'class' => ApiLoggerBehavior::class,
                'logStorage' => [
                    'class' => DbLogStorage::class,
                    'dbComponent' => $app->get('api_logs_db')
                ],
            ]);
        }