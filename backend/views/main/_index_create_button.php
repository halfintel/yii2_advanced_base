<?php


use common\models\User;
use yii\bootstrap5\Html;


/** @var User|null $user */
/** @var string $buttonTitle */
/** @var bool|null $isNeedSelectedBrand */
/** @var bool|null $isDisabled */


if (!empty($isDisabled)) {
    return;
}

if (empty($user)) {
    $userId = Yii::$app->user->getId();

    $user = User::findOne(['id' => $userId]);
}

?>


<p>
    <?php echo Html::a('Create ' . $buttonTitle, ['create'], ['class' => 'btn btn-success']); ?>
</p>
