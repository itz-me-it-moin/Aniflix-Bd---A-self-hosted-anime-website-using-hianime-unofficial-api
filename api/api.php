<?php

// API Configuration
class APIConfig {
    const API_URL = 'url';
    const STREAM_URL = 'url';
    const API_KEY = 'api key';

    // Function to get API URL
    public static function getApiUrl() {
        return self::API_URL;
    }

    // Function to get Stream URL
    public static function getStreamUrl() {
        return self::STREAM_URL;
    }

    // Function to get API Key
    public static function getApiKey() {
        return self::API_KEY;
    }
}

?>
