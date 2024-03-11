<?php

namespace lujie\amazon\sp\api;

/**
* This class is autogenerated by the OpenAPI gii generator
*/
class VendorShipmentsConst
{
        public const SHIPMENT_CONFIRMATION_SHIPMENT_CONFIRMATION_TYPE_ORIGINAL = 'Original';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_CONFIRMATION_TYPE_REPLACE = 'Replace';
        
        public const SHIPMENT_CONFIRMATION_SHIPMENT_TYPE_TRUCK_LOAD = 'TruckLoad';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_TYPE_LESS_THAN_TRUCK_LOAD = 'LessThanTruckLoad';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_TYPE_SMALL_PARCEL = 'SmallParcel';
        
        public const SHIPMENT_CONFIRMATION_SHIPMENT_STRUCTURE_PALLETIZED_ASSORTMENT_CASE = 'PalletizedAssortmentCase';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_STRUCTURE_LOOSE_ASSORTMENT_CASE = 'LooseAssortmentCase';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_STRUCTURE_PALLET_OF_ITEMS = 'PalletOfItems';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_STRUCTURE_PALLETIZED_STANDARD_CASE = 'PalletizedStandardCase';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_STRUCTURE_LOOSE_STANDARD_CASE = 'LooseStandardCase';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_STRUCTURE_MASTER_PALLET = 'MasterPallet';
        public const SHIPMENT_CONFIRMATION_SHIPMENT_STRUCTURE_MASTER_CASE = 'MasterCase';
        
        public const SHIPMENT_TRANSACTION_TYPE_NEW = 'New';
        public const SHIPMENT_TRANSACTION_TYPE_CANCEL = 'Cancel';
        
        public const SHIPMENT_CURRENT_SHIPMENT_STATUS_CREATED = 'Created';
        public const SHIPMENT_CURRENT_SHIPMENT_STATUS_TRANSPORTATION_REQUESTED = 'TransportationRequested';
        public const SHIPMENT_CURRENT_SHIPMENT_STATUS_CARRIER_ASSIGNED = 'CarrierAssigned';
        public const SHIPMENT_CURRENT_SHIPMENT_STATUS_SHIPPED = 'Shipped';
        
        public const SHIPMENT_SHIPMENT_FREIGHT_TERM_COLLECT = 'Collect';
        public const SHIPMENT_SHIPMENT_FREIGHT_TERM_PREPAID = 'Prepaid';
        
        public const SHIPMENT_INFORMATION_SHIP_MODE_SMALL_PARCEL = 'SmallParcel';
        public const SHIPMENT_INFORMATION_SHIP_MODE_LTL = 'LTL';
        
        public const LABEL_DATA_LABEL_FORMAT_PDF = 'PDF';
        
        public const SHIPMENT_STATUS_DETAILS_SHIPMENT_STATUS_CREATED = 'Created';
        public const SHIPMENT_STATUS_DETAILS_SHIPMENT_STATUS_TRANSPORTATION_REQUESTED = 'TransportationRequested';
        public const SHIPMENT_STATUS_DETAILS_SHIPMENT_STATUS_CARRIER_ASSIGNED = 'CarrierAssigned';
        public const SHIPMENT_STATUS_DETAILS_SHIPMENT_STATUS_SHIPPED = 'Shipped';
        
        public const TRANSPORTATION_DETAILS_SHIP_MODE_TRUCK_LOAD = 'TruckLoad';
        public const TRANSPORTATION_DETAILS_SHIP_MODE_LESS_THAN_TRUCK_LOAD = 'LessThanTruckLoad';
        public const TRANSPORTATION_DETAILS_SHIP_MODE_SMALL_PARCEL = 'SmallParcel';
        
        public const TRANSPORTATION_DETAILS_TRANSPORTATION_MODE_ROAD = 'Road';
        public const TRANSPORTATION_DETAILS_TRANSPORTATION_MODE_AIR = 'Air';
        public const TRANSPORTATION_DETAILS_TRANSPORTATION_MODE_OCEAN = 'Ocean';
        
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_PAID_BY_BUYER = 'PaidByBuyer';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_COLLECT_ON_DELIVERY = 'CollectOnDelivery';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_DEFINED_BY_BUYER_AND_SELLER = 'DefinedByBuyerAndSeller';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_F_O_B_PORT_OF_CALL = 'FOBPortOfCall';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_PREPAID_BY_SELLER = 'PrepaidBySeller';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_PAID_BY_SELLER = 'PaidBySeller';
        
        public const IMPORT_DETAILS_HANDLING_INSTRUCTIONS_OVERSIZED = 'Oversized';
        public const IMPORT_DETAILS_HANDLING_INSTRUCTIONS_FRAGILE = 'Fragile';
        public const IMPORT_DETAILS_HANDLING_INSTRUCTIONS_FOOD = 'Food';
        public const IMPORT_DETAILS_HANDLING_INSTRUCTIONS_HANDLE_WITH_CARE = 'HandleWithCare';
        
        public const CONTAINERS_CONTAINER_TYPE_CARTON = 'carton';
        public const CONTAINERS_CONTAINER_TYPE_PALLET = 'pallet';
        
        public const ITEM_DETAILS_HANDLING_CODE_OVERSIZED = 'Oversized';
        public const ITEM_DETAILS_HANDLING_CODE_FRAGILE = 'Fragile';
        public const ITEM_DETAILS_HANDLING_CODE_FOOD = 'Food';
        public const ITEM_DETAILS_HANDLING_CODE_HANDLE_WITH_CARE = 'HandleWithCare';
        
        public const CONTAINER_IDENTIFICATION_CONTAINER_IDENTIFICATION_TYPE_SSCC = 'SSCC';
        public const CONTAINER_IDENTIFICATION_CONTAINER_IDENTIFICATION_TYPE_AMZNCC = 'AMZNCC';
        public const CONTAINER_IDENTIFICATION_CONTAINER_IDENTIFICATION_TYPE_GTIN = 'GTIN';
        public const CONTAINER_IDENTIFICATION_CONTAINER_IDENTIFICATION_TYPE_BPS = 'BPS';
        public const CONTAINER_IDENTIFICATION_CONTAINER_IDENTIFICATION_TYPE_CID = 'CID';
        
        public const TAX_REGISTRATION_DETAILS_TAX_REGISTRATION_TYPE_VAT = 'VAT';
        public const TAX_REGISTRATION_DETAILS_TAX_REGISTRATION_TYPE_GST = 'GST';
        
        public const STOP_FUNCTION_CODE_PORT_OF_DISCHARGE = 'PortOfDischarge';
        public const STOP_FUNCTION_CODE_FREIGHT_PAYABLE_AT = 'FreightPayableAt';
        public const STOP_FUNCTION_CODE_PORT_OF_LOADING = 'PortOfLoading';
        
        public const DIMENSIONS_UNIT_OF_MEASURE_IN = 'In';
        public const DIMENSIONS_UNIT_OF_MEASURE_FT = 'Ft';
        public const DIMENSIONS_UNIT_OF_MEASURE_METER = 'Meter';
        public const DIMENSIONS_UNIT_OF_MEASURE_YARD = 'Yard';
        
        public const VOLUME_UNIT_OF_MEASURE_CU_FT = 'CuFt';
        public const VOLUME_UNIT_OF_MEASURE_CU_IN = 'CuIn';
        public const VOLUME_UNIT_OF_MEASURE_CU_M = 'CuM';
        public const VOLUME_UNIT_OF_MEASURE_CU_Y = 'CuY';
        
        public const WEIGHT_UNIT_OF_MEASURE_G = 'G';
        public const WEIGHT_UNIT_OF_MEASURE_KG = 'Kg';
        public const WEIGHT_UNIT_OF_MEASURE_OZ = 'Oz';
        public const WEIGHT_UNIT_OF_MEASURE_LB = 'Lb';
        
        public const ITEM_QUANTITY_UNIT_OF_MEASURE_CASES = 'Cases';
        public const ITEM_QUANTITY_UNIT_OF_MEASURE_EACHES = 'Eaches';
        
        public const PACKED_QUANTITY_UNIT_OF_MEASURE_CASES = 'Cases';
        public const PACKED_QUANTITY_UNIT_OF_MEASURE_EACHES = 'Eaches';
        
        public const DURATION_DURATION_UNIT_DAYS = 'Days';
        public const DURATION_DURATION_UNIT_MONTHS = 'Months';
        
}
