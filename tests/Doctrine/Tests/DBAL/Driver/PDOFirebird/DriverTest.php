<?php
/**
 * Created by PhpStorm.
 * User: dh
 * Date: 13/11/14
 * Time: 18:24
 */

namespace Doctrine\Tests\DBAL\Driver\PDOFirebird;


use Doctrine\DBAL\Driver\PDOFirebird\Driver;
use Doctrine\Tests\DBAL\Driver\AbstractFirebirdDriverTest;

class DriverTest extends AbstractFirebirdDriverTest {

    public function testReturnsName()
    {
        $this->assertSame('pdo_firebird', $this->driver->getName());
    }

    protected function createDriver()
    {
        return new Driver();
    }

} 