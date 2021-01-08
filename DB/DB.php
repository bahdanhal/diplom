<?php
namespace DB;
class DB
{
    private $xml;
    private $table;
    private $element;
    
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
            $this->xml = simplexml_load_file(DB_FILE);
            fwrite($xmlFile, $xmlStr);
            fclose($xmlFile);
            $this->xml->addChild('users');
            $this->xml->addChild('sessions');
        } else {
            $this->xml = simplexml_load_file(DB_FILE);
        }
        $this->table = $tableName;
        if($tableName == 'users'){
            $this->element = 'user';
        } else {
            $this->element = 'session';
        }
        
    }
    
    public function create($value, $key){
        $node = $this->xml->{$this->table}[0]->addChild($this->element);
        print_r($key);
        $node->addChild($value, $key);
        $this->xml->saveXML(DB_FILE);
        
    }
    
    public function find($findValue, $findKey){
        foreach($this->xml->{$this->table}[0] as $seg)
        {
            if($seg->{$findValue} == $findKey) {
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
        foreach($this->xml->{$this->table}[0] as $seg)
        {
            if($seg->{$findValue} == $findKey) {
                $findRes[$counter++] = $seg;
            }
        }
        if (isset($findRes)){
            return $findRes;
        }
        return null;
    }
    
    public function update($findValue, $findKey, $updateValue, $updateKey){
        foreach($this->xml->{$this->table}[0] as $seg)
        {
            //echo $findValue.' ' .$seg[$findValue]. ' '. $findKey.' '. $updateValue. ' '.$updateKey.' ';
            if($seg->{$findValue} == $findKey) {
                $seg->{$updateValue} = $updateKey;
                //$seg->addAttribute($updateValue, $updateKey);
            }
        }
        $this->xml->saveXML(DB_FILE);
        
        
    }
    
    public function delete($value, $key){
        foreach($this->xml->{$this->table}[0] as $seg)
        {

            if($seg->{$value} == $key) {
                $dom = dom_import_simplexml($seg);
                $dom->parentNode->removeChild($dom);
            }
        }
        $this->xml->saveXML(DB_FILE);
    }
    
    public function screening($data)
    {
        $data = trim($data); 
        return htmlspecialchars(addslashes($data));
    }
    
    public function max($value) {
        $max = 0;
        /*foreach($this->xml->{$this->table}[0] as $seg)
        {
            print_r($seg);
            print_r($seg->attributes()->{$value});
            if($seg->attributes()->{$value} > $max) {
                $max = $seg->attributes()->{$value};
                
            }
        }
        echo $max;*/
        foreach ($this->xml->users->user as $user){

            if((int)$user->user_id > $max){
                $max = $user->user_id;
             
            }
        }
        return $max;
    } 
}

