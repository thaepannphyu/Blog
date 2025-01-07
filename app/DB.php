<?php
namespace app;
require("../config.php");

class DB{
      private  $host ;
      private   $dbname ;
      private  $username;
      private  $password ;
      private $connection;

    public function __construct() {
        $this->host=db_host;
        $this->dbname=db_name;
        $this->username=user_name;
        $this->password=user_password;
        $this->connection=$this->connect($this->host,$this->dbname,$this->username,$this->password);
    }
    public function connect($host,$dbname,$username,$password){
            try {
                $pdo = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (\PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
    }

    public function getConnection(){
        return $this->connection;
    }
    }
?>