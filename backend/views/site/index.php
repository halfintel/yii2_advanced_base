<?php
$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
    <?php echo \common\components\DateHelper::convertDateTime() ?>
</div>