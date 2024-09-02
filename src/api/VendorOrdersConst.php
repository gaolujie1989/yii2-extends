<?php

namespace lujie\amazon\sp\api;

/**
* This class is autogenerated by the OpenAPI gii generator
*/
class VendorOrdersConst
{
        public const ORDER_PURCHASE_ORDER_STATE_NEW = 'New';
        public const ORDER_PURCHASE_ORDER_STATE_ACKNOWLEDGED = 'Acknowledged';
        public const ORDER_PURCHASE_ORDER_STATE_CLOSED = 'Closed';
        
        public const ORDER_DETAILS_PURCHASE_ORDER_TYPE_REGULAR_ORDER = 'RegularOrder';
        public const ORDER_DETAILS_PURCHASE_ORDER_TYPE_CONSIGNED_ORDER = 'ConsignedOrder';
        public const ORDER_DETAILS_PURCHASE_ORDER_TYPE_NEW_PRODUCT_INTRODUCTION = 'NewProductIntroduction';
        public const ORDER_DETAILS_PURCHASE_ORDER_TYPE_RUSH_ORDER = 'RushOrder';
        
        public const ORDER_DETAILS_PAYMENT_METHOD_INVOICE = 'Invoice';
        public const ORDER_DETAILS_PAYMENT_METHOD_CONSIGNMENT = 'Consignment';
        public const ORDER_DETAILS_PAYMENT_METHOD_CREDIT_CARD = 'CreditCard';
        public const ORDER_DETAILS_PAYMENT_METHOD_PREPAID = 'Prepaid';
        
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_PAID_BY_BUYER = 'PaidByBuyer';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_COLLECT_ON_DELIVERY = 'CollectOnDelivery';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_DEFINED_BY_BUYER_AND_SELLER = 'DefinedByBuyerAndSeller';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_F_O_B_PORT_OF_CALL = 'FOBPortOfCall';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_PREPAID_BY_SELLER = 'PrepaidBySeller';
        public const IMPORT_DETAILS_METHOD_OF_PAYMENT_PAID_BY_SELLER = 'PaidBySeller';
        
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_EX_WORKS = 'ExWorks';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_FREE_CARRIER = 'FreeCarrier';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_FREE_ON_BOARD = 'FreeOnBoard';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_FREE_ALONG_SIDE_SHIP = 'FreeAlongSideShip';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_CARRIAGE_PAID_TO = 'CarriagePaidTo';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_COST_AND_FREIGHT = 'CostAndFreight';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_CARRIAGE_AND_INSURANCE_PAID_TO = 'CarriageAndInsurancePaidTo';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_COST_INSURANCE_AND_FREIGHT = 'CostInsuranceAndFreight';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_DELIVERED_AT_TERMINAL = 'DeliveredAtTerminal';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_DELIVERED_AT_PLACE = 'DeliveredAtPlace';
        public const IMPORT_DETAILS_INTERNATIONAL_COMMERCIAL_TERMS_DELIVER_DUTY_PAID = 'DeliverDutyPaid';
        
        public const TAX_REGISTRATION_DETAILS_TAX_REGISTRATION_TYPE_VAT = 'VAT';
        public const TAX_REGISTRATION_DETAILS_TAX_REGISTRATION_TYPE_GST = 'GST';
        
        public const MONEY_UNIT_OF_MEASURE_POUNDS = 'POUNDS';
        public const MONEY_UNIT_OF_MEASURE_OUNCES = 'OUNCES';
        public const MONEY_UNIT_OF_MEASURE_GRAMS = 'GRAMS';
        public const MONEY_UNIT_OF_MEASURE_KILOGRAMS = 'KILOGRAMS';
        
        public const ORDER_ITEM_ACKNOWLEDGEMENT_ACKNOWLEDGEMENT_CODE_ACCEPTED = 'Accepted';
        public const ORDER_ITEM_ACKNOWLEDGEMENT_ACKNOWLEDGEMENT_CODE_BACKORDERED = 'Backordered';
        public const ORDER_ITEM_ACKNOWLEDGEMENT_ACKNOWLEDGEMENT_CODE_REJECTED = 'Rejected';
        
        public const ORDER_ITEM_ACKNOWLEDGEMENT_REJECTION_REASON_TEMPORARILY_UNAVAILABLE = 'TemporarilyUnavailable';
        public const ORDER_ITEM_ACKNOWLEDGEMENT_REJECTION_REASON_INVALID_PRODUCT_IDENTIFIER = 'InvalidProductIdentifier';
        public const ORDER_ITEM_ACKNOWLEDGEMENT_REJECTION_REASON_OBSOLETE_PRODUCT = 'ObsoleteProduct';
        
        public const ITEM_QUANTITY_UNIT_OF_MEASURE_CASES = 'Cases';
        public const ITEM_QUANTITY_UNIT_OF_MEASURE_EACHES = 'Eaches';
        
        public const ORDER_STATUS_PURCHASE_ORDER_STATUS_OPEN = 'OPEN';
        public const ORDER_STATUS_PURCHASE_ORDER_STATUS_CLOSED = 'CLOSED';
        
}
