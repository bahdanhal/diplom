<?php
namespace DB;
class DB
{
    private $connection;

    public  function __construct($host, $dbname, $user, $pass)
    {
        try{
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        } catch (PDOException $e){
            print "Ошибка!: " . $e->getMessage() . "<br>";
        }
        
    }
    
    public function create($table, $id, $valuesNames, $values){
        $this->connection->query("INSERT INTO $table SET $valuesNames VALUES ($values)");
    };
    
    public function readBy($table, $name, $value){
       return $this->connection->query("SELECT * FROM $table WHERE $name = $value")->fetch();
    };
    
    public function readAll($table, $sort){
        return $this->connection->query("SELECT * FROM $table SORT BY $sort")->fetchAll();
    };

    public function update($table, $id, $valuesNames, $values){
        $this->connection->query("UPDATE $table SET $valuesNames VALUES ($values) WHERE id = $id");  
    };
    
    public function delete($table, $id);
        $this->connection->query("DELETE FROM $table WHERE id = $id");  
    };


    public function getConnection()
    {
        return $connection;
    }
}

