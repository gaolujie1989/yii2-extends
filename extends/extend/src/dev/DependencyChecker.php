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
     * @var array
     */
    private $loopRequired = [];

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
            $this->checkCodeNs($extension);
            $this->checkRequiredLoop($extension);
        }
        return empty($this->invalidNs) && empty($this->loopRequired);
    }

    /**
     * @param array $extension
     * @inheritdoc
     */
    public function checkCodeNs(array $extension): void
    {
        if (empty($extension['alias'])) {
            return;
        }
        foreach ($extension['alias'] as $alias => $path) {
            if (strpos($alias, 'test') !== false) {
                continue;
            }
            $extensionNs = strtr(substr($alias, 1), ['/' => '\\']);
            $extensionFiles = glob($path . '/*.php');
            foreach ($extensionFiles as $codeFile) {
                $checkNs = $this->getCodeRequiredNs($codeFile);
                $invalidNs = array_filter($checkNs, function ($ns) use ($extension, $extensionNs) {
                    if (strpos($ns, $extensionNs) === 0) {
                        return false;
                    }
                    return !$this->isNsValid($ns, $extension);
                });
                if ($invalidNs) {
                    $this->invalidNs[$alias][$codeFile] = $invalidNs;
                }
            }
        }
    }

    /**
     * @param string $ns
     * @param array $extension
     * @return bool
     * @inheritdoc
     */
    public function isNsValid(string $ns, array $extension): bool
    {
        $dependencyExtensionNames = $extension[$this->dependencyKey] ?? [];
        if (empty($dependencyExtensionNames)) {
            return false;
        }
        foreach ($dependencyExtensionNames as $dependencyExtensionName) {
            $dependencyExtensionAliases = $this->extensions[$dependencyExtensionName]['alias'] ?? [];
            if (empty($dependencyExtensionAliases)) {
                continue;
            }
            foreach ($dependencyExtensionAliases as $dependencyAlias => $dependencyPath) {
                $dependencyNs = strtr(substr($dependencyAlias, 1), ['/' => '\\']);
                if (strpos($ns, $dependencyNs) === 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $codeFile
     * @return array
     */
    public function getCodeRequiredNs(string $codeFile): array
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
     * @param array $extension
     * @param string|null $extensionName
     * @inheritdoc
     */
    public function checkRequiredLoop(array $extension, ?string $extensionName = null): void
    {
        $dependencyExtensionNames = $extension[$this->dependencyKey] ?? [];
        if (empty($dependencyExtensionNames)) {
            return;
        }
        if ($extensionName === null) {
            $extensionName = $extension['name'];
        }
        if (in_array($extensionName, $dependencyExtensionNames, true)) {
            $this->loopRequired[] = $extensionName;
            return;
        }
        foreach ($dependencyExtensionNames as $dependencyExtensionName) {
            $dependencyExtension = $this->extensions[$dependencyExtensionName];
            $this->checkRequiredLoop($dependencyExtension, $extensionName);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getInvalidNs(): array
    {
        return $this->invalidNs;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getLoopRequired(): array
    {
        return $this->loopRequired;
    }
}
