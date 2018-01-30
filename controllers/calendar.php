<?php

class Calendar extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){


		Session::init();
		$google = new Google();
		$client = $google->client;

		// if( !$client->getAccessToken() ){
		
		// 	$client->setLoginHint($this->me['user_email']);
		// 	// $client->setApprovalPrompt('force');
		// 	$client->isAccessTokenExpired(true);
		// 	$client->getRefreshToken();

		// 	$client->setScopes( $google->_scopes );
		// 	$client->setRedirectUri( URL . 'auth/google_oauth2/' );
		// 	$getAuthUrl = $client->createAuthUrl();

		// 	echo $getAuthUrl; die;
		// 	#
		// 	Session::set( 'google_redirect_uri', URL.'calendar/' ); 
		// 	header('Location: ' . filter_var($getAuthUrl, FILTER_SANITIZE_URL));
		// 	die;

		// 	// $isExpired = $client->isAccessTokenExpired(); // true (bool Returns True if the access_token is expired.)
  //   		// $refresh = $client->getRefreshToken(); //null because not gahe refresh token

  //   		/*$client->getGoogleClient()->setAccessType("offline"); //your recomendation
  //   		$client->getGoogleClient()->setApprovalPrompt("force"); //your recomendation*/

  //   		/*$isAgainExpired = $client->isAccessTokenExpired(); // still true (expired)

  //   		if ($request->request->get('parentId') != null) {
  //   		}*/
		// }

		// die;
		// print_r($userProfile); die;
		$gCalendar = $google->app('calendar');

		/*$results = $gCalendar->upcoming( array(
			'calendarId' => $this->getCalendarId()
		) );

		echo 'This upcoming!';
		print_r($results); die;*/


		$results = $gCalendar->listEvents( array(
			'calendarId' => $this->getCalendarId()
		) );

		echo 'list Events!';
		print_r($results); die;
	}

	public function listEvents() {
		
		if( empty($this->me) || $this->format!='json' ) $this->error();

		$google = new Google();
		$gCalendar = $google->app('calendar');

		echo json_encode( $gCalendar->listEvents( array(
			'calendarId' => $this->getCalendarId()
		) ) );
	}

	public function upcoming()
	{
		if( empty($this->me) || $this->format!='json' ) $this->error();

		$google = new Google();
		$gCalendar = $google->app('calendar');

		echo json_encode( $gCalendar->upcoming( array(
			'calendarId' => $this->getCalendarId()
		) ) );
	}
}