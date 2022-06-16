<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2;

use AS2\PartnerInterface;
use AS2\PartnerRepositoryInterface;
use lujie\as2\models\As2Partner;
use yii\base\BaseObject;

/**
 * Class PartnerRepository
 * @package lujie\as2
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PartnerRepository extends BaseObject implements PartnerRepositoryInterface
{
    /**
     * @param string $id
     * @return PartnerInterface|null
     * @inheritdoc
     */
    public function findPartnerById($id): ?PartnerInterface
    {
        $as2Partner = As2Partner::find()->as2Id($id)->one();
        return $as2Partner ? new Partner($as2Partner) : null;
    }
}