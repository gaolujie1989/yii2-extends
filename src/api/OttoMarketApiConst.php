<?php

namespace lujie\otto\api;

/**
 * This class is autogenerated by the OpenAPI gii generator
 */
class OttoMarketApiConst
{
    public const PRODUCTS_V3_REL_SELF = 'self';
    public const PRODUCTS_V3_REL_FAILED = 'failed';
    public const PRODUCTS_V3_REL_SUCCEEDED = 'succeeded';
    public const PRODUCTS_V3_REL_UNCHANGED = 'unchanged';

    public const PRODUCTS_V3_TYPE_IMAGE = 'IMAGE';
    public const PRODUCTS_V3_TYPE_DIMENSIONAL_DRAWING = 'DIMENSIONAL_DRAWING';
    public const PRODUCTS_V3_TYPE_COLOR_VARIANT = 'COLOR_VARIANT';
    public const PRODUCTS_V3_TYPE_ENERGY_EFFICIENCY_LABEL = 'ENERGY_EFFICIENCY_LABEL';
    public const PRODUCTS_V3_TYPE_MATERIAL_SAMPLE = 'MATERIAL_SAMPLE';
    public const PRODUCTS_V3_TYPE_PRODUCT_DATASHEET = 'PRODUCT_DATASHEET';
    public const PRODUCTS_V3_TYPE_USER_MANUAL = 'USER_MANUAL';
    public const PRODUCTS_V3_TYPE_MANUFACTURER_WARRANTY = 'MANUFACTURER_WARRANTY';
    public const PRODUCTS_V3_TYPE_SAFETY_DATASHEET = 'SAFETY_DATASHEET';
    public const PRODUCTS_V3_TYPE_ASSEMBLY_INSTRUCTIONS = 'ASSEMBLY_INSTRUCTIONS';
    public const PRODUCTS_V3_TYPE_WARNING_LABEL = 'WARNING_LABEL';

    public const PRODUCTS_V3_STATUS_PENDING = 'PENDING';
    public const PRODUCTS_V3_STATUS_ONLINE = 'ONLINE';
    public const PRODUCTS_V3_STATUS_RESTRICTED = 'RESTRICTED';
    public const PRODUCTS_V3_STATUS_REJECTED = 'REJECTED';
    public const PRODUCTS_V3_STATUS_INACTIVE = 'INACTIVE';

    public const PRODUCTS_V3_NORM_AMOUNT_1 = 1;
    public const PRODUCTS_V3_NORM_AMOUNT_100 = 100;
    public const PRODUCTS_V3_NORM_AMOUNT_1000 = 1000;

    public const PRODUCTS_V3_NORM_UNIT_STK = 'Stk';
    public const PRODUCTS_V3_NORM_UNIT_QM = 'qm';
    public const PRODUCTS_V3_NORM_UNIT_KG = 'kg';
    public const PRODUCTS_V3_NORM_UNIT_L = 'l';
    public const PRODUCTS_V3_NORM_UNIT_M = 'm';
    public const PRODUCTS_V3_NORM_UNIT_ML = 'ml';
    public const PRODUCTS_V3_NORM_UNIT_G = 'g';
    public const PRODUCTS_V3_NORM_UNIT_PAAR = 'Paar';
    public const PRODUCTS_V3_NORM_UNIT_RM = 'RM';
    public const PRODUCTS_V3_NORM_UNIT_DM3 = 'dm3';

    public const PRODUCTS_V3_SALES_UNIT_STK = 'Stk';
    public const PRODUCTS_V3_SALES_UNIT_QM = 'qm';
    public const PRODUCTS_V3_SALES_UNIT_KG = 'kg';
    public const PRODUCTS_V3_SALES_UNIT_L = 'l';
    public const PRODUCTS_V3_SALES_UNIT_M = 'm';
    public const PRODUCTS_V3_SALES_UNIT_ML = 'ml';
    public const PRODUCTS_V3_SALES_UNIT_G = 'g';
    public const PRODUCTS_V3_SALES_UNIT_PAAR = 'Paar';
    public const PRODUCTS_V3_SALES_UNIT_RM = 'RM';
    public const PRODUCTS_V3_SALES_UNIT_DM3 = 'dm3';

    public const PRODUCTS_V3_VAT_FULL = 'FULL';
    public const PRODUCTS_V3_VAT_REDUCED = 'REDUCED';
    public const PRODUCTS_V3_VAT_FREE = 'FREE';

    public const PRODUCTS_V3_STATE_PENDING = 'pending';
    public const PRODUCTS_V3_STATE_DONE = 'done';

    public const QUANTITIES_V2_REL_SELF = 'self';
    public const QUANTITIES_V2_REL_PREV = 'prev';
    public const QUANTITIES_V2_REL_NEXT = 'next';

    public const ORDERS_V4_CANCELLATION_REASON_CANCELLED_ON_CUSTOMER_WISH = 'CANCELLED_ON_CUSTOMER_WISH';
    public const ORDERS_V4_CANCELLATION_REASON_CANCELLED_ON_PARTNER_WISH = 'CANCELLED_ON_PARTNER_WISH';
    public const ORDERS_V4_CANCELLATION_REASON_PAYMENT_ABORTED = 'PAYMENT_ABORTED';
    public const ORDERS_V4_CANCELLATION_REASON_PAYMENT_FRAUD = 'PAYMENT_FRAUD';
    public const ORDERS_V4_CANCELLATION_REASON_PARTNER_TERMINATED = 'PARTNER_TERMINATED';
    public const ORDERS_V4_CANCELLATION_REASON_ILLEGAL_PRODUCT = 'ILLEGAL_PRODUCT';
    public const ORDERS_V4_CANCELLATION_REASON_MARKETPLACE_FRAUD = 'MARKETPLACE_FRAUD';

    public const ORDERS_V4_FULFILLMENT_STATUS_ANNOUNCED = 'ANNOUNCED';
    public const ORDERS_V4_FULFILLMENT_STATUS_PROCESSABLE = 'PROCESSABLE';
    public const ORDERS_V4_FULFILLMENT_STATUS_SENT = 'SENT';
    public const ORDERS_V4_FULFILLMENT_STATUS_RETURNED = 'RETURNED';
    public const ORDERS_V4_FULFILLMENT_STATUS_CANCELLED_BY_PARTNER = 'CANCELLED_BY_PARTNER';
    public const ORDERS_V4_FULFILLMENT_STATUS_CANCELLED_BY_MARKETPLACE = 'CANCELLED_BY_MARKETPLACE';

    public const RETURN_SHIPMENTS_V1_CARRIER_DHL = 'DHL';
    public const RETURN_SHIPMENTS_V1_CARRIER_GLS = 'GLS';
    public const RETURN_SHIPMENTS_V1_CARRIER_HERMES = 'HERMES';
    public const RETURN_SHIPMENTS_V1_CARRIER_HES = 'HES';

    public const RETURN_SHIPMENTS_V1_STATE_ANNOUNCED = 'ANNOUNCED';
    public const RETURN_SHIPMENTS_V1_STATE_ON_THE_WAY = 'ON_THE_WAY';
    public const RETURN_SHIPMENTS_V1_STATE_DELIVERED = 'DELIVERED';

    public const SHIPMENTS_V1_STATE_SENT = 'SENT';
    public const SHIPMENTS_V1_STATE_ON_THE_WAY = 'ON_THE_WAY';
    public const SHIPMENTS_V1_STATE_DELIVERED = 'DELIVERED';
    public const SHIPMENTS_V1_STATE_DELIVERED_FOR_PICKUP = 'DELIVERED_FOR_PICKUP';
    public const SHIPMENTS_V1_STATE_DELIVERY_ATTEMPT_FAILED = 'DELIVERY_ATTEMPT_FAILED';

