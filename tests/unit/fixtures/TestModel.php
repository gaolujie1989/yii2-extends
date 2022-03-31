<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\fixtures;

use yii\base\BaseObject;

class TestModel extends BaseObject
{
    public $id;

    public $created_by = 1;

    public static function findOne($id): ?TestModel
    {
        if ($id > 0) {
            return new TestModel(['id' => $id]);
        }
        return null;
    }
}
