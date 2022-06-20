<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\CryptoHelper;
use AS2\MessageInterface;
use AS2\MimePart;
use AS2\Utils;

/**
 * Class Management
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Management extends \AS2\Management
{
    /**
     * Function decompresses, decrypts and verifies the received AS2 message
     * Takes an AS2 message as input and returns the actual payload ex. X12 message.
     *
     * @return MimePart
     */
    public function processMessage(MessageInterface $message, MimePart $payload)
    {
        $this->getLogger()->info(
            'Begin Processing of received AS2 message',
            [
                'message_id' => $message->getMessageId(),
            ]
        );

        $body = Utils::normalizeBase64($payload->getBody());

        // Force encode binary data to base64, `openssl_pkcs7_` doesn't work with binary data
        if (! Utils::decodeBase64($body)) {
            $body = Utils::encodeBase64($body);
            $payload->setBody($body);
        }

        unset($body);

        // Check if message from this partner are expected to be encrypted
        if (! $payload->isEncrypted() && $message->getSender()->getEncryptionAlgorithm()) {
            throw new \RuntimeException(sprintf('Incoming message from AS2 partner `%s` are defined to be encrypted',
                $message->getSender()->getAs2Id()));
        }

        $isDecompressed = false;
        $micContent = null;
        $micAlg = null;

        // Check if payload is encrypted and if so decrypt it
        if ($payload->isEncrypted()) {
            $this->getLogger()->debug('Inbound AS2 message is encrypted.');
            $payload = CryptoHelper::decrypt(
                $payload,
                $message->getReceiver()->getCertificate(),
                [
                    $message->getReceiver()->getPrivateKey(),
                    $message->getReceiver()->getPrivateKeyPassPhrase(),
                ]
            );

            $this->getLogger()->debug('The inbound AS2 message data has been decrypted.');
            $message->setEncrypted();
        }

        // Check for compression before signature check
        if ($payload->isCompressed()) {
            $this->getLogger()->debug('Decompressing received message before checking signature...');
            $payload = CryptoHelper::decompress($payload);
            $isDecompressed = true;
            $message->setCompressed();
        }

        // Check if message from this partner are expected to be signed
        // if (!$payload->isSigned() && $message->getSender()->getSignatureAlgorithm()) {
        //     throw new \RuntimeException(sprintf('Incoming message from AS2 partner `%s` are defined to be signed.', $message->getSender()->getAs2Id()));
        // }

        // Check if message is signed and if so verify it
        if ($payload->isSigned()) {
            $this->getLogger()->debug('Message is signed, Verifying it using public key.');

            $message->setSigned();

            // Get the partners public and ca certificates
            // TODO: refactory
            $cert = $message->getSender()->getCertificate();

            if (empty($cert)) {
                throw new \RuntimeException('Partner has no signature verification key defined');
            }

            // Verify message using raw payload received from partner
//            if (! CryptoHelper::verify($payload, $cert)) {
//                throw new \RuntimeException('Signature Verification Failed');
//            }

            $this->getLogger()->debug('Digital signature of inbound AS2 message has been verified successful.');
            $this->getLogger()->debug(
                sprintf(
                    'Found %s payload attachments in the inbound AS2 message.',
                    $payload->getCountParts() - 1
                )
            );

            /*
             * Calculate the MIC after signing or encryption of the message but prior to
             * doing any decompression but include headers for unsigned messages
             * (see RFC4130 section 7.3.1 for details)
             */
            $micAlg = $payload->getParsedHeader('Disposition-Notification-Options', 2, 0);
            if (! $micAlg) {
                $micAlg = $payload->getParsedHeader('Content-Type', 0, 'micalg');
            }

            foreach ($payload->getParts() as $part) {
                if (! $part->isPkc7Signature()) {
                    $payload = $part;
                }
            }

            $micContent = $payload;
        }

        // Check if the message has been compressed and if so decompress it
        if ($payload->isCompressed()) {
            // Per RFC5402 compression is always before encryption but can be before or
            // after signing of message but only in one place
            if ($isDecompressed) {
                throw new \RuntimeException('Message has already been decompressed. Per RFC5402 it cannot occur twice.');
            }
            $this->getLogger()->debug('Decompressing received message after decryption...');
            $payload = CryptoHelper::decompress($payload);
            $message->setCompressed();
        }

        // Saving the message mic for sending it in the MDN
        if ($micContent !== null) {
            // Saving the message mic for sending it in the MDN
            $message->setMic(CryptoHelper::calculateMIC($micContent, $micAlg));
        }

        return $payload;
    }
}