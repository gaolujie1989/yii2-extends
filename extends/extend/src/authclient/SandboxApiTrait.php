<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use Iterator;
use yii\base\NotSupportedException;
use yii\helpers\Inflector;

/**
 * Trait SandboxApiTrait
 *
 * @property $sandboxUrlMap = []
 *
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait SandboxApiTrait
{
    /**
     * @var bool
     */
    protected $sandbox = false;

    /**
     * @param bool $sandbox
     * @inheritdoc
     */
    public function setSandbox(bool $sandbox = true): void
    {
        $this->sandbox = $sandbox;
        $sandboxUrlMap = $this->sandboxUrlMap ?? [];
        $map = $this->sandbox ? $sandboxUrlMap : array_flip($sandboxUrlMap);
        $this->apiBaseUrl = strtr($this->apiBaseUrl, $map);
        $this->authUrl = strtr($this->authUrl, $map);
        $this->tokenUrl = strtr($this->tokenUrl, $map);
    }
}