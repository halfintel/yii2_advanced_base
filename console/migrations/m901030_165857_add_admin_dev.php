<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m901030_165857_add_admin_dev
 */
class m901030_165857_add_admin_dev extends Migration
{
    // @codingStandardsIgnoreEnd

    /**
     * Runs for the migate/up command
     *
     * @return void
     * @throws \yii\base\Exception
     */
    public function safeUp(): void
    {
        $time = time();
        $password_hash = Yii::$app->getSecurity()->generatePasswordHash('admindev');
        $auth_key = Yii::$app->security->generateRandomString();
        $table = User::tableName();

        $this->upsert($table, [
            'username' => 'admindev',
            'email' => 'test@example.com',
            'password_hash' => $password_hash,
            'auth_key' => $auth_key,
            'created_at' => $time,
            'updated_at' => $time
        ]);
    }

    /**
     * Runs for the migate/down command
     *
     * @return void
     */
    public function safeDown(): void
    {
        $this->delete(User::tableName(), ['username' => 'admindev']);
    }
}
