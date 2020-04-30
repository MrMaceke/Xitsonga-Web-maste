<?php
   /**
     * SQL Connection constants
     * 
     * @Author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class FeedbackConstants {
        const SUCCESSFUL = 999;
        const FAILED = -999;
        const SESSION_EXPIRED = -101;
        
        const SUCCESS_MESSAGE_RIDE_REQUEST = "We'll notify you soon as we find a person travelling around the same time.";
        const SUCCESS_MESSAGE_PASSENGER_REQUEST = "We'll notify you soon as we find a person travelling around the same time.";
        const SUCCESS_MESSAGE_ACCEPT_RIDE_REQUEST = "Congrants, your trip is ready. Please remember to bring the amount for the trip.";
        const SUCCESS_MESSAGE_ACCEPT_TRAVEL_BUDDY_REQUEST = "Congrants, your trip is ready. We have notified your passenger.";
        const SUCCESS_MESSAGE_TRIP_STARTED = "We've notified %s that you are approaching their pickup location.";
        const SUCCESS_MESSAGE_TRIP_CANCELLED= "We've notified %s that you can't make the trip anymore.";
        const SUCCESS_MESSAGE_TRIP_ALREADY_STARTED = "Trip already started, %s already knows you are coming to their pick location.";
        const SUCCESS_MESSAGE_TRIP_DROPOFF = "The trip has been completed. We would like you hear your experience using Kutoa.";
        const SUCCESS_MESSAGE_TRIP_ALREADY_DROPOFF = "The trip has already been completed, possible by the person you shared the ride with. We would like you hear your experience using Kutoa.";

        const NOTIFICATION_TITLE_RIDE_ACCEPTED = "Kutoa";
        
        
        const NOTIFICATION_MESSAGE_TRIP_CANCELLED = "Sorry, %s had to cancel the trip. We are looking for another person for you to ride with.";
        const NOTIFICATION_MESSAGE_TRIP_DROPOFF = "Trip drop off, %s has completed the trip. Thanks for using Kutoa";
        const NOTIFICATION_MESSAGE_TRIP_STARTED = "Get ready, %s is on their way to pick you up.";
        const NOTIFICATION_MESSAGE_RIDE_ACCEPTED = "Ride offer accepted - %s has agreed to share a ride with you.";
        const NOTIFICATION_MESSAGE_TRAVEL_BUDDY_ACCEPTED = "Travel offer accepted - %s has agreed to give you a ride.";
        const NOTIFICATION_MESSAGE_TRIP_PICKUP = "You are currently with your passenger. Enjoy your trip";
        const NOTIFICATION_MESSAGE_CHAT_MESSAGE = "%s sent you a message.";
        
        const ANDROID_ACTIVITY_HOME = "HomeActivity";
        const ANDROID_ACTIVITY_SCHEDULED_RIDE = "ScheduledRideActivity";
        const ANDROID_ACTIVITY_PENDING_RIDE_REQUESTS = "PendingRideRequestsActivity";
        const ANDROID_ACTIVITY_CHAT = "ChatActivity";
        
        const ANDROID_ACTION_OPEN_TRIP = "Open Trip";
        const ANDROID_ACTION_OPEN_TRIP_NAVIGATE = "Navigate";
        const ANDROID_ACTION_RATE_DRIVER = "Rate Driver";
        const ANDROID_ACTION_CHECK_PROGRESS = "Progress";
        const ANDROID_ACTION_PENDING_REQUESTS = "Requests";
        
        const SMS_KUTOA_DIGIT_CODE = "Kutoa digit code is %s";
        
        const KUTO_IMAGE_LOCATION = "http://kutoa.waxbill.co.za/php/";
        const FACEBOOK_PROFILE_IMAGE = "https://graph.facebook.com/%s/picture?width=300&height=300";
    }