<?php
include_once(__DIR__ . '/BaseController.php');
class NavigationController extends BaseController {
	
	function beforeAction(){
		define('AUTH_REQUIRED', false);
		parent::beforeAction();
		$this->set("tab", 'index');
	}

	function showAction($id){
		$oNav = $this->backend->getNav($id);
		if ($oNav->get("page")){
			$oPage = $this->backend->getPage($oNav->get("page"));
			$this->set('oPage', $oPage);
		} else {
			$this->set("error", "No page is yet assigned to this location");
		}
	}


	private function getChildren($aNav, &$aChildren=array()){
		foreach($aNav as $key => $entry) {
			$x = array();
			$x["title"] = $entry['name'];
			if (count($entry['children'])){
				$x["folder"] = TRUE;
				$x["expanded"] = TRUE;
				$x["children"] = $this->getChildren($entry['children']);
			} else {
				$x["folder"] = FALSE;
			}

			$x["key"] = $key;
			$jsonResult[] = $x;
		}
		return $jsonResult;
		
	}
	
	function gettreeAction(){
		$this->render = 0;
		$aNav = $this->backend->getNavigationTree();
		//print "<pre>" . print_r($aNav, true) . "</pre>";exit;
		
		foreach($aNav as $key => $entry) {
			$x = array();
			$x["title"] = $entry['name'];
			if (count($entry['children'])){
				$x["folder"] = TRUE;
				$x["expanded"] = TRUE;
				$x["children"] = $this->getChildren($entry['children']);
			} else {
				$x["folder"] = FALSE;
			}

			$x["key"] = $key;
			$jsonResult[] = $x;
		}
		
		
		header("Content-type: application/json");
		print json_encode($jsonResult);
		exit;
		
	}
	


}
