<?php
    require_once 'GeneralUtils.php';
    require_once 'NamedQuery.php';
    require_once 'NamedConstants.php';
    require_once 'AuditAPICallsEntity.php';
    require_once 'BotAuditEntity.php';
    require_once 'EntityManager.php';
    /**
     * Access and modifies user related information from database
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class AuditsAPICallsDAO{
        private $aEntityManager;
        public function AuditsAPICallsDAO() {
            $this->aEntityManager = new EntityManager(NULL);
        }
        
        /**
         * 
         * @param JSON data - {item,translation,type, caller}
         * @return Array with status and message
         */
        public function AddAuditAPITrail($data) {
            $aAuditAPICallsEntity = new AuditAPICallsEntity();
            
            $aAuditAPICallsEntity->setItem($data[item]);
            $aAuditAPICallsEntity->setTranslation($data[translation]);
            $aAuditAPICallsEntity->setType($data[type]);
            $aAuditAPICallsEntity->setCaller($data[caller]);
                    
            $this->aEntityManager->setTable($aAuditAPICallsEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aAuditAPICallsEntity->ToArray());
            
            if($aResult['status']){
                $this->aEntityManager->getSql()->commitTransaction();
                return $aResult;
            }
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
        
        /**
         * 
         * @param JSON data - {item,translation,type, caller}
         * @return Array with status and message
         */
        public function AddBotMessengerTrail($user, $messangerId) {
            $aBotAuditEntity = new BotAuditEntity();
            
            $aBotAuditEntity->userId = $user;
            $aBotAuditEntity->messageId = $messangerId;
           
            $this->aEntityManager->setTable($aBotAuditEntity);
                        
            $this->aEntityManager->getSql()->beginTransaction();
            
            $aResult = $this->aEntityManager->addData($aBotAuditEntity->ToArray());
            
            if($aResult['status']){
                $this->aEntityManager->getSql()->commitTransaction();
                return $aResult;
            }
            $this->aEntityManager->getSql()->rollbackTransaction();
            return $aResult;
        }
        
         /**
         * 
         * @return Array with status and message
         */
        public function increaseRetryCountBotMessageWithId($messageId) {
            $aNameQuery = new NamedQuery("update bot_audit_calls set retry_count = retry_count + 1 where messageId = (?1?)");
            $aNameQuery->setParameter(1, $messageId);
            
            $aResult =  $this->aEntityManager->getSql()->updateData($aNameQuery->getQuery());
            
            if($aResult){
                return array(status=> true, message=> "Entity detail updated successfully");
            }
            return array(status=> true, message=> "Remove entity failed");
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function retrieveBotMessageWithId($messageId) {
            $aNameQuery = new NamedQuery("SELECT * from bot_audit_calls where messageId = (?1?)");
            $aNameQuery->setParameter(1, $messageId);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No Message recorded yet");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
         /**
         * 
         * @return Array with status and message
         */
        public function retrieveUserBotMessageByDate($userId, $date) {
            $aNameQuery = new NamedQuery("SELECT count(*) as count from bot_audit_calls where userId = (?1?) and date_created >= (?2?) group by userId");
            $aNameQuery->setParameter(1, $userId);
            $aNameQuery->setParameter(2, $date);
            
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No Message recorded yet");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditAPICalls() {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_AUDIT_API_CALLS);
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No audit api calls recorded yet");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditDinstincAPICallsByType($aType1, $aType2, $aLimit,$aDate) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_AUDIT_DISTINC_API_CALLS_WITH_TYPE );
            $aNameQuery->setParameter(1, $aType1);
            $aNameQuery->setParameter(2, $aType2);
            $aNameQuery->setParameterInteger(3, $aLimit);
            $aNameQuery->setParameterInteger(4, $aDate);
 
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No audit api calls recorded yet");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditAPICallsByType($aType1, $aType2, $aLimit,$aDate) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_AUDIT_API_CALLS_WITH_TYPE);
            $aNameQuery->setParameter(1, $aType1);
            $aNameQuery->setParameter(2, $aType2);
            $aNameQuery->setParameterInteger(3, $aLimit);
            $aNameQuery->setParameterInteger(4, $aDate);
 
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No audit api calls recorded yet");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditAPICallsByTypeAndSystem($aType1, $aType2,$system,$aLimit,$aDate) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_AUDIT_API_CALLS_WITH_TYPE_AND_SYSTEM);
            $aNameQuery->setParameter(1, $aType1);
            $aNameQuery->setParameter(2, $aType2);
             $aNameQuery->setParameter(5, "%".$system."%");
            $aNameQuery->setParameterInteger(3, $aLimit);
            $aNameQuery->setParameterInteger(4, $aDate);
             
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No audit api calls recorded yet");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
        
        
        /**
         * 
         * @return Array with status and message
         */
        public function listAuditAPICallsByTypeAndDateAndSystem($aType1, $aType2,$system,$aLimit,$aStartDate, $aEndDate) {
            $aNameQuery = new NamedQuery(NamedConstants::$LIST_ALL_AUDIT_API_CALLS_WITH_TYPE_DATE_AND_SYSTEM);
            $aNameQuery->setParameter(1, $aType1);
            $aNameQuery->setParameter(2, $aType2);
             $aNameQuery->setParameter(6, "%".$system."%");
            $aNameQuery->setParameterInteger(3, $aLimit);
            $aNameQuery->setParameterInteger(4, $aStartDate);
            $aNameQuery->setParameterInteger(5, $aEndDate);
             
            $aResult =  $this->aEntityManager->queryResults($aNameQuery->getQuery());
            $aCount = $this->aEntityManager->getSql()->getResultCount($aResult['resultsArray']);
            
            if($aCount == 0){
                 return array(status=> false, message=>"No audit api calls recorded yet");
            }else{
                $aRecords = $this->aEntityManager->getSql()->getRecordsInResults($aResult['resultsArray']);
                return array(status=> true,resultsArray => $aRecords );
            }
        }
    }
?>
