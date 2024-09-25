<?php

namespace lujie\amazon\sp\api;

/**
* This class is autogenerated by the OpenAPI gii generator
*/
class FulfillmentOutbound20200701Const
{
        public const FULFILLMENT_POLICY_FILL_OR_KILL = 'FillOrKill';
        public const FULFILLMENT_POLICY_FILL_ALL = 'FillAll';
        public const FULFILLMENT_POLICY_FILL_ALL_AVAILABLE = 'FillAllAvailable';
        
        public const FULFILLMENT_ORDER_STATUS_NEW = 'New';
        public const FULFILLMENT_ORDER_STATUS_RECEIVED = 'Received';
        public const FULFILLMENT_ORDER_STATUS_PLANNING = 'Planning';
        public const FULFILLMENT_ORDER_STATUS_PROCESSING = 'Processing';
        public const FULFILLMENT_ORDER_STATUS_CANCELLED = 'Cancelled';
        public const FULFILLMENT_ORDER_STATUS_COMPLETE = 'Complete';
        public const FULFILLMENT_ORDER_STATUS_COMPLETE_PARTIALLED = 'CompletePartialled';
        public const FULFILLMENT_ORDER_STATUS_UNFULFILLABLE = 'Unfulfillable';
        public const FULFILLMENT_ORDER_STATUS_INVALID = 'Invalid';
        
        public const DROP_OFF_LOCATION_TYPE_FRONT_DOOR = 'FRONT_DOOR';
        public const DROP_OFF_LOCATION_TYPE_DELIVERY_BOX = 'DELIVERY_BOX';
        public const DROP_OFF_LOCATION_TYPE_GAS_METER_BOX = 'GAS_METER_BOX';
        public const DROP_OFF_LOCATION_TYPE_BICYCLE_BASKET = 'BICYCLE_BASKET';
        public const DROP_OFF_LOCATION_TYPE_GARAGE = 'GARAGE';
        public const DROP_OFF_LOCATION_TYPE_RECEPTIONIST = 'RECEPTIONIST';
        public const DROP_OFF_LOCATION_TYPE_FALLBACK_NEIGHBOR_DELIVERY = 'FALLBACK_NEIGHBOR_DELIVERY';
        public const DROP_OFF_LOCATION_TYPE_DO_NOT_LEAVE_UNATTENDED = 'DO_NOT_LEAVE_UNATTENDED';
        
        public const FEE_NAME_F_B_A_PER_UNIT_FULFILLMENT_FEE = 'FBAPerUnitFulfillmentFee';
        public const FEE_NAME_F_B_A_PER_ORDER_FULFILLMENT_FEE = 'FBAPerOrderFulfillmentFee';
        public const FEE_NAME_F_B_A_TRANSPORTATION_FEE = 'FBATransportationFee';
        public const FEE_NAME_F_B_A_FULFILLMENT_C_O_D_FEE = 'FBAFulfillmentCODFee';
        
        public const FULFILLMENT_ACTION_SHIP = 'Ship';
        public const FULFILLMENT_ACTION_HOLD = 'Hold';
        
        public const FULFILLMENT_PREVIEW_ITEM_SHIPPING_WEIGHT_CALCULATION_METHOD_PACKAGE = 'Package';
        public const FULFILLMENT_PREVIEW_ITEM_SHIPPING_WEIGHT_CALCULATION_METHOD_DIMENSIONAL = 'Dimensional';
        
        public const FULFILLMENT_RETURN_ITEM_STATUS_NEW = 'New';
        public const FULFILLMENT_RETURN_ITEM_STATUS_PROCESSED = 'Processed';
        
        public const FULFILLMENT_SHIPMENT_FULFILLMENT_SHIPMENT_STATUS_PENDING = 'PENDING';
        public const FULFILLMENT_SHIPMENT_FULFILLMENT_SHIPMENT_STATUS_SHIPPED = 'SHIPPED';
        public const FULFILLMENT_SHIPMENT_FULFILLMENT_SHIPMENT_STATUS_CANCELLED_BY_FULFILLER = 'CANCELLED_BY_FULFILLER';
        public const FULFILLMENT_SHIPMENT_FULFILLMENT_SHIPMENT_STATUS_CANCELLED_BY_SELLER = 'CANCELLED_BY_SELLER';
        
        public const AMOUNT_UNIT_OF_MEASURE_EACHES = 'Eaches';
        
        public const DELIVERY_QUANTITY_UNIT_OF_MEASURE_EACH = 'Each';
        
