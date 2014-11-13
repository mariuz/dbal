<?php
/**
 * Created by PhpStorm.
 * User: dfh
 * Date: 12/03/14
 * Time: 20:50
 */

namespace Doctrine\DBAL\Schema;


class FirebirdSchemaManager extends AbstractSchemaManager {


    /**
     * Gets Table Column Definition.
     *
     * @param array $tableColumn
     *
     * @return \Doctrine\DBAL\Schema\Column
     */
    protected function _getPortableTableColumnDefinition($tableColumn)
    {
        // TODO: Implement _getPortableTableColumnDefinition() method.
    }
}