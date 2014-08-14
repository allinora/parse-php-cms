<?php
namespace ParseApp;

use Parse\ParseClient;
use Parse\ParseQuery;
use Parse\ParseObject;
use Parse\ParseUser;
use Parse\ParseException;
use Parse\ParseFile;
use Cocur\Slugify\Slugify;

use \Exception as Exception;

class Cms {
	
	function __construct(){
		ParseClient::initialize(
		      PARSE_APP_ID,
		      PARSE_REST_KEY,
		      PARSE_MASTER_KEY
		    );
	}
	
	public function createUser($params){
		$user = new ParseUser();
		$user->setUsername($params['login']);
		$user->setPassword($params['passwd']);
		try {
		  	$user->signUp();
		} catch (ParseException $ex) {
			die("Exception: " . $ex->getMessage());
		}
		return $user;
	}

	public function loginUser($params){
		try {
			$user = ParseUser::Login($params['login'], $params['passwd']);
			return $user;
		} catch (ParseException $ex) {
			die("Exception: " . $ex->getMessage());
		}
	}

	public function logoutUser($params){
		ParseUser::logOut();
	}
	
	public function getPageByName($name){
		$query = new ParseQuery('Pages');
		$query->equalTo('name', $name);
		
		$object = $query->first();
		if (is_object($object)){
			return $object;
		}
		die("Could not find a page with this address");
		return false;
	}

	public function getPages(){
		$query = new ParseQuery("Pages");
		$aPages = $query->find();
		$_SESSION['pages'] = array();
		foreach($aPages as $page){
			$_SESSION['pages'][$page->getObjectId()] = $page;
		}
		return $aPages;
	}
	
	public function getPage($id){
		return $_SESSION['pages'][$id];
	}
	
	public function savePage($params){
		if (empty($params['id'])){
			throw new Exception("id is required");
		}
		$oPage = $this->getPage($params['id']);
		$oPage->set("data", $params['data']);
		$oPage->save();
		unset ($_SESSION['pages'][$params['id']]);
		return;
		
	}
	
	
	function addPage($params){
		if (empty($params['name'])){
			throw new Exception("name is required");
		}
		if (empty($params['type'])){
			throw new Exception("type is required");
		}
		if ($this->pageExists($params['name'])){
			throw new Exception("A page with this name already exists");
		}
		
		
		
		
		$oPage = new ParseObject("Pages");
		$oPage->set("name", $params['name']);
		$oPage->set("type", $params['type']);
		$oPage->save();
		return $oPage;
	}

	function addNav($params){
		if (empty($params['name'])){
			throw new Exception("name is required");
		}
		
		$oNav = new ParseObject("Navigation");
		$oNav->set("name", $params['name']);

		if (!empty($params['parent_id'])){
			$oNav->set("parent_id", $params['parent_id']);
		}
		if (!empty($params['page'])){
			$oNav->set("page", $params['page']);
		}
		
		$oNav->save();
		
		unset($_SESSION['navigation']);
		unset($_SESSION['nav']);  // Make this more intelligent
		return $oNav;
	}
	
	function getOneLevel($parent_id=null){
		$query = new ParseQuery("Navigation");
		if (empty($parent_id)){
			$parent_id = null;
		}
		$query->equalTo('parent_id', $parent_id);
		$aNav = $query->find();
		return $aNav;
	}
	
	function getChildren($id, &$aChildren){
		$aNodes = $this->getOneLevel($id);
		foreach ($aNodes as $node){
			$aChildren[$node->getObjectId()]['object_id'] = $node->getObjectId();
			$aChildren[$node->getObjectId()]['name'] = $node->get("name");
			$aChildren[$node->getObjectId()]['page'] = $node->get("page");
			$aChildren[$node->getObjectId()]['parent_id'] = $node->get("parent_id");
			$aGrandChildren = array();
			$this->getChildren($node->getObjectId(), $aGrandChildren);
			$aChildren[$node->getObjectId()]['children'] = $aGrandChildren;
		}
	}
	
	
	function getNav($id){
		if (!isset($_SESSION['nav'][$id])){
			$this->getNavs();  // Refresh session
		}
		return $_SESSION['nav'][$id];
	}
	
