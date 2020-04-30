<?php
    require_once 'DbTable.php';
    /**
     * Entity for table "bot_audit_calls"
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class BotAuditEntity extends DbTable{
        public $userId;
        public $messageId ;
        
        public function BotAuditEntity() {
            parent::__construct(DATABASE_NAME, "bot_audit_calls");

            $fieldList = array("userId","messageId");
            $this->setFieldList($fieldList);
        }
        
        public function  ToArray(){
            return array($this->userId,$this->messageId);
        }
    }

?>
