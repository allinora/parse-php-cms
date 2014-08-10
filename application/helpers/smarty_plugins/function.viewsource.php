<?php
function smarty_function_viewsource($params, &$smarty){
	$file=ROOT . DS . $params["file"];
	$str="<div  class='viewsource'>"  . highlight_file($file, true) . "</div>";
	return $str;
}
?>