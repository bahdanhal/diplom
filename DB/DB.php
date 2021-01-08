<?php
namespace DB;
class DB
{
    private $xml;
    private $table;
    
    public  function __construct($tableName){
        if (!file_exists(DB_FILE)) {
            $xmlFile = fopen(DB_FILE, "w");
            if(!$xmlFile){
                exit('Failed to open database.');
            }
            $xmlStr = "<?xml version = '1.0' encoding='UTF-8'?>
<auth>
</auth>";
            // записываем в файл текст
            fwrite($xmlFile, $xmlStr);
            fclose($xmlFile);
        }
        $this->table = $tableName;
        $this->xml = simplexml_load_file(DB_FILE);
    }
    
    public function create($value, $key){
        $user = $this->xml->addChild($this->table);
        $user->addAttribute($value, $key);
        $this->xml->saveXML(DB_FILE);
        
    }
    
    public function find($findValue, $findKey){
        foreach($this->xml->{$this->table} as $seg)
        {
            if($seg[$findValue] == $findKey) {
                $findRes = $seg;
                break;
            }
        }
        if (isset($findRes)){
            return $findRes;
        }
        return null;
    }
    
    public function findAll($findValue, $findKey){
        $counter = 0;
        foreach($this->xml->{$this->table} as $seg)
        {
            if($seg[$findValue] == $findKey) {
                $findRes[$counter++] = $seg;
            }
        }
        if (isset($findRes)){
            return $findRes;
        }
        return null;
    }
    
    public function update($findValue, $findKey, $updateValue, $updateKey){
        foreach($this->xml->{$this->table} as $seg)
        {
            if($seg[$findValue] == $findKey) {
                $seg[$updateValue] = $updateKey;
            }
        }
        $this->xml->saveXML(DB_FILE);
        
    }
    
    public function delete($value, $key){
        foreach($this->xml->{$this->table} as $seg)
        {
            if($seg[$value] == $key) {
                $dom = dom_import_simplexml($seg);
                $dom->parentNode->removeChild($dom);
            }
        }
        $this->xml->saveXML(DB_FILE);
    }
    
    public function screening($data)
    {
        $data = trim($data); //~ удаление пробелов из начала и конца строки
        return htmlspecialchars(addslashes($data));
    }
}

