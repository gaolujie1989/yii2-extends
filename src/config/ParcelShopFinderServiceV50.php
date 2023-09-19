<?php

require_once dirname(__DIR__) . '/helpers/SoapClientCodeGeneratorConfigHelper.php';

use lujie\dpd\helpers\DpdSoapClientConfigHelper;

return DpdSoapClientConfigHelper::createSoapConfig(
    'ParcelShopFinderService',
    'https://public-ws.dpd.com/services/ParcelShopFinderService/V5_0/?wsdl'
);
