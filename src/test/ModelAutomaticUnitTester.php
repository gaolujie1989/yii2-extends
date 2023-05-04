<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\test;

use Faker\Factory;
use Faker\Generator;
use lujie\extend\helpers\ClassHelper;
use PHPUnit\Framework\Assert;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;

/**
 * Class ModelAutomaticUnitTester
 *
 * 自动测试流程
 * 数据类型格式和范围的测试没必要，Validator已经测试过了，
 * 只需要测试必填像和不可修改的字段，这两种字段一般都得手动设置，Relation的必填像和不可修改的字段也得测试
 * 查询测试主要测试查询条件字段是否已经生效，主要针对关联查询和自定义查询，默认的查询已经测试过了
 *
 * @package lujie\extend\test
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelAutomaticUnitTester extends BaseObject
{
    public $modelClass;

    public $formClass;

    public $searchClass;

    public $language;

    /**
     * @var Generator Faker generator instance
     */
    private $_generator;

    public function getGenerator(): Generator
    {
        if ($this->_generator === null) {
            $language = $this->language ?? Yii::$app->language;
            $this->_generator = Factory::create(str_replace('-', '_', $language));
        }
        return $this->_generator;
    }

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

    public function formRules(): array
    {
        return [];
    }

    public function searchRules(): array
    {
        return [];
    }

    public function generateInvalidData(array $rules): array
    {

    }

    public function generateValidData(array $rules): array
    {

    }

    /**
     * @param string $attribute
     * @param int $type
     * @return int|string|void
     * @inheritdoc
     */
    public function generateValue(string $attribute, int $type)
    {
        $generator = $this->getGenerator();
        switch ($type) {
            case 'string':
                return $generator->text();
                break;
            case 'int':
                return $generator->numberBetween();
                break;
            case 'number':
                return $generator->randomNumber();
                break;
        }
    }

    public function testSave(): void
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->formClass();
        $formRules = $this->formRules();
        $values = [];
        $generator = $this->getGenerator();
        foreach ($formRules as $rule) {
            $attributes = (array)array_shift($rule);
            $ruleType = array_shift($rule);
            $ruleConfig = $rule;
            switch ($ruleType) {
                case 'required':
                    Assert::assertFalse($model->save());
                    $errors = $model->getErrors();
                    Assert::assertNotEmpty($errors);
                    foreach ($attributes as $attribute) {
                        Assert::assertArrayHasKey($attribute, $errors);
                        Assert::stringContains($errors[$attribute], 'required');
                    }
                    foreach ($attributes as $attribute) {
                        $values[$attribute] = $generator->text();
                    }
                    break;
                case 'string':
                    Assert::assertArrayHasKey('max', $ruleConfig);
                    Assert::assertNotEmpty($ruleConfig['max']);
                    foreach ($attributes as $attribute) {
                        $values[$attribute] = $generator->text($ruleConfig['max'] + 1);
                    }
                    Assert::assertFalse($model->save());
                    $errors = $model->getErrors();
                    Assert::assertNotEmpty($errors);
                    foreach ($attributes as $attribute) {
                        Assert::assertArrayHasKey($attribute, $errors);
                        Assert::stringContains($errors[$attribute], 'max');
                    }
                    foreach ($attributes as $attribute) {
                        $values[$attribute] = $generator->text($ruleConfig['max'] + 1);
                    }
                    break;
                case 'integer':
                    $min = $ruleConfig['min'] ?? -2147483648;
                    $max = $ruleConfig['max'] ?? 2147483647;
                    foreach ($attributes as $attribute) {
                        $values[$attribute] = $generator->numberBetween($min, $max);
                    }
                    Assert::assertFalse($model->save());
                    $errors = $model->getErrors();
                    Assert::assertNotEmpty($errors);
                    foreach ($attributes as $attribute) {
                        Assert::assertArrayHasKey($attribute, $errors);
                        Assert::stringContains($errors[$attribute], 'max');
                    }
                    foreach ($attributes as $attribute) {
                        $values[$attribute] = $generator->text($ruleConfig['max'] + 1);
                    }
                    break;
                case 'number':
                    break;
            }
        }
    }

    public function testSearch(): void
    {

    }
}
