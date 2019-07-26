<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\alias\db;

use yii\base\InvalidArgumentException;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;

/**
 * Class NameQuery
 * @package lujie\alias\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ReturnFieldQuery extends ActiveQuery
{
    /**
     * @var string
     */
    public $returnField;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->queryField)) {
            throw new InvalidArgumentException('The property `queryField` must be set');
        }
    }

    /**
     * @param null $db
     * @return int|string
     * @inheritdoc
     */
    public function one($db = null)
    {
        $one = $this->select([$this->returnField])->asArray()->one($db);
        return $one[$this->returnField];
    }

    /**
     * @param null $db
     * @return array|void|\yii\db\ActiveRecord[]
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all($db = null): void
    {
        throw new NotSupportedException('The method `all` not supported by ReturnFieldQuery');
    }
}
