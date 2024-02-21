<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\helpers;

use Laminas\Code\Generator\AbstractMemberGenerator;
use Phpro\SoapClient\CodeGenerator\Assembler;
use Phpro\SoapClient\CodeGenerator\Config\Config;
use Phpro\SoapClient\CodeGenerator\Rules;
use Phpro\SoapClient\Soap\DefaultEngineFactory;
use Soap\Engine\Engine;
use Soap\ExtSoapEngine\ExtSoapOptions;
use yii\base\BaseObject;

/**
 * Class SoapClientCodeGeneratorConfigHelper
 * @package lujie\dpd\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DpdSoapClientConfigHelper
{
    /**
     * @param string $name
     * @param string $wsdl
     * @inheritdoc
     */
    public static function createSoapConfig(string $name, string $wsdl): Config
    {
        $config = Config::create()
            ->setEngine($engine = DefaultEngineFactory::create(
                ExtSoapOptions::defaults($wsdl, [])
                    ->disableWsdlCache()
            ))
            ->setTypeDestination('soap/Type')
            ->setTypeNamespace('lujie\dpd\soap\Type')
            ->setClientDestination('soap')
            ->setClientName($name . 'Client')
            ->setClientNamespace('lujie\dpd\soap')
            ->setClassMapDestination('soap')
            ->setClassMapName($name . 'Classmap')
            ->setClassMapNamespace('lujie\dpd\soap');
        return self::addDefaultRules($config, $engine);
    }

    /**
     * @param Config $config
     * @param Engine $engine
     * @return Config
     * @inheritdoc
     */
    public static function addDefaultRules(Config $config, Engine $engine): Config
    {
        return $config
            ->addRule(new Rules\AssembleRule(new Assembler\GetterAssembler(new Assembler\GetterAssemblerOptions())))
            ->addRule(new Rules\AssembleRule(new Assembler\ImmutableSetterAssembler(
                new Assembler\ImmutableSetterAssemblerOptions()
            )))
            ->addRule(new Rules\AssembleRule(new Assembler\FluentSetterAssembler(
                    (new Assembler\FluentSetterAssemblerOptions())->withReturnType()->withTypeHints())
            ))
            ->addRule(new Rules\AssembleRule(new Assembler\ExtendAssembler(BaseObject::class)))
            ->addRule(
                new Rules\IsRequestRule(
                    $engine->getMetadata(),
                    new Rules\MultiRule([
                        new Rules\AssembleRule(new Assembler\RequestAssembler()),
                    ])
                )
            )
            ->addRule(
                new Rules\IsResultRule(
                    $engine->getMetadata(),
                    new Rules\MultiRule([
                        new Rules\AssembleRule(new Assembler\ResultAssembler()),
                    ])
                )
            );
    }
}
