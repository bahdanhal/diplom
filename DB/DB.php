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
    
    public function create($table, $values){
        $valuesStr = '';
        foreach($values as $valueName => $value){
            $valuesStr .= "`$valueName`=:$valueName, ";
        }
        $valuesStr = substr($valuesStr,0,-2);  

        $statement = $this->connection->prepare("INSERT INTO $table" . ($valuesStr ? " SET $valuesStr" : ' VALUES()'));

        foreach($values as $valueName => $value){
            $statement->bindValue(":$valueName", $value);
        }  
        $statement->execute();
        return $this->connection->lastInsertId($table);
    }
    
    public function findBy($table, $name = false, $value = false, $sort = false, $limit = false){
        $statement = $this->connection->prepare(
            "SELECT * FROM $table".
            (($name && $value) ? " WHERE `$name` = :value" : ' WHERE 1') .
            ($sort?(" SORT BY $sort") : "") .
            ($limit?(" LIMIT ".$limit[0] . ", " . $limit[1]) : "")
        );
        //$statement->bindValue(":table", $table);
        //$statement->bindValue(":name", $name);
        if($value){
            $statement->bindValue(":value", $value);
        }

        if($statement->execute()){
            while($tmp = $statement->fetch()){
                $result[] = $tmp;

            }
            return $result ?? false;
        }
        return false;
        
    }
    
    public function findAll($table, $sort = false){
        $statement = $this->connection->prepare("SELECT * FROM $table SORT BY `$sort`");
        if($statement->execute()){
            while($tmp = $statement->fetch()){
                $result[] = $tmp;
            }
            return $result;
        }
        return false;    
    }

    public function update($table, $whereName, $whereValue, $values){
        $valuesStr = '';
        foreach($values as $valueName => $value){
            $valuesStr .= "`$valueName`=:$valueName, ";
        }
        $valuesStr = substr($valuesStr,0,-2);
        
        $statement = $this->connection->prepare("UPDATE $table SET $valuesStr WHERE `$whereName` = :whereValue");  
        
        foreach($values as $valueName => $value){
            $statement->bindValue(":$valueName", $value);
        } 
        $statement->bindValue(":whereValue", $whereValue);
        $statement->execute();
        return $statement->rowCount();
    }
    
    public function delete($table, $values){
        $valuesStr = '';
        foreach($values as $valueName => $value){
            $valuesStr .= "`$valueName`=:$valueName and ";
        }
        $valuesStr = substr($valuesStr,0,-5);

        $statement = $this->connection->prepare("DELETE FROM $table WHERE `id` IN (SELECT `id`
                FROM $table
                WHERE $valuesStr
            );
        ");  

        foreach($values as $valueName => $value){
            $statement->bindValue(":$valueName", $value);
        } 

        $statement->execute();
    }



    public function getConnection()
    {
        return $connection;
    }
}

