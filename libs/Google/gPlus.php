<?php

class gPlus extends Google {
	public $_error = array();

	public function __construct() {
		parent::__construct();

		// $this->client->useApplicationDefaultCredentials();
		// $this->client->addScope(Google_Service_Plus::PLUS_ME);
    }

 	public function get() {

 		$me = array();

 		try {

 			$this->service = new Google_Service_Plus($this->client);
 			$me = $this->service->people->get('me');

 			print_r($me);

 		}catch (Exception $e) {
        	// $this->client->setRedirectUri( 'http://localhost/tpg.office/events/lists' );
	 		$authUrl = $this->client->createAuthUrl();
	 		// $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

	 		// echo $authUrl;
	 		// header('Location: '.$authUrl);
        }

 	  	print_r($me); die;
 	}  	

}