<?php

namespace lujie\as2;

use AS2\PartnerInterface;
use lujie\as2\models\As2Message;
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

    public function getAs2Id()
    {
        return $this->as2Partner->as2_id;
    }

    public function getEmail()
    {
        // TODO: Implement getEmail() method.
    }

    public function getTargetUrl()
    {
        // TODO: Implement getTargetUrl() method.
    }

    public function getContentType()
    {
        // TODO: Implement getContentType() method.
    }

    public function getContentTransferEncoding()
    {
        // TODO: Implement getContentTransferEncoding() method.
    }

    public function getSubject()
    {
        // TODO: Implement getSubject() method.
    }

    public function getAuthMethod()
    {
        // TODO: Implement getAuthMethod() method.
    }

    public function getAuthUser()
    {
        // TODO: Implement getAuthUser() method.
    }

    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    public function getSignatureAlgorithm()
    {
        // TODO: Implement getSignatureAlgorithm() method.
    }

    public function getEncryptionAlgorithm()
    {
        // TODO: Implement getEncryptionAlgorithm() method.
    }

    public function getCertificate()
    {
        // TODO: Implement getCertificate() method.
    }

    public function getPrivateKey()
    {
        // TODO: Implement getPrivateKey() method.
    }

    public function getPrivateKeyPassPhrase()
    {
        // TODO: Implement getPrivateKeyPassPhrase() method.
    }

    public function getCompressionType()
    {
        // TODO: Implement getCompressionType() method.
    }

    public function getMdnMode()
    {
        // TODO: Implement getMdnMode() method.
    }

    public function getMdnOptions()
    {
        // TODO: Implement getMdnOptions() method.
    }

    public function getMdnSubject()
    {
        // TODO: Implement getMdnSubject() method.
    }
}