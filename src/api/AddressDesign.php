<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class AddressDesign extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Lists all layouts including the content for each layout.
     * @tag AddressDesign

     */
    public function getAddressLayout(): void
    {
        $this->api("/rest/address_layout");
    }
                
    /**
     * @description Creates a new layout with the name and isDefault flag given.
     * @tag AddressDesign
     * @param array $query
     *      - *layout* - string - required
     *          - The layout array
     * @return array
     *      - *isDefault* - boolean
     *      - *uuid* - string
     *      - *countries* - array
     *          - Class AddressLayout
     */
    public function createAddressLayout(array $query): array
    {
        return $this->api(array_merge(["/rest/address_layout"], $query), 'POST');
    }
                    
    /**
     * @description Gets a layout by the country ID. The ID of the country must be specified.
     * @tag AddressDesign
     * @param int $countryId The ID of the country
     */
    public function getAddressLayoutCountryByCountryId(int $countryId): void
    {
        $this->api("/rest/address_layout/country/{$countryId}");
    }
                    
    /**
     * @description Gets the default layout. If no layout is set as default, the layout with the lowest ID will be returned.
     * @tag AddressDesign

     */
    public function getAddressLayoutDefault(): void
    {
        $this->api("/rest/address_layout/default");
    }
                    
    /**
     * @description Lists the countries that are already used in other layouts.
     * @tag AddressDesign
     * @param string $uuid The UUID of the current layout
     * @return array
     */
    public function getAddressLayoutUsedCountriesUuidByUuid(string $uuid): array
    {
        return $this->api("/rest/address_layout/used_countries/{uuid?}");
    }
                    
    /**
     * @description Deletes a layout. The ID of the layout must be specified.
     * @tag AddressDesign
     * @param string $uuid The UUID of the layout
     */
    public function deleteAddressLayoutByUuid(string $uuid): void
    {
        $this->api("/rest/address_layout/{$uuid}", 'DELETE');
    }
                
    /**
     * @description Gets a layout. The ID of the layout must be specified.
     * @tag AddressDesign
     * @param string $uuid The UUID of the layout
     * @return array
     *      - *isDefault* - boolean
     *      - *uuid* - string
     *      - *countries* - array
     *          - Class AddressLayout
     */
    public function getAddressLayoutByUuid(string $uuid): array
    {
        return $this->api("/rest/address_layout/{$uuid}");
    }
                
    /**
     * @description Updates a layout. The ID of the layout must be specified.
     * @tag AddressDesign
     * @param string $uuid The UUID of the layout
     * @param array $query
     *      - *layout* - string - required
     *          - The layout array
     * @return array
     *      - *isDefault* - boolean
     *      - *uuid* - string
     *      - *countries* - array
     *          - Class AddressLayout
     */
    public function updateAddressLayoutByUuid(string $uuid, array $query): array
    {
        return $this->api(array_merge(["/rest/address_layout/{$uuid}"], $query), 'PUT');
    }
                    
    /**
     * @description Lists all available fields.
     * @tag AddressDesign

     */
    public function getAddressLayoutFields(): void
    {
        $this->api("/rest/address_layout_fields");
    }
    
}