    public const SHIPMENTS_V1_CARRIER_DHL = 'DHL';
    public const SHIPMENTS_V1_CARRIER_DHL_FREIGHT = 'DHL_FREIGHT';
    public const SHIPMENTS_V1_CARRIER_DHL_HOME_DELIVERY = 'DHL_HOME_DELIVERY';
    public const SHIPMENTS_V1_CARRIER_GLS = 'GLS';
    public const SHIPMENTS_V1_CARRIER_HERMES = 'HERMES';
    public const SHIPMENTS_V1_CARRIER_DPD = 'DPD';
    public const SHIPMENTS_V1_CARRIER_UPS = 'UPS';
    public const SHIPMENTS_V1_CARRIER_HES = 'HES';
    public const SHIPMENTS_V1_CARRIER_HELLMANN = 'HELLMANN';
    public const SHIPMENTS_V1_CARRIER_DB_SCHENKER = 'DB_SCHENKER';
    public const SHIPMENTS_V1_CARRIER_IDS = 'IDS';
    public const SHIPMENTS_V1_CARRIER_EMONS = 'EMONS';
    public const SHIPMENTS_V1_CARRIER_DACHSER = 'DACHSER';
    public const SHIPMENTS_V1_CARRIER_LOGWIN = 'LOGWIN';
    public const SHIPMENTS_V1_CARRIER_KUEHNE_NAGEL = 'KUEHNE_NAGEL';
    public const SHIPMENTS_V1_CARRIER_SCHOCKEMOEHLE = 'SCHOCKEMOEHLE';
    public const SHIPMENTS_V1_CARRIER_KOCH = 'KOCH';
    public const SHIPMENTS_V1_CARRIER_REITHMEIER = 'REITHMEIER';
    public const SHIPMENTS_V1_CARRIER_CARGOLINE = 'CARGOLINE';
    public const SHIPMENTS_V1_CARRIER_BURSPED = 'BURSPED';
    public const SHIPMENTS_V1_CARRIER_GEL = 'GEL';
    public const SHIPMENTS_V1_CARRIER_DHL_EXPRESS = 'DHL_EXPRESS';
    public const SHIPMENTS_V1_CARRIER_MEYER_JUMBO = 'MEYER_JUMBO';
    public const SHIPMENTS_V1_CARRIER_BTW = 'BTW';
    public const SHIPMENTS_V1_CARRIER_RHENUS = 'RHENUS';
    public const SHIPMENTS_V1_CARRIER_OTHER_FORWARDER = 'OTHER_FORWARDER';

    public const RETURNS_V2_STATUS_ANNOUNCED = 'ANNOUNCED';
    public const RETURNS_V2_STATUS_ACCEPTED = 'ACCEPTED';
    public const RETURNS_V2_STATUS_REJECTED = 'REJECTED';
    public const RETURNS_V2_STATUS_MISDIRECTED = 'MISDIRECTED';

    public const RETURNS_V2_CONDITION_A_B_C_D_E = 'A/B/C/D/E';

    public const RETURNS_V3_CONDITION_A_B_C_D_E = 'A/B/C/D/E';

    public const RETURNS_V3_REASON_THIRD_PARTY_ITEM = 'THIRD_PARTY_ITEM';
    public const RETURNS_V3_REASON_WRONG_ITEM = 'WRONG_ITEM';
    public const RETURNS_V3_REASON_EXCHANGE = 'EXCHANGE';
    public const RETURNS_V3_REASON_DAMAGE_TO_THE_HYGIENE_SEAL = 'DAMAGE_TO_THE_HYGIENE_SEAL';
    public const RETURNS_V3_REASON_ITEM_DAMAGED = 'ITEM_DAMAGED';
    public const RETURNS_V3_REASON_RETURN_PERIOD_EXCEEDED = 'RETURN_PERIOD_EXCEEDED';
    public const RETURNS_V3_REASON_ITEM_NOT_IN_THE_PARCEL = 'ITEM_NOT_IN_THE_PARCEL';

    public const RETURNS_V3_STATUS_ANNOUNCED = 'ANNOUNCED';
    public const RETURNS_V3_STATUS_ACCEPTED = 'ACCEPTED';
    public const RETURNS_V3_STATUS_REJECTED = 'REJECTED';
    public const RETURNS_V3_STATUS_MISDIRECTED = 'MISDIRECTED';

