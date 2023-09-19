<?php

require_once dirname(__DIR__) . '/helpers/SoapClientCodeGeneratorConfigHelper.php';

use lujie\dpd\helpers\SoapClientCodeGeneratorConfigHelper;

return SoapClientCodeGeneratorConfigHelper::createDPDSoapConfig(
    'LoginService',
    'https://public-ws-stage.dpd.com/services/LoginService/V2_0/?wsdl'
);
