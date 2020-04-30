<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "activations"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class AuditAPICallsEntity extends DbTable{
        private $item;
        private $translation;
        private $type;
        private $caller;
        
        public function AuditAPICallsEntity() {
            parent::__construct(DATABASE_NAME,TABLE_ENTITY_API);

            $fieldList = array("item","translation","type","caller");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->item,$this->translation,$this->type,$this->caller);
        }
        
        public function getItem() {
            return $this->item;
        }

        public function getCaller() {
            return $this->caller;
        }

        public function setItem($item) {
            $this->item = $item;
        }

        public function setCaller($caller) {
            $this->caller = $caller;
        }
        
            public function getTranslation() {
            return $this->translation;
        }

        public function getType() {
            return $this->type;
        }

        public function setTranslation($translation) {
            $this->translation = $translation;
        }

        public function setType($type) {
            $this->type = $type;
        }
    }
?>
