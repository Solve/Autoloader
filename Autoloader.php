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

    private $_namespacesDirs = array();
    private $_prefixesDirs = array();
    private $_namespacesDefaultDirs = array();
    private $_defaultDirs = array();

    public function getRegisteredNamespaces() {
        return $this->_namespacesDirs;
    }

    public function registerNamespace($namespaceName, $paths) {
        if (empty($this->_namespacesDirs[$namespaceName])) $this->_namespacesDirs[$namespaceName] = array();
        var_dump((array)$paths);die();
        array_merge($this->_namespacesDirs[$namespaceName], (array)$paths);
    }

}