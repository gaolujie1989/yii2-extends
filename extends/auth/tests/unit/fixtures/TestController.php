<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\fixtures;

use Yii;
use yii\console\Controller;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class TestController
 * @package lujie\auth\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TestController extends Controller
{
    /**
     * @return ActiveDataProvider
     * @inheritdoc
     */
    public function actionIndex(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => (new Query())->from('migration'),
        ]);
    }
}