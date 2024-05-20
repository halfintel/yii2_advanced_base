<?php

use kartik\date\DatePicker;
use yii\base\Model;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/** @var Model $searchModel */
/** @var string $fieldNameFrom */
/** @var string $fieldNameTo */
/** @var bool|null $showButtonAll */
/** @var bool|null $isModal */
/** @var string|null $formId */
/** @var string|null $formClass */

$this->registerJsFile('/scripts/main/filter_by_date.js', ['position' => \yii\web\View::POS_END]);

if (!isset($showButtonAll)) {
    $showButtonAll = false;
}

if (empty($formId)) {
    $formId = 'filter_by_date_form';
}

if (!empty($isModal)) {
    $this->registerJsFile('/scripts/main/filter_by_date_modal.js', ['position' => \yii\web\View::POS_END]);
}

if (!isset($formClass)) {
    $formClass = 'col-sm-8 col-md-6 col-xl-5';
}
?>

<?php
$form = ActiveForm::begin([
    'action' => \yii\helpers\Url::current(),
    'method' => 'get',
    'options' => [
        'id' => $formId,
        'class' => 'filter_by_date_form ' . $formClass
    ]
]);
?>
<div class="form-group">
    <?= Html::button('Last day', ['class' => 'btn btn-primary filter_by_date_last_day']) ?>
    <?= Html::button('Last week', ['class' => 'btn btn-primary filter_by_date_last_week']) ?>
    <?= Html::button('Last month', ['class' => 'btn btn-primary filter_by_date_last_month']) ?>

    <?php if ($showButtonAll): ?>
        <?= Html::button('All', ['class' => 'btn btn-primary filter_by_date_all']) ?>
    <?php endif; ?>
</div>

<div class="form-group d-flex">
    <?= DatePicker::widget([
        'model' => $searchModel,
        'attribute' => $fieldNameFrom,
        'attribute2' => $fieldNameTo,
        'options' => ['placeholder' => 'Start date'],
        'options2' => ['placeholder' => 'End date'],
        'type' => DatePicker::TYPE_RANGE,
        'form' => $form,
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
        ]
    ]) ?>


    <?= $this->render('/main/_button_with_loader', [
        'type' => 'submit',
        'class' => 'filter_by_date_search',
        'text' => 'Search',
    ]) ?>
</div>
<?php ActiveForm::end(); ?>


<style>
    .filter_by_date_last_month,
    .filter_by_date_last_week,
    .filter_by_date_all,
    .filter_by_date_search {
        margin-left: 15px;
    }

    .filter_by_date_form div.form-control {
        padding: 0;
        border: 0;
    }
</style>
