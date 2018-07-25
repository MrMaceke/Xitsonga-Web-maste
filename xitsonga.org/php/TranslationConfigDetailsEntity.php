<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class TranslationConfigDetailsEntity extends DbTable{
        public $configId;
        public $item;
        public $pattern;
        public $swapRight;
        public $swapLeft; 
        public $pushLast; 
        public $pushFirst;

        public function TranslationConfigDetailsEntity() {
            parent::__construct(DATABASE_NAME,"translations_config_details");

            $fieldList = array("config_id","item","pattern","swap_left","swap_right","push_first","push_last");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->configId,$this->item,$this->pattern,$this->swapLeft,$this->swapRight, $this->pushFirst, $this->pushLast);
        }
    }

?>