        public const INVALID_ITEM_REASON_CODE_INVALID_VALUES = 'InvalidValues';
        public const INVALID_ITEM_REASON_CODE_DUPLICATE_REQUEST = 'DuplicateRequest';
        public const INVALID_ITEM_REASON_CODE_NO_COMPLETED_SHIP_ITEMS = 'NoCompletedShipItems';
        public const INVALID_ITEM_REASON_CODE_NO_RETURNABLE_QUANTITY = 'NoReturnableQuantity';
        
        public const CURRENT_STATUS_IN_TRANSIT = 'IN_TRANSIT';
        public const CURRENT_STATUS_DELIVERED = 'DELIVERED';
        public const CURRENT_STATUS_RETURNING = 'RETURNING';
        public const CURRENT_STATUS_RETURNED = 'RETURNED';
        public const CURRENT_STATUS_UNDELIVERABLE = 'UNDELIVERABLE';
        public const CURRENT_STATUS_DELAYED = 'DELAYED';
        public const CURRENT_STATUS_AVAILABLE_FOR_PICKUP = 'AVAILABLE_FOR_PICKUP';
        public const CURRENT_STATUS_CUSTOMER_ACTION = 'CUSTOMER_ACTION';
        public const CURRENT_STATUS_UNKNOWN = 'UNKNOWN';
        public const CURRENT_STATUS_OUT_FOR_DELIVERY = 'OUT_FOR_DELIVERY';
        public const CURRENT_STATUS_DELIVERY_ATTEMPTED = 'DELIVERY_ATTEMPTED';
        public const CURRENT_STATUS_PICKUP_SUCCESSFUL = 'PICKUP_SUCCESSFUL';
        public const CURRENT_STATUS_PICKUP_CANCELLED = 'PICKUP_CANCELLED';
        public const CURRENT_STATUS_PICKUP_ATTEMPTED = 'PICKUP_ATTEMPTED';
        public const CURRENT_STATUS_PICKUP_SCHEDULED = 'PICKUP_SCHEDULED';
        public const CURRENT_STATUS_RETURN_REQUEST_ACCEPTED = 'RETURN_REQUEST_ACCEPTED';
        public const CURRENT_STATUS_REFUND_ISSUED = 'REFUND_ISSUED';
        public const CURRENT_STATUS_RETURN_RECEIVED_IN_FC = 'RETURN_RECEIVED_IN_FC';
        
        public const ADDITIONAL_LOCATION_INFO_AS_INSTRUCTED = 'AS_INSTRUCTED';
        public const ADDITIONAL_LOCATION_INFO_CARPORT = 'CARPORT';
        public const ADDITIONAL_LOCATION_INFO_CUSTOMER_PICKUP = 'CUSTOMER_PICKUP';
        public const ADDITIONAL_LOCATION_INFO_DECK = 'DECK';
        public const ADDITIONAL_LOCATION_INFO_DOOR_PERSON = 'DOOR_PERSON';
        public const ADDITIONAL_LOCATION_INFO_FRONT_DESK = 'FRONT_DESK';
        public const ADDITIONAL_LOCATION_INFO_FRONT_DOOR = 'FRONT_DOOR';
        public const ADDITIONAL_LOCATION_INFO_GARAGE = 'GARAGE';
        public const ADDITIONAL_LOCATION_INFO_GUARD = 'GUARD';
        public const ADDITIONAL_LOCATION_INFO_MAIL_ROOM = 'MAIL_ROOM';
        public const ADDITIONAL_LOCATION_INFO_MAIL_SLOT = 'MAIL_SLOT';
        public const ADDITIONAL_LOCATION_INFO_MAILBOX = 'MAILBOX';
        public const ADDITIONAL_LOCATION_INFO_MC_BOY = 'MC_BOY';
        public const ADDITIONAL_LOCATION_INFO_MC_GIRL = 'MC_GIRL';
        public const ADDITIONAL_LOCATION_INFO_MC_MAN = 'MC_MAN';
        public const ADDITIONAL_LOCATION_INFO_MC_WOMAN = 'MC_WOMAN';
        public const ADDITIONAL_LOCATION_INFO_NEIGHBOR = 'NEIGHBOR';
        public const ADDITIONAL_LOCATION_INFO_OFFICE = 'OFFICE';
        public const ADDITIONAL_LOCATION_INFO_OUTBUILDING = 'OUTBUILDING';
        public const ADDITIONAL_LOCATION_INFO_PATIO = 'PATIO';
        public const ADDITIONAL_LOCATION_INFO_PORCH = 'PORCH';
        public const ADDITIONAL_LOCATION_INFO_REAR_DOOR = 'REAR_DOOR';
        public const ADDITIONAL_LOCATION_INFO_RECEPTIONIST = 'RECEPTIONIST';
        public const ADDITIONAL_LOCATION_INFO_RECEIVER = 'RECEIVER';
        public const ADDITIONAL_LOCATION_INFO_SECURE_LOCATION = 'SECURE_LOCATION';
        public const ADDITIONAL_LOCATION_INFO_SIDE_DOOR = 'SIDE_DOOR';
        
