<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\barcode\assigning;

use lujie\barcode\assigning\models\Barcode;
use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ModelHelper;
use lujie\extend\helpers\TransactionHelper;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\base\UserException;
use yii\db\BaseActiveRecord;

/**
 * Class BarcodeAssigner
 * @package lujie\barcode\assigning
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BarcodeAssigner extends BaseObject
{
    /**
     * @var string[]
     */
    public $modelTypes = [
        'XXX_CLASS' => 'XXX_TYPE'
    ];

    /**
     * @param BaseActiveRecord $model
     * @return string|null
     * @inheritdoc
     */
    protected function getModelType(BaseActiveRecord $model): ?string
    {
        foreach ($this->modelTypes as $modelClass => $modelType) {
            if ($model instanceof $modelClass) {
                return $modelType;
            }
        }
        return null;
    }

    /**
     * @param BaseActiveRecord $model
     * @param string $barcodeField
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function assign(BaseActiveRecord $model, string $barcodeField, string $codeType = Barcode::CODE_TYPE_EAN): bool
    {
        $modelType = $this->getModelType($model);
        if ($modelType === null) {
            throw new InvalidArgumentException('Invalid model class');
        }
        $barcode = Barcode::find()->codeType($codeType)->unassigned()->one();
        if ($barcode === null) {
            throw new UserException("{$codeType} is empty");
        }
        $barcode->model_id = $model->primaryKey[0];
        $barcode->model_type = $modelType;
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
    public function revoke(BaseActiveRecord $model, string $barcodeField, string $codeType = Barcode::CODE_TYPE_EAN): bool
    {
        $modelType = $this->getModelType($model);
        if ($modelType === null) {
            throw new InvalidCallException('Invalid model class');
        }
        $modelId = $model->primaryKey[0];
        $codeText = $model->{$barcodeField};
        $barcode = Barcode::find()->codeType($codeType)->codeText($codeText)->modelType($modelType)->modelId($modelId)->one();
        if ($barcode === null) {
            throw new UserException('Barcode not assigned');
        }
        $barcode->model_id = 0;
        $barcode->model_type = '';
        $model->{$barcodeField} = '';
        return TransactionHelper::transaction(static function () use ($barcode, $model, $barcodeField) {
            return $barcode->save(false) && $model->save(false, [$barcodeField]);
        }, $barcode::getDb());
    }
}
