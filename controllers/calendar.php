<?php

class Calendar extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		Session::init();

		if ( isset($_SESSION['access_token']) ){
			$timestamp = intval($_SESSION['access_token']['created']);
			$difference = time() - $timestamp;
			if($difference > 3600){
				$this->_autoLoginWithGoogle();
				die;
				// https://developers.google.com/identity/protocols/OAuth2ForDevices
			}
		}

		$this->view->render("calendar/display");
	}

	public function __test(){

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


		echo '<pre>';
		print_r($results); die;
		echo '</pre>';
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

	public function add()
	{
		if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('colors', $this->model->query('system')->listEventColors() );

        $this->view->setPage('path', 'Forms/events');
		$this->view->render("add");
	}


	public function _color()
	{
		$google = new Google();
		$gCalendar = $google->app('calendar');


		$results = $gCalendar->listColors();
		print_r($results); die;
	}
	public function _insert() {
		

		Session::init();
		$google = new Google();
		// $client = $google->client;

		$gCalendar = $google->app('calendar');

		$calendarId = 'thaipropertyguide.com_i43s582pm9ttup8lcad2la4o2c@group.calendar.google.com';
		// insertEvents
		/*$results = $gCalendar->insertEvent( array(
			'title' => 'Test 2',
			'calendarId' => $calendarId,
			'start' => date('Y-m-d 00:00:00'),
			'end' => date('Y-m-d 00:00:00'),
		) );*/

		$eventId = 'v6eotq8ule8l0aakhfqros3pho';

		// get
		$results = $gCalendar->getEvent($calendarId, $eventId);


		// update
		// $results = $gCalendar->updateEvent($calendarId, $eventId, array('title'=>'test 3'));


		// delete
		// $gCalendar->deleteEvent($calendarId, $eventId); die;
		

		echo '<pre>';
		print_r($results); die;
		echo '</pre>';
	}


	public function insertEvent()
	{
		
		$calendarId = 'thaipropertyguide.com_i43s582pm9ttup8lcad2la4o2c@group.calendar.google.com';

		try {
            $form = new Form();
            $form   ->post('event_title')->val('is_empty')
                    ->post('event_text')
            		->post('event_location')
                    ->post('event_color');

            $form->submit();
            $postData = $form->fetch();

            $startTime = !empty($_POST['start_time']) ? $_POST['start_time'].':00' : '00:00:00';
        	$endTime = !empty($_POST['end_time']) ? $_POST['end_time'].':00' : '00:00:00';

        	$invite = isset($_POST['invite'])? $_POST['invite']: null;
            // $postData['event_has_invite'] = $has_invite;
            $postData['event_start'] = $_POST['start_date'].' '.$startTime;
            $postData['event_end'] = $_POST['end_date'].' '.$endTime;
            $postData['event_allday'] = isset($_POST['allday']) ? 1 : 0;

            $postData['calendarId'] = isset($_POST['calendarId']) ? $_POST['calendarId']: $calendarId;


            if( empty($arr['error']) ){


            	$google = new Google();
				$gCalendar = $google->app('calendar');

                $results = $gCalendar->insertEvent( array(
                	'title' => $postData['event_title'],
                	'location' => $postData['event_location'],
                	'description' => $postData['event_text'],

                	'colorId' => $postData['event_color'],

                	
                	'allday' => $postData['event_allday'],
                	'start' => $postData['event_start'],
                	'end' => $postData['event_end'],


                	'calendarId' => $calendarId,

                ) );
                
                $arr['message'] = 'Saved';
                // $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);

	}
}