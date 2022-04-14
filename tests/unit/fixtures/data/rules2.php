<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\auth\rules\AuthorRule;
use lujie\auth\rules\ModelAccessRule;
use lujie\auth\rules\QueryResultRule;

return [
    'isAuthor' => [
        'class' => AuthorRule::class,
    ],
    'QueryResult' => [
        'class' => QueryResultRule::class,
    ],
];