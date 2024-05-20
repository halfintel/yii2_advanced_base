<?php

namespace backend\components;


use common\models\User;
use Yii;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\db\ActiveRecord;
use yii\grid\ActionColumn;
use yii\helpers\Url;

class ViewHelper
{
    public static function getIndexColumnButtons(bool $showUpdate = true, bool $showDelete = true, bool $showCopy = false): array
    {
        $isRightCreate = true;

        $isRightRead = true;

        $isRightUpdate = true;

        $isRightDelete = true;

        return [
            'class' => ActionColumn::className(),
            'template' => '{view} {update} {delete} {copy}',
            'visibleButtons' => [
                'view' => function ($model, $key, $index) use ($isRightRead) {
                    return $isRightRead;
                },
                'update' => function ($model, $key, $index) use ($showUpdate, $isRightUpdate) {
                    return $showUpdate && $isRightUpdate;
                },
                'delete' => function ($model, $key, $index) use ($showDelete, $isRightDelete) {
                    return $showDelete && $isRightDelete;
                },

            ],
            'buttons' => [
                'copy' => function ($url, $model, $key) use ($showCopy, $isRightCreate) {
                    if (!$showCopy || !$isRightCreate) {
                        return '';
                    }
                    
                    return Html::a('<i class="fa-solid fa-copy"></i>', $url, [
                        'title' => Yii::t('yii', 'Copy'),
                    ]);
                },
            ],
            'urlCreator' => function ($action, ActiveRecord|array $model, $key, $index, $column) {

                if (is_array($model)) {

                    $id = $model['id'];

                } else {
                    $id = $model->id;
                }

                return Url::toRoute([$action, 'id' => $id]);
            }
        ];
    }

    public static function getSuccessFailText(bool $isSuccess, string $textIfSuccess = 'yes', string $textIfFail = 'no'): string
    {
        if ($isSuccess) {
            $class = 'text-success';
            $text = $textIfSuccess;

        } else {
            $class = 'text-error';
            $text = $textIfFail;
        }

        return '<span class="' . $class . '">' . $text . '</span>';
    }

    public static function getActiveText(int $active): string
    {
        if ($active === 0) {
            return 'Pause (0)';

        } else {
            return 'Active (1)';
        }
    }

    public static function getActiveColumn(string $columnName = 'active'): array
    {
        return [
            'attribute' => $columnName,
            'format' => 'html',
            'value' => function ($data) use ($columnName) {

                if ($data['active'] === 1) {
                    $class = 'text-success';

                } else {
                    $class = 'text-error';
                }

                return '<span class="' . $class . '">' . ViewHelper::getActiveText($data[$columnName]) . '</span>';
            },
            'options' => ['class' => 'w100'],
        ];
    }

    public static function getStatusColumn(Model $model, string $columnName = 'status'): array
    {
        return [
            'attribute' => $columnName,
            'value' => function ($data) use ($model, $columnName) {
                $column = $data[$columnName];
                $statusText = $model::STATUSES[$column] ?? 'undefined';

                return $statusText . ' (' . $column . ')';
            },
        ];
    }

    /**
     * @param string $dir = 'sql_requests'
     * @param string $columnName = 'filename'
     * @return array
     */
    public static function getFileNameColumn(string $dir, string $columnName): array
    {
        return [
            'attribute' => $columnName,
            'format' => 'html',
            'value' => function ($data) use ($dir, $columnName) {
                $link = Yii::$app->params['backend_url'] . '/' . $dir . '/' . $data[$columnName];

                return '<a href="' . $link . '">' . $link . '</a>';
            },
            'options' => ['class' => 'w100'],
        ];
    }

    public static function getErrorHtml(string $text = 'Please, try again'): string
    {
        return '<div class="alert alert-danger">' . $text . '</div>';
    }
}