        public const RETURN_ITEM_DISPOSITION_SELLABLE = 'Sellable';
        public const RETURN_ITEM_DISPOSITION_DEFECTIVE = 'Defective';
        public const RETURN_ITEM_DISPOSITION_CUSTOMER_DAMAGED = 'CustomerDamaged';
        public const RETURN_ITEM_DISPOSITION_CARRIER_DAMAGED = 'CarrierDamaged';
        public const RETURN_ITEM_DISPOSITION_FULFILLER_DAMAGED = 'FulfillerDamaged';
        
        public const EVENT_CODE_EVENT_101 = 'EVENT_101';
        public const EVENT_CODE_EVENT_102 = 'EVENT_102';
        public const EVENT_CODE_EVENT_201 = 'EVENT_201';
        public const EVENT_CODE_EVENT_202 = 'EVENT_202';
        public const EVENT_CODE_EVENT_203 = 'EVENT_203';
        public const EVENT_CODE_EVENT_204 = 'EVENT_204';
        public const EVENT_CODE_EVENT_205 = 'EVENT_205';
        public const EVENT_CODE_EVENT_206 = 'EVENT_206';
        public const EVENT_CODE_EVENT_301 = 'EVENT_301';
        public const EVENT_CODE_EVENT_302 = 'EVENT_302';
        public const EVENT_CODE_EVENT_304 = 'EVENT_304';
        public const EVENT_CODE_EVENT_306 = 'EVENT_306';
        public const EVENT_CODE_EVENT_307 = 'EVENT_307';
        public const EVENT_CODE_EVENT_308 = 'EVENT_308';
        public const EVENT_CODE_EVENT_309 = 'EVENT_309';
        public const EVENT_CODE_EVENT_401 = 'EVENT_401';
        public const EVENT_CODE_EVENT_402 = 'EVENT_402';
        public const EVENT_CODE_EVENT_403 = 'EVENT_403';
        public const EVENT_CODE_EVENT_404 = 'EVENT_404';
        public const EVENT_CODE_EVENT_405 = 'EVENT_405';
        public const EVENT_CODE_EVENT_406 = 'EVENT_406';
        public const EVENT_CODE_EVENT_407 = 'EVENT_407';
        public const EVENT_CODE_EVENT_408 = 'EVENT_408';
        public const EVENT_CODE_EVENT_409 = 'EVENT_409';
        public const EVENT_CODE_EVENT_411 = 'EVENT_411';
        public const EVENT_CODE_EVENT_412 = 'EVENT_412';
        public const EVENT_CODE_EVENT_413 = 'EVENT_413';
        public const EVENT_CODE_EVENT_414 = 'EVENT_414';
        public const EVENT_CODE_EVENT_415 = 'EVENT_415';
        public const EVENT_CODE_EVENT_416 = 'EVENT_416';
        public const EVENT_CODE_EVENT_417 = 'EVENT_417';
        public const EVENT_CODE_EVENT_418 = 'EVENT_418';
        public const EVENT_CODE_EVENT_419 = 'EVENT_419';
        
        public const WEIGHT_UNIT_KG = 'KG';
        public const WEIGHT_UNIT_KILOGRAMS = 'KILOGRAMS';
        public const WEIGHT_UNIT_LB = 'LB';
        public const WEIGHT_UNIT_POUNDS = 'POUNDS';
        
        public const SHIPPING_SPEED_CATEGORY_STANDARD = 'Standard';
        public const SHIPPING_SPEED_CATEGORY_EXPEDITED = 'Expedited';
        public const SHIPPING_SPEED_CATEGORY_PRIORITY = 'Priority';
        public const SHIPPING_SPEED_CATEGORY_SCHEDULED_DELIVERY = 'ScheduledDelivery';
        
        public const FEATURE_SETTINGS_FEATURE_FULFILLMENT_POLICY_REQUIRED = 'Required';
        public const FEATURE_SETTINGS_FEATURE_FULFILLMENT_POLICY_NOT_REQUIRED = 'NotRequired';
        
}