    public const RECEIPTS_V3_DELIVERY_COST_TYPE_DELIVERY_FEE_STANDARD = 'DELIVERY_FEE_STANDARD';
    public const RECEIPTS_V3_DELIVERY_COST_TYPE_DELIVERY_FEE_FREIGHT_SURCHARGE = 'DELIVERY_FEE_FREIGHT_SURCHARGE';

    public const RECEIPTS_V3_TYPE_VOUCHER = 'VOUCHER';

    public const RECEIPTS_V3_PAYMENT_PROVIDER_PLAZA = 'PLAZA';
    public const RECEIPTS_V3_PAYMENT_PROVIDER_OTTOPAYMENTS = 'OTTOPAYMENTS';

    public const RECEIPTS_V3_PAYMENT_METHOD_CREDIT_CARD_ONLINE = 'CREDIT_CARD_ONLINE';
    public const RECEIPTS_V3_PAYMENT_METHOD_INVOICE_SINGLE = 'INVOICE_SINGLE';
    public const RECEIPTS_V3_PAYMENT_METHOD_INVOICE_INSTALLMENTS = 'INVOICE_INSTALLMENTS';
    public const RECEIPTS_V3_PAYMENT_METHOD_DIRECT_DEBIT_INSTALLMENTS = 'DIRECT_DEBIT_INSTALLMENTS';
    public const RECEIPTS_V3_PAYMENT_METHOD_PREPAYMENT = 'PREPAYMENT';
    public const RECEIPTS_V3_PAYMENT_METHOD_DIRECT_DEBIT = 'DIRECT_DEBIT';
    public const RECEIPTS_V3_PAYMENT_METHOD_PAYPAL = 'PAYPAL';

    public const RECEIPTS_V3_TAX_TYPE_VAT = 'VAT';

    public const RECEIPTS_V3_PRICE_MODIFICATION_TYPE_PARTNER_DISCOUNT = 'PARTNER_DISCOUNT';
    public const RECEIPTS_V3_PRICE_MODIFICATION_TYPE_DEFECT_COMPENSATION = 'DEFECT_COMPENSATION';
    public const RECEIPTS_V3_PRICE_MODIFICATION_TYPE_REFUND_COMPLAINT_ITEM = 'REFUND_COMPLAINT_ITEM';
    public const RECEIPTS_V3_PRICE_MODIFICATION_TYPE_REFUND_PAYPAL_DISPUTE = 'REFUND_PAYPAL_DISPUTE';
    public const RECEIPTS_V3_PRICE_MODIFICATION_TYPE_REFUND_ESCALATION = 'REFUND_ESCALATION';

    public const RECEIPTS_V3_RECEIPT_TYPE_PURCHASE = 'PURCHASE';
    public const RECEIPTS_V3_RECEIPT_TYPE_REFUND = 'REFUND';
    public const RECEIPTS_V3_RECEIPT_TYPE_PARTIAL_REFUND = 'PARTIAL_REFUND';

    public const RECEIPTS_V3_REFUND_TYPE_RETURN = 'RETURN';
    public const RECEIPTS_V3_REFUND_TYPE_CANCELLATION = 'CANCELLATION';

    public const RECEIPTS_V3_PARTIAL_REFUND_TYPE_REFUND_COMPLAINT_ITEM = 'REFUND_COMPLAINT_ITEM';
    public const RECEIPTS_V3_PARTIAL_REFUND_TYPE_REFUND_PAYPAL_DISPUTE = 'REFUND_PAYPAL_DISPUTE';
    public const RECEIPTS_V3_PARTIAL_REFUND_TYPE_REFUND_ESCALATION = 'REFUND_ESCALATION';

    public const RECEIPTS_V3_SERVICE_TYPE_DISPOSAL = 'DISPOSAL';

    public const PRICE_REDUCTIONS_V1_STATUS_PROCESSED = 'PROCESSED';
    public const PRICE_REDUCTIONS_V1_STATUS_INITIATED = 'INITIATED';
    public const PRICE_REDUCTIONS_V1_STATUS_REJECTED = 'REJECTED';

}
