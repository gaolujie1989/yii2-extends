<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\authclient;

use Http\Client\Common\PluginClient;
use lujie\extend\psr\http\Yii2HttpHandler;
use Phpro\SoapClient\Caller\EngineCaller;
use Phpro\SoapClient\Caller\EventDispatchingCaller;
use Phpro\SoapClient\Soap\DefaultEngineFactory;
use Soap\ExtSoapEngine\ExtSoapOptions;
use Soap\ExtSoapEngine\Wsdl\Naming\Md5Strategy;
use Soap\ExtSoapEngine\Wsdl\PermanentWsdlLoaderProvider;
use Soap\Psr18Transport\Psr18Transport;
use Soap\Wsdl\Loader\FlatteningLoader;
use Soap\Wsdl\Loader\StreamWrapperLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\helpers\FileHelper;

/**
 * Class BaseSoapClient
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseSoapClient extends Component
{
    /**
     * @var string
     */
    public $baseUrl = '';

    /**
     * @var string
     */
    public $sandboxUrl = '';

    /**
     * @var string
     */
    public $productionUrl = '';

    /**
     * @var bool
     */
    protected $sandbox = false;

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * [
     *      'wsdl' => [Client::class, Classmap::class],
     * ]
     * @var array
     */
    public $clientFactories = [];

    /**
     * @var array
     */
    private $clients = [];

    /**
     * @var Yii2HttpHandler
     */
    public $httpHandler = [];

    /**
     * @var string
     */
    public $wsdlCacheDir = '@runtime/wsdl';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->httpHandler = Instance::ensure($this->httpHandler, Yii2HttpHandler::class);
    }

    /**
     * @param bool $sandbox
     * @inheritdoc
     */
    public function setSandbox(bool $sandbox = true): void
    {
        $this->sandbox = $sandbox;
        $this->baseUrl = $this->sandbox ? $this->sandboxUrl : $this->productionUrl;
    }

    /**
     * @param string $clientClass
     * @return array
     * @inheritdoc
     */
    public function getHttpPlugins(string $clientClass): array
    {
        return [];
    }

    /**
     * @return string|null
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function getWsdlCacheDir(): ?string
    {
        if (empty($this->wsdlCacheDir)) {
            return null;
        }
        $cacheDir = Yii::getAlias($this->wsdlCacheDir, false);
        if ($cacheDir === false) {
            return null;
        }
        FileHelper::createDirectory($cacheDir);
        return $cacheDir;
    }

    /**
     * @param string $wsdl
     * @param string $clientClass
     * @param string|object $classMapClass
     * @return mixed
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    protected function createClient(string $wsdl, string $clientClass, string|object $classMapClass): mixed
    {
        $soapOptions = ExtSoapOptions::defaults($wsdl, [])
            ->withClassMap($classMapClass::getCollection());
        if ($wsdlCacheDir = $this->getWsdlCacheDir()) {
            $soapOptions = $soapOptions
                ->withWsdlProvider(new PermanentWsdlLoaderProvider(
                    new FlatteningLoader(new StreamWrapperLoader()),
                    new Md5Strategy(),
                    $wsdlCacheDir
                ));
        }
        $engine = DefaultEngineFactory::create(
            $soapOptions,
            Psr18Transport::createForClient(
                new PluginClient(
                    $this->httpHandler,
                    $this->getHttpPlugins($clientClass)
                )
            ),
        );

        $eventDispatcher = new EventDispatcher();
        $caller = new EventDispatchingCaller(new EngineCaller($engine), $eventDispatcher);

        return new $clientClass($caller);
    }

    /**
     * @param $name
     * @param $params
     * @return mixed
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        foreach ($this->clientFactories as $wsdlPath => [$clientClass, $classMapClass]) {
            if (empty($this->clients[$wsdlPath])) {
                $wsdlUrl = trim($this->baseUrl, '/') . '/' . trim($wsdlPath, '/');
                $this->clients[$wsdlPath] = $this->createClient($wsdlUrl, $clientClass, $classMapClass);
            }
            $client = $this->clients[$wsdlPath];
            if (method_exists($client, $name)) {
                return call_user_func_array([$client, $name], $params);
            }
        }
        return parent::__call($name, $params);
    }
}
