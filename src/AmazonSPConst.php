<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp;

use yii\authclient\CacheStateStorage;
use yii\authclient\StateStorageInterface;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class AmazonSPConst
 * @package lujie\amazon\sp
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AmazonSPConst
{
    public const  MARKETPLACE_BR = 'A2Q3Y263D00KWC';
    public const  MARKETPLACE_CA = 'A2EUQ1WTGCTBG2';
    public const  MARKETPLACE_MX = 'A1AM78C64UM0Y8';
    public const  MARKETPLACE_US = 'ATVPDKIKX0DER';
    public const  MARKETPLACE_AE = 'A2VIGQ35RCS4UG';
    public const  MARKETPLACE_DE = 'A1PA6795UKMFR9';
    public const  MARKETPLACE_ES = 'A1RKKUPIHCS9HS';
    public const  MARKETPLACE_FR = 'A13V1IB3VIYZZH';
    public const  MARKETPLACE_GB = 'A1F83G8C2ARO7P';
    public const  MARKETPLACE_NL = 'A1805IZSGTT6HS';
    public const  MARKETPLACE_IN = 'A21TJRUUN4KGV';
    public const  MARKETPLACE_IT = 'APJ6JRA9NG5V4';
    public const  MARKETPLACE_TR = 'A33AVAJ2PDY3EV';
    public const  MARKETPLACE_SG = 'A19VAU5U5O7RUS';
    public const  MARKETPLACE_AU = 'A39IBJ37TRP1C6';
    public const  MARKETPLACE_JP = 'A1VC38T7YXB528';
    public const  MARKETPLACE_CN = 'AAHKV2X7AFYLW';

    public const REGION_US_EAST_1 = 'us-east-1';
    public const REGION_US_WEST_2 = 'us-west-2';
    public const REGION_EU_WEST_1 = 'eu-west-1';
    public const HOST_US_EAST_1 = 'sellingpartnerapi-na.amazon.com';
    public const HOST_US_WEST_2 = 'sellingpartnerapi-fe.amazon.com';
    public const HOST_EU_WEST_1 = 'sellingpartnerapi-eu.amazon.com';
}