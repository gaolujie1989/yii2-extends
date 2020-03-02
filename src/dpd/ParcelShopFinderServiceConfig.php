<?php

use Phpro\SoapClient\CodeGenerator\Assembler;
use Phpro\SoapClient\CodeGenerator\Config\Config;
use Phpro\SoapClient\CodeGenerator\Rules;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;

return Config::create()
    ->setEngine(ExtSoapEngineFactory::fromOptions(
        ExtSoapOptions::defaults('https://public-ws-stage.dpd.com/services/ParcelShopFinderService/V5_0/?wsdl', [])
            ->disableWsdlCache()
    ))
    ->setTypeDestination('/app/dpd/src/dpd/Type')
    ->setTypeNamespace('dpd\Type')
    ->setClientDestination('/app/dpd/src/dpd')
    ->setClientName('ParcelShopFinderServiceClient')
    ->setClientNamespace('dpd')
    ->setClassMapDestination('/app/dpd/src/dpd')
    ->setClassMapName('ParcelShopFinderServiceClassmap')
    ->setClassMapNamespace('dpd')
    ->addRule(new Rules\AssembleRule(new Assembler\GetterAssembler(new Assembler\GetterAssemblerOptions())))
    ->addRule(new Rules\AssembleRule(new Assembler\ImmutableSetterAssembler()))
    ->addRule(
        new Rules\TypenameMatchesRule(
            new Rules\MultiRule([
                new Rules\AssembleRule(new Assembler\RequestAssembler()),
                new Rules\AssembleRule(new Assembler\ConstructorAssembler(new Assembler\ConstructorAssemblerOptions())),
            ]),
            '/(?<!Response)$/i'
        )
    )
    ->addRule(
        new Rules\TypenameMatchesRule(
            new Rules\MultiRule([
                new Rules\AssembleRule(new Assembler\ResultAssembler()),
            ]),
            '/Response$/i'
        )
    )
;
