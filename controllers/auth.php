<?php

class Auth extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		if( empty($this->me) ) $this->login();
		$this->error();
	}
	// /users/password/edit?reset_password_token=FqthkUs4hYU3ppn5yMSV
	/*public function password() {
		
	}*/

	/*public function google_connect() {
	}*/
	/*public function google_signup()
	{
		$google = new Google();
		$client = $google->client;

		$client->setScopes( $google->_scopes );

		$client->setRedirectUri( URL . 'auth/google_oauth2/');
		$getAuthUrl = $client->createAuthUrl();

		header('Location: ' . filter_var($getAuthUrl, FILTER_SANITIZE_URL));
	}*/
	public function google_oauth2()
	{
		// require_once WWW_VENDORS.'google_apis/vendor/autoload.php';

		Session::init();
		// $client = new Google_Client();
		$google = new Google();
		$client = $google->client;

		/*$isExpired = $client->isAccessTokenExpired();
		$refresh = $client->getRefreshToken();*/

		/*$client->setAuthConfig( WWW_VENDORS. 'google_apis/client_secret.json');
		$client->setAccessType("offline");
		$client->setIncludeGrantedScopes(true);*/

		// $client->revokeToken(); ยกเลิกโทเคน

		try{
			$client->setRedirectUri( URL . 'auth/google_oauth2/');

			if( isset($_GET['code']) ){
				$client->authenticate($_GET['code']);
				$_SESSION['access_token'] = $client->getAccessToken();
			}

			if ( isset($_SESSION['access_token']) ){

				$_SESSION['access_token']['expires_in'] = 3600*24;
				$client->setAccessToken($_SESSION['access_token']);
			}

			if ( $client->getAccessToken() ) {

	  			$plus = new Google_Service_Plus($client);
				$userProfile = $plus->people->get('me');


				$emails = $userProfile->getEmails();
				$name = $userProfile->getName();
				$email = $emails[0]['value'];


				$userid = $this->model->query('users')->loginWithGoogle( $userProfile->id, $email );
				if( !empty($userid) ){
					
					$google_redirect_uri = Session::set( 'google_redirect_uri' ); 
					$google_redirect_uri = isset($google_redirect_uri) ? $google_redirect_uri: URL;
					Cookie::set( COOKIE_KEY_USER, $userid, time() + (3600*24));
					header("Location: ". filter_var($google_redirect_uri, FILTER_SANITIZE_URL) ); die;
				}				
				// print "Your Profile: <pre>" . print_r($userProfile, true) . "</pre>";
	  		}

  		}catch (Exception $e) {
  			$arr['message'] = '';
  		}

  		$redirect_uri = URL.'login?error=login_with_google';
  		header("Location: ". filter_var($redirect_uri, FILTER_SANITIZE_URL));		
	}
}