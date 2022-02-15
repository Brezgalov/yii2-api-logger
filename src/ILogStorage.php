<?php

namespace Brezgalov\ApiLogger;

interface ILogStorage
{
    /**
     * @param ApiLogDto $logDto
     * @return mixed
     * @throws \Exception
     */
    public function storeLog(ApiLogDto $logDto);

    /**
     * @param $id
     * @param ApiLogDto $logDto
     * @return bool
     * @throws \yii\db\Exception
     */
    public function updateLog($id, ApiLogDto $logDto);
}