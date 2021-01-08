<?php
namespace DB;
class DB
{
    private $xml;
    private $table;
    private $element;
    
    /**
     * 
     * @param string $tableName
     */
    public  function __construct($tableName){
        if (!file_exists(DB_FILE)) {
            $xmlFile = fopen(DB_FILE, "w");
            if(!$xmlFile){
                exit('Failed to open database.');
            }
            $xmlStr = "<?xml version = '1.0' encoding='UTF-8'?>
<auth>

</auth>";

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
    
    /**
     * creating new node
     * @param string $value
     * @param string $key
     */
    public function create($value, $key){
        $node = $this->xml->{$this->table}[0]->addChild($this->element);
        $node->addChild($value, $key);
        $this->xml->saveXML(DB_FILE);
        
    }
    
    /**
     * finding one node with value and key
     * @param string $findValue
     * @param string $findKey
     * @return \SimpleXMLElement|NULL
     */
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
    
    /**
     * finding all results with value and key
     * @param string $findValue
     * @param string $findKey
     * @return \SimpleXMLElement[]|NULL
     */
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
    
    /**
     * 
     * @param string $findValue
     * @param string $findKey
     * @param string $updateValue
     * @param string $updateKey
     */
    public function update($findValue, $findKey, $updateValue, $updateKey){
        foreach($this->xml->{$this->table}[0] as $seg)
        {
            if($seg->{$findValue} == $findKey) {
                $seg->{$updateValue} = $updateKey;
            }
        }
        $this->xml->saveXML(DB_FILE);
        
        
    }
    
    /**
     * 
     * @param string $value
     * @param string $key
     */
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
    
    /**
     * data protection
     * @param string $data
     * @return string
     */
    public function screening($data)
    {
        $data = trim($data); 
        return htmlspecialchars(addslashes($data));
    }
    
    /**
     * max id in table for unique numbers
     * @param string $value
     * @return number
     */
    public function max($value) {
        $max = 0;
        foreach ($this->xml->users->user as $user){

            if((int)$user->user_id > $max){
                $max = $user->user_id;
             
            }
        }
        return $max;
    } 
}

