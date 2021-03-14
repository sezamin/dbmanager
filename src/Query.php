<?php


namespace Sezamin\Db;

use \PDO;

class Query
{
    /* @var $conn PDO */
    private $conn = null;
    /* @param $conn PDO */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    /*
    * @param $sql string
    * @param $binds array
    * @return array
    **/
    public function selectRaw($sql, $binds = []){
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($binds);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $records = $stmt->fetchAll();
            return $records;
        } catch(\Exception $e) {
            throw $e;
        }
    }
    /*
    * @param $table string
    * @param $values array
    * @param $where string
    * @return int
    **/
    public function update($table, $values = [], $where = ''){
        if(!$this->isTable($table)){
            return false;
        }
        if(!is_array($values) || count($values) === 0 || $where === ''){
            return false;
        }

        $iValues = [];
        $iBinds = [];
        foreach($values as $vKey =>$v){
            $iValues[] = "`{$vKey}`=?";
            $iBinds[] = $v;
        }
        $sql = "UPDATE `{$table}` SET " . implode(", ", $iValues) . " WHERE {$where}";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($iBinds);
            $rowCount = $stmt->rowCount();
            return $rowCount;
        } catch(\Exception $e) {
            throw $e;
        }
    }
    /*
    * @param $table string
    * @param $values array
    * @return int
    **/
    public function insert($table, $values = []){
        if(!$this->isTable($table)){
            return false;
        }
        if(!is_array($values) || count($values) === 0){
            return false;
        }

        $iFields = [];
        $iValues = [];
        $iBinds = [];
        foreach($values as $vKey =>$v){
            $iFields[] = "`{$vKey}`";
            $iValues[] = '?';
            $iBinds[] = $v;
        }
        $sql = "INSERT INTO `{$table}` (" . implode(", ", $iFields).") VALUES(".implode(", ", $iValues).")";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($iBinds);
            $rowCount = $stmt->rowCount();
            return $rowCount;
        } catch(\Exception $e) {
            throw $e;
        }
    }
    /*
    * @param $table string
    * @param $where string
    * @param $binds array
    * @return int
    **/
    public function delete($table, $where, $binds = []){
        if(!$this->isTable($table)){
            return false;
        }

        if(!is_string($where) || strlen($where) === 0 || !is_array($binds)){
            return false;
        }

        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($binds);
            $rowCount = $stmt->rowCount();
            return $rowCount;
        } catch(\Exception $e) {
            throw $e;
        }
    }

    public function isTable($table){
        if(
            is_string($table)
            && preg_match("#^[a-z0-9\_]+$#imus", $table)
        ){
            return true;
        }
        return false;
    }

    /**
     * @return \PDO
     **/
    function getConnection(){
        return $this->conn;
    }

    /**
     * @return integer
     **/
    function lastInsertId(){
        return $this->conn->lastInsertId();
    }

}