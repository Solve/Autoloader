<?php
/*
 * This file is a part of Solve framework.
 *
 * @author Alexandr Viniychuk <alexandr.viniychuk@icloud.com>
 * @copyright 2009-2014, Alexandr Viniychuk
 * created: 27.10.14 08:50
 */

namespace Solve\Autoloader\Tests;

use Solve\Autoloader\Autoloader;

require_once __DIR__ . '/../Autoloader.php';

class AutoloaderTest extends \PHPUnit_Framework_TestCase {

    public function testRegistrationFunctions() {
        $loader = new Autoloader();
        $loader->registerNamespace('Foo', __DIR__ . '/assets/Foo');
        var_dump($loader->getRegisteredNamespaces());die();
    }


}
