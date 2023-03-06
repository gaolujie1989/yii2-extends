<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\controllers\console;

use lujie\common\option\helpers\OptionHelper;
use lujie\common\option\models\Option;
use lujie\configuration\ConfigDataLoader;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\loader\DataLoaderInterface;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;

/**
 * Class ModelOptionController
 * @package lujie\common\option\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionController extends Controller
{
    /**
     * @var DataLoaderInterface
     */
    public $optionLoaders = [
        'class' => ConfigDataLoader::class,
        'configType' => 'options'
    ];

    /**
     * @param string $optionFile
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \yii\db\IntegrityException
     * @inheritdoc
     */
    public function actionUpdate(string $optionFile, bool $delete = false): void
    {
        $result = OptionHelper::updateOptions($optionFile, $delete);
        $this->stdout(VarDumper::dumpAsString($result));
    }

    /**
     * @param bool $delete
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\IntegrityException
     * @inheritdoc
     */
    public function actionSync(bool $delete = false): void
    {
        $this->optionLoaders = Instance::ensure($this->optionLoaders, DataLoaderInterface::class);
        $result = OptionHelper::updateOptions($this->optionLoaders->all(), $delete);
        $this->stdout(VarDumper::dumpAsString($result));
    }
}
