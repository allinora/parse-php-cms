<?php
include_once(__DIR__ . '/BaseController.php');
class AdminController extends BaseController {
	
	function beforeAction(){
		define('AUTH_REQUIRED', true);
		parent::beforeAction();
		$this->set("tab", 'index');
		$this->setWrapperDir("/admin");


		$this->aNav = $this->backend->getNavigationTree();
		$this->set('aNav', $this->aNav);

		$this->aPages = $this->backend->getPages();
		$this->set('aPages', $this->aPages);
	}

	
	
	function addpageAction(){
		$this->render = 0;
		$this->backend->addPage($_REQUEST);
		$this->redirect("admin", "pages");
	}


	function savepageAction(){
		$this->render = 0;
		$this->backend->savePage($_REQUEST);
		$this->redirect("admin", "pages");
	}

	function editpageAction($id){
		$this->render = 1;
		$oPage = $this->backend->getPage($id);
		
		$this->set('oPage', $oPage);
		
		
	}
	
	function pagesAction(){
	}

	function navigationAction(){
		$this->render = 0;
		print "<pre>" . print_r($aNav, true) . "</pre>";
	}

	
	
	function createnavAction(){
		$navSelector = $this->backend->getNavSelector();
		$this->set('navSelector', $navSelector);
	}
	
	function addnavAction(){
		$this->render = 0;
		$oNav = $this->backend->addNav($_REQUEST);
		$this->redirect("admin", "editnav/" . $oNav->getObjectID());
	}


	function editnavAction($id){
		$oNav = $this->backend->getNav($id);
		$this->set('oNav', $oNav);


		
		
		$aBreadCrumbs = array();
		$this->backend->getBreadCrumbs($id,$aBreadCrumbs);
		$this->set("fullname", implode(' /  ', array_reverse($aBreadCrumbs)));


		
		


		$navSelector = $this->backend->getNavSelector($oNav->get("parent_id"));
		$this->set('navSelector', $navSelector);
		
	}
	
	function indexAction() {
	}

	function afterAction() {

	}
}
