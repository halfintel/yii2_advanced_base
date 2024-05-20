<?php

use yii\helpers\Html;

?>
<div class="row justify-content-center">
    <div class="col-10 col-md-7 col-lg-5 col-xl-4">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

                <?php echo $form->field($model, 'username', [
                    'options' => ['class' => 'form-group has-feedback'],
                    'inputTemplate' => '{input}',
                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                    'wrapperOptions' => ['class' => 'input-group mb-3']
                ])
                    ->label(false)
                    ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

                <?php echo $form->field($model, 'password', [
                    'options' => ['class' => 'form-group has-feedback'],
                    'inputTemplate' => '{input}',
                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                    'wrapperOptions' => ['class' => 'input-group mb-3']
                ])
                    ->label(false)
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

                <div class="row">
                    <div class="col-12">
                        <?php echo Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                </div>

                <?php \yii\bootstrap4\ActiveForm::end(); ?>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>