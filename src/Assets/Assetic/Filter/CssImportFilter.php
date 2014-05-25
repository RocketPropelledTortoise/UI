<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2012 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AsseticExt\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Asset\FileAsset;
use Assetic\Filter\FilterInterface;
use Assetic\Filter\BaseCssFilter;

/**
 * Inlines imported stylesheets.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class CssImportFilter extends BaseCssFilter
{
    private $importFilter;

    public function setImportFilter(FilterInterface $importFilter)
    {
        $this->importFilter = $importFilter;
    }

    protected $registeredVars = array();

    public function setVariable($variable, $value)
    {
        $this->registeredVars[$variable] = $value;
    }

    public function unsetVariable($name)
    {
        unset($this->registeredVars[$name]);
    }

    /**
     * Lessphp Load Paths
     *
     * @var array
     */
    protected $importDirs = array();

    /**
     * Adds a load path to the paths used by lessphp
     *
     * @param string $path Load Path
     */
    public function addImportDir($path)
    {
        $this->importDirs[] = $path;
    }

    public function filterLoad(AssetInterface $asset)
    {
        $sourceRoot = $asset->getSourceRoot();
        $sourcePath = $asset->getSourcePath();
        $importFilter = $this->importFilter;
        $importDirs = $this->importDirs;

        $callback = function ($matches) use ($importFilter, $sourceRoot, $sourcePath, $importDirs) {
            if (!$matches['url'] || null === $sourceRoot) {
                return $matches[0];
            }

            if (null !== $sourcePath) {
                // document-relative
                $importPath = $matches['url'];
            } else {
                return $matches[0];
            }

            // ignore other imports
            /*if (!in_array(pathinfo($importPath, PATHINFO_EXTENSION), array('css', 'less'))) {
                return $matches[0];
            }*/

            $file = self::findFile($importPath, $importDirs, $sourceRoot);

            $finalImportRoot = dirname($file) . '/';
            $finalImportPath = str_replace($finalImportRoot, '', $file);

            if (!$file) {
                // ignore not found imports
                return $matches[0];
            } else {
                $import = new FileAsset($file, array($importFilter), $finalImportRoot, $finalImportPath);
            }

            $import->setTargetPath($sourcePath);

            return $import->dump();
        };

        $content = $asset->getContent();
        $lastHash = md5($content);

        do {
            $content = $this->filterImports($content, $callback);
            if (count($this->registeredVars)) {
                $content = strtr($content, $this->registeredVars);
            }
            $hash = md5($content);
        } while ($lastHash != $hash && $lastHash = $hash);

        $asset->setContent($content);
    }

    protected function findImport($url)
    {
        return null;
    }

    public function filterDump(AssetInterface $asset)
    {
    }

    public static function findFile($url, $dirs, $currentDir = null)
    {
        if ($currentDir) {
            array_unshift($dirs, $currentDir);
        }

        foreach ((array) $dirs as $dir) {
            $full = rtrim($dir, '/') . '/' . $url;
            if (file_exists($file = $full . '.less') || file_exists($file = $full)) {
                return $file;
            }
        }

        return null;
    }
}
