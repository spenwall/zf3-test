<?php

namespace Weddingstar\Db\Table;

use Zend\Db\TableGateway;

/**
 * Weddingstar database table abstract class
 * 
 * @version SVN: $Id: Abstract.php 52934 2017-09-19 15:14:23Z twaldner $
 */
abstract class TableAbstract extends AbstractTableGateway
{
    const ENUM_SITE_COMMON    = 'common';
    const ENUM_SITE_CONFETTI  = 'confetti';
    const ENUM_SITE_KNOT      = 'knot';
    const ENUM_SITE_AUSTRALIA = 'australia'; 
    
    const TABLE_NAME = 'abstract';
    
    /**
     * _name
     *
     * Database table name
     *
     * @var    string
     * @access protected
     */    
    protected $_name = self::TABLE_NAME;
    
    /**
     * __construct 
     *
     * Initialize class
     * 
     * @param array $config - config data
     * 
     * @return void
     */
    public function __construct($config = array())
    {
        /*
         * as of php 5.5 we must use static scope to refer to the 
         * calling class const if the protected variable hasn't been set.
         */
        if ($this->_name == self::TABLE_NAME) {
            $this->_name = static::TABLE_NAME;
        }

        if (!Zend_Registry::isRegistered(WSTAR_REG_DB_OVERRIDE)) {
            return parent::__construct($config);
        }
        
        $dbOverride = Zend_Registry::get(WSTAR_REG_DB_OVERRIDE);

        /*
         * We only want to query the database once to get the list of
         * overriden tables. This is why this variable is declared static.
         * The listTables() method will only be called once, regardless
         * of how many times this constructor is called.
         */
        static $overrideTables = null;

        // use override table if it exists
        if (is_array($config)) {
            if (!isset($config[self::ADAPTER])) {
                if ($dbOverride != null) {
                    $tableName = $this->_name;

                    // set override tables array
                    if ($overrideTables == null) {
                        $overrideTables = $dbOverride->listTables();
                    }

                    if (in_array($tableName, $overrideTables)) {
                        $config[self::ADAPTER] = $dbOverride;
                    }
                }
            }
        }

        parent::__construct($config);
    }

    /**
     * Get number of rows in table
     * 
     * @return int
     */
    public function getCount()
    {
        $select = $this->select();
        $select->from($this, array('count(*) as amount'));
        $rows = $this->fetchAll($select);
       
        return $rows[0]->amount;       
    }

    /**
     * Get the database table name
     * 
     * @return string
     */
    public function getTableName()
    {
        return $this->_name;
    }

    /**
     * Get database name of db adapter
     * 
     * @return string
     */
    public function getAdapterDbName()
    {
        $dbName = '';
        $nameIndex = 'dbname';

        $db = $this->getAdapter();
        $config = $db->getConfig();

        if (isset($config[$nameIndex])) {        
            $dbName = $config[$nameIndex];
        }

        return $dbName;
    }
    
    /**
     * Takes a select object, and either returns the key from the cache or runs the query and caches it
     *
     * @param string         $cacheKey - key into the cache
     * @param Zebd_Db_Select $select   - select query that needs to be run
     * 
     * @return mixed
     */
    public function cache($cacheKey, $select)
    {
        $results = false;

        if (Zend_Registry::isRegistered(WSTAR_REG_CACHE)) {
            $cache = Zend_Registry::get(WSTAR_REG_CACHE);
            $results = $cache->load($cacheKey);
        }
        
        if ($results === false) {    
            $results = $this->fetchAll($select)->toArray();
            
            if (Zend_Registry::isRegistered(WSTAR_REG_CACHE)) {
                $cache->save($results, $cacheKey);
            }    
        }
        return $results;
    }
    
    /**
     * Performs a find row with $value in column $col. Returns ONLY a single Row.
     * 
     * @param string $col   - column you are looking for a value in
     * @param string $value - value you are looking for in the column
     * @param string $order - column to order by
     * @param string $group - column to group by (Used to force a single row return on queries that would normally return multiple rows.)
     * 
     * @return Zedn_Db_Table_Rowset Returns all results found.
     */
    public function findForColumnValue($col, $value, $order = null, $group = array())
    {
        $select = $this->select()->where($col . ' = ?', $value);
        if (! empty($order)) {
            $select->order($order);
        }
        if (! empty($group)) {
            $select->group($group);
        }
        return $this->fetchRow($select);
    }    
    
    /**
     * Performs a fetch all with $value in column $col
     * 
     * @param string $col   - column you are looking for a value in
     * @param string $value - value you are looking for in the column
     * 
     * @return Zedn_Db_Table_Rowset Returns all results found.
     */
    public function findRowsForColumnValue($col, $value)
    {
        return $this->fetchAll($this->select()->where($col . ' = ?', $value));
    } 
    
    /**
     * Performs a fetch all with $value in column $col and ordered by $order
     * 
     * @param string $col   - column you are looking for a value in
     * @param string $value - value you are looking for in the column
     * @param string $order - order by clause
     * 
     * @return Zend_Db_Table_Rowset Returns all results found
     */
    public function findRowsForColumnValueOrdered($col, $value, $order)
    {
        $select = $this->select()->where($col . ' = ?', $value);
        if (!empty($order)) {
            $select->order($order);
        }
        return $this->fetchAll($select);
    } 
    
    /**
     * Performs a fetch all with $value in column $col
     * 
     * @param string $col   - column you are looking for a value in
     * @param string $value - value you are looking for in the column
     * 
     * @return Zedn_Db_Table_Rowset Returns all results found.
     */
    public function findRowsForColumnLikeValue($col, $value)
    {
        return $this->fetchAll($this->select()->where($col . ' LIKE ?', '%' . $value . '%'));
    }     
    
    /**
     * Performs a fetch all rows with $values in column $col
     * 
     * @param string $col    - column you are looking for a value in
     * @param array  $values - values you are looking for in the column
     * 
     * @return Zedn_Db_Table_Rowset Returns all results found.
     */
    public function findRowsWithColumnValues($col, $values)
    {
        return $this->fetchAll($this->select()->where($col . ' IN (?)', $values));
    }    
    
    /**
     * Replace function to execute a MySQL REPLACE
     * 
     * @param array $data - data array just as if it was for insert()
     * 
     * @return Zend_Db_Statement_Mysqli
     */
    public function replace(array $data)
    {
        // get the columns for the table
        $tableInfo = $this->info();
        $tableColumns = $tableInfo['cols'];

        // columns submitted for insert
        $dataColumns = array_keys($data);

        // intersection of table and insert cols
        $valueColumns = array_intersect($tableColumns, $dataColumns);
        sort($valueColumns);

        // generate SQL statement
        $cols = '';
        $vals = '';
        foreach ($valueColumns as $col) {
            $cols .= $this->getAdapter()->quoteIdentifier($col) . ',';
            $vals .= (is_object($data[$col]))
                ? $data[$col]->__toString()
                : $this->getAdapter()->quoteInto('?', $data[$col]);
            $vals .= ',';
        }
        $cols = rtrim($cols, ',');
        $vals = rtrim($vals, ',');
        $sql = 'REPLACE INTO ' . $this->_name . ' (' . $cols . ') VALUES (' . $vals . ');';

        return $this->_db->query($sql);
    }    
    
    /**
     * Given a column name, and a value to look for,
     * Returns a single dimensioned array of data for the given column you want.
     * eg. Categories table -> 'type', 'theme', 'id' returns all the ids in an array of categories of type 'theme'.
     * 
     * @param string $comparisonColumn - column be checked against.
     * @param mixed  $value            - value you are looking for in the comparisson column
     * @param string $returnedColumn   - column you wish to get the data back for.
     * 
     * @return array(mixed)
     */
    public function getSingleColumnValuesForComparison($comparisonColumn, $value, $returnedColumn)
    {
        $results = array();
        $rows = $this->findRowsForColumnValue($comparisonColumn, $value);
        foreach ($rows as $row) {
            $results[] = $row->{$returnedColumn};
        }
        return $results;
    }    
    
    /**
     * Returns the site that is relevant for the seo info being requested.
     * 
     * @return string What site seo to use.
     */
    public function determineSite()
    {
        $siteInfo = Zend_Registry::get(WSTAR_REG_SITE_INFO);
        if ($siteInfo->isKnot()) {
            return self::ENUM_SITE_KNOT;
        }
        if ($siteInfo->isConfettiBased()) {
            return self::ENUM_SITE_CONFETTI;
        }
        if ($siteInfo->isAustralian()) {
            return self::ENUM_SITE_AUSTRALIA;
        }
        return self::ENUM_SITE_COMMON;
    }      
}
