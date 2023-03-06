<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test;

use yii\db\BaseActiveRecord;
use yii\test\BaseActiveFixture;

/**
 * Class ActiveFixture
 * @package lujie\extend\test
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordFixture extends BaseActiveFixture
{
    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function load(): void
    {
        $this->data = [];
        foreach ($this->getData() as $alias => $row) {
            /** @var BaseActiveRecord $model */
            $model = new $this->modelClass();
            $model->setAttributes($row);
            $model->save(false);
            $this->data[$alias] = $model;
        }
    }

    /**
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function unload(): void
    {
        /** @var BaseActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        /** @var BaseActiveRecord[] $models */
        $models = $modelClass::find()->all();
        foreach ($models as $model) {
            $model->delete();
        }
        parent::unload();
    }
}
