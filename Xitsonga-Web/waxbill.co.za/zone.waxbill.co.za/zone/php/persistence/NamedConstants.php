<?php
    /**
     * SQL strings
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
     
    class NamedConstants{
       // User select constants
       const FIND_SYSTEM_USER_RECORD_BY_USER_KEY = "SELECT user.*, role.role_name,password.password,password.salt FROM system_users as user inner join system_roles as role on role.role_id = user.role_id inner join system_passwords as password on password.user_id = user.user_id WHERE user.record_status ='1' and LOWER(user.user_key) = LOWER(?1?) LIMIT 1";
       const FIND_SYSTEM_USER_RECORD_BY_USER_ID = "SELECT user.*, role.role_name,password.password,password.salt FROM system_users as user inner join system_roles as role on role.role_id = user.role_id inner join system_passwords as password on password.user_id = user.user_id WHERE user.record_status ='1' and LOWER(user.user_id) = LOWER(?1?) LIMIT 1";
       const FIND_SYSTEM_USER_RECORD_BY_EMAIL = "SELECT user.*, role.role_name FROM system_users as user inner join system_roles as role on role.role_id = user.role_id WHERE user.record_status ='1' and LOWER(user.email) = LOWER(?1?) LIMIT 1";
       const FIND_SYSTEM_PASSWORD_AND_ACTIVATION_RECORD_BY_USER_ID = "SELECT password.user_id, password.password as password,password.salt,activation.status FROM system_passwords as password inner join system_activations as activation on password.user_id = activation.user_id WHERE password.record_status ='1' and activation.record_status ='1' and LOWER(password.user_id) = LOWER(?1?) LIMIT 1";
       const FIND_SYSTEM_PASSWORD_RECORD_BY_USER_KEY = "SELECT password.* FROM system_passwords as password inner join system_users as user on user.user_id = password.user_id WHERE user.record_status ='1' and LOWER(user.user_key) = LOWER(?1?) LIMIT 1";
       const FIND_SYSTEM_PREVIOUS_LOGIN_RECORD_BY_USER_KEY = "SELECT login.* FROM system_logins as login inner join system_users as user on user.user_id = login.user_id WHERE user.record_status ='1' and LOWER(user.user_key) = LOWER(?1?) order by login.date_created desc LIMIT 1,1";
       const FIND_SYSTEM_LATEST_LOGIN_RECORD_BY_USER_KEY = "SELECT login.* FROM system_logins as login inner join system_users as user on user.user_id = login.user_id WHERE user.record_status ='1' and LOWER(user.user_key) = LOWER(?1?) order by login.date_created desc LIMIT 1";
       const FIND_SYSTEM_USER_RECORDS = "SELECT user.*, role.role_name FROM system_users as user inner join system_passwords as password on password.user_id = user.user_id inner join system_roles as role on role.role_id = user.role_id WHERE user.record_status ='1' order by user.date_created desc";
       const FIND_SYSTEM_ROLE_RECORDS = "SELECT * FROM system_roles WHERE record_status ='1' order by role_name asc";
       
       // User update constants
       const UPDATE_SYSTEM_USER_PASSWORD = "UPDATE system_passwords set password = ?1?, salt = ?2? where user_id = ?3?";
       const UPDATE_SYSTEM_USER = "UPDATE system_users set email = ?1?, role_id = ?2? where user_id = ?3?";
       const UPDATE_SYSTEM_USER_EMAIL_ADDRESS = "UPDATE system_users set email = ?1? where user_key = ?2?";
       
       // System group constants
        const FIND_SYSTEM_GROUP_RECORD_BY_GROUP_NAME = "SELECT * FROM system_groups WHERE record_status ='1' and LOWER(group_name) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_GROUP_RECORD_BY_GROUP_ID = "SELECT * FROM system_groups WHERE record_status ='1' and LOWER(group_id) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_GROUP_RECORDS = "SELECT * FROM system_groups WHERE record_status ='1' order by group_value asc";
        
       // System group update constants
        const UPDATE_SYSTEM_GROUP = "UPDATE system_groups set group_name = ?1?, group_value = ?2?, group_description = ?3? where group_id = ?4?";
        const DELETE_SYSTEM_GROUP = "UPDATE system_groups set record_status = ?1? where group_id = ?2?";
        
        // System properties constants
        const FIND_SYSTEM_PROPERTY_RECORD_BY_PROPERTY_NAME = "SELECT * FROM system_properties WHERE record_status ='1' and LOWER(property_name) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_PROPERTY_RECORD_BY_PROPERTY_ID = "SELECT property.*,grp.group_name FROM system_properties as property inner join system_groups as grp on grp.group_id = property.group_id WHERE property.record_status ='1' and LOWER(property.property_id) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_PROPERTY_RECORDS = "SELECT property.*,grp.group_name FROM system_properties as property inner join system_groups as grp on grp.group_id = property.group_id WHERE property.record_status ='1' order by property_value asc";
        const FIND_SYSTEM_PROPERTY_RECORDS_BY_GROUP_NAME = "SELECT property.*,grp.group_name FROM system_properties as property inner join system_groups as grp on grp.group_id = property.group_id WHERE property.record_status ='1' and LOWER(grp.group_name) = LOWER (?1?) order by property_value asc";

        // System properties update constants
        const DELETE_SYSTEM_PROPERTY = "UPDATE system_properties set record_status = ?1? where property_id = ?2?";
        const UPDATE_SYSTEM_PROPERTY = "UPDATE system_properties set property_name = ?1?, property_value = ?2?, property_description = ?3?,group_id = ?4? where property_id = ?5?";
        const UPDATE_SYSTEM_PROPERTY_DESCRIPTION = "UPDATE system_properties set property_description = ?1? where property_id = ?2?";
        
        // Entity select constants
        const FIND_SYSTEM_ENTITY_RECORD_BY_NAME = "SELECT entity.*,property.property_name,user.user_key FROM system_entity as entity inner join system_properties as property on property.property_id = entity.entity_type inner join system_users as user on entity.user_id = user.user_id WHERE entity.record_status ='1' and LOWER(entity.entity_name) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_ENTITY_RECORD_BY_ID = "SELECT entity.*,property.property_name,user.user_key FROM system_entity as entity inner join system_properties as property on property.property_id = entity.entity_type inner join system_users as user on entity.user_id = user.user_id WHERE entity.record_status ='1' and LOWER(entity.entity_id) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_ENTITY_RECORDS_BY_TYPE_NAME = "select entity.* from system_entity as entity inner join system_properties as property on entity.entity_type = property.property_id where entity.record_status ='1' and LOWER(property.property_name) = LOWER(?1?)";
        const FIND_SYSTEM_ENTITY_RECORDS_BY_TYPE_NAME_AND_CLIENT_ID = "select entity.* from system_entity as entity inner join system_users as user on user.user_id = entity.user_id inner join system_properties as property on entity.entity_type = property.property_id where entity.record_status ='1' and LOWER(property.property_name) = LOWER(?1?) and LOWER(user.user_key) = LOWER(?2?)";
        const FIND_SYSTEM_ENTITY_RECORDS_BY_ENTITY_DETAIL_CONTENT = "select entity.* from system_entity as entity inner join system_entity_details as detail on detail.entity_id = entity.entity_id where entity.record_status ='1' and LOWER(detail.entity_detail_content) = LOWER(?1?)";
        const FIND_SYSTEM_ENTITY_RECORDS_GROUP_NAME = "SELECT entity.*,property.property_name,user.user_key FROM system_entity as entity inner join system_properties as property on property.property_id = entity.entity_type inner join system_groups as grp on grp.group_id = property.group_id inner join system_users as user on entity.user_id = user.user_id WHERE entity.record_status ='1' and LOWER(grp.group_name) = LOWER(?1?)";
        const FIND_SYSTEM_ENTITY_RECORDS_BY_GROUP_NAME_AND_CLIENT_ID = "SELECT entity.*,property.property_name,user.user_key FROM system_entity as entity inner join system_properties as property on property.property_id = entity.entity_type inner join system_groups as grp on grp.group_id = property.group_id inner join system_users as user on entity.user_id = user.user_id WHERE entity.record_status ='1' and LOWER(grp.group_name) = LOWER(?1?) and LOWER(user.user_key) = LOWER(?2?)";
        
        // Entity details select constants
        const FIND_SYSTEM_ENTITY_DETAIL_RECORD_BY_TYPE_AND_ENTITY_ID = "SELECT detail.* FROM system_entity_details as detail inner join system_entity as entity on entity.entity_id = detail.entity_id WHERE detail.record_status ='1' and LOWER(detail.entity_id) = LOWER(?1?) and LOWER(detail.entity_detail_type) = LOWER(?2?) LIMIT 1";
        const FIND_SYSTEM_ENTITY_DETAIL_RECORDS_BY_ENTITY_ID = "SELECT detail.*,property.property_name, grp.group_name FROM system_entity_details as detail inner join system_entity as entity on entity.entity_id = detail.entity_id inner join system_properties as property on detail.entity_detail_type = property.property_id inner join system_groups as grp on grp.group_id = property.group_id WHERE detail.record_status ='1' and LOWER(detail.entity_id) = LOWER(?1?) order by grp.group_value asc, property.property_value asc";
        const FIND_SYSTEM_ENTITY_DETAIL_RECORDS_BY_ENTITY_NAME = "SELECT detail.*,property.property_name, grp.group_name FROM system_entity_details as detail inner join system_entity as entity on entity.entity_id = detail.entity_id inner join system_properties as property on detail.entity_detail_type = property.property_id inner join system_groups as grp on grp.group_id = property.group_id WHERE detail.record_status ='1' and LOWER(entity.entity_name) = LOWER(?1?) order by grp.group_value asc, property.property_value asc";

        // Entity details update constants
        const UPDATE_SYSTEM_ENTITY_DETAIL_RECORD_CONTENT = "UPDATE system_entity_details set entity_detail_content = ?1? where entity_detail_id = ?2?";
        const UPDATE_SYSTEM_ENTITY_DETAIL_RECORD_CONTENT_BY_TYPE_AND_ENTITY_ID= "UPDATE system_entity_details set entity_detail_content = ?1? where LOWER(entity_id) = LOWER(?2?) and LOWER(entity_detail_type) = LOWER(?3?)";
        
         // Entity links select constants
        const FIND_SYSTEM_ENTITY_LINK_RECORD_BY_NAME = "SELECT * FROM system_entity_links as link WHERE link.record_status ='1' and LOWER(link.entity_link_name) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_ENTITY_LINK_RECORD_BY_SUB_ENTITY = "SELECT * FROM system_entity_links as link WHERE link.record_status ='1' and LOWER(link.sub_entity) = LOWER(?1?) LIMIT 1";
        const FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_MAIN_ENTITY_AND_LINK_TYPE = "SELECT link.*,property.property_name FROM system_entity_links as link inner join system_properties as property on property.property_id = link.entity_link_type WHERE link.record_status ='1' and LOWER(link.main_entity) = LOWER(?1?) and LOWER(property.property_name) = LOWER(?2?) LIMIT 300";
        const FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_MAIN_ENTITIES_AND_LINK_TYPE = "SELECT link.*,property.property_name FROM system_entity_links as link inner join system_properties as property on property.property_id = link.entity_link_type WHERE link.record_status ='1' and LOWER(link.main_entity) in (?1?) and LOWER(property.property_name) = LOWER(?2?) LIMIT 300";        
        const FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_SUB_ENTITY_AND_LINK_TYPE = "SELECT link.*,property.property_name FROM system_entity_links as link inner join system_properties as property on property.property_id = link.entity_link_type WHERE link.record_status ='1' and LOWER(link.sub_entity) = LOWER(?1?) and LOWER(property.property_name) = LOWER(?2?) LIMIT 300";
        const FIND_SYSTEM_ENTITY_LINK_RECORDS_BY_MAIN_ENTITY_AND_LINK_TYPE_GROUP_NAME = "SELECT link.*,property.property_name FROM system_entity_links as link inner join system_properties as property on property.property_id = link.entity_link_type inner join system_groups as grp on grp.group_id = property.group_id WHERE link.record_status ='1' and LOWER(link.main_entity) = LOWER(?1?) and LOWER(grp.group_name) = LOWER(?2?) order by grp.group_value asc LIMIT 300";
        
        // Development deals select constants
        const FIND_FINANCIAL_DEVELOPMENT_DEALS_RECORDS = "SELECT * FROM financial_development_deals WHERE record_status ='1' order by date_created desc";
        const FIND_FINANCIAL_DEVELOPMENT_DEALS_RECORD_BY_DEAL_CODE = "SELECT * FROM financial_development_deals WHERE record_status ='1' AND LOWER(deal_code) = LOWER(?1?) LIMIT 1";
       
        // Development deals update constants
        const UPDATE_FINANCIAL_DEVELOPMENT_DEALS_RECORD_BY_DEAL_CODE = "UPDATE financial_development_deals set deal_name = ?1?, deal_description = ?2?, deal_price = ?3?, start_date = ?4?, end_date = ?5?  where deal_code = ?6?";
        const REMOVE_FINANCIAL_DEVELOPMENT_DEALS_RECORD_BY_DEAL_CODE = "UPDATE financial_development_deals set record_status = '0' where deal_code = ?1?";

        // Quotes select constants
        const FIND_FINANCIAL_QUOTE_RECORD_BY_QUOTE_NO = "SELECT * FROM financial_quotes WHERE record_status ='1' AND LOWER(quote_name) = LOWER(?1?) LIMIT 1";
        const FIND_FINANCIAL_QUOTE_RECORD_BY_PROJECT_ID = "SELECT * FROM financial_quotes WHERE record_status ='1' AND LOWER(project_id) = LOWER(?1?) LIMIT 1";        
        const FIND_FINANCIAL_QUOTE_DETAIL_RECORDS_BY_QUOTE_NO = "SELECT details.*,deals.deal_name,deals.deal_description FROM financial_quotes_details as details inner join financial_development_deals as deals on deals.deal_code = details.deal_code WHERE details.record_status ='1' AND LOWER(details.quote_name) = LOWER(?1?)";
        const FIND_FINANCIAL_QUOTE_RECORDS = "SELECT * FROM financial_quotes WHERE record_status ='1' order by date_created desc";

        // Quotes update constants
        const UPDATE_FINANCIAL_QUOTE_PROJECT_ID_FOR_QUOTE_NUMBER = "UPDATE financial_quotes set project_id = ?1? where quote_name = ?2?";
        const DELETE_FINANCIAL_QUOTES_FOR_PROJECT = "UPDATE financial_quotes set record_status = ?1? where project_id = ?2?";
        
         // System support tickets select contants
        const FIND_SYSTEM_SUPPORT_RECORDS = "SELECT * FROM system_support WHERE record_status ='1' order by date_created desc";
        const FIND_SYSTEM_SUPPORT_RECORDS_BY_USER_ID = "SELECT * FROM system_support WHERE record_status ='1'  AND LOWER(user_id) = LOWER(?1?) order by date_created desc";
        const FIND_SYSTEM_SUPPORT_RECORD_BY_SUPPORT_ID = "SELECT * FROM system_support WHERE record_status ='1' AND LOWER(support_id) = LOWER(?1?) LIMIT 1";
        
        // System support tickets update contants
        const UPDATE_SYSTEM_SUPPORT_STATUS = "UPDATE system_support set support_status = ?1? where support_id = ?2?";
        
        // Payment select contants
        const FIND_FINANCIAL_PAY_RECORD_BY_PAYMENT_CODE = "SELECT * FROM financial_payment WHERE record_status ='1' AND LOWER(payment_id) = LOWER(?1?) LIMIT 1";
        const FIND_FINANCIAL_PAYMENTS = "SELECT * FROM financial_payment WHERE record_status ='1' order by date_created desc";
        const FIND_FINANCIAL_PAYMENTS_USER_ID = "SELECT * FROM financial_payment WHERE record_status ='1' AND LOWER(user_id) = LOWER(?1?) order by date_created desc";
        const FIND_FINANCIAL_PAYMENTS_PROJECT_ID = "SELECT * FROM financial_payment WHERE record_status ='1' AND LOWER(project_id) = LOWER(?1?) order by date_created desc";
    }