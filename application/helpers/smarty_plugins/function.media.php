<?php

function smarty_function_media($params, &$smarty){
    $file=$params["file"];
	if( defined('MEDIA_PREFIX')){
	    $file=MEDIA_PREFIX . DS .  $file;
	}

	if (defined('DEVELOPMENT_ENVIRONMENT') && DEVELOPMENT_ENVIRONMENT  === true){
		$file.="?" . date("Ymdhis");
	} else {
		if ($smarty->get_template_vars(deployment_timestamp)){
			
		}
		$file.="?" . $smarty->get_template_vars(deployment_timestamp);
	}

	$data="";
	// Default media=screen
	$media="screen";
	if ($params["media"]){
		$media=$params["media"];
	}
	if ($params["comment"]){
		$data.="<!-- " . $params["comment"] . " -->\n";
	}
	
	if ($params["type"]=="css"){
		$data.= "<link rel='stylesheet' type='text/css' href='$file.css' media='$media' />";
	}

    if ($params["type"]=="less"){
        $data.= "<link rel='stylesheet/less' type='text/css' href='$file.less' media='$media'  />";
    }

    if ($params["type"]=="less/css"){
        $data.= "<link rel='$rel' type='text/css' href='$file.less' media='$media' />";
    }
	
	if ($params["type"]=="js"){
		$data.= "<script type='text/javascript' src='$file.js'></script>";
	}
	
	return $data;
}

/* vim: set expandtab: */

