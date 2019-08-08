<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman;

use Exception;
use MongoDate;
use Xhgui_Config;
use Xhgui_Saver;
use Xhgui_Util;

class XHProfiler
{
    /**
     * @var bool
     */
    private static $enable = false;

    public static function init(): void
    {
        if (!extension_loaded('xhprof') && !extension_loaded('uprofiler')
            && !extension_loaded('tideways') && !extension_loaded('tideways_xhprof')) {
            self::$enable = false;
            return;
        }

        if (defined('XHGUI_BASH_PATH') && file_exists(XHGUI_BASH_PATH . '/src/bootstrap.php')) {
            require XHGUI_BASH_PATH . '/src/bootstrap.php';
        } else {
            self::$enable = false;
            return;
        }

        $filterPath = Xhgui_Config::read('profiler.filter_path');
        if (is_array($filterPath) && in_array($_SERVER['DOCUMENT_ROOT'], $filterPath, true)) {
            self::$enable = false;
            return;
        }
        if ((!extension_loaded('mongo') && !extension_loaded('mongodb'))
            && Xhgui_Config::read('save.handler') === 'mongodb') {
            self::$enable = false;
            return;
        }

        self::$enable = true;
    }

    /**
     * @throws Exception
     * @inheritdoc
     */
    public static function start(): void
    {
        if (!self::$enable || !Xhgui_Config::shouldRun()) {
            return;
        }

        if (!isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
        }

        $extension = Xhgui_Config::read('extension');
        if ($extension === 'uprofiler' && extension_loaded('uprofiler')) {
            uprofiler_enable(UPROFILER_FLAGS_CPU | UPROFILER_FLAGS_MEMORY);
        } else if ($extension === 'tideways_xhprof' && extension_loaded('tideways_xhprof')) {
            tideways_xhprof_enable(TIDEWAYS_XHPROF_FLAGS_MEMORY | TIDEWAYS_XHPROF_FLAGS_MEMORY_MU | TIDEWAYS_XHPROF_FLAGS_MEMORY_PMU | TIDEWAYS_XHPROF_FLAGS_CPU);
        } else if ($extension === 'tideways' && extension_loaded('tideways')) {
            tideways_enable(TIDEWAYS_FLAGS_CPU | TIDEWAYS_FLAGS_MEMORY);
            tideways_span_create('sql');
        } else if (function_exists('xhprof_enable')) {
            if (PHP_MAJOR_VERSION === 5 && PHP_MINOR_VERSION > 4) {
                xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_NO_BUILTINS);
            } else {
                xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
            }
        } else {
            throw new Exception("Please check the extension name in config/config.default.php \r\n,you can use the 'php -m' command.", 1);
        }
    }

    public static function end(): void
    {
        if (!self::$enable || !Xhgui_Config::shouldRun()) {
            return;
        }

        $extension = Xhgui_Config::read('extension');
        if ($extension === 'uprofiler' && extension_loaded('uprofiler')) {
            $data['profile'] = uprofiler_disable();
        } else if ($extension === 'tideways_xhprof' && extension_loaded('tideways_xhprof')) {
            $data['profile'] = tideways_xhprof_disable();
        } else if ($extension === 'tideways' && extension_loaded('tideways')) {
            $data['profile'] = tideways_disable();
            $sqlData = tideways_get_spans();
            $data['sql'] = array();
            if(isset($sqlData[1])){
                foreach($sqlData as $val){
                    if(isset($val['n'], $val['a']['sql']) && $val['n'] === 'sql'){
                        $_time_tmp = isset($val['b'][0], $val['e'][0]) ?($val['e'][0]-$val['b'][0]):0;
                        if(!empty($val['a']['sql'])){
                            $data['sql'][] = [
                                'time' => $_time_tmp,
                                'sql' => $val['a']['sql']
                            ];
                        }
                    }
                }
            }
        } else {
            $data['profile'] = xhprof_disable();
        }

        $uri = $_SERVER['REQUEST_URI'] ?? null;
        if (empty($uri) && isset($_SERVER['argv'])) {
            $cmd = basename($_SERVER['argv'][0]);
            $uri = $cmd . ' ' . implode(' ', array_slice($_SERVER['argv'], 1));
        }

        $time = $_SERVER['REQUEST_TIME'] ?? time();
        $requestTimeFloat = explode('.', $_SERVER['REQUEST_TIME_FLOAT']);
        if (!isset($requestTimeFloat[1])) {
            $requestTimeFloat[1] = 0;
        }

        if (Xhgui_Config::read('save.handler') === 'file') {
            $requestTs = array('sec' => $time, 'usec' => 0);
            $requestTsMicro = array('sec' => $requestTimeFloat[0], 'usec' => $requestTimeFloat[1]);
        } else {
            $requestTs = new MongoDate($time);
            $requestTsMicro = new MongoDate($requestTimeFloat[0], $requestTimeFloat[1]);
        }

        $data['meta'] = array(
            'url' => $uri,
            'SERVER' => $_SERVER,
            'get' => $_GET,
            'env' => $_ENV,
            'simple_url' => Xhgui_Util::simpleUrl($uri),
            'request_ts' => $requestTs,
            'request_ts_micro' => $requestTsMicro,
            'request_date' => date('Y-m-d', $time),
        );

        try {
            $config = Xhgui_Config::all();
            $config += array('db.options' => array());
            $saver = Xhgui_Saver::factory($config);
            $saver->save($data);
        } catch (Exception $e) {
            echo 'xhgui - ' . $e->getMessage() . "\n";
        }
    }
}
