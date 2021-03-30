<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\debug;

use Yii;
use yii\console\Application;

/**
 * Class Module
 * @package lujie\extend\debug
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Module extends \yii\debug\Module
{
    /**
     * @var array
     */
    public $logTargetConfig = [
        'levels' => ['profile', 'info', 'warning', 'error'],
        'logVars' => [],
        'except' => ['yii\db\*'],
    ];

    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        if (Yii::$app instanceof Application) {
            $this->initPanels();
        }
    }

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        if (Yii::$app instanceof Application) {
            $this->logTarget = $app->getLog()->targets['debug'] = new LogTarget($this, $this->logTargetConfig);
        } else {
            parent::bootstrap($app);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function corePanels(): array
    {
        $corePanels = parent::corePanels();
        if (Yii::$app instanceof Application) {
            unset($corePanels['request'], $corePanels['assets'], $corePanels['user']);
        }
        return $corePanels;
    }
}
