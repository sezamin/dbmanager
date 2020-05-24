<?php


namespace Sezamin\Db;


class Connection
{
    private $config = [
        'HOST'=>'localhost',
        'PORT'=>3306,
        'NAME'=>'test',
        'USER'=>'dev',
        'PASSWORD'=>'',
    ];
    private $conn = null;
    private $_isConnected = false;
    private $error = '';

    function isConnected(){
        return $this->_isConnected;
    }

    /**
     * @param $config array|string
     * @param $value int|string
     * @return void
    **/
    function setConfig($config, $value = ''){
        $mainKeys = array_keys($this->config);
        if(is_array($config)){
            foreach($config as $cKey => $v){
                $key = strtoupper($cKey);
                if(in_array($key, $mainKeys)){
                    $this->config[$key] = $v;
                }
            }
        }else if(is_string($config)){
            $key = strtoupper($config);
            if(in_array($key, $mainKeys)){
                $this->config[$key] = $value;
            }
        }
    }

    /**
     * @return void
     **/
    function connect(){
        $serverName = $this->config['HOST'];
        if($this->config['PORT'] !== 3306){
            $serverName .= $serverName . ":" . $this->config['PORT'];
        }
        $dbName = $this->config['NAME'];
        $userName = $this->config['USER'];
        $password = $this->config['PASSWORD'];

        try {
            $conn = new \PDO("mysql:host={$serverName};dbname={$dbName}", $userName, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->_isConnected = true;
            $this->conn = $conn;
        } catch(\PDOException $e) {
            throw $e;
        }
    }
    /**
     * @return Query
     **/
    function query(){
        return new Query($this->conn);
    }
}