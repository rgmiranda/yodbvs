<?php

namespace yodbvs\db\adapters;

abstract class Adapter {

    protected $pdo;
    /**
     * Se conecta con la base de datos
     * @throws \PDOException
     */
    public function __construct(
        $host = false, $port = false, $username = false,
        $password = false, $dbname = false
    ) {
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
        $class = '\\' . __NAMESPACE__ . "\\{$config['adapter']}";
        return new $class($config['host'], $config['port'], $config['user'], $config['pass'], $config['dbname']);
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