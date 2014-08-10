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
	
	public function createFolder($name){
		if ($this->folderExists($name)){
			throw new \Exception("A folder with this name already exists. Please choose another");
		}
		
		$user = ParseUser::getCurrentUser();
		// Make a new folder
		$folder = new ParseObject("Folders");
		$folder->set("name", $name);
		$folder->set("user", $user);
		$folder->save();
		unset($_SESSION['folders']);
		return $folder;
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
	
	
	function addPage($params){
		if (empty($params['slug'])){
			throw new Exception("slug is required");
		}
		if (empty($params['type'])){
			throw new Exception("type is required");
		}

		$slugger = new Slugify();
		$slug = $slugger->slugify($params['slug']);

		if ($this->pageExists($slug)){
			throw new Exception("A page with this slug already exists");
		}
		
		
		
		
		$oPage = new ParseObject("Pages");
		$oPage->set("slug", $slug);
		$oPage->set("type", $params['type']);
		$oPage->save();
		return $oPage;
	}

	function addNav($params){
		if (empty($params['name'])){
			throw new Exception("name is required");
		}
		if (empty($params['page'])){
			throw new Exception("page is required");
		}

		
		$oNav = new ParseObject("Navigation");
		$oNav->set("name", $params['name']);
		$oNav->set("page", $params['page']);

		if (!empty($params['navparent'])){
			$oNav->set("navparent", $params['navparent']);
		}
		
		$oNav->save();
		return $oNav;
	}
	
	
	function getNavs(){
		$query = new ParseQuery("Navigation");
		$aNav = $query->find();
		$_SESSION['nav'] = array();
		foreach($aNav as $nav){
			$_SESSION['nav'][$nav->getObjectId()] = $nav;
		}
		return $aNav;
	}
	
	function getNavigationTree(){
		
		if (isset($_SESSION['navigation'])){
			return $_SESSION['navigation'];
		}
		$aTree = array();
		
		$aNavigation = $this->getNavs();
		//print "<pre>" . print_r($aNavigation, true) . "</pre>";
		
		foreach ($aNavigation as &$nav){
			if (!empty($nav->get('navparent'))){
				continue;
			}
			$this->getNavKids($nav);
			$aTree[$nav->getObjectID()]['name'] = $nav->get('name');
			$aTree[$nav->getObjectID()]['url'] = $nav->get('page');
			$aKids = unserialize($nav->children);
			if (!empty($aKids)){
				foreach($aKids as $kid){
					$aTree[$nav->getObjectID()]['kids'][$kid->getObjectId()]['name'] = $kid->get('name');
					$aTree[$nav->getObjectID()]['kids'][$kid->getObjectId()]['url'] = $kid->get('page');
				}
			}
			
			
		}
		//print "<pre>aTree" . print_r($aTree, true) . "</pre>";exit;	
		$_SESSION['navigation'] = $aTree;
		return $aTree;
	}
	
	function getNavKids(&$nav){
		$query = new ParseQuery("Navigation");
		$query->equalTo('navparent', $nav->getObjectID());
		$aKids = $query->find();
		$nav->children = serialize($aKids);
	}
	
	private function pageExists($slug){

		$query = new ParseQuery('Pages');
		$query->equalTo('slug', $slue);
		
		$object = $query->first();
		if (is_object($object)){
			return true;
		}
		return false;
		
	}
	
}