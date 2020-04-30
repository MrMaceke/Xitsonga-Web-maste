<?php
    /**
     * SQL strings
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
     
    class NamedConstants{
        
       public static $FIND_RECORD_BY_USER_ID = "SELECT user_id,firstname, lastname, email, password, password_salt,admin_user FROM users WHERE  LOWER(user_id) = LOWER(?1?) LIMIT 1";

       public static $UPDATE_ACTIVATE_STATUS = "UPDATE users set activation_status = ?1? where user_id = ?2?";
       
       public static $UPDATE_USER = "UPDATE users set firstname = ?1?, lastname = ?2? where user_id = ?3?";
       
       public static $UPDATE_USER_ACCESS_LEVEL = "UPDATE users set admin_user = ?1? where user_id = ?2?";
       
       public static $UPDATE_USER_PASSWORD = "UPDATE users set password = ?1?, password_salt = ?2? where user_id = ?3?";
       
       public static $FIND_ACTIVATION_RECORD_BY_USER_ID = "SELECT * FROM activations WHERE  LOWER(user_id) = LOWER(?1?) LIMIT 1";
       
       public static $FIND_ACTIVATION_RECORD_BY_HASH = "SELECT U.* FROM activations as A inner join users as U on U.user_id = A.user_id WHERE LOWER(A.activation_key) = LOWER(?1?) LIMIT 1";

       public static $FIND_RECORD_BY_EMAIL = "SELECT user_id,firstname, lastname, email, password, password_salt,admin_user,activation_status,account_status FROM users WHERE  LOWER(email) = LOWER(?1?) LIMIT 1";

       public static $FIND_USER_RECORD_BY_EMAIL = "SELECT user_id,firstname, lastname, email FROM users WHERE  LOWER(email) like LOWER(?1?) LIMIT 15";
       
       public static $LIST_ALL_USERS = "SELECT * from users";
       
       public static $LIST_ALL_AUDIO_CONSTRUCTS = "SELECT * from audio_constructs where audio_construct_status = 1 order by construct asc";
       
       public static $LIST_ALL_MIGRATED_USERS  = "SELECT * from users where activation_status = (?1?)";
       
       public static $LIST_USERS_BY_ACCESS_LEVEL  = "SELECT * from users where activation_status = 1 and admin_user = (?1?)";
       
       public static $LIST_ALL_MAIN_ITEM_TYPES = "SELECT user_id,item_type, description from item_type where type ='1' or type ='3' order by description asc LIMIT 100";
       
       public static $GET_ITEM_TYPE_BY_ID = "SELECT user_id,item_type, description from item_type where item_type = LOWER(?1?) order by description asc LIMIT 1";
       
       public static $LIST_ALL_ITEM_TYPES_BY_TYPE = "SELECT user_id,item_type, description from item_type where active = '1' and type = (?1?) order by description asc LIMIT 100";

       public static $LIST_ALL_ITEM_TYPES = "SELECT I.type,I.date_created,I.item_type, I.description, U.firstname, U.lastname from item_type AS I inner join users as U on U.user_id = I.user_id where I.active = '1' order by I.date_created desc";
       
       public static $LIST_ALL_ENTITIES = "SELECT E.*,U.firstname, U.lastname,U.email, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and I.description != 'Blog Post' and LOWER(SUBSTR(E.entity_name,1,1)) = LOWER(?1?) order by E.entity_name asc";
     
       public static $LIST_ALL_ENTITIES_COUNT = "SELECT E.*,U.firstname, U.lastname,U.email, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and I.description != 'Blog Post' order by E.entity_name asc";
       
       public static $LIST_ALL_ENTITIES_BY_USER_ID_COUNT = "SELECT E.*,U.firstname, U.lastname,U.email, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and E.user_id = (?1?) and I.description != 'Blog Post' order by E.entity_name asc";
	 
       public static $LIST_ENTITIES_BY_TYPE = "SELECT E.*,U.firstname, U.lastname,U.email,U.facebook_reg, U.facebook_id, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and (LOWER(I.description) = LOWER(?1?) or LOWER(I.description) = LOWER(?4?) or LOWER(I.description) = LOWER(?5?) or LOWER(I.description) = LOWER(?6?) or LOWER(I.description) = LOWER(?7?) or LOWER(I.description) = LOWER(?8?) or LOWER(I.description) = LOWER(?9?))  order by E.entity_name asc LIMIT ?2?, ?3?";
       
       public static $LIST_ENTITIES_BY_TYPE_AND_FIRST_LETTER = "SELECT E.*,U.firstname, U.lastname,U.email,U.facebook_reg, U.facebook_id, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and LOWER(I.description) = LOWER(?1?) and LOWER(SUBSTR(E.entity_name,1,1)) = LOWER(?2?) order by E.entity_name asc";
       
       public static $LIST_ENTITIES_BY_TYPE_ORDER_BY_DATE = "SELECT E.*,U.firstname, U.lastname,U.email,U.facebook_reg, U.facebook_id, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and LOWER(I.description) = LOWER(?1?) order by E.date_created desc LIMIT 1";
       
       public static $LIST_ENTITIES_BY_SUB_TYPE = "SELECT E.*,U.firstname, U.lastname,U.email,U.facebook_reg, U.facebook_id, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join entity_details as ED on ED.entity_id = E.entity_id inner join item_type as I on I.item_type = ED.content where E.active ='1' and LOWER(I.description) = LOWER(?1?) order by E.entity_name asc LIMIT ?2?, ?3?";
       
       public static $LIST_ENTITIES_CONTAINING_SUB_TYPE = "SELECT E.*,U.firstname, U.lastname,U.email,U.facebook_reg, U.facebook_id, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join entity_details as ED on ED.entity_id = E.entity_id inner join item_type as I on I.item_type = ED.item_type where E.active ='1' and LOWER(I.description) = LOWER(?1?) and ED.active = '1' order by E.entity_name asc LIMIT ?2?, ?3?";
       
       public static $SEARCH_ENTITIES_BY_NAME = "SELECT E.*,I.description from entity as E inner join item_type as I on I.item_type = E.item_type inner join entity_details as ED on (ED.entity_id = E.entity_id and LOWER(ED.item_type) = 10) where E.active ='1' and ( LOWER(E.entity_name) like LOWER(?1?) or LOWER(ED.content) like LOWER(?10?)) and ( LOWER(I.description) = LOWER(?2?) or LOWER(I.description) = LOWER(?3?) or LOWER(I.description) = LOWER(?4?) or LOWER(I.description) = LOWER(?5?) or LOWER(I.description) = LOWER(?6?)) order by E.entity_name asc";
       
       public static $SEARCH_ENTITIES_BY_NAME_EXCLUDING = "SELECT E.*,I.description from entity as E inner join item_type as I on I.item_type = E.item_type inner join entity_details as ED on (ED.entity_id = E.entity_id and LOWER(ED.item_type) = 10) where E.active ='1' and ( (LOWER(E.entity_name) like LOWER(?1?) or LOWER(ED.content) like LOWER(?10?)) and (LOWER(E.entity_name) != LOWER(?11?) and LOWER(ED.content) != LOWER(?12?))) and ( LOWER(I.description) = LOWER(?2?) or LOWER(I.description) = LOWER(?3?) or LOWER(I.description) = LOWER(?4?) or LOWER(I.description) = LOWER(?5?) or LOWER(I.description) = LOWER(?6?)) order by E.entity_name asc LIMIT 10";
              
       public static $LIST_TOWNS = "SELECT E.*,U.firstname, U.lastname,U.email,U.facebook_reg, U.facebook_id, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and LOWER(I.description) = LOWER(?1?) order by E.date_created desc LIMIT ?2?, ?3?";

       public static $COUNT_ENTITIES_BY_TYPE = "SELECT E.*,U.firstname, U.lastname,U.email, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and LOWER(I.description) = LOWER(?1?) order by E.date_created desc";

       public static $COUNT_ENTITIES_BY_SUB_TYPE = "SELECT E.*,U.firstname, U.lastname,U.email,U.facebook_reg, U.facebook_id, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join entity_details as ED on ED.entity_id = E.entity_id inner join item_type as I on I.item_type = ED.content where E.active ='1' and LOWER(I.description) = LOWER(?1?)";
              
       public static $LIST_ENTITIES_CONTAINS_WORD = "SELECT E.*,U.firstname, U.lastname, U.facebook_reg, U.facebook_id, U.email, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and (LOWER(E.entity_name) like LOWER(?1?) or LOWER(E.entity_name) like LOWER(?2?) or LOWER(E.entity_name) like LOWER(?3?) ) and LOWER(E.entity_name) != LOWER(?4?) and (I.description = 'proverbs' OR I.description = 'phrases' OR I.description = 'idioms') order by E.date_created desc";

       public static $LIST_ENTITIY_BY_NAME = "SELECT E.*,U.firstname, U.lastname, U.facebook_reg, U.facebook_id, U.email, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and LOWER(E.entity_name) = LOWER(?1?) order by E.date_created desc";
       
       public static $LIST_ENTITIY_BY_ID = "SELECT E.*,U.firstname, U.lastname, U.facebook_reg, U.facebook_id, U.email, I.description from entity as E inner join users as U on U.user_id = E.user_id inner join item_type as I on I.item_type = E.item_type where E.active ='1' and LOWER(E.entity_id) = LOWER(?1?) order by E.date_created desc";
       
       public static $GET_ITEM_TYPE_BY_DESCRIPTION = "select * from item_type where LOWER(description) = LOWER(?1?)";
       
       public static $GET_ENTITY_DETAIL_BY_ENTITY_ID = "select U.firstname,U.lastname,U.facebook_reg, U.facebook_id, ED.*,I.description from entity_details as ED inner join item_type as I on I.item_type = ED.item_type inner join users U on U.user_id = ED.user_id where ED.entity_id = LOWER(?1?) and ED.active ='1'";
       
       public static $GET_ENTITY_DETAIL_BY_ID = "select U.firstname,U.lastname, ED.*,I.description from entity_details as ED inner join item_type as I on I.item_type = ED.item_type inner join users U on U.user_id = ED.user_id where ED.entity_details_id = LOWER(?1?) and ED.active ='1' LIMIT 1";

       public static $GET_ENTITY_DETAIL_BY_ENTITY_ID_AND_TYPE = "select ED.*,I.description from entity_details as ED inner join item_type as I on I.item_type = ED.item_type where ED.entity_id = LOWER(?1?) and I.description = LOWER(?2?) and ED.active ='1'";

       public static $GET_MOST_RECENT_ENTITY_BY_USER = "select * from entity where LOWER(user_id) = LOWER(?1?) AND LOWER(item_type) = LOWER(?2?) AND LOWER(date_created) = LOWER(?3?) order by date_created desc";

       public static $UPDATE_ITEM_TYPE = "UPDATE item_type set description = ?1?, type = ?2? where item_type = ?3?";
       
       public static $REMOVE_ITEM_TYPE = "UPDATE item_type set active = '0' where item_type = ?1?";
       
        public static $UPDATE_ENTITY = "UPDATE entity set entity_name = ?1?, item_type = ?2? where entity_id = ?4?";

        public static $REMOVE_ENTITY = "UPDATE entity set active = '0' where entity_id = ?1?";

        public static $UPDATE_ENTITY_DETAIL = "UPDATE entity_details set content = ?1? where entity_details_id = ?2?";

        public static $REMOVE_ENTITY_DETAIL = "UPDATE entity_details set active = '0' where entity_details_id = ?1?";

        public static $LIST_AUDIT_FOR_ITEM = "SELECT A.*,U.firstname, U.lastname, U.email from audit_table as A inner join users as U on U.user_id = A.user_id where LOWER(A.item_id) = LOWER(?1?) order by A.date_created desc";
        
        public static $LIST_AUDIT_BY_USER = "SELECT A.*,U.firstname, U.lastname, U.email from audit_table as A inner join users as U on U.user_id = A.user_id where LOWER(A.user_id) = LOWER(?1?) order by A.date_created desc LIMIT 30";
        
        public static $LIST_AUDIT_BY_USER_ID = "SELECT A.*,U.firstname, U.lastname, U.email from audit_table as A inner join users as U on U.user_id = A.user_id where LOWER(A.user_id) = LOWER(?1?) order by A.date_created desc";
    
        public static $LIST_ALL_EXERCISES = "SELECT U.firstname,U.lastname,E.* from exercises as E inner join users as U on U.user_id = E.user_id where E.active = 1 order by E.date_created asc";
        
        public static $LIST_EXERCISES_BY_PUBLISHED = "SELECT U.firstname,U.lastname,E.* from exercises as E inner join users as U on U.user_id = E.user_id where E.published = ?1? and E.active = 1 order by E.date_created desc LIMIT ?2?, ?3?";
        
        public static $COUNT_EXERCISES_BY_PUBLISHED = "SELECT U.firstname,U.lastname,E.* from exercises as E inner join users as U on U.user_id = E.user_id where E.published = ?1? and E.active = 1 order by E.date_created desc";

        public static $LIST_ALL_EXERCISES_BY_URL = "SELECT U.firstname,U.lastname,E.* from exercises as E inner join users as U on U.user_id = E.user_id where E.exercise_title = LOWER(?1?) and E.active = 1 order by E.date_created desc";
        
        public static $LIST_ALL_QUESTIONS_BY_EXERCISE_ID = "SELECT U.firstname,U.lastname,Q.* from questions as Q inner join users as U on U.user_id = Q.user_id where Q.exercise_id = LOWER(?1?) and Q.active = 1 order by Q.date_created asc";
        
        public static $LIST_ALL_ANSWERS_BY_QUESTION_ID = "SELECT U.firstname,U.lastname,Q.* from answers as Q inner join users as U on U.user_id = Q.user_id where Q.question_id = LOWER(?1?) order by Q.date_created asc";

        public static $REMOVE_ANSWERS_BY_QUESTION = "DELETE from answers where question_id = LOWER(?1?)";
        
        public static $EDIT_EXERCISE_BY_ID = "UPDATE exercises set exercise_title = ?1?, exercises_text = ?2?, published = ?3? where exercise_id = LOWER(?4?)";
        
        public static $REMOVE_EXERCISE_BY_ID = "UPDATE exercises set active = 0 where exercise_id = LOWER(?1?)";
        
        public static $EDIT_QUESTION_BY_ID = "UPDATE questions set question_text = ?1? where question_id = LOWER(?2?)";
        
        public static $REMOVE_QUESTION_BY_ID = "UPDATE questions set active = 0 where question_id = LOWER(?1?)";
        
        public static $LIST_ALL_AUDIT_API_CALLS = "SELECT * from audit_api_calls order by date_created desc LIMIT 1000";
        
        public static $LIST_ALL_AUDIT_API_CALLS_WITH_TYPE = "SELECT * from audit_api_calls where date_created >= ('?4?') AND (type like LOWER(?1?) or type like LOWER(?2?)) ORDER BY date_created desc LIMIT ?3?";
        
        public static $LIST_ALL_AUDIT_API_CALLS_WITH_TYPE_AND_SYSTEM = "SELECT * from audit_api_calls where date_created >= ('?4?') AND (((type like LOWER(?1?) or type like LOWER(?2?)) and caller like LOWER(?5?))) ORDER BY date_created asc LIMIT ?3?";

        public static $LIST_ALL_AUDIT_API_CALLS_WITH_TYPE_DATE_AND_SYSTEM = "SELECT * from audit_api_calls where date_created <= ('?5?') AND date_created >= ('?4?') AND (((type like LOWER(?1?) or type like LOWER(?2?)) and caller like LOWER(?6?))) ORDER BY date_created asc LIMIT ?3?";
        
        public static $LIST_ALL_AUDIT_DISTINC_API_CALLS_WITH_TYPE = "SELECT *, count( * ) AS views from audit_api_calls where date_created >= ('?4?') AND (type like LOWER(?1?) or type like LOWER(?2?)) GROUP BY item ORDER BY views desc, date_created asc LIMIT ?3?";
         
        public static $FIND_CACH_BY_NAME = "SELECT * from definations_cache where LOWER(item) = LOWER(?1?) and LOWER(device) = LOWER(?2?) LIMIT 1";
    }
?>
