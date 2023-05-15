<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\otto;

use lujie\data\exchange\transformers\TransformerInterface;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\BaseObject;

/**
 * Class PmSalesChannelOrderTransformer
 * @package lujie\sales\channel\channels\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoSalesChannelOrderTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @param SalesChannelOrder[] $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return array_map([$this, 'transformSalesChannelOrder'], $data);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @return array
     * @inheritdoc
     */
    protected function transformSalesChannelOrder(SalesChannelOrder $salesChannelOrder): ?array
    {
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            $additional = $salesChannelOrder->additional;
            return [
                'trackingKey' => [
                    'carrier' => $additional['carrier'],
                    'trackingNumber' => $additional['trackingNumbers'],
                ],
                'shipDate' => date('c', $additional['shipped_at']),
            ];
        }
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
            return [];
        }
        return null;
    }
}
