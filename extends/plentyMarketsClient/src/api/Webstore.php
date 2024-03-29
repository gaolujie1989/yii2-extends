<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class Webstore extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Lists all clients (stores)
     * @tag Webstore
     * @return array
     */
    public function getWebstores(): array
    {
        return $this->api("/rest/webstores");
    }
    
}
