<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class TranslationConfigEntity extends DbTable{
        public $configId; 
        public $item; 
        public $replacement;
        public $language;
        public $user;

        public function TranslationConfigEntity() {
            parent::__construct(DATABASE_NAME,"translations_config");

            $fieldList = array("config_id","item","replacement","language","user");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->configId,$this->item,$this->replacement,$this->language,$this->user);
        }
    }

?>
