<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

/**
 * Class PlentyMarketsConst
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsConst
{
    public const COUNTRY_CODES = [
        1 => 'DE', //Germany
        2 => 'AT', //Austria
        3 => 'BE', //Belgium
        4 => 'CH', //Switzerland
        5 => 'CY', //Cyprus
        6 => 'CZ', //Czech Republic
        7 => 'DK', //Denmark
        8 => 'ES', //Spain
        9 => 'EE', //Estonia
        10 => 'FR', //France
        11 => 'FI', //Finland
        12 => 'UK', //United Kingdom
        13 => 'GR', //Greece
        14 => 'HU', //Hungary
        15 => 'IT', //Italy
        16 => 'IE', //Ireland
        17 => 'LU', //Luxembourg
        18 => 'LV', //Latvia
        19 => 'MT', //Malta
        20 => 'NO', //Norway
        21 => 'NL', //Netherlands
        22 => 'PT', //Portugal
        23 => 'PL', //Poland
        24 => 'SE', //Sweden
        25 => 'SG', //Singapore
        26 => 'SK', //Slovakia
        27 => 'SI', //Slovenia
        28 => 'US', //USA
        29 => 'AU', //Australia
        30 => 'CA', //Canada
        31 => 'CN', //China
        32 => 'JP', //Japan
        33 => 'LT', //Lithuania
        34 => 'LI', //Liechtenstein
        35 => 'MC', //Monaco
        36 => 'MX', //Mexico
        37 => 'IC', //Canary Islands
        38 => 'IN', //India
        39 => 'BR', //Brazil
        40 => 'RU', //Russia
        41 => 'RO', //Romania
        42 => 'EA', //Ceuta
        43 => 'EA', //Melilla
        44 => 'BG', //Bulgaria
        45 => 'XZ', //Kosovo
        46 => 'KG', //Kyrgyzstan
        47 => 'KZ', //Kazakhstan
        48 => 'BY', //Belarus
        49 => 'UZ', //Uzbekistan
        50 => 'MA', //Morocco
        51 => 'AM', //Armenia
        52 => 'AL', //Albania
        53 => 'EG', //Egypt
        54 => 'HR', //Croatia
        55 => 'MV', //Maldives
        56 => 'MY', //Malaysia
        57 => 'HK', //Hong Kong
        58 => 'YE', //Yemen
        59 => 'IL', //Israel
        60 => 'TW', //Taiwan
        61 => 'GP', //Guadeloupe
        62 => 'TH', //Thailand
        63 => 'TR', //Turkey
        64 => 'GR', //Greek Islands
        65 => 'ES', //Balearic Islands
        66 => 'NZ', //New Zealand
        67 => 'AF', //Afghanistan
        68 => 'AX', //Aland Islands
        69 => 'DZ', //Algeria
        70 => 'AS', //American Samoa
        71 => 'AD', //Andorra
        72 => 'AO', //Angola
        73 => 'AI', //Anguilla
        74 => 'AQ', //Antarctica
        75 => 'AG', //Antigua and Barbuda
        76 => 'AR', //Argentina
        77 => 'AW', //Aruba
        78 => 'AZ', //Azerbaijan
        79 => 'BS', //The Bahamas
        80 => 'BH', //Bahrain
        81 => 'BD', //Bangladesh
        82 => 'BB', //Barbados
        83 => 'BZ', //Belize
        84 => 'BJ', //Benin
        85 => 'BM', //Bermuda
        86 => 'BT', //Bhutan
        87 => 'BO', //Bolivia
        88 => 'BA', //Bosnia and Herzegovina
        89 => 'BW', //Botswana
        90 => 'BV', //Bouvet Island
        91 => 'IO', //British Indian Ocean Territory
        92 => 'BN', //Brunei Darussalam
        93 => 'BF', //Burkina Faso
        94 => 'BI', //Burundi
        95 => 'KH', //Cambodia
        96 => 'CM', //Cameroon
        97 => 'CV', //Cape Verde
        98 => 'KY', //Cayman Islands
        99 => 'CF', //Central African Republic
        100 => 'TD', //Chad
        101 => 'CL', //Chile
        102 => 'CX', //Christmas Island
        103 => 'CC', //Cocos Islands/Keeling Islands
        104 => 'CO', //Columbia
        105 => 'KM', //Comoros
        106 => 'CG', //Congo
        107 => 'CD', //Democratic Republic of the Congo
        108 => 'CK', //Cook Islands
        109 => 'CR', //Costa Rica
        110 => 'CI', //Ivory coast
        112 => 'CU', //Cuba
        113 => 'DJ', //Djibouti
        114 => 'DM', //Dominica
        115 => 'DO', //Dominican Republic
        116 => 'EC', //Ecuador
        117 => 'SV', //El Salvador
        118 => 'GQ', //Equatorial Guinea
        119 => 'ER', //Eritrea
        120 => 'ET', //Ethiopia
        121 => 'FK', //Falkland Islands
        122 => 'FO', //Faroe Islands
        123 => 'FJ', //Fiji
        124 => 'GF', //French Guiana
        125 => 'PF', //French Polynesia
        126 => 'TF', //French Southern and Antarctic Lands
        127 => 'GA', //Gabon
        128 => 'GM', //Gambia
        129 => 'GE', //Georgia
        130 => 'GH', //Ghana
        131 => 'GI', //Gibraltar
        132 => 'GL', //Greenland
        133 => 'GD', //Grenada
        134 => 'GU', //Guam
        135 => 'GT', //Guatemala
        136 => 'GG', //Guernsey
        137 => 'GN', //Guinea
        138 => 'GW', //Guinea-Bissau
        139 => 'GY', //Guyana
        140 => 'HT', //Haiti
        141 => 'HM', //Heard Island and McDonald Islands
        142 => 'VA', //Vatican City
        143 => 'HN', //Honduras
        144 => 'IS', //Iceland
        145 => 'ID', //Indonesia
        146 => 'IR', //Iran
        147 => 'IQ', //Iraq
        148 => 'IM', //Isle of Man
        149 => 'JM', //Jamaica
        150 => 'JE', //Jersey
        151 => 'JO', //Jordan
        152 => 'KE', //Kenya
        153 => 'KI', //Kiribati
        154 => 'KP', //Democratic People's Republic of Korea
        155 => 'KR', //Republic of Korea
        156 => 'KW', //Kuwait
        158 => 'LA', //Laos
        159 => 'LB', //Lebanon
        160 => 'LS', //Lesotho
        161 => 'LR', //Liberia
        162 => 'LY', //Libya
        163 => 'MO', //Macao
        164 => 'MK', //Macedonia
        165 => 'MG', //Madagascar
        166 => 'MW', //Malawi
        168 => 'ML', //Mali
        169 => 'MH', //Marshall Islands
        170 => 'MQ', //Martinique
        171 => 'MR', //Mauritania
        172 => 'MU', //Mauritius
        173 => 'YT', //Mayotte
        174 => 'FM', //Micronesia
        175 => 'MD', //Moldova
        176 => 'MN', //Mongolia
        177 => 'ME', //Montenegro
        178 => 'MS', //Montserrat
        179 => 'MZ', //Mozambique
        180 => 'MM', //Myanmar
        181 => 'NA', //Namibia
        182 => 'NR', //Nauru
        183 => 'NP', //Nepal
        184 => 'AN', //Netherlands Antilles
        185 => 'NC', //New Caledonia
        186 => 'NI', //Nicaragua
        187 => 'NE', //Niger
        188 => 'NG', //Nigeria
        189 => 'NU', //Niue
        190 => 'NF', //Norfolk Island
        191 => 'MP', //Northern Mariana Islands
        192 => 'OM', //Oman
        193 => 'PK', //Pakistan
        194 => 'PW', //Palau
        195 => 'PS', //Palestinian territories
        196 => 'PA', //Panama
        197 => 'PG', //Papua New Guinea
        198 => 'PY', //Paraguay
        199 => 'PE', //Peru
        200 => 'PH', //Philippines
        201 => 'PN', //Pitcairn Islands
        202 => 'PR', //Puerto Rico
        203 => 'QA', //Qatar
        204 => 'RE', //Reunion
        205 => 'RW', //Rwanda
        206 => 'SH', //Saint Helena
        207 => 'KN', //Saint Kitts and Nevis
        208 => 'LC', //Saint Lucia
        209 => 'PM', //Saint Pierre and Miquelon
        210 => 'VC', //Saint Vincent and the Grenadines
        211 => 'WS', //Samoa
        212 => 'SM', //San Marino
        213 => 'ST', //Sao Tome and Principe
        214 => 'SA', //Saudi Arabia
        215 => 'SN', //Senegal
        216 => 'RS', //Serbia
        217 => 'SC', //Seychelles
        218 => 'SL', //Sierra Leone
        219 => 'SB', //Solomon Islands
        220 => 'SO', //Somalia
        221 => 'ZA', //South Africa
        222 => 'GS', //South Georgia and the South Sandwich Islands
        223 => 'LK', //Sri Lanka
        224 => 'SD', //Sudan
        225 => 'SR', //Suriname
        226 => 'SJ', //Spitsbergen and Jan Mayen
        227 => 'SZ', //Swaziland
        228 => 'SY', //Syria
        229 => 'TJ', //Tajikistan
        230 => 'TZ', //Tanzania
        231 => 'TL', //Timor-Leste
        232 => 'TG', //Togo
        233 => 'TK', //Tokelau
        234 => 'TO', //Tonga
        235 => 'TT', //Trinidad and Tobago
        236 => 'TN', //Tunisia
        237 => 'TM', //Turkmenistan
        238 => 'TC', //Turks and Caicos Islands
        239 => 'TV', //Tuvalu
        240 => 'UG', //Uganda
        241 => 'UA', //Ukraine
        242 => 'UM', //United States Minor Outlying Islands
        243 => 'UY', //Uruguay
        244 => 'VU', //Vanuatu
        245 => 'VE', //Venezuela
        246 => 'VN', //Vietnam
        247 => 'VG', //British Virgin Islands
        248 => 'VI', //United States Virgin Islands
        249 => 'WF', //Wallis and Futuna
        250 => 'EH', //Western Sahara
        252 => 'ZM', //Zambia
        253 => 'ZW', //Zimbabwe
        254 => 'AE', //United Arab Emirates
        255 => 'DE', //Helgoland
        256 => 'DE', //Buesingen
        258 => 'CUW', //Curaçao
        259 => 'SXM', //Sint Maarten
        260 => 'BES', //BES Islands
        261 => 'BL', //Saint Barthélemy
        262 => 'IT', //Livigno
        263 => 'IT', //Campione d'Italia
        264 => 'IT', //Lake Lugano from Ponte Tresa to Porto Ceresio
        0 => '--', //Unknown
    ];

    public const ORDER_DATE_TYPE_IDS = [
        'DeletedOn' => 1,
        'CreatedOn' => 2,
        'PaidOn' => 3,
        'LastUpdate' => 4,
        'OutgoingItemsBookedOn' => 5,
        'ReturnDate' => 6,
        'PaymentDueDate' => 7,
        'EstimatedShippingDate' => 8,
        'StartDate' => 9,
        'EndDate' => 10,
        'EstimatedDeliveryDate' => 11,
        'TransferDateMarketplace' => 12,
        'CancellationDate' => 13,
        'LastRun' => 14,
        'NextRun' => 15,
        'PurchaseDate' => 16,
        'FinishDate' => 17,
    ];

    public const ORDER_PROPERTY_TYPE_IDS = [
        'WAREHOUSE' => 1,
        'SHIPPING_PROFILE' => 2,
        'PAYMENT_METHOD' => 3,
        'PAYMENT_STATUS' => 4,
        'EXTERNAL_SHIPPING_PROFILE' => 5,
        'DOCUMENT_LANGUAGE' => 6,
        'EXTERNAL_ORDER_ID' => 7,
        'CUSTOMER_SIGN' => 8,
        'DUNNING_LEVEL' => 9,
        'SELLER_ACCOUNT' => 10,
        'WEIGHT' => 11,
        'WIDTH' => 12,
        'LENGTH' => 13,
        'HEIGHT' => 14,
        'FLAG' => 15,
        'EXTERNAL_TOKEN_ID' => 16,
        'EXTERNAL_ITEM_ID' => 17,
        'COUPON_CODE' => 18,
        'COUPON_TYPE' => 19,
        'SALES_TAX_ID_NUMBER' => 34,
        'MAIN_DOCUMENT_NUMBER' => 33,
        'PAYMENT_TRANSACTION_ID' => 45,
        'EXTERNAL_TAX_SERVICE' => 47,
        'MERCHANT_ID' => 60,
        'REPORT_ID' => 61,
        'PREFERRED_STORAGE_LOCATION_ID' => 63,
        'AMAZON_SHIPPING_LABEL' => 64,
        'EBAY_PLUS' => 994,
        'FULFILLMENT_SERVICE' => 995,
    ];

    public const ORDER_PROPERTY_TYPES_FROM_API = [
        1 => 'Warehouse',
        2 => 'Shipping profile',
        3 => 'Payment method',
        4 => 'Payment status',
        5 => 'External Shipping profile',
        6 => 'Document language',
        7 => 'External order ID',
        8 => 'Customer sign',
        9 => 'Dunning level',
        10 => 'Seller account',
        11 => 'Weight',
        12 => 'Width',
        13 => 'Length',
        14 => 'Height',
        15 => 'Flag',
        16 => 'External Token ID',
        17 => 'External item ID',
        18 => 'Coupon code',
        19 => 'Coupon type',
        20 => 'original warehouse',
        21 => 'original quantity',
        22 => 'category',
        23 => 'market fee',
        24 => 'stock (partially) reversed',
        25 => 'dispute status',
        26 => 'Edit by contact forbidden',
        27 => 'interval type',
        28 => 'interval value',
        29 => 'unit',
        30 => 'location reserved',
        31 => 'External item shipment id',
        32 => 'Partial shipping costs',
        33 => 'Document number',
        34 => 'Sales tax identification number',
        35 => 'Return reason',
        36 => 'Item status',
        37 => 'Fulfillment center ID',
        38 => 'Fulfillment center country ISO code',
        39 => 'ID of the associated reorder item',
        40 => 'Listing type',
        41 => 'External order item ID',
        42 => 'Return key ID',
        43 => 'Communication key ID',
        44 => 'With Amazon VCS',
        45 => 'Payment transaction ID',
        46 => 'Sold coupon code',
        47 => 'External VAT calculation service',
        48 => 'Order item status',
        49 => 'External delivery number',
        50 => 'SAP order number',
        51 => 'Settlement ID',
        52 => 'Discount',
        53 => 'Item package unit',
        54 => 'Item minimum purchase',
        55 => 'Item delivery time in days',
        56 => 'Item discountable',
        57 => 'Remaining item value (in %)',
        58 => 'Return by customer',
        60 => 'Merchant ID',
        61 => 'Report ID',
        62 => 'Externe Quellauftrags-ID',
        63 => 'Bevorzugte Lagerort-ID',
        64 => 'Shipping label from Amazon',
        992 => 'Trade representative',
        993 => 'ebay cancellation ID',
        994 => 'With ebay plus',
        995 => 'Fulfillment service',
        996 => 'With click and collect',
        997 => 'With Amazon TCS',
        998 => 'ebay payment transaction ID',
        999 => 'Consent for transmitting data to shipping service provider has b',
    ];

    public const ORDER_ITEM_PROPERTY_TYPE_IDS = [
        'WAREHOUSE' => 1,
        'SHIPPING_PROFILE' => 2,
        'PAYMENT_METHOD' => 3,
        'WEIGHT' => 11,
        'WIDTH' => 12,
        'LENGTH' => 13,
        'HEIGHT' => 14,
        'EXTERNAL_TOKEN_ID' => 16,
        'EXTERNAL_ITEM_ID' => 17,
        'COUPON_CODE' => 18,
        'COUPON_TYPE' => 19,
        'ORIGINAL_WAREHOUSE' => 20,
        'ORIGINAL_QUANTITY' => 21,
        'CATEGORY_ID' => 22,
        'MARKET_FEE' => 23,
        'STOCK_REVERSING' => 24,
        'DISPUTE_STATUS' => 25,
        'NO_CHANGE_BY_CONTACT' => 26,
        'SIZE' => 29,
        'LOCATION_RESERVED' => 30,
        'EXTERNAL_SHIPMENT_ITEM_ID' => 31,
        'PARTIAL_SHIPPING_COSTS' => 32,
        'MAIN_DOCUMENT_NUMBER' => 33,
        'SALES_TAX_ID_NUMBER' => 34,
        'RETURNS_REASON' => 35,
        'RETURNS_ITEM_STATUS' => 36,
        'FULFILLMENT_CENTER_ID' => 37,
        'FULFILLMENT_CENTER_COUNTRY_ISO' => 38,
        'REORDER_ITEM_ID' => 39,
        'LISTING_TYPE' => 40,
        'SOLD_COUPON_CODE' => 46,
        'ORDER_ITEM_STATE' => 48,
    ];

    public const ADDRESS_TYPE_IDS = [
        'billing' => 1,
        'delivery' => 2,
        'sender' => 3,
        'return' => 4,
        'client' => 5,
        'contractor' => 6,
        'warehouse' => 7,
        'pos' => 8,
    ];

    public const ADDRESS_OPTION_TYPE_IDS = [
        'VATNumber' => 1,
        'ExternalAddressID' => 2,
        'EntryCertificate' => 3,
        'Telephone' => 4,
        'Email' => 5,
        'PostNumber' => 6,
        'PersonalId' => 7,
        'BBFC' => 8,
        'Birthday' => 9,
        'SessionID' => 10,
        'Title' => 11,
        'ContactPerson' => 12,
    ];

    public const CONTACT_TYPE_IDS = [
        'Customer' => 1,
        'SalesLead' => 2,
        'SalesRepresentative' => 3,
        'Supplier' => 4,
        'Producer' => 5,
        'Partner' => 6,
    ];

    public const CONTACT_OPTION_TYPE_IDS = [
        'Telephone' => 1,
        'Email' => 2,
        'Telefax' => 3,
        'WebPage' => 4,
        'Marketplace' => 5,
        'IdentificationNumber' => 6,
        'Payment' => 7,
        'UserName' => 8,
        'Group' => 9,
        'Access' => 10,
        'Additional' => 11,
        'Salutation' => 12,
        'ConvertedBy' => 13,
    ];

    public const REFERRER_IDS = [
        'ManuelleEingabe' => '0',
        'MandantShop' => '1',
        'Ebay' => '2',
        'EBayUnitedStates' => '2.01',
        'EBayCanadaEnglish' => '2.02',
        'EBayUK' => '2.03',
        'EBayAustralia' => '2.04',
        'EBayAustria' => '2.05',
        'EBayBelgiumFrench' => '2.06',
        'EBayFrance' => '2.07',
        'EBayGermany' => '2.08',
        'EBayMotors' => '2.09',
        'EBayItaly' => '2.10',
        'EBayBelgiumDutch' => '2.11',
        'EBayNetherlands' => '2.12',
        'EBaySpain' => '2.13',
        'EBaySwitzerland' => '2.14',
        'EBayHongKong' => '2.15',
        'EBayIndia' => '2.16',
        'EBayIreland' => '2.17',
        'EBayMalaysia' => '2.18',
        'EBayCanadaFrench' => '2.19',
        'EBayPhilippines' => '2.20',
        'EBayPoland' => '2.21',
        'EBaySingapore' => '2.22',
        'Elmar' => '3',
        'Amazon' => '4',
        'AmazonGermany' => '4.01',
        'AmazonUK' => '4.02',
        'AmazonUSA' => '4.03',
        'AmazonFrance' => '4.04',
        'AmazonItaly' => '4.05',
        'AmazonSpain' => '4.06',
        'AmazonCanada' => '4.07',
        'AmazonMexico' => '4.08',
        'AmazonGermanyB2B' => '4.21',
        'AmazonUKB2B' => '4.22',
        'Yatego' => '5',
        'Kelkoo' => '6',
        'GoogleProducts' => '7',
        'Shopify' => '9',
        'DIB' => '10',
        'Ricardo' => '101',
        'RealDe' => '102',
        'Kassensystem' => '103',
        'AmazonFBA' => '104',
        'AmazonFBAGermany' => '104.01',
        'AmazonFBAUK' => '104.02',
        'AmazonFBAUSA' => '104.03',
        'AmazonFBAFrance' => '104.04',
        'AmazonFBAItaly' => '104.05',
        'AmazonFBASpain' => '104.06',
        'AmazonFBACanada' => '104.07',
        'AmazonFBAMexico' => '104.08',
        'AmazonFBAGermanyB2B' => '104.21',
        'AmazonFBAUKB2B' => '104.22',
        'ZentralverkaufDe' => '105',
        'RakutenDe' => '106',
        'RakutenCoUk' => '106.02',
        'NeckermannDeEnterprise' => '107',
        'OttoCooperation' => '108',
        'OttoIntegration' => '108.02',
        'OTTODirektversand' => '108.03',
        'Shopgate' => '109',
        'Allyouneed' => '110',
        'Gimahhot' => '111',
        'BilligerDe' => '112',
        'ShopShare' => '113',
        'Quelle' => '114',
        'Restposten' => '115',
        'Kauflux' => '116',
        'Home24' => '117',
        'Zalando' => '118',
        'NeckermannAtEnterprise' => '119',
        'NeckermannAtCrossDocking' => '120',
        'Idealo' => '121',
        'IdealoDirektkauf' => '121.02',
        'LaRedoute' => '122',
        'Larry' => '123',
        'SumoScout' => '124',
        'Hood' => '125',
        'ParfumDEAL' => '126',
        'BeezUP' => '127',
        'Tracdelight' => '130',
        'PlusDe' => '131',
        'GartenXXLDe' => '132',
        'Twenga' => '133',
        'SporTrade' => '134',
        'Newsletter2Go' => '135',
        'PlayCom' => '136',
        'GrosshandelEu' => '137',
        'Hertie' => '138',
        'CouchCommerce' => '139',
        'MyBestBrands' => '142',
        'CdiscountCom' => '143',
        'CdiscountComCLogistique' => '143.02',
        'DaWandaCom' => '144',
        'Fruugo' => '145',
        'Flubit' => '147',
        'WEBAPI' => '148',
        'Mercateo' => '149',
        'Check24' => '150',
        'BOLCom' => '152',
        'Criteo' => '153',
        'Netto' => '154',
        'GartenXXLAt' => '155',
        'OTTO' => '160',
        'OTTOCooperation' => '160.10',
    ];
}
