<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\dev;

use yii\base\BaseObject;

/**
 * Class ModuleDependencyChecker
 * @package lujie\extend\dev
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DependencyChecker extends BaseObject
{
    /**
     * @var string[]
     */
    public $checkNsPrefixes = ['lujie'];

    /**
     * @var string[]
     */
    public $dependencyNsPrefixes = ['lujie\extend', 'lujie\common', 'lujie\data'];

    /**
     * yii extension config
     * @var array
     */
    public $extensions = [];

    /**
     * @var array
     */
    private $invalidNs = [];

    /**
     * @var string
     */
    public $dependencyKey = 'require';

    /**
     * @inheritdoc
     */
    public function check(): bool
    {
        $this->invalidNs = [];
        foreach ($this->extensions as $extension) {
            if (empty($extension['alias'])) {
                continue;
            }
            foreach ($extension['alias'] as $alias => $path) {
                if (strpos($alias, 'test') !== false) {
                    continue;
                }
                $extensionNs = strtr(substr($alias, 1), ['/' => '\\']);
                $extensionFiles = glob($path . '/*.php');
                foreach ($extensionFiles as $codeFile) {
                    $checkNs = $this->getCheckNs($codeFile);
                    $invalidNs = array_filter($checkNs, function ($ns) use ($extension, $extensionNs) {
                        if (strpos($ns, $extensionNs) === 0) {
                            return false;
                        }
                        if (empty($extension[$this->dependencyKey])) {
                            return true;
                        }
                        foreach ($extension[$this->dependencyKey] as $dependencyExtensionName) {
                            if (empty($this->extensions[$dependencyExtensionName]['alias'])) {
                                continue;
                            }
                            foreach ($this->extensions[$dependencyExtensionName]['alias'] as $dependencyAlias => $dependencyPath) {
                                $dependencyNs = strtr(substr($dependencyAlias, 1), ['/' => '\\']);
                                if (strpos($ns, $dependencyNs) === 0) {
                                    return false;
                                }
                            }
                        }
                        return true;
                    });
                    if ($invalidNs) {
                        $this->invalidNs[$alias][$codeFile] = $invalidNs;
                    }
                }
            }
        }
        return empty($this->invalidNs);
    }

    /**
     * @param string $codeFile
     * @return array
     */
    public function getCheckNs(string $codeFile): array
    {
        $codeContent = file_get_contents($codeFile);
        if (preg_match_all('/use ([\w\\\]+);/', $codeContent, $matches)) {
            return array_filter($matches[1], function ($ns) {
                foreach ($this->dependencyNsPrefixes as $dependencyNsPrefix) {
                    if (strpos($ns, $dependencyNsPrefix) === 0) {
                        return false;
                    }
                }
                foreach ($this->checkNsPrefixes as $checkNsPrefix) {
                    if (strpos($ns, $checkNsPrefix) === 0) {
                        return true;
                    }
                }
                return false;
            });
        }
        return [];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getInvalidNs(): array
    {
        return $this->invalidNs;
    }
}
