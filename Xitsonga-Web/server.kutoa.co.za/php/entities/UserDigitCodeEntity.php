<?php
    require_once __DIR__. '/../persistence/DbTable.php';
    require_once __DIR__. '/../constants/TableNamesConstants.php';
    
    /**
     * Entity for table "user_digit_code"
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class UserDigitCodeEntity extends DbTable{
        private $phoneNumber;
        private $digitCode;
        
        public function UserDigitCodeEntity() {
            parent::__construct(TableNamesConstants::USER_DIGIT_CODE);

            $fieldList = array("phone_number","digit_code");
            $this->setFieldList($fieldList);
        }
        
        public function ToArray(){
            return array(trim($this->phoneNumber),trim($this->digitCode));
        }
        public function getPhoneNumber() {
            return $this->phoneNumber;
        }

        public function getDigitCode() {
            return $this->digitCode;
        }

        public function setPhoneNumber($phoneNumber) {
            $this->phoneNumber = $phoneNumber;
        }

        public function setDigitCode($digitCode) {
            $this->digitCode = $digitCode;
        }
    }