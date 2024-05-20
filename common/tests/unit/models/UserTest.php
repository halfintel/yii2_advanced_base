<?php

namespace unit\models;

use Codeception\Test\Unit;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\web\ServerErrorHttpException;


class UserTest extends Unit
{
    public function testCreate(): void
    {
        $model = new User();

        $model->username = 'aaa';

        $model->email = 'a@a.a';

        $model->password = 'aaa';

        verify($model->validate())->true();

        verify($model->save())->true();
    }

    /**
     * @throws Exception
     */
    public function testFindOne(): void
    {
        $model = User::findByUsername('admindev');

        $model->generateEmailVerificationToken();

        $model->generatePasswordResetToken();
        
        verify(User::isPasswordResetTokenValid($model->password_reset_token))->true();

        $model->removePasswordResetToken();

        verify($model->validatePassword('admindev'))->true();

        $authKey = $model->getAuthKey();

        verify($model->validateAuthKey($authKey))->true();

        $id = $model->getId();

        verify($id === 1)->true();


        $model2 = User::findIdentity(1);

        verify($model2->id === 1)->true();
    }

    public function testDelete(): void
    {
        User::deleteUser(1);

        $model = User::findOne(['id' => 1]);

        verify($model->status === User::STATUS_DELETED)->true();
    }

    public static function createModels()
    {
        $time = time();
        $password_hash = Yii::$app->getSecurity()->generatePasswordHash('admindev');
        $auth_key = Yii::$app->security->generateRandomString();

        Yii::$app->db->createCommand()->insert(User::tableName(), [
            'username' => 'admindev',
            'email' => 'test@example.com',
            'password_hash' => $password_hash,
            'auth_key' => $auth_key,
            'created_at' => $time,
            'updated_at' => $time
        ]);
    }
}
