<?php
include_once(__DIR__ . '/BaseController.php');
class AdminController extends BaseController {
	
	function beforeAction(){
		define('AUTH_REQUIRED', true);
		parent::beforeAction();
		$this->set("tab", 'index');
		$this->setWrapperDir("/admin");
	}

	
	
	function addpageAction(){
		$this->render = 0;
		$this->backend->addPage($_REQUEST);
		$this->redirect("admin", "pages");
	}
	
	function pagesAction(){
		$aPages = $this->backend->getPages();
		$this->set('aPages', $aPages);
	}

	function navigationAction(){
		$aNav = $this->backend->getNavigationTree();
		$this->set('aNav', $aNav);
		//print "<pre>" . print_r($aNav, true) . "</pre>";
	}

	
	
	function createnavAction(){
		$aNav = $this->backend->getNavs();
		$this->set('aNav', $aNav);

		$aPages = $this->backend->getPages();
		$this->set('aPages', $aPages);
	}
	
	function addnavAction(){
		$this->render = 0;
		$this->backend->addNav($_REQUEST);
		$this->redirect("admin", "navigation");
	}
	
	function indexAction() {
	}

	function afterAction() {

	}
}
