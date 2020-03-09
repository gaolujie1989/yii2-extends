<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\generators;


use lujie\template\document\engines\TemplateEngineInterface;
use yii\base\BaseObject;
use yii\di\Instance;
use yii2tech\html2pdf\Manager;

/**
 * Class PdfGenerator
 * @package lujie\template\document\generators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PdfGenerator extends BaseObject implements DocumentGeneratorInterface
{
    /**
     * @var Manager
     */
    public $html2pdf = 'html2pdf';

    /**
     * @var array
     */
    public $options = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->html2pdf = Instance::ensure($this->html2pdf, Manager::class);
    }

    /**
     * @param string $html
     * @return string
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function generate(string $html): string
    {
        return $this->html2pdf->convert($html, $this->options)->name;
    }
}
