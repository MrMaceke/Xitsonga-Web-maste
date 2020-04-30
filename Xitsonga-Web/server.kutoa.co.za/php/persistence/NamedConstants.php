<?php
    /**
     * SQL strings
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
     
    class NamedConstants{
       // Digit codes constants
       const UPDATE_USER_DIGIT_CODE_BY_PHONE_NUMBER = "UPDATE user_digit_code set record_status = ?1? where phone_number = ?2?";
       const DELETE_USER_BY_USER_ID = "UPDATE users set record_status = ?1? where user_id = ?2?";
       const DELETE_CONTACT_BY_USER_ID = "DELETE FROM emergency_contacts where user_id = ?1?";
       const UPDATE_USER_BY_USER_ID = "UPDATE users set firebase_id = ?2?, facebook_id = ?3?, phone_number = ?4?, firstname = ?5?, lastname = ?6?, email_address = ?7?, gender = ?8? where user_id = ?1?";
       const SELECT_USER_DIGIT_CODE_BY_PHONE_NUMBER = "select * from user_digit_code where record_status = '1' and phone_number = ?1?";
       
       // User constants       
       const SELECT_USER_BY_PHONE_NUMBER = "select * from users where record_status = '1' and phone_number = ?1?";
       const SELECT_USER_BY_FACEBOOK_ID = "select * from users where record_status = '1' and facebook_id = ?1?";
       const SELECT_USER_BY_USER_ID = "select * from users where record_status = '1' and user_id = ?1?";
       const SELECT_CONTACTS_BY_USER_ID = "select * from emergency_contacts where user_id = ?1?";
       const SELECT_USER_BY_F_F_P = "select * from users where record_status = '1' and firebase_id = ?1? and facebook_id = ?2? and phone_number = ?3?";
       
       // Ride constants       
       const SELECT_RIDE_REQUEST_EXCLUDE_USER_ID_BY_DISTANCE = "select  u.firebase_id,u.facebook_id, u.firstname, u.lastname,u.phone_number, rd.* from ride_requests as rd inner join users as u on u.user_id = rd.user_id where rd.status = '0' and rd.record_status = '1' and rd.request_type = ?1? and rd.user_id != ?2? and (6371 * acos (cos ( radians(?3?) )* cos( radians( rd.pickup_latitude ) )* cos( radians( rd.pickup_longitude ) - radians(?4?) )+ sin ( radians(?3?) )* sin( radians( rd.pickup_latitude ) ))) <= ?5? order by rd.date_created desc";
       const SELECT_RIDE_REQUESTS_EXCLUDE_USER_ID = "select u.firebase_id,u.facebook_id, u.firstname, u.lastname,u.phone_number, rd.* from ride_requests as rd inner join users as u on u.user_id = rd.user_id where rd.status = '0' and rd.record_status = '1' and rd.request_type = ?1? and rd.user_id != ?2? order by rd.date_created desc";
       const SELECT_RIDE_REQUESTS_BY_USER_ID = "select u.firebase_id,u.facebook_id, u.firstname, u.lastname,u.phone_number, rd.* from ride_requests as rd inner join users as u on u.user_id = rd.user_id where rd.status = '0' and rd.record_status = '1' and rd.user_id = ?1?";
      
       const SELECT_RIDE_REQUEST_BY_REQUEST_ID = "select u.firebase_id, u.facebook_id, u.firstname, u.lastname,u.phone_number, rd.* from ride_requests as rd inner join users as u on u.user_id = rd.user_id where rd.record_status = '1' and rd.request_id = ?1?";
       const SELECT_LATEST_RIDE_REQUEST_BY_USER_ID = "select u.firebase_id,u.facebook_id, u.firstname, u.lastname,u.phone_number, rd.* from ride_requests as rd inner join users as u on u.user_id = rd.user_id where rd.status = '0' and rd.record_status = '1' and rd.user_id = ?1? order by rd.date_created desc LIMIT 1";
       const UPDATE_RIDE_REQUEST_STATUS_BY_REQUEST_ID = "UPDATE ride_requests set status = ?1? where request_id = ?2?";
       const DELETE_REQUEST_BY_REQUEST_ID = "UPDATE ride_requests set record_status = '0' where request_id = ?1?";
       
       const SELECT_TRIP_REQUEST_ID = "select t.status as trip_status,t.trip_id,t.driver_id,t.passenger_id,t.pickup, rd.* from trips as t inner join ride_requests rd on rd.request_id = t.request_id where t.record_status = '1' and t.request_id = ?1?";
       const SELECT_TRIPS_BY_USER_ID = "select t.status as trip_status,t.trip_id,t.driver_id,t.passenger_id, rd.* from trips as t inner join ride_requests rd on rd.request_id = t.request_id where t.record_status = '1' and t.status != 2 and (t.driver_id = ?1? or t.passenger_id = ?2?)";
       const SELECT_OLD_TRIPS_BY_USER_ID = "select t.status as trip_status,t.trip_id,t.driver_id,t.passenger_id, rd.* from trips as t inner join ride_requests rd on rd.request_id = t.request_id where t.record_status = '1' and t.status = 2 and (t.driver_id = ?1? or t.passenger_id = ?2?)";
       const SELECT_TRIPS_IN_PROGRESS_BY_USER_ID = "select t.status as trip_status,t.trip_id,t.driver_id,t.passenger_id,t.pickup, rd.* from trips as t inner join ride_requests rd on rd.request_id = t.request_id where t.record_status = '1' and t.status = 1 and (t.driver_id = ?1? or t.passenger_id = ?1?) order by date_created desc LIMIT 1";
       const SELECT_TRIPS_IN_PROGRESS_BY_DRIVER_ID = "select t.status as trip_status,t.trip_id,t.driver_id,t.passenger_id,t.pickup, rd.* from trips as t inner join ride_requests rd on rd.request_id = t.request_id where t.record_status = '1' and t.status = 1 and (t.driver_id = ?1?) order by date_created desc LIMIT 1";
       const SELECT_TRIPS_IN_PROGRESS_BY_PASSENGER_ID = "select t.status as trip_status,t.trip_id,t.driver_id,t.passenger_id,t.pickup, rd.* from trips as t inner join ride_requests rd on rd.request_id = t.request_id where t.record_status = '1' and t.status = 1 and (t.passenger_id = ?1?) order by date_created desc LIMIT 1";
       const SELECT_TRACK_FOR_TRIP__BY_REQUEST_ID  = "select * from track where record_status = '1' and request_id = ?1? order by date_created desc LIMIT 1";
       
       const SELECT_RATINGS_FOR_TRIP__BY_REQUEST_ID  = "select * from trip_rating where record_status = '1' and request_id = ?1? order by date_created desc";
       const SELECT_RATINGS_FOR_TRIP__BY_USER_ID  = "select * from trip_rating where record_status = '1' and user_id = ?1? order by date_created desc";
       
       const UPDATE_TRIP_STATUS_BY_REQUEST_ID = "UPDATE trips set status = ?1? where record_status = '1' and request_id = ?2?";
       const UPDATE_TRIP_PICKUP_BY_REQUEST_ID = "UPDATE trips set pickup = ?1? where record_status = '1' and request_id = ?2?";
       const DELETE_TRIP_BY_REQUEST_ID = "UPDATE trips set record_status = '0' where record_status = '1' and request_id = ?1?";
    }