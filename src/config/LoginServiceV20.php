<?php

require_once dirname(__DIR__) . '/helpers/SoapClientCodeGeneratorConfigHelper.php';

use lujie\dpd\helpers\DpdSoapClientConfigHelper;

return DpdSoapClientConfigHelper::createSoapConfig(
    'LoginService',
    'https://public-ws.dpd.com/services/LoginService/V2_0/?wsdl'
);
