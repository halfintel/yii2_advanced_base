<?php

/** @var Model $searchModel */
/** @var string $fieldNameFrom */
/** @var string $fieldNameTo */

/** @var bool|null $showButtonAll */

use yii\base\Model;

if (!isset($showButtonAll)) {
    $showButtonAll = false;
}
?>

<?= $this->render('/main/_filter_by_date-part', [
    'searchModel' => $searchModel,
    'fieldNameFrom' => $fieldNameFrom,
    'fieldNameTo' => $fieldNameTo,
    'showButtonAll' => $showButtonAll,
    'isModal' => false,
]) ?>
