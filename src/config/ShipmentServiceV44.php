<?php

require_once dirname(__DIR__) . '/helpers/SoapClientCodeGeneratorConfigHelper.php';

use lujie\dpd\helpers\DpdSoapClientConfigHelper;

return DpdSoapClientConfigHelper::createSoapConfig(
    'ShipmentService',
    'https://public-ws.dpd.com/services/ShipmentService/V4_4/?wsdl'
);
