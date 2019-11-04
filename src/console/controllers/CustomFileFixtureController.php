<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\console\controllers;

use yii\faker\FixtureController;
use yii\helpers\FileHelper;

/**
 * Class CustomFixtureController
 * @package lujie\extend\console\controllers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CustomFileFixtureController extends FixtureController
{
    /**
     * @var string
     */
    public $dataFileSuffix = '.xxx';

    /**
     * @var callable, input $dataFile, $fixtures
     */
    public $fileGenerator;

    /**
     * @param string $templateName
     * @param string $templatePath
     * @param string $fixtureDataPath
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function generateFixtureFile($templateName, $templatePath, $fixtureDataPath): void
    {
        $fixtures = [];

        for ($i = 0; $i < $this->count; $i++) {
            $fixtures[$templateName . $i] = $this->generateFixture($templatePath . '/' . $templateName . '.php', $i);
        }

        // data file full path
        $dataFile = $fixtureDataPath . '/' . $templateName . $this->dataFileSuffix;

        // data file directory, create if it doesn't exist
        $dataFileDir = dirname($dataFile);
        if (!file_exists($dataFileDir)) {
            FileHelper::createDirectory($dataFileDir);
        }

        call_user_func($this->fileGenerator, $dataFile, $fixtures);
    }
}
