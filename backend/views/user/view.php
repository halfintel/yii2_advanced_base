<?php

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <?= $this->render('/main/_view_update_delete_buttons', [
        'model' => $model,
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            ['label' => 'Status', 'value' => $model->status_text],
            'created_at:datetime',
            'updated_at:datetime'
        ],
    ]) ?>

</div>
