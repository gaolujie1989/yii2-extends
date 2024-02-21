<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\data\exchange\DataExchanger;
use lujie\sales\channel\importers\OttoBrandImporter;
use yii\base\UserException;

/**
 * Class PullOttoBrandTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullOttoBrandTask extends BasePullOttoCommonItemsTask
{
    /**
     * @var DataExchanger
     */
    public $importer = OttoBrandImporter::class;

    /**
     * @return \Generator
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function batchOttoItems(): \Generator
    {
        $salesChannel = $this->getOttoSalesChannel();
        return $salesChannel->client->batchV3ProductBrands(['page' => $this->page]);
    }
}
