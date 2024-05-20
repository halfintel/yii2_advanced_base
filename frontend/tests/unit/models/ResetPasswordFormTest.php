<?php

namespace frontend\tests\unit\models;

use common\fixtures\UserFixture;
use frontend\models\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    public function testResetWrongToken()
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function () {
            new ResetPasswordForm('');
        });

        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function () {
            new ResetPasswordForm('notexistingtoken_1391882543');
        });
    }
}
