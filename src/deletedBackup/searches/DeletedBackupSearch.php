<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\deleted\backup\searches;

use lujie\common\deleted\backup\models\DeletedBackup;
use lujie\extend\db\SearchTrait;

/**
 * Class DeletedBackupSearch
 * @package lujie\common\deleted\backup\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedBackupSearch extends DeletedBackup
{
    use SearchTrait;
}
