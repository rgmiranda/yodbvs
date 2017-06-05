<?php

namespace yodbvs\db\adapters;

abstract class Adapter {

    protected $pdo;
    /**
     * Se conecta con la base de datos
     * @throws \PDOException
     */
    public function __construct($config) {
        $host = empty($config['host']) ? false : $config['host'];
        $port = empty($config['port']) ? false : $config['port'];
        $dbname = empty($config['dbname']) ? false : $config['dbname'];
        $username = empty($config['user']) ? false : $config['user'];
        $password = empty($config['pass']) ? false : $config['pass'];
        $dsn = $this->getDSN($host, $port, $dbname);
        $this->pdo = new \PDO($dsn, $username, $password);
    }
 
    /**
     * Runs an SQL query
     * @throws \PDOException
     */
    public function query($sql) {
        $this->pdo->query($sql);
    }

    public static function createInstance($config) {
        $adapter = empty($config['adapter']) ? FALSE : $config['adapter'];
        $class = '\\' . __NAMESPACE__ . "\\{$adapter}";
        return new $class($config);
    }
 
    /**
     * Must return an array() that contains all the schema object names in the database
     * @example return array('articles', 'comments', 'posts')
     * @throws \PDOException
     * @return array()
     */
    //public abstract function getSchema();
 
    /**
     * Given a schema object name, returns the SQL query that will create 
     * that schema object on any machine running the DBMS of choice.
     * @example CREATE TABLE / CREATE PROCEDURE queries in MySQL
     * @throws \PDOException

     */
    //public abstract function getSchemaObject($name);

    protected abstract function getDSN($host = false, $port = false, $dbname = false);
}