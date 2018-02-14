<?php

namespace Xoops\Core;

/**
 * SplClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 * 
 * From the PHP Framework Interop Group
 *
 * Examples
 * 
 * @code
 *     $myLibLoader = new SplClassLoader('mylib', '/path/to/mylib/src');
 *     $myLibLoader->register();
 *     
 *     $zendLoader = new SplClassLoader('Zend', '/path/to/zend/lib');
 *     $zendLoader->setNamespaceSeparator('_');
 *     $zendLoader->register();
 * @endcode
 *
 * @category Xoops\Core\Psr0ClassLoader
 * @package  Xoops
 * @author   Jonathan H. Wage <jonwage@gmail.com>
 * @author   Roman S. Borschel <roman@code-factory.org>
 * @author   Matthew Weier O'Phinney <matthew@zend.com>
 * @author   Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author   Fabien Potencier <fabien.potencier@symfony-project.org>
 * @author   Richard Griffith <richard@geekwright.com>
 * @link     https://gist.github.com/jwage/221634
 * @see      http://www.php-fig.org/
 */
class Psr0ClassLoader
{
    private $fileExtension = '.php';
    private $namespace;
    private $includePath;
    private $namespaceSeparator = '\\';

    /**
     * Creates a new SplClassLoader that loads classes of the
     * specified namespace.
     * 
     * @param string $ns          The namespace to use.
     * @param string $includePath Path to the namespaces top directory
     * 
     * @return void
     */
    public function __construct($ns = null, $includePath = null)
    {
        $this->namespace = $ns;
        $this->includePath = $includePath;
    }

    /**
     * addLoader sets all basic options and registers the autoloader
     * 
     * @param type $namespace namespace
     * @param type $path      path to the namespace's top directory
     * @param type $separator namespace separator
     * @param type $extension file extension
     * 
     * @return SplClassLoader
     */
    public static function addLoader($namespace, $path, $separator = '\\', $extension = '.php')
    {
        $loaderClass = get_called_class();
        $loader = new $loaderClass($namespace, $path);
        $loader->setNamespaceSeparator($separator);
        $loader->setFileExtension($extension);
        $loader->register();
        return $loader;
    }
    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     * 
     * @param string $sep The separator to use.
     * 
     * @return void
     */
    public function setNamespaceSeparator($sep)
    {
        $this->namespaceSeparator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return string
     */
    public function getNamespaceSeparator()
    {
        return $this->namespaceSeparator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     * 
     * @param string $includePath include path
     * 
     * @return void
     */
    public function setIncludePath($includePath)
    {
        $this->includePath = $includePath;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath include path
     */
    public function getIncludePath()
    {
        return $this->includePath;
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     * 
     * @param string $fileExtension file extension
     * 
     * @return void
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     * 
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     * 
     * @return void
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * 
     * @return void
     */
    public function loadClass($className)
    {
        if (null === $this->namespace
            || $this->namespace.$this->namespaceSeparator
            === substr($className, 0, strlen($this->namespace.$this->namespaceSeparator))
        ) {
            $fileName = '';
            $namespace = '';
            if (false !== ($lastNsPos = strripos($className, $this->namespaceSeparator))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace(
                    $this->namespaceSeparator,
                    DIRECTORY_SEPARATOR,
                    $namespace
                ) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->fileExtension;

            $absolute = ($this->includePath !== null ? $this->includePath . DIRECTORY_SEPARATOR : '') . $fileName;
            if (file_exists($absolute)) {
                require $absolute;
            }
            //require ($this->includePath !== null ? $this->includePath . DIRECTORY_SEPARATOR : '') . $fileName;
        }
    }
}
