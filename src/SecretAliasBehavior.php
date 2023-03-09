<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\alias\behaviors;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\StringHelper;

/**
 * Class SecretAliasBehavior
 * @package lujie\alias\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SecretAliasBehavior extends AliasPropertyBehavior
{
    public const TYPE_ENCRYPTION_BY_KEY = 'ENCRYPTION_BY_KEY';
    public const TYPE_ENCRYPTION_BY_PASSWORD = 'ENCRYPTION_BY_PASSWORD';
    public const TYPE_DATA_HASH = 'DATA_HASH';
    public const TYPE_PASSWORD_HASH = 'PASSWORD_HASH';

    public $type = 'TYPE_ENCRYPTION_BY_KEY';

    /**
     * @var string|int
     */
    public $key = null;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->type === self::TYPE_ENCRYPTION_BY_KEY || $this->type === self::TYPE_ENCRYPTION_BY_PASSWORD) {
            if (empty($this->key)) {
                throw new InvalidConfigException('Key must be set on ENCRYPTION');
            }
        }
        if ($this->type === self::TYPE_PASSWORD_HASH) {
            if (!is_int($this->type) || $this->type < 4 || $this->type > 31) {
                throw new InvalidConfigException('Key must between 4 and 31 on PASSWORD_HASH');
            }
        }
    }

    /**
     * @param string $name
     * @return bool|mixed|string
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getAliasProperty(string $name)
    {
        $value = parent::getAliasProperty($name);
        $security = Yii::$app->getSecurity();
        switch ($this->type) {
            case self::TYPE_ENCRYPTION_BY_KEY:
                $value = $security->decryptByKey($value, $this->key);
                break;
            case self::TYPE_ENCRYPTION_BY_PASSWORD:
                $value = $security->decryptByPassword($value, $this->key);
                break;
            case self::TYPE_DATA_HASH:
                $value = $security->validateData($value, $this->key);
                break;
            case self::TYPE_PASSWORD_HASH:
                $value = '';
                break;
            default:
                throw new InvalidConfigException('Invalid secret type');
        }
        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function setAliasProperty(string $name, $value): void
    {
        $security = Yii::$app->getSecurity();
        switch ($this->type) {
            case self::TYPE_ENCRYPTION_BY_KEY:
                $value = $security->encryptByKey($value, $this->key);
                break;
            case self::TYPE_ENCRYPTION_BY_PASSWORD:
                $value = $security->encryptByPassword($value, $this->key);
                break;
            case self::TYPE_DATA_HASH:
                $value = $security->hashData($value, $this->key);
                break;
            case self::TYPE_PASSWORD_HASH:
                $value = $security->generatePasswordHash($value, $this->key);
                break;
            default:
                throw new InvalidConfigException('Invalid secret type');
        }
        parent::setAliasProperty($name, $value);
    }
}
