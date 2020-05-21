<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use yii\authclient\OAuth2;

/**
 * Class RestOAuth2Client
 * @package lujie\anyconnect\clients
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class RestOAuth2Client extends OAuth2
{
    use RestClientTrait;

    /**
     * @var array
     */
    public $resources = [];

    /**
     * @var array
     */
    public $extraActions = [];

    /**
     * @var string
     */
    public $suffix = '';
}
