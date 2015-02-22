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
        $loader->registerNamespacePath('Foo', __DIR__ . '/assets/Foo');
        $this->assertArrayHasKey('Foo', $loader->getRegisteredNamespaces());
    }

    public function testLoading() {
        $loader = new Autoloader();
        $loader->registerNamespacePath('Foo', __DIR__ . '/assets');
        $loader->registerNamespaceSharedPaths(__DIR__ . '/assets/AllNamespaces');
        $loader->registerSharedPath(__DIR__ . '/assets/custom', true);


        $location = $loader->detectClassLocation('Foo\\FooClass');
        $this->assertEquals(__DIR__ . '/assets/Foo/FooClass.php', $location, 'Class with namespace found');

        $location = $loader->detectClassLocation('Foo\\Inner\\FooInner');
        $this->assertEquals(__DIR__ . '/assets/Foo/Inner/FooInner.php', $location, 'Class with inner namespace found');

        $this->assertNull($loader->detectClassLocation('NonExistsClass'));

        $this->assertNotNull($loader->detectClassLocation('Foo2\\AltFooClass'));
        $this->assertNotNull($loader->detectClassLocation('UserFooClass'));
    }


}
