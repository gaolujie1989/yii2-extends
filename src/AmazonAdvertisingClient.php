<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising;

use Iterator;
use yii\authclient\BaseClient;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\httpclient\Request;

/**
 * Class AmazonAdvertisingClient
 * @package lujie\amazon\advertising
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AmazonAdvertisingClient extends OAuth2
{
    /**
     * @var string
     */
    public $tokenUrl = AmazonAdvertisingConst::TOKEN_URL_EU;

    /**
     * @var string
     */
    public $scope = AmazonAdvertisingConst::SCOPE_CPC;

    /**
     * @var string
     */
    public $version = 'v2';

    /**
     * @return array
     * @inheritdoc
     */
    protected function initUserAttributes(): array
    {
        return $this->api('/v2/profiles');
    }


}
