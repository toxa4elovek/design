<?php
/*
 * This file is part of DBUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Provides functionality to retrieve meta data from a MySQL database.
 *
 * @since      Class available since Release 1.0.0
 */
class PHPUnit_Extensions_Database_DB_MetaData_MySQL extends PHPUnit_Extensions_Database_DB_MetaData
{
    protected $schemaObjectQuoteChar = '`';

    /**
     * Returns an array containing the names of all the tables in the database.
     *
     * @return array
     */
    public function getTableNames()
    {
        $query     = 'SHOW TABLES';
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        $tableNames = [];
        while (($tableName = $statement->fetchColumn(0))) {
            $tableNames[] = $tableName;
        }

        return $tableNames;
    }

    /**
     * Returns an array containing the names of all the columns in the
     * $tableName table,
     *
     * @param  string $tableName
     * @return array
     */
    public function getTableColumns($tableName)
    {
        $query     = 'SHOW COLUMNS FROM ' . $this->quoteSchemaObject($tableName);
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        $columnNames = [];
        while (($columnName = $statement->fetchColumn(0))) {
            $columnNames[] = $columnName;
        }

        return $columnNames;
    }

    /**
     * Returns an array containing the names of all the primary key columns in
     * the $tableName table.
     *
     * @param  string $tableName
     * @return array
     */
    public function getTablePrimaryKeys($tableName)
    {
        $query     = 'SHOW INDEX FROM ' . $this->quoteSchemaObject($tableName);
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $columnNames = [];
        while (($column = $statement->fetch())) {
            if ($column['Key_name'] == 'PRIMARY') {
                $columnNames[] = $column['Column_name'];
            }
        }

        return $columnNames;
    }
}
