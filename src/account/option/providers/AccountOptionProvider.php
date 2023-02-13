<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\option\providers;

use lujie\common\account\models\Account;
use lujie\common\account\models\AccountQuery;
use lujie\common\option\providers\DbOptionProvider;
use yii\db\QueryInterface;

/**
 * Class AccountOptionProvider
 * @package lujie\common\account\option\providers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountOptionProvider extends DbOptionProvider
{
    public $modelClass = Account::class;

    public $filterKeys = ['name'];

    public $keyMap = [
        'account_id' => 'value',
        'name' => 'label',
    ];

    public $suffix = '';

    /**
     * @param string $type
     * @return bool
     * @inheritdoc
     */
    public function hasType(string $type): bool
    {
        if (stripos($this->type, $this->suffix) !== false) {
            return true;
        }
    }

    /**
     * @param string $type
     * @return string
     * @inheritdoc
     */
    public function getAccountType(string $type): string
    {
        return strtoupper(substr($type, 0, -strlen($this->suffix)));
    }

    /**
     * @param string $type
     * @param string|null $key
     * @return QueryInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getQuery(string $type, ?string $key = null): QueryInterface
    {
        $accountType = $this->getAccountType($type);
        /** @var AccountQuery $query */
        $query = parent::getQuery($type, $key);
        if ($accountType) {
            $query->type($accountType);
        }
        return $query;
    }
}