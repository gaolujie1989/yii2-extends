<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use yii\authclient\OAuth2;

/**
 * Class RestOAuth2
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RestOAuth2 extends OAuth2
{
    use RestApiTrait, BatchApiTrait, OAuthExtendTrait;
}