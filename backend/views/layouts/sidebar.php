<?php

use common\models\User;

/** @var string $assetDir */

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/site/index" class="brand-link">
        <img src="<?php echo $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">TheWay</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo Yii::$app->user->identity->username; ?></a>
            </div>
        </div>

        <nav class="mt-2">
            <?php
            $userId = Yii::$app->user->getId();

            $user = User::findOne(['id' => $userId]);

            $items = [];

            $items[] = ['label' => 'Users', 'url' => ['user/index'], 'icon' => 'user'];


            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => $items,
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>