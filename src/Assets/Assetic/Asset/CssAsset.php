<?php

namespace Rocket\UI\Assets\Assetic\Asset;

use Assetic\Util\LessUtils;
use AsseticExt\Filter\CssImportFilter;
use Assetic\Util\PathUtils;
use Assetic\Asset\FileAsset;
use Assetic\Filter\FilterInterface;

/**
 * Represents a CSS asset loaded from a file.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class CssAsset extends FileAsset implements WeightedAsset
{
    /**
     * @var int
     */
    protected $weight = 0;

    private $source;
    protected $registeredVars = array();

    public function setVariable($variable, $value)
    {
        $this->registeredVars[$variable] = $value;
    }

    public function unsetVariable($name)
    {
        unset($this->registeredVars[$name]);
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Lessphp Load Paths
     *
     * @var array
     */
    protected $importDirs = array();

    /**
     * List of files that are included, hierarchically
     *
     * @var array
     */
    protected $includedFilesArray;

    /**
     * Track if we're on the main file or not
     *
     * @var array
     */
    protected $isMainFile = false;

    /**
     * The cache to use to accelerate sub assets loading
     *
     * @var \Assetic\Cache\CacheInterface
     */
    protected $cache;

    /**
     * Adds a load path to the paths used by lessphp
     *
     * @param string $path Load Path
     */
    public function addImportDir($path)
    {
        $this->importDirs[] = $path;
    }

    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * Constructor.
     *
     * @param string $source     An absolute path
     * @param array  $filters    An array of filters
     * @param string $sourceRoot The source asset root directory
     * @param string $sourcePath The source asset path
     * @param array  $vars
     * @param array  $files      The array that will contain a list of included files
     *
     * @throws \InvalidArgumentException If the supplied root doesn't match the source when guessing the path
     */
    public function __construct($source, $filters = array(), $sourceRoot = null, $sourcePath = null, array $vars = array(), $files = null)
    {
        $this->source = $source;

        $this->includedFilesArray = $files;
        if ($files === null) {
            $this->isMainFile = true;
            $this->includedFilesArray = array(
                'name' => $source,
            );
        }

        parent::__construct($source, $filters, $sourceRoot, $sourcePath, $vars);
    }

    public function getIncludedFiles()
    {
        return $this->includedFilesArray;
    }

    public function getCachedTree($lastModified)
    {
        $key = md5($this->source . '_tree');
        if ($this->cache->has($key)) {

            $tree = unserialize($this->cache->get($key));

            if ($tree['tree']['mtime'] != $lastModified) {
                return false;
            }

            if ($this->compareFiles($tree['tree']['files']) === false) {
                return false;
            }

            return $tree['lastModified'];
        }

        return false;
    }

    protected function compareFiles($tree)
    {
        foreach ($tree as $file => $data) {

            if (filemtime($file) != $data['mtime']) {
                return false;
            }

            if (array_key_exists('files', $data)) {
                $result = $this->compareFiles($data['files']);
                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getLastModified()
    {
        $source = PathUtils::resolvePath($this->source, $this->getVars(), $this->getValues());

        if (!is_file($source)) {
            throw new \RuntimeException(sprintf('The source file "%s" does not exist.', $source));
        }

        $lastModified = filemtime($source);
        $this->includedFilesArray['size'] = filesize($source);
        $this->includedFilesArray['mtime'] = $lastModified;

        if (\App::environment() == 'production' && $this->isMainFile) {
            $result = $this->getCachedTree($lastModified);
            if ($result !== false) {
                return $result;
            }
        }

        $sourceRoot = $this->getSourceRoot();
        $sourcePath = $this->getSourcePath();
        $importDirs = $this->importDirs;
        $registeredVars = $this->registeredVars;

        $allFiles = & $this->includedFilesArray;

        $callback = function ($matches) use ($sourceRoot, $sourcePath, $importDirs, $registeredVars, & $allFiles, & $lastModified) {
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

            $file = CssImportFilter::find_file($importPath, $importDirs, $sourceRoot);

            $finalImportRoot = dirname($file) . '/';
            $finalImportPath = str_replace($finalImportRoot, '', $file);

            if (!$file) {
                echo '/* ASSET : NOT FOUND :' . $matches['url']."*/\n";

                // ignore not found imports
                return $matches[0];
            } else {
                $import = new CssAsset($file, array(), $finalImportRoot, $finalImportPath, array(), array());
                foreach ($importDirs as $dir) {
                    $import->addImportDir($dir);
                }
                foreach ($registeredVars as $var => $value) {
                    $import->setVariable($var, $value);
                }

                $filemtime = $import->getLastModified();
                if ($filemtime > $lastModified) {
                    $lastModified = $filemtime;
                }

                $allFiles['files'][$file] = $import->getIncludedFiles();
            }

            $import->setTargetPath($sourcePath);

            return $import->dump();
        };

        $content = file_get_contents($source);
        $lastHash = md5($content);

        do {
            $content = LessUtils::filterImports($content, $callback);

            if (count($this->registeredVars) && strposa($content, $this->registeredVars) !== false) {
                $content = strtr($content, $this->registeredVars);
            }
            $hash = md5($content);
        } while ($lastHash != $hash && $lastHash = $hash);

        //Add the content so we don't have to reload it
        $this->setContent($content);

        if (\App::environment() == 'production' && $this->isMainFile) {
            $data = serialize(array('lastModified' => $lastModified, 'tree' => $this->includedFilesArray));
            $this->cache->set(md5($this->source . '_tree'), $data);
        }

        return $lastModified;
    }


    public function load(FilterInterface $additionalFilter = null)
    {
        $content = $this->getContent();
        if ($content === null) {
            $source = PathUtils::resolvePath($this->source, $this->getVars(), $this->getValues());

            if (!is_file($source)) {
                throw new \RuntimeException(sprintf('The source file "%s" does not exist.', $source));
            }

            $content = file_get_contents($source);
        }

        $this->doLoad($content, $additionalFilter);
    }
}
