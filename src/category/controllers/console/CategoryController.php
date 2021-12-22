<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\category\controllers\console;

use lujie\common\category\models\Category;
use yii\console\Controller;

/**
 * Class CategoryController
 * @package lujie\common\category\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CategoryController extends Controller
{
    /**
     * @param string $name
     * @param int $parentId
     * @param int $position
     * @return Category
     * @inheritdoc
     */
    public function actionCreate(string $name, int $parentId = 0, int $position = 0): Category
    {
        $category = new Category();
        $category->parent_id = $parentId;
        $category->name = $name;
        if ($position) {
            $category->position = $position;
        }
        $category->save(false);
        return $category;
    }
}