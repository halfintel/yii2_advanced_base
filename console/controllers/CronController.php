<?php

namespace console\controllers;


use common\models\User;
use yii\console\Controller;


class CronController extends Controller
{
    /**
     * Usage: docker exec -i study_cards-frontend-1 php yii cron/create-admin
     */
    public function actionCreateAdmin(): void
    {
        $model = new User();

        $model->email = 'i777ff@gmail.com';

        $model->username = 'admindev';

        $model->setPassword('admindev');

        $model->generateAuthKey();

        $model->status = User::STATUS_ACTIVE;

        if ($model->save()){
            echo 'Ok';
        } else {
            echo json_encode($model->getErrors());
        }
    }
}

