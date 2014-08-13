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


// Load the compose autoload
require_once (dirname(__DIR__) . DS . 'config' . 	DS . 'config.php');


// Start the session. Note the session should be started after loading the vendor/autoload.php
session_start();


include_once(dirname(__DIR__) . "/library/ParseApp/Cms.php");
$backend = new ParseApp\Cms(); // This is the main parse code


$aSession = array();

$topLevel = $backend->getOneLevel();
foreach ($topLevel as $node){
	$aSession[$node->getObjectId()]['object_id'] = $node->getObjectId();
	$aSession[$node->getObjectId()]['name'] = $node->get("name");
	$aSession[$node->getObjectId()]['page'] = $node->get("page");
	$aSession[$node->getObjectId()]['parent_id'] = $node->get("parent_id");
	
	$aChildren = array();
	$backend->getChildren($node->getObjectId(), $aChildren);
	
	$aSession[$node->getObjectId()]['children'] = $aChildren;
}


print_r($aSession);
