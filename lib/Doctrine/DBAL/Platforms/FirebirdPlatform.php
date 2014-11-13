<?php
/**
 * Created by PhpStorm.
 * User: dfh
 * Date: 12/03/14
 * Time: 19:58
 */

namespace Doctrine\DBAL\Platforms;


class FirebirdPlatform extends AbstractPlatform {

    /**
     * Returns the SQL snippet that declares a boolean column.
     *
     * @param array $columnDef
     *
     * @return string
     */
    public function getBooleanTypeDeclarationSQL(array $columnDef)
    {
        return 'SMALLINT';
    }

    /**
     * Returns the SQL snippet that declares a 4 byte integer column.
     *
     * @param array $columnDef
     *
     * @return string
     */
    public function getIntegerTypeDeclarationSQL(array $columnDef)
    {
        return 'INTEGER';
    }

    /**
     * Returns the SQL snippet that declares an 8 byte integer column.
     *
     * @param array $columnDef
     *
     * @return string
     */
    public function getBigIntTypeDeclarationSQL(array $columnDef)
    {
        return 'BIGINT';
    }

    /**
     * Returns the SQL snippet that declares a 2 byte integer column.
     *
     * @param array $columnDef
     *
     * @return string
     */
    public function getSmallIntTypeDeclarationSQL(array $columnDef)
    {
        return 'SMALLINT';
    }

    /**
     * Returns the SQL snippet that declares common properties of an integer column.
     *
     * @param array $columnDef
     *
     * @return string
     */
    protected function _getCommonIntegerTypeDeclarationSQL(array $columnDef)
    {
        return '';
    }

    /**
     * Lazy load Doctrine Type Mappings.
     *
     * @return void
     */
    protected function initializeDoctrineTypeMappings()
    {
        $this->doctrineTypeMapping = array(
            'bigint'          => 'bigint',
            'char'            => 'string',
            'varchar'         => 'string',
            'date'            => 'date',
            'timestamp'       => 'datetime',
            'time'            => 'time',
            'decimal'         => 'decimal',
            'float'           => 'float',
            'blob'            => 'blob',
            'integer'         => 'integer',
            'numeric'         => 'decimal',
            'double'          => 'float',
            'smallint'        => 'smallint',
        );
    }

    /**
     * Returns the SQL snippet used to declare a CLOB column type.
     *
     * @param array $field
     *
     * @return string
     */
    public function getClobTypeDeclarationSQL(array $field)
    {
        return 'BLOB SUB_TYPE 1';
    }

    /**
     * Returns the SQL Snippet used to declare a BLOB column type.
     *
     * @param array $field
     *
     * @return string
     */
    public function getBlobTypeDeclarationSQL(array $field)
    {
        return 'BLOB SUB_TYPE 0';
    }

    /**
     * Returns the SQL to create a sequence on this platform.
     * @param Sequence $sequence
     * @return string
     */
    public function getCreateSequenceSQL(Sequence $sequence)
    {
        return sprintf('create generator %s', $sequence->getName());
    }

    /**
     * Returns the SQL snippet to drop an existing sequence.
     *
     * @param \Doctrine\DBAL\Schema\Sequence $sequence
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\DBALException If not supported on this platform.
     */
    public function getDropSequenceSQL($sequence)
    {
        return sprintf('drop generator %s', $sequence->getName());
    }

    /**
     * @param string $sequenceName
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\DBALException If not supported on this platform.
     */
    public function getSequenceNextValSQL($sequenceName)
    {
        return sprintf('select gen_id(%s, 1 ) from RDB$DATABASE', $sequenceName);
    }

    /**
     * Whether the platform supports sequences.
     *
     * @return boolean
     */
    public function supportsSequences()
    {
        return true;
    }

    /**
     * Adds an driver-specific LIMIT clause to the query.
     *
     * @param string  $query
     * @param integer|null $limit
     * @param integer|null $offset
     *
     * @return string
     */
    protected function doModifyLimitQuery($query, $limit, $offset)
    {
        if ($limit !== null) {
            $query .= ' FIRST ' . $limit;
        }

        if ($offset !== null) {
            $query .= ' SKIP ' . $offset;
        }

        return $query;
    }

    /**
     * @return string
     */
    public function getCreateTemporaryTableSnippetSQL()
    {
        return "CREATE GLOBAL TEMPORARY TABLE";
    }

    /**
     * {@inheritDoc}
     */
    public function getGuidExpression()
    {
        return 'GEN_UUID()';
    }

    /**
     * Gets the name of the platform.
     *
     * @return string
     */
    public function getName()
    {
        return 'firebird';
    }
}