<?php

namespace console\controllers;


use common\models\User;
use yii\console\Controller;


class CronController extends Controller
{
    /**
     * Usage: docker exec -i study_cards-frontend-1 php yii cron/test
     */
    public function actionTest(): void
    {
        echo 'test';
    }
}

