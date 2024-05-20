<?php

use backend\components\ViewHelper;
use common\models\User;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\UserSearch $searchModel */

$this->title = 'Users';

$this->params['breadcrumbs'][] = $this->title;

$userId = Yii::$app->user->getId();

$user = User::findOne(['id' => $userId]);
?>
<div class="user-index">

    <div class="col-12 d-flex align-items-center">

        <?= $this->render('/main/_index_create_button', [
            'user' => $user,
            'buttonTitle' => 'User',
            'isNeedSelectedBrand' => false,
        ]) ?>

    </div>

    <?= $this->render('/main/_filter_by_date', [
        'searchModel' => $searchModel,
        'fieldNameFrom' => 'date_from',
        'fieldNameTo' => 'date_to',
        'showButtonAll' => true,
    ]) ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            ['label' => 'Status', 'attribute' => 'status_text'],
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            ViewHelper::getIndexColumnButtons(),
        ],
    ]); ?>


</div>
