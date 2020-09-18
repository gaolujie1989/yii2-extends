<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\debug;

use Yii;

/**
 * Class Module
 * @package lujie\extend\debug
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Module extends \yii\debug\Module
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        if (Yii::$app instanceof yii\console\Application) {
            $this->initPanels();
        }
    }

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        if (Yii::$app instanceof yii\console\Application) {
            $this->logTarget = $app->getLog()->targets['debug'] = new LogTarget($this);
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
        if (Yii::$app instanceof yii\console\Application) {
            unset($corePanels['request'], $corePanels['assets'], $corePanels['user']);
        }
        return $corePanels;
    }
}