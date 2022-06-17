<?php
/**
 * @copyright Copyright (c) 2019
 */

use AS2\MessageInterface;
use AS2\PartnerInterface;

return [
    'as2MessageStatus' => [
        'PENDING' => MessageInterface::STATUS_PENDING,
        'SUCCESS' => MessageInterface::STATUS_SUCCESS,
        'ERROR' => MessageInterface::STATUS_ERROR,
        'WARNING' => MessageInterface::STATUS_WARNING,
        'RETRY' => MessageInterface::STATUS_RETRY,
        'IN_PROCESS' => MessageInterface::STATUS_IN_PROCESS,
    ],
    'as2MessageDirection' => [
        'INBOUND' => MessageInterface::DIR_INBOUND,
        'OUTBOUND' => MessageInterface::DIR_OUTBOUND,
    ],
    'as2MdnStatus' => [
        'PENDING' => MessageInterface::MDN_STATUS_PENDING,
        'RECEIVED' => MessageInterface::MDN_STATUS_RECEIVED,
        'SENT' => MessageInterface::MDN_STATUS_SENT,
        'ERROR' => MessageInterface::MDN_STATUS_ERROR,
    ],
    'as2MdnMode' => [
        'SYNC' => PartnerInterface::MDN_MODE_SYNC,
        'ASYNC' => PartnerInterface::MDN_MODE_ASYNC,
    ],
    'as2Signature' => [
        'SHA256' => ['value' => 'SHA256', 'name' => 'SHA256'],
        'SHA512' => ['value' => 'SHA512', 'name' => 'SHA512'],
    ],
    'as2Encryption' => [
        'AES-128' => ['value' => 'AES_128_CBC', 'name' => 'AES-128'],
        'AES-192' => ['value' => 'AES_192_CBC', 'name' => 'AES-192'],
        'AES-256' => ['value' => 'AES_256_CBC', 'name' => 'AES-256'],
    ],
    'as2Compression' => [
        'null' => '0',
        'zlib' => 'zlib',
        'deflate' => 'deflate'
    ],
];