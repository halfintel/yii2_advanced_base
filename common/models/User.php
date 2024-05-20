<?php

namespace common\models;

use common\components\RuleHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public const SCENARIO_CREATE = 'create';

    public string $status_text = '';// 'Active', usage - getStatusTextForSelect()
    public string $new_password = '';// field for create/update form

    /**
     * {@inheritdoc}
     */
    public static function tableName():string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $model = new self();

        return [
            RuleHelper::required($model, 'username'),
            RuleHelper::string($model, 'username', 255),
            RuleHelper::unique($model, 'username'),

            RuleHelper::string($model, 'auth_key', 32),

            ['new_password', 'required', 'on' => self::SCENARIO_CREATE],
            RuleHelper::string($model, 'new_password', 32),

            RuleHelper::string($model, 'password_reset_token', 255),

            RuleHelper::required($model, 'email'),
            RuleHelper::string($model, 'email', 255),
            RuleHelper::email($model, 'email'),
            RuleHelper::unique($model, 'email'),
//            ['email', 'checkValidEmail'],

            RuleHelper::integer($model, 'status'),
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            RuleHelper::inRange($model, 'status', [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]),

            RuleHelper::string($model, 'verification_token', 255),

            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Usage:
     * User::find()->select(['*', User::getStatusText()])
     */
    public static function getStatusTextForSelect(): string
    {
        return 'CASE
                    WHEN `status` = ' . self::STATUS_DELETED . ' THEN "Deleted"
                    WHEN `status` = ' . self::STATUS_INACTIVE . ' THEN "Inactive"
                    WHEN `status` = ' . self::STATUS_ACTIVE . ' THEN "Active"
                    ELSE "Incorrect"
                END as `status_text`';
    }

    public function beforeSave($insert): bool
    {
        if ($this->isNewRecord) {

            $this->generateAuthKey();

            $this->created_at = new Expression('NOW()');

        } else {
            $this->updated_at = new Expression('NOW()');
        }

        if (!empty($this->new_password)) {

            $this->setPassword($this->new_password);
        }

        return parent::beforeSave($insert);
    }

    public static function deleteUser(int $id)
    {
        $model = self::findOne(['id' => $id]);

        $model->status = self::STATUS_DELETED;

        $model->save(false);
    }
}
