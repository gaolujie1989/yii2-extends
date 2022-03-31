<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\fixtures;

use yii\base\BaseObject;
use yii\web\IdentityInterface;

/**
 * Class TestUser
 * @package lujie\auth\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TestUser extends BaseObject implements IdentityInterface
{
    public $id;

    public $data = [
        'permissions' => [
            'xxxPermission1',
            'xxxPermission2',
        ]
    ];

    public static function findIdentity($id)
    {
        return $id >= 1 ? new self(['id' => $id]) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
