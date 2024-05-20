<?php

/** @var string $type */
/** @var string $class */
/** @var string $text */

/**
 * Usage:
 * add to php:
 * <?= $this->render('/main/_button_with_loader', [
 * 'type' => 'submit',
 * 'class' => 'your_class',
 * 'text' => 'Text in button',
 * ]) ?>
 *
 * when you want to show loader:
 * $('.btnLoader.your_class').addClass('btnLoader-showLoader')
 * $('.btnLoader.your_class').attr('disabled', true)
 */
?>

<button type="<?= $type ?>" class="btn btn-success <?= $class ?> btnLoader">
    <span class="btnLoader__text"><?= $text ?></span>
    <span class="btnLoader__loader"></span>
</button>

<style>
    .btnLoader {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btnLoader .btnLoader__text {
        display: block;
    }

    .btnLoader .btnLoader__loader {
        display: none;
    }

    .btnLoader.btnLoader-showLoader .btnLoader__text {
        display: none;
    }

    .btnLoader.btnLoader-showLoader .btnLoader__loader {
        display: inline-block;
    }


    .btnLoader__loader {
        width: 20px;
        height: 20px;
        border: 3px solid #FFF;
        border-bottom-color: #007bff;
        border-radius: 50%;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
