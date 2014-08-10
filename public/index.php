<?php	
define('DS', DIRECTORY_SEPARATOR);


// Timezone: Set it to your timezone
date_default_timezone_set("Europe/Zurich"); 

// Do not autostart session
ini_set("session.auto_start", "Off");

// Session path: Change is according to your liking or just remove it if you are using some other session management
ini_set("session.save_path", dirname(__DIR__) . DS . "tmp" . DS . "sessions");

// Load the compose autoload
require_once (dirname(__DIR__) . DS . 'vendor' . 	DS . 'autoload.php');


// Start the session. Note the session should be started after loading the vendor/autoload.php
session_start();

// Get the url so that it can be passed to the framework
@list($url, $params) = explode('?', $_SERVER["REQUEST_URI"], 2);   // Get the url

// Run the framework
$framework = new Allinora\Simple\Framework($url);


// Utility functions

// Do something before the framework is called
function _app_preHook(){
	// For example check the request via an IDS
}

// Do something with the content just before its being printed.
function _app_contentHook($_content){
	//For example pass it via tidy or do translations
	return $_content;
}