	function getNavs(){
		if (isset($_SESSION['nav'])){
			return $_SESSION['nav'];
		}
		$query = new ParseQuery("Navigation");
		$aNav = $query->find();
		$_SESSION['nav'] = array();
		foreach($aNav as $nav){
			$_SESSION['nav'][$nav->getObjectId()] = $nav;
		}
		return $aNav;
	}
	
	
	
	function getNavigationTree(){
		if (!empty($_SESSION['navigation'])){
			return $_SESSION['navigation'];
		}
		// print "<pre>" . print_r($_SESSION, true)  . "</pre>";
		$this->getNavs();
		$aTree = array();

		$topLevel = $this->getOneLevel();
		foreach ($topLevel as $node){
			//print "<pre>node" . print_r($node, true) . "</pre>";
			$aTree[$node->getObjectId()]['object_id'] = $node->getObjectId();
			$aTree[$node->getObjectId()]['name'] = $node->get("name");
			$aTree[$node->getObjectId()]['page'] = $node->get("page");
			$aTree[$node->getObjectId()]['parent_id'] = $node->get("parent_id");

			$aChildren = array();
			$this->getChildren($node->getObjectId(), $aChildren);

			$aTree[$node->getObjectId()]['children'] = $aChildren;
		}
		
		//print "<pre>aTree" . print_r($aTree, true) . "</pre>";exit;	
		$_SESSION['navigation'] = $aTree;
		return $aTree;
	}
	
	function findChildren($id, &$aTree){
		foreach($aTree as $object_id => $aNodes){
			if ($object_id == $id){
				return $aNodes['children'];
			}
			return $this->findChildren($id, $aNodes['children']);
		}
	}



	function getBreadCrumbs($id, &$aBreadCrumbs){
		$oNav = $this->getNav($id);
		if (!is_object($oNav)){
			return;
		}
		$aBreadCrumbs[] = $oNav->get("name");
		if (!empty($oNav->get("parent_id"))){
			$this->getBreadCrumbs($oNav->get("parent_id"), $aBreadCrumbs);
		}
		return $aBreadCrumbs;
	}



	function getBreadCrumbsx($id, &$aTree, &$aBreadCrumbs){
		foreach($aTree as $object_id => $aNodes){
			$aBreadCrumbs[] = $aNodes['name'];
			print "Setting: " . $aNodes['name'] . "<br>";
			if ($object_id == $id){
				return $aBreadCrumbs;
			}
			return $this->getBreadCrumbs($id, $aNodes['children'], $aBreadCrumbs);
		}
	}
	



	function getNavSelector($selected_id = null){
		$navSelector = $this->getSelectList($this->getNavigationTree(), 'parent_id', array('selected_id' => $selected_id));
		return $navSelector; 
	}



	private function getSelectList($aTree, $name = 'selectFiler', $aParams = array()){
		$str = "";
	
		$str .= "<select id='$name' name='$name'>\n";
		$str .= "<option value=''>None</option>\n";
		$str .= $this->getSelectOptions($aTree, 0, $aParams);

		$str .= "</select>\n";
		
		return $str;


	}


	private function getSelectOptions($aTree, $level = 0, $aParams = array()){
		$str = "";
		$selected_id = isset($aParams['selected_id']) ? $aParams['selected_id'] : null;
		
		
		
		foreach($aTree as $folder){
			$name = str_repeat (  '&nbsp;&nbsp;&nbsp;' ,  $level )  . '- ' . $folder['name'];
			$str .=  "<option ";
			$str .= " value='" . $folder['object_id'] . "'";
			if ($selected_id == $folder['object_id']) {
				$str .= " selected ";
			}
			$str .= ">" .  $name . "</option>\n";
			if (is_array($folder['children'])){
				$str .= $this->getSelectOptions($folder['children'], $level+1, $aParams)	;
			}

			
		}
		return $str;



	}
	
	private function pageExists($name){

		$query = new ParseQuery('Pages');
		$query->equalTo('name', $name);
		
		$object = $query->first();
		if (is_object($object)){
			return true;
		}
		return false;
		
	}
	
}