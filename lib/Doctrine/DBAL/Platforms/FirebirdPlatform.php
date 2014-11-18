<?php
/**
 * Created by PhpStorm.
 * User: dfh
 * Date: 12/03/14
 * Time: 19:58
 */

namespace Doctrine\DBAL\Platforms;


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\TableDiff;

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
     * {@inheritDoc}
     */
    public function getCreateSequenceSQL(Sequence $sequence)
    {
        return 'CREATE SEQUENCE ' . $sequence->getQuotedName($this);
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
        return sprintf('SELECT GEN_ID(%S, 1 ) FROM RDB$DATABASE', $sequenceName);
    }

    /**
     * {@inheritDoc}
     */
    public function getTruncateTableSQL($tableName, $cascade = false)
    {
        return 'DELETE FROM '.$tableName;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsReleaseSavepoints()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsForeignKeyOnUpdate()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxIdentifierLength()
    {
        return 30;
    }

    /**
     * {@inheritDoc}
     */
    public function fixSchemaElementName($schemaElementName)
    {
        if (strlen($schemaElementName) > 30) {
            return substr($schemaElementName, 0, 30);
        }

        return $schemaElementName;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateFormatString()
    {
        return 'Y-m-d 00:00:00';
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeFormatString()
    {
        return '1900-01-01 H:i:s';
    }

    /**
     * {@inheritDoc}
     *
     * Firebird returns all column names in SQL result sets in uppercase.
     */
    public function getSQLResultCasing($column)
    {
        return strtoupper($column);
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
     * {@inheritDoc}
     */
    public function prefersSequences()
    {
        return true;
    }

    public function getAlterTableSQL(TableDiff $diff)
    {
        // TODO: (DF) Implement this
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     *
     */
    public function getDropDatabaseSQL($database)
    {
        // Not supported in Firebird
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getDropForeignKeySQL($foreignKey, $table)
    {
        if ($foreignKey instanceof ForeignKeyConstraint) {
            $foreignKey = $foreignKey->getQuotedName($this);
        }

        if ($table instanceof Table) {
            $table = $table->getQuotedName($this);
        }

        return 'ALTER TABLE ' . $table . ' DROP CONSTRAINT ' . $foreignKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getDropSequenceSQL($sequence)
    {
        if ($sequence instanceof Sequence) {
            $sequence = $sequence->getQuotedName($this);
        }

        return 'DROP SEQUENCE ' . $sequence;
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
            $query .= ' ROWS ' . $limit;
        }

        if ($offset !== null) {
            $query .= ' TO ' . $offset;
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