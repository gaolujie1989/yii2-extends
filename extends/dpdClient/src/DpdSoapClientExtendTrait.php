<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd;

use lujie\dpd\constants\DpdConst;
use lujie\dpd\helpers\DpdSoapHelper;
use lujie\dpd\soap\Type\FaultCodeType;
use lujie\dpd\soap\Type\ParcelInformationType;
use lujie\dpd\soap\Type\ShipmentResponse;
use lujie\dpd\soap\Type\ShipmentServiceData;
use lujie\dpd\soap\Type\StoreOrders;
use lujie\extend\models\AddressInterface;
use yii\base\UserException;
use yii\helpers\Json;

/**
 * Trait DpdSoapClientExtendTrait
 * @package lujie\dpd
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait DpdSoapClientExtendTrait
{
    /**
     * @param AddressInterface $sender
     * @param string $senderAddressType
     * @param AddressInterface $recipient
     * @param string $recipientAddressType
     * @param string $refId
     * @param array $refNos
     * @param string $orderType
     * @return ParcelInformationType
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function createSingleParcel(
        AddressInterface $sender,
        string           $senderAddressType,
        AddressInterface $recipient,
        string           $recipientAddressType,
        string           $refId,
        array            $refNos = [],
        string           $orderType = DpdConst::ORDER_TYPE_CONSIGNMENT,
        int              $weightG = 0,
        int              $volumeMM3 = 0
    ): ParcelInformationType
    {
        $generalShipmentData = DpdSoapHelper::createGeneralShipmentData(
            $sender,
            $senderAddressType,
            $recipient,
            $recipientAddressType,
            $this->getSendingDepot(),
            $refId,
            $refNos,
            $weightG,
            $volumeMM3,
        );
        $order = new ShipmentServiceData([
            'generalShipmentData' => $generalShipmentData,
            'productAndServiceData' => DpdSoapHelper::createProductAndServiceData($orderType),
        ]);

        $storeOrders = new StoreOrders();
        $storeOrders->setPrintOptions(DpdSoapHelper::createPrintOptions());
        $storeOrders->setOrder($order);

        $storeOrdersResponse = $this->storeOrders($storeOrders);
        $shipmentResponse = $storeOrdersResponse->getOrderResult()->getShipmentResponses();
        if (is_array($shipmentResponse)) {
            $shipmentResponse = $shipmentResponse[0];
        }
        $faults = $shipmentResponse->getFaults();
        if ($faults) {
            $faults = array_map(static function (FaultCodeType $fault) {
                return [
                    'faultCode' => $fault->getFaultCode(),
                    'message' => $fault->getMessage(),
                ];
            }, is_array($faults) ? $faults : [$faults]);
            throw new UserException('DPD StoreOrders with faults: ' . Json::encode($faults));
        }
        $parcelInformation = $shipmentResponse->getParcelInformation();
        return is_array($parcelInformation) ? $parcelInformation[0] : $parcelInformation;
    }
}
