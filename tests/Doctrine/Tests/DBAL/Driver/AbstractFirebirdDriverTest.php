<?php
/**
 * Created by PhpStorm.
 * User: dh
 * Date: 13/11/14
 * Time: 18:21
 */

namespace Doctrine\Tests\DBAL\Driver;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\FirebirdPlatform;
use Doctrine\DBAL\Schema\FirebirdSchemaManager;

class AbstractFirebirdDriverTest extends AbstractDriverTest {

    protected function createDriver()
    {
        return $this->getMockForAbstractClass('Doctrine\DBAL\Driver\AbstractFirebirdDriver');
    }

    protected function createPlatform()
    {
        return new FirebirdPlatform();
    }

    protected function createSchemaManager(Connection $connection)
    {
        return new FirebirdSchemaManager($connection);
    }

}