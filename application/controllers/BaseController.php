<?php

class BaseController extends Allinora\Simple\Controller {
	protected function checkSession(){
		if (!defined('AUTH_REQUIRED')){
		 	define('AUTH_REQUIRED', false); // By default auth is not required
		}
		
		
		// print "<pre>SESSION" . print_r($_SESSION, true) . "</pre>";
		if (!AUTH_REQUIRED){
			// dont do session checks for login, logout, create, authenticate, etc
			return;
		}
		if (!$this->getSession("token")){
			$this->redirect("access", "login");
		}
	}


	public function init($checkSession){
		if ($checkSession){
			$this->checkSession();
		}
		header('Content-type: text/html; charset=utf-8');
		$this->set("time", time());
		if (!isset($this->backend)) {
			include_once(ROOT . "/library/ParseApp/Cms.php");
			$this->backend = new ParseApp\Cms(); // This is the main parse code
		}
	}

	function beforeAction() {
		$this->render=1;
		$this->init(1);
	
	}

	function afterAction() {
		parent::afterAction();
	}
	

	function logoutAction(){
		$_SESSION = array();
		unset($_SESSION);
		$this->redirect("access", "login");
	}
	

	

}
