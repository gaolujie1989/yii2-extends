<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * Class MockIdentity
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockIdentity extends BaseObject implements IdentityInterface
{
    /**
     * @var array
     */
    public static $identities = [
        [
            'id' => 1,
            'authKey' => 'auth_key_111',
            'accessToken' => 'access_token_111',
        ]
    ];

    /**
     * @var string|int
     */
    public $id;

    /**
     * @var string
     */
    public $authKey;

    public static function findIdentity($id)
    {
        $identities = ArrayHelper::index(static::$identities, 'id');
        if (isset($identities[$id])) {
            return new self([
                'id' => $identities[$id]['id'],
                'authKey' => $identities[$id]['authKey'],
            ]);
        }
        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $identities = ArrayHelper::index(static::$identities, 'accessToken');
        if (isset($identities[$token])) {
            return new self([
                'id' => $identities[$token]['id'],
                'authKey' => $identities[$token]['authKey'],
            ]);
        }
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->authKey === $authKey;
    }
}
