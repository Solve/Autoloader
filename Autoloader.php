<?php
/*
 * This file is a part of Solve framework.
 *
 * @author Alexandr Viniychuk <alexandr.viniychuk@icloud.com>
 * @copyright 2009-2014, Alexandr Viniychuk
 * created: 27.10.14 08:37
 */

namespace Solve\Autoloader;


class Autoloader {

    private $_namespacesPaths = array();
    private $_prefixesPaths = array();
    private $_namespacesSharedDirs = array();
    private $_sharedPaths = array();

    public function getRegisteredNamespaces() {
        return $this->_namespacesPaths;
    }

    public function registerNamespacePath($namespaceName, $paths) {
        if (empty($this->_namespacesPaths[$namespaceName])) $this->_namespacesPaths[$namespaceName] = array();
        $this->_namespacesPaths[$namespaceName] = array_merge($this->_namespacesPaths[$namespaceName], (array)$paths);
        return $this;
    }

    public function registerNamespaceSharedPaths($path) {
        $this->_namespacesSharedDirs = array_merge($this->_namespacesSharedDirs, (array)$path);
        return $this;
    }

    public function registerPrefix($prefix, $paths) {
        if (empty($this->_prefixesPaths[$prefix])) $this->_prefixesPaths[$prefix] = array();
        $this->_prefixesPaths[$prefix] = array_merge($this->_prefixesPaths[$prefix], (array)$paths);
        return $this;
    }

    public function registerSharedPath($paths, $recursive = false) {
        $paths = (array)$paths;
        if ($recursive) {
            foreach($paths as $path) {
                foreach(glob($path . '/*') as $filePath) {
                    if (is_dir($filePath)) {
                        $this->_sharedPaths[] = $filePath;
                    }
                }
            }
        }
        $this->_sharedPaths = array_merge($this->_sharedPaths, $paths);
        $this->_sharedPaths = array_unique($this->_sharedPaths);
        return $this;
    }

    public function detectClassLocation($className) {
        if (($pos = strrpos($className, '\\')) !== false) {
            $namespace = substr($className, 0, $pos);
            $className = substr($className, $pos + 2);
            $classNameAsPath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            foreach($this->_namespacesPaths as $namespaceItem => $namespaceDirs) {
                if (strpos($namespace, $namespaceItem) !== 0) continue;

                foreach($namespaceDirs as $path) {
                    if (is_file($path . DIRECTORY_SEPARATOR . $classNameAsPath)) {
                        return $path . DIRECTORY_SEPARATOR . $classNameAsPath;
                    }
                }
            }

            foreach ($this->_namespacesSharedDirs as $path) {
                if (is_file($path . DIRECTORY_SEPARATOR . $className . '.php')) {
                    return $path . DIRECTORY_SEPARATOR . $className . '.php';
                }
            }

        } else {
            $classNameAsPath = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            foreach($this->_prefixesPaths as $prefixItem => $prefixDirs) {
                if (strpos($className, $prefixItem) !== 0) continue;

                foreach($prefixDirs as $path) {
                    if (is_file($path . DIRECTORY_SEPARATOR . $classNameAsPath)) {
                        return $path . DIRECTORY_SEPARATOR . $classNameAsPath;
                    }
                }
            }

            foreach ($this->_sharedPaths as $path) {
                if (is_file($path . DIRECTORY_SEPARATOR . $classNameAsPath)) {
                    return $path . DIRECTORY_SEPARATOR . $classNameAsPath;
                }
            }

        }
        return null;
    }

    public function loadClass($className) {
        if ($filePath = $this->detectClassLocation($className)) {
            require_once $filePath;

            return true;
        }
        return false;
    }

    public function register($prepend = true) {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        if (false === spl_autoload_register(array($this, 'loadClass'), true, $prepend)) {
            throw new \Exception(sprintf('Unable to register %s::autoload as an autoloading method.', get_called_class()));
        }
    }

    public function unregister() {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

}