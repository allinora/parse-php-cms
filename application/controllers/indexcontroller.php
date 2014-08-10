<?php
include_once(__DIR__ . '/BaseController.php');
class IndexController extends BaseController {
	
	function beforeAction(){
		parent::beforeAction();
		$this->set("tab", 'index');

	}

	
	function indexAction() {
		$aNav = $this->backend->getNavigationTree();
		$this->set('aNav', $aNav);
		//print "<pre>" . print_r($aNav, true) . "</pre>";
	}

	function afterAction() {

	}


}
