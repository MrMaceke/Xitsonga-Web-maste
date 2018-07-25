<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class TranslationEntity extends DbTable{
        public $input;
        public $output;
        public $language;
        public $build;
        public $rating;

        public function TranslationEntity() {
            parent::__construct(DATABASE_NAME,"translations");

            $fieldList = array("input","output","language","build","rating");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->input,$this->output,$this->language,$this->build,$this->rating);
        }
    }

?>
