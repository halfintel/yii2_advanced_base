<?php

namespace common\components;


use backend\models\ReportCampaignSearch;
use common\models\Click;
use common\models\Request;
use common\models\Search;
use common\models\User;
use common\models\Utm;
use Yii;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;


class TestHelper
{
    public static function getDebugBacktrace(): array
    {
        $result = [];

        $debug = debug_backtrace();

        foreach ($debug as $item) {
            if (
                isset($item['file'])
                && isset($item['line'])
                && isset($item['function'])
            ) {
                $result[] = $item['file'] . ':' . $item['line'] . ' (' . $item['function'] . ')';

            } else {
                if (isset($item['object'])) {
                    unset($item['object']);
                }

                $result[] = $item;
            }
        }

        return $result;
    }

    public static function getMemoryUsageInMB(): float|int
    {
        $memoryUsageBytes = memory_get_usage();

        return $memoryUsageBytes / 1024 / 1024;
    }

    public static function login(): void
    {
        $user = User::findByUsername('admindev');

        \Yii::$app->user->login($user);
    }
}

