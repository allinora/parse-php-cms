<?php
include_once(dirname(__FILE__) . "/BaseController.php");
class AccessController extends BaseController {

	function beforeAction(){
		parent::beforeAction();
		$this->setWrapperDir("/access");
		
	}

	function indexAction() {
		$this->render=0;
		print "What do you want to do today?";
		
	}
	
	function loginAction(){
		// Just load the appropriate template
		
	}
	function logoutAction(){
		$this->render = 0;
		$this->backend->logoutUser();
		$this->clearSession();
		$this->redirect("index", "index");
	}

	function authenticateAction(){
		$this->render = 0;
		$user = $this->backend->loginUser($_REQUEST);
		$this->setSession("token", $user->getSessionToken());
		$this->setSession("username", $user->getUsername());
		$this->redirect("index", "index");
	}

	function createAction(){
		$this->render = 0;
		$this->backend->createUser($_REQUEST);
	}

}
