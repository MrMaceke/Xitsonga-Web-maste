<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class DefinationEntity extends DbTable{
        public $item;
        public $defination;
        public $device;
        
        public function DefinationEntity() {
            parent::__construct(DATABASE_NAME,"definations_cache");

            $fieldList = array("item","defination","device");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->item,$this->defination,$this->device);
        }
    }

?>
