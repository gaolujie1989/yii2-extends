<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\test;

use Faker\Factory;
use Faker\Generator;
use Faker\ORM\Spot\EntityPopulator;
use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ValueHelper;
use PHPUnit\Framework\Assert;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use function Qcloud\Cos\startWith;

/**
 * Class ModelAutomaticUnitTester
 * @package lujie\extend\test
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AutomaticUnitTester extends BaseAutomaticTester
{
    /**
     * @var BaseActiveRecord|string
     */
    public $modelClass;

    /**
     * @var BaseActiveRecord|string
     */
    public $formClass;

    /**
     * @var BaseActiveRecord|string
     */
    public $searchClass;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->modelClass)) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
        if (empty($this->formClass)) {
            $this->formClass = ClassHelper::getFormClass($this->modelClass);
        }
        if (empty($this->searchClass)) {
            $this->searchClass = ClassHelper::getSearchClass($this->modelClass);
        }
    }

    protected function testWithEmptyValuesAndReturnErrors(): array
    {
        $model = new $this->formClass();
        Assert::assertFalse($model->validate());
        return $model->getErrors();
    }

    protected function testWithInvalidValuesAndReturnErrors(array $invalidValues): array
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->formClass();
        $model->setAttributes($invalidValues);
        Assert::assertFalse($model->validate());
        return $model->getErrors();
    }

    protected function testWithValidValuesAndReturnSavedValues(array $validValues): array
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->formClass();
        $model->setAttributes($validValues);
        Assert::assertTrue($model->validate());
        Assert::assertTrue($model->save());
        $model->refresh();
        return $model->toArray(
            array_merge($this->editableAttributes, $this->readonlyAttributes, $this->linkableAttributes),
            array_keys($this->relations)
        );
    }
}
