<?php

class gDrive extends Google {
	public $_error = array();

	public function __construct() {
		parent::__construct();

		$this->client->addScope(Google_Service_Drive::DRIVE);
		
    }

 	public function get() {

 		/*$this->client->setRedirectUri( 'http://localhost/tpg.office/events/lists' );
 		$authUrl = $this->client->createAuthUrl();
 		$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
 		
 		header('Location: '.$authUrl);*/
 	}  	

}