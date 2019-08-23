<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode\assigning;

use lujie\barcode\assigning\models\Barcode;
use lujie\extend\helpers\TransactionHelper;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\db\BaseActiveRecord;

/**
 * Class BarcodeAssigner
 * @package lujie\barcode\assigning
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BarcodeAssigner extends BaseObject
{
    /**
     * @param BaseActiveRecord $model
     * @param string $barcodeField
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function assignEAN(BaseActiveRecord $model, string $barcodeField): bool
    {
        $barcode = Barcode::find()->ean()->unassigned()->one();
        if ($barcode === null) {
            throw new InvalidCallException('EAN is empty');
        }
        $barcode->assigned_id = $model->primaryKey[0];
        $model->{$barcodeField} = $barcode->code_text;
        return TransactionHelper::transaction(static function () use ($barcode, $model, $barcodeField) {
            return $barcode->save(false) && $model->save(false, [$barcodeField]);
        }, $barcode::getDb());
    }

    /**
     * @param BaseActiveRecord $model
     * @param string $barcodeField
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function revokeEAN(BaseActiveRecord $model, string $barcodeField): bool
    {
        $assignedId = $model->primaryKey[0];
        $codeText = $model->{$barcodeField};
        $barcode = Barcode::find()->ean()->assignedId($assignedId)->codeText($codeText)->one();
        if ($barcode === null) {
            throw new InvalidArgumentException('Invalid EAN');
        }
        $barcode->assigned_id = 0;
        $model->{$barcodeField} = '';
        return TransactionHelper::transaction(static function () use ($barcode, $model, $barcodeField) {
            return $barcode->save(false) && $model->save(false, [$barcodeField]);
        }, $barcode::getDb());
    }
}
