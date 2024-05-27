<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\command;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use yii\base\BaseObject;

/**
 * Class BaseCommander
 * @package lujie\extend\command
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseCommander extends BaseObject
{
    public $binPaths = [];

    public $timeout = 60;

    public $workingDir = null;

    /**
     * @param array $options
     * @return array
     * @inheritdoc
     */
    protected function parseOptions(array $options): array
    {
        $mapper = static function (string $content): array {
            $content = trim($content);
            if ($content[0] !== '-') {
                $content = '-' . $content;
            }
            return explode(' ', $content, 2);
        };

        $reducer = static fn(array $carry, array $option): array => array_merge($carry, $option);
        return array_reduce(array_map($mapper, $options), $reducer, []);
    }

    /**
     * @param string $binPath
     * @param array $options
     * @return Process
     * @inheritdoc
     */
    protected function createProcess(string $binPath, array $options): Process
    {
        $process = new Process(array_merge([$binPath], $options));
        if ($this->timeout) {
            $process->setTimeout($this->timeout);
        }
        if ($this->workingDir) {
            $process->setWorkingDirectory($this->workingDir);
        }
        return $process;
    }

    /**
     * @param string $binPath
     * @param array $options
     * @param string $pdf
     * @return string
     * @inheritdoc
     */
    protected function run(string $binPath, array $options): string
    {
        $process = $this->createProcess($binPath, $options);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }
}
