<?php
include_once(__DIR__ . '/BaseController.php');
class PagesController extends BaseController {
	
	function beforeAction(){
		parent::beforeAction();
		$this->set("tab", 'index');
	}

	
	function indexAction() {
	}

	function afterAction() {

	}

	function showAction($name){
	
		$aNav = $this->backend->getNavigationTree();
		$this->set('aNav', $aNav);
		
		$oPage = $this->backend->getPageBySlug($name);
		$this->set('oPage', $oPage);
		
	}
}
