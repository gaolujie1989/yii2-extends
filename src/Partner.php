<?php

namespace lujie\as2;

use AS2\PartnerInterface;
use lujie\as2\models\As2Partner;
use yii\base\BaseObject;

/**
 * Class Partner
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Partner extends BaseObject implements PartnerInterface
{
    /**
     * @var As2Partner
     */
    private $as2Partner;

    /**
     * @param As2Partner $as2Partner
     * @param array $config
     */
    public function __construct(As2Partner $as2Partner, array $config = [])
    {
        $this->as2Partner = $as2Partner;
        parent::__construct($config);
    }

    public function getAs2Id(): string
    {
        return $this->as2Partner->as2_id;
    }

    public function getEmail(): string
    {
        return $this->as2Partner->email;
    }

    public function getTargetUrl(): string
    {
        return $this->as2Partner->target_url;
    }

    public function getContentType(): string
    {
        return $this->as2Partner->content_type;
    }

    public function getContentTransferEncoding(): string
    {
        return $this->as2Partner->content_transfer_encoding;
    }

    public function getSubject(): string
    {
        return $this->as2Partner->subject;
    }

    public function getAuthMethod(): ?string
    {
        return $this->as2Partner->auth_method;
    }

    public function getAuthUser(): string
    {
        return $this->as2Partner->auth_user;
    }

    public function getAuthPassword(): string
    {
        return $this->as2Partner->auth_password;
    }

    public function getSignatureAlgorithm(): ?string
    {
        return $this->as2Partner->signature_algorithm;
    }

    public function getEncryptionAlgorithm(): ?string
    {
        return $this->as2Partner->encryption_algorithm;
    }

    public function getCertificate(): string
    {
        return $this->as2Partner->certificate;
    }

    public function getPrivateKey(): string
    {
        return $this->as2Partner->private_key;
    }

    public function getPrivateKeyPassPhrase(): string
    {
        return $this->as2Partner->private_key_pass_phrase;
    }

    public function getCompressionType(): string
    {
        return $this->as2Partner->compression_type;
    }

    public function getMdnMode(): string
    {
        return $this->as2Partner->mdn_mode;
    }

    public function getMdnOptions(): string
    {
        return $this->as2Partner->mdn_options;
    }

    public function getMdnSubject(): string
    {
        return $this->as2Partner->mdn_subject;
    }
}