<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\dev;

use yii\base\BaseObject;
use Yii;

/**
 * Class ModuleDependencyChecker
 * @package lujie\extend\dev
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModuleDependencyChecker extends BaseObject
{
    /**
     * @var string[]
     */
    public $dependencyNsPrefixes = ['lujie'];

    /**
     * @var string[]
     */
    public $codePools = ['@lujie/data/connecting'];

    public function checker()
    {
        foreach ($this->codePools as $codePool) {
            $codePool = Yii::getAlias($codePool);
            $codeFiles = glob($codePool . '/*');
            foreach ($codeFiles as $codeFile) {
                $codeContent = file_get_contents($codeFile);
            }
        }
    }
}