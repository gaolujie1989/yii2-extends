<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\test;

use lujie\extend\helpers\ValueHelper;
use PHPUnit\Framework\Assert;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class BaseAutomaticTester
 *
 * Create/Update测试流程
 * 数据类型格式和范围的测试没必要，Validator已经测试过了
 * 只需要测试一些需要手动设置的字段
 * 1. 必填字段测试
 * 2. 不可修改字段测试
 * 3. 关联字段测试，是否自动验证关联
 * 4. Relation的字段测试
 *
 * Search测试流程
 * 主要针对手动设置的关联查询和自定义查询，默认的查询条件测试，主要针对相同字段报错的情况
 * 1. 先测试单个字段查询条件是否生效
 * 2. 测试多个字段查询条件是否生效，主要是看是否有冲突
 *
 * @package lujie\extend\test
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseAutomaticTester extends BaseObject
{
    /**
     * @var array
     * [
     *  'xxx_no' => ['string', 20],
     *  'xxx_qty' => ['integer'],
     *  'xxx_price' => ['double', 10, 'decimals' => 3],
     *  'xxx_status' => ['integer', 'values' => [1, 2, 3]],
     *  'xxxItems' => [true, [
     *      'xxx_no' => ['string', 20],
     *  ]]
     *  'xxxAddress' => [false, [
     *      'xxx_no' => ['string', 20],
     *  ]]
     */
    public $attributeTypes = [];

    public $requiredAttributes = [];

    public $editableAttributes = [];

    public $readonlyAttributes = [];

    public $linkableAttributes = [];

    /**
     * @var array
     * ['relation' => ['indexKey1', 'indexKey2']]
     */
    public $relations = [];

    /**
     * @var array
     * [
     *  'xxx_no' => LIKE/LEFT_LIKE/BETWEEN
     *  'status' => IN
     *  'xxx_at' => BETWEEN
     * ]
     */
    public $searchableAttributes = [];

    /**
     * @var FakerGuesser
     */
    public $fakerGuesser;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->fakerGuesser === null) {
            $this->fakerGuesser = new FakerGuesser();
        }
    }

    /**
     * @param bool $valid
     * @return array
     * @inheritdoc
     */
    protected function generateValues(bool $valid = true): array
    {
        return $this->fakerGuesser->guessValues($this->attributeTypes, $valid);
    }

    public function testRequired(): void
    {
        $message = 'Test with empty values, validation should be failed and only with required fields errors';
        $errors = $this->testWithEmptyValuesAndReturnErrors();
        Assert::assertNotEmpty($errors, $message);
        Assert::assertEquals($this->requiredAttributes, array_keys($errors), $message);
    }

    /**
     * @return array
     * @inheritdoc
     */
    abstract protected function testWithEmptyValuesAndReturnErrors(): array;

    public function testWithInvalidValues(): void
    {
        $message = 'Test with invalid values, validation should be failed and only with editable fields errors';
        $invalidValues = $this->generateValues(false);
        $errors = $this->testWithInvalidValuesAndReturnErrors($invalidValues);
        Assert::assertNotEmpty($errors, $message);
        Assert::assertEquals($this->editableAttributes, array_keys($errors), $message);
    }

    abstract protected function testWithInvalidValuesAndReturnErrors(array $invalidValues): array;

    /**
     * @inheritdoc
     */
    public function testWithValidValues(): void
    {
        //test editable fields valid success
        $message = 'Test with valid values, validation should be success and fields should be saved';
        $validValues = $this->generateValues(true);
        $savedValues = $this->testWithValidValuesAndReturnSavedValues($validValues);
        $editableValues = array_intersect_key($validValues, array_flip($this->editableAttributes));
        $savedEditableValues = array_intersect_key($savedValues, array_flip($this->editableAttributes));
        Assert::assertEquals($editableValues, $savedEditableValues, $message);

        if ($this->readonlyAttributes) {
            $message = 'Readonly fields should not be changed, with default empty values';
            $readonlyValues = array_intersect_key($validValues, array_flip($this->readonlyAttributes));
            Assert::assertNotEmpty(array_filter($readonlyValues), $message);
            $savedReadonlyValues = array_intersect_key($savedValues, array_flip($this->readonlyAttributes));
            Assert::assertEmpty(array_filter($savedReadonlyValues), $message);
        }

        if ($this->linkableAttributes) {
            $message = 'Linkable fields should be saved';
            $linkableValues = array_intersect_key($validValues, array_flip($this->linkableAttributes));
            $savedLinkableValues = array_intersect_key($savedValues, array_flip($this->linkableAttributes));
            Assert::assertNotEmpty(array_filter($savedLinkableValues), $message);
            Assert::assertNotEquals($linkableValues, $savedLinkableValues, $message);
        }

        if ($this->relations) {
            $message = 'Relations should be saved';
            $relations = array_intersect_key($validValues, $this->relations);
            Assert::assertNotEmpty($relations, $message);
            $savedRelations = array_intersect_key($savedValues, $this->relations);
            Assert::assertEquals(array_keys($relations), array_keys($savedRelations), $message);
            foreach ($relations as $relationKey => $relationValues) {
                $savedRelationValues = $savedRelations[$relationKey];
                if (ArrayHelper::isAssociative($relationValues)) { //not multiple
                    $savedRelationValues = array_intersect_key($savedEditableValues, $relationValues);
                } else {
                    foreach ($savedRelationValues as $index => $values) {
                        $savedRelationValues[$index] = array_intersect_key($values, reset($relationValues));
                    }
                    if ($indexKeys = $this->relations[$relationKey]) {
                        $relationValues = ArrayHelper::index($relationValues, static function ($values) use ($indexKeys) {
                            ValueHelper::getIndexValues($values, $indexKeys);
                        });
                        $savedRelationValues = ArrayHelper::index($savedRelationValues, static function ($values) use ($indexKeys) {
                            ValueHelper::getIndexValues($values, $indexKeys);
                        });
                    }
                }
                Assert::assertEquals($relationValues, $savedRelationValues, 'Relation ' . $relationKey . ' should be saved');
            }
        }
    }

    abstract protected function testWithValidValuesAndReturnSavedValues(array $validValues): array;

    public function testSearch(): void
    {
        $validValues = $this->generateValues(true);
        $savedValues = $this->testWithValidValuesAndReturnSavedValues($validValues);
        $withResultConditions = [];
        $noResultConditions = [];
        foreach ($this->searchableAttributes as $attribute => $searchType) {
            if (is_int($attribute)) {
                $attribute = $searchType;
                $searchType = null;
            }
            switch ($searchType) {
                case 'LIKE':
                    $withResultCondition[] = [
                        $attribute => $savedValues[$attribute]
                    ];
                    $noResultCondition = [
                        $attribute => $savedValues[$attribute]
                    ];
                    break;
                case 'LEFT_LIKE':
                    break;
                case 'BETWEEN':
                    break;
                default:
                    break;
            }
        }
    }

    abstract protected function testWithSearchValuesAndReturnResults(array $searchValues): array;
}
