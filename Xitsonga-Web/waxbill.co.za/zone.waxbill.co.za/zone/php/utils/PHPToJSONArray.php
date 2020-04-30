<?php
    /**
     * Creates a JSONArray
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class PHPToJSONArray {
        
        public function newPaymentJSON($userId, $projectId,$paymentReference,$paymentDescription, $paymentAmount,$paymentDate) {
            $aJSON = "\"userId\":\"$userId\","."\"projectId\":\"$projectId\","."\"paymentReference\":\"$paymentReference\","."\"paymentDescription\":\"$paymentDescription\","."\"paymentDate\":\"$paymentDate\","."\"paymentAmount\":\"$paymentAmount\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for property value update
         * 
         * @param String pPropertyId
         * @param String pPropertyDescription
         * 
         * @see SystemPropertyDAO::updateSystemPropertyValue
         * 
         * @return JSONArray
         */
        public function updatePropertyDescriptionJSON($pPropertyId, $pPropertyDescription) {
            $aJSON = "\"propertyId\":\"$pPropertyId\","."\"propertyDescription\":\"$pPropertyDescription\"";
            return json_decode("{".$aJSON."}");
        }
         /**
         * Generates JSONArray for support ticket
         * 
         * @param String pStatus
         * @param String pticketId
         * 
         * @see SystemSupportDAO::updateSystemSupportTicketStatusByTicketId
         * 
         * @return JSONArray
         */
        public function updateTicketStatusJSON($pStatus, $pticketId) {
            $aJSON = "\"status\":\"$pStatus\","."\"supportId\":\"$pticketId\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for entity link select
         * 
         * @param String pMainEntity
         * @param String pLinkTypeName
         * 
         * @see SystemEntityLinkDAO::findRecordsByMainEntityAndLinkType
         * 
         * @return JSONArray
         */
        public function entityLinkQueryJSON($pMainEntity, $pLinkTypeName) {
            $aJSON = "\"mainEntity\":\"$pMainEntity\","."\"linkTypeName\":\"$pLinkTypeName\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for entity link select
         * 
         * @param String pSubEntity
         * @param String pLinkTypeName
         * 
         * @see SystemEntityLinkDAO::findRecordsByMainEntityAndLinkType
         * 
         * @return JSONArray
         */
        public function entityLinkQueryBySubLinkJSON($pSubEntity, $pLinkTypeName) {
            $aJSON = "\"subEntity\":\"$pSubEntity\","."\"linkTypeName\":\"$pLinkTypeName\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for entity select by type name
         * 
         * @param String pTypeName
         * 
         * @see SystemEntityDAO::retrieveEntityWithTypeName
         * @return JSONArray
         */
        public function entityQueryByTypeNameJSON($pTypeName) {
            $aJSON = "\"entityTypeName\":\"$pTypeName\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for entity select by type name and client id
         * 
         * @param String pTypeName
         * 
         * @see SystemEntityDAO::retrieveEntityWithTypeName
         * @return JSONArray
         */
        public function entityQueryByTypeNameAndClientIdJSON($pTypeName, $pClientId) {
            $aJSON = "\"entityTypeName\":\"$pTypeName\","."\"clientId\":\"$pClientId\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for entity link select
         * 
         * @param String pMainEntity
         * @param String pTypeGroupName
         * 
         * @see SystemEntityLinkDAO::findRecordsByMainEntityAndLinkTypeGroupName
         * 
         * @return JSONArray
         */
        public function entityLinkQueryByGroupJSON($pMainEntity, $pTypeGroupName) {
            $aJSON = "\"mainEntity\":\"$pMainEntity\","."\"typeGroupName\":\"$pTypeGroupName\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for entity insert
         * 
         * @param String pUserId
         * @param String pEntityType
         * @param String pEntityName
         * 
         * @return JSONArray
         */
        public function newEntityJSON($pUserId, $pEntityType,$pEntityName) {
            $aJSON = "\"userId\":\"$pUserId\","."\"entityType\":\"$pEntityType\","."\"entityName\":\"$pEntityName\"";
            return json_decode("{".$aJSON."}");
        }
        /**
         * Generates JSONArray for entity link insert
         * 
         * @param String pUserId
         * @param String pMainEntity
         * @param String pSubEntity
         * @param String pEntityLinkType
         * @param String pEntityLinkName
         * 
         * @return JSONArray
         */
        public function newEntityLinkJSON($pUserId,$pMainEntity,$pSubEntity,$pEntityLinkType,$pEntityLinkName) {
            $aJSON = "\"userId\":\"$pUserId\","."\"mainEntity\":\"$pMainEntity\","."\"subEntity\":\"$pSubEntity\","."\"entityLinkType\":\"$pEntityLinkType\","."\"entityLinkName\":\"$pEntityLinkName\"";
            return json_decode("{".$aJSON."}");
        }
        
        public function newEntityDetailJSON($pUserId,$pEntityId,$pEntityDetailType,$pContent) {
            $aJSON = "\"userId\":\"$pUserId\","."\"entityId\":\"$pEntityId\","."\"propertyId\":\"$pEntityDetailType\","."\"entityContent\":\"$pContent\"";
            return json_decode("{".$aJSON."}");
        }
        
        public function addOrUpdateEntityDetailJSON($pUserId,$pEntityId,$pEntityDetailId,$pEntityDetailType,$pContent) {
            $aJSON = "\"userId\":\"$pUserId\","."\"entityId\":\"$pEntityId\","."\"entityDetailId\":\"$pEntityDetailId\","."\"propertyId\":\"$pEntityDetailType\","."\"entityContent\":\"$pContent\"";
            return json_decode("{".$aJSON."}");
        }
    }
