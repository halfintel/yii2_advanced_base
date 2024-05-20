<?php

namespace common\components;


use yii\base\Model;
use yii\web\ServerErrorHttpException;

class RuleHelper
{
    public const RULES_CUSTOM_MESSAGES = [
        'required' => '{attribute} is required',
        'incorrect' => '{attribute} is incorrect',
        'integer' => '{attribute} must be an integer',
        'float' => '{attribute} must be a float',
        'string' => '{attribute} must be a string',
        'json_string' => '{attribute} must be a json string',
        'in_range' => '{attribute} must be in range',
        'not_unique' => '{attribute} is not unique',
        'date' => '{attribute} is not date',
        'email' => '{attribute} is incorrect',
        'email_phone_not_unique' => 'Email + phone not unique',
    ];

    /**
     * @param string $field - name of Request field
     * @throws ServerErrorHttpException
     */
    public static function getErrorMessage(Model $model, string $field, string $rulesCustomMessage): string
    {
        if (!isset(self::RULES_CUSTOM_MESSAGES[$rulesCustomMessage])) {
            throw new ServerErrorHttpException('Something went wrong ' . $rulesCustomMessage);
        }

        $message = self::RULES_CUSTOM_MESSAGES[$rulesCustomMessage];

        return str_replace('{attribute}', $model->getAttributeLabel($field), $message);
    }

    /**
     * @param string $fieldName
     * @param string $rulesCustomMessage
     * @return string
     * @throws ServerErrorHttpException
     */
    public static function getErrorMessageWithoutModel(string $fieldName, string $rulesCustomMessage): string
    {
        if (!isset(self::RULES_CUSTOM_MESSAGES[$rulesCustomMessage])) {
            throw new ServerErrorHttpException('Something went wrong');
        }

        $message = self::RULES_CUSTOM_MESSAGES[$rulesCustomMessage];

        return str_replace('{attribute}', $fieldName, $message);
    }


    /**
     * @throws ServerErrorHttpException
     */
    public static function required(Model $model, string $field): array
    {
        return [$field, 'required', 'message' => RuleHelper::getErrorMessage($model, $field, 'required')];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function integer(Model $model, string $field, int|null $min = null, int|null $max = null): array
    {
        $data = [$field, 'integer', 'message' => RuleHelper::getErrorMessage($model, $field, 'integer')];

        if (!is_null($min)) {
            $data['min'] = $min;
        }

        if (!is_null($max)) {
            $data['max'] = $max;
        }

        return $data;
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function float(Model $model, string $field): array
    {
        return [$field, 'number', 'message' => RuleHelper::getErrorMessage($model, $field, 'float')];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function string(Model $model, string $field, int $max): array
    {
        return [$field, 'string', 'max' => $max, 'message' => RuleHelper::getErrorMessage($model, $field, 'string')];
    }

    /**
     * Usage:
     * use ActiveRecordCheckJsonString;
     * RuleHelper::jsonString($model, 'field_name', 1000),//1000 - max limit
     */
    public static function jsonString(Model $model, string $field, int $max): array
    {
        return [$field, 'checkValidJsonString', 'params' => ['model' => $model, 'max' => $max]];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function inRange(Model $model, string $field, array $range): array
    {
        return [$field, 'in', 'range' => $range, 'message' => RuleHelper::getErrorMessage($model, $field, 'in_range') . json_encode($range)];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function email(Model $model, string $field): array
    {
        return [$field, 'email', 'message' => RuleHelper::getErrorMessage($model, $field, 'email')];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function unique(Model $model, string $field): array
    {
        return [$field, 'unique', 'message' => RuleHelper::getErrorMessage($model, $field, 'not_unique')];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function imageExtension(Model $model, string $field): array
    {
        $extensions = 'jpeg, jpg';

        return [$field, 'file', 'extensions' => $extensions];
    }

    /**
     * Usage:
     * use ActiveRecordCheckImage;
     * RuleHelper::image($model, 'image', 1000, 1000)
     */
    public static function image(Model $model, string $field, int $maxWidth, int $maxHeight): array
    {
        return [$field, 'checkValidImage', 'params' => ['model' => $model, 'max-width' => $maxWidth, 'max-height' => $maxHeight]];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public static function date(Model $model, string $field, string $format = 'php:Y-m-d'): array
    {
        return [$field, 'date', 'format' => $format, 'message' => RuleHelper::getErrorMessage($model, $field, 'date')];
    }

    public static function safe(Model $model, string $field): array
    {
        return [$field, 'safe'];
    }

    public static function uniqueColumns(Model $model, array $fields): array
    {
        return [$fields, 'unique', 'targetAttribute' => $fields, 'message' => RuleHelper::getErrorMessage($model, implode(',', $fields), 'not_unique')];
    }

    /**
     * Usage:
     * use ActiveRecordCheckFilename;
     * RuleHelper::filename($model, 'field_name', 'csv'),
     */
    public static function filename(Model $model, string $field, string $extension): array
    {
        return [$field, 'checkValidFilename', 'params' => ['model' => $model, 'extension' => $extension]];
    }


    public static function stringOnlyLowercaseAndDigits(Model $model, string $field): array
    {
        return [$field, 'match', 'pattern' => '/^[a-z0-9]+$/', 'message' => RuleHelper::getErrorMessage($model, $field, 'only_lowercase_and_digits')];
    }
}

