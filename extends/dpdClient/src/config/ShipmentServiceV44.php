<?php

require_once dirname(__DIR__) . '/helpers/SoapClientCodeGeneratorConfigHelper.php';

use lujie\dpd\helpers\SoapClientCodeGeneratorConfigHelper;

return SoapClientCodeGeneratorConfigHelper::createDPDSoapConfig(
    'ShipmentService',
    'https://public-ws-stage.dpd.com/services/ShipmentService/V4_4/?wsdl'
);
