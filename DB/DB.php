<?php
namespace DB;
use PDO;

class DB
{
    private $connection;

    public  function __construct($host, $dbname, $user, $pass)
    {
        try{
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e){
            print "Ошибка!: " . $e->getMessage() . "<br>";
        }
        
    }
    
    public function create($table, $valuesNames, $values){
        $statement = $this->connection->prepare("INSERT INTO :table SET `$valuesNames` VALUES (':values')");
        $statement->bindParam(":table", $table);
        //$statement->bindParam(":valuesNames", $valuesNames);
        $statement->bindParam(":values", $values);
        $statement->execute();
        return $this->connection->lastInsertId($table);
    }
    
    public function findBy($table, $name, $value){
        $statement = $this->connection->prepare("SELECT * FROM $table WHERE `$name` = ':value'");
        //$statement->bindParam(":table", $table);
        //$statement->bindParam(":name", $name);
        $statement->bindParam(":value", $value);
        //print_r($statement);
        if($statement->execute()){
            return $statement->fetch();
        }
        return false;
        
    }
    
    public function findAll($table, $sort = false){
        $statement = $this->connection->prepare("SELECT * FROM $table SORT BY :sort");
        $statement->bindParam(":sort", $sort);
        if($statement->execute()){
            return $statement->fetch();
        }
        return false;    
    }

    public function update($table, $id, $valuesNames, $values){
        $statement = $this->connection->prepare("UPDATE $table SET $valuesNames VALUES (:values) WHERE id = :id");  
        //$statement->bindParam(":table", $table);
        $statement->bindParam(":values", $values);
        $statement->bindParam(":id", $id);
        $statement->execute();
    }
    
    public function delete($table, $id){
        $statement = $this->connection->prepare("DELETE FROM $table WHERE id = :id");  
        //$statement->bindParam(":table", $table);
        $statement->bindParam(":id", $id);
        $statement->execute();
    }



    public function getConnection()
    {
        return $connection;
    }
}

