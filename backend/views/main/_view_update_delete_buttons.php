<?php

use common\models\User;
use yii\bootstrap5\Html;
use yii\db\ActiveRecord;


/** @var ActiveRecord $model */
/** @var bool|null $isDisabled */
/** @var bool|null $isDisabledDelete */


if (!empty($isDisabled)) {
    return;
}


$userId = Yii::$app->user->getId();

$user = User::findOne(['id' => $userId]);


if (!empty($isDisabledDelete)) {
    $isRightDelete = false;
    
} else {
    $isRightDelete = true;
}
?>


<p>
    <?php
        echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
    ?>
    <?php
    if ($isRightDelete) {
        echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]);
    }
    ?>
</p>
