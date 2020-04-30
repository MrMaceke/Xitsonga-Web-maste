<?php
    require_once __DIR__. '/../constants/NotificationConstants.php';
    /**
     * Generates a JSON object
     * 
     * @author Sneidon Dumela <sneidon@yahoo.com>
     * @version 1.0
     */
    class NotificationUtils {
         /**
         * Formats input message to JSON
         * 
         * @param string message
         * @param NumberFormatter statusCode
         * @return JSON string
         */
        public static function getNotification($activityName, $actionName, $message,$openItemId) {
            return "{ "
                    ."\"".NotificationConstants::ACTIVITY_NAME."\":" ."\"".$activityName ."\","
                    ."\"".NotificationConstants::NOTIFICATION_MESSAGE."\":"."\"". $message ."\","
                    ."\"".NotificationConstants::NOTIFICATION_ACTION."\":"."\"". $actionName ."\","
                    ."\"".NotificationConstants::NOTIFICATION_ITEM_ID."\":"."\"" . $openItemId ."\""
                    . "}";
        }
         /**
         * Formats input message to JSON
         * 
         * @param string message
         * @param NumberFormatter statusCode
         * @return JSON string
         */
        public static function getChatNotification($activityName, $actionName, $message, $chat,$openItemId) {
            return "{ "
                    ."\"".NotificationConstants::ACTIVITY_NAME."\":" ."\"".$activityName ."\","
                    ."\"".NotificationConstants::NOTIFICATION_MESSAGE."\":"."\"". $message ."\","
                    ."\"".NotificationConstants::NOTIFICATION_CHAT."\":"."\"". $chat ."\","
                    ."\"".NotificationConstants::NOTIFICATION_ACTION."\":"."\"". $actionName ."\","
                    ."\"".NotificationConstants::NOTIFICATION_ITEM_ID."\":"."\"" . $openItemId ."\""
                    . "}";
        }
    }
