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
				// https://developers.google.com/identity/protocols/OAuth2ForDevices
			}
			else{
				$this->view->setData('colors', $this->model->query('system')->listEventColors() );
				$this->view->render("calendar/display");
			}
		}
		else{
			$this->_autoLoginWithGoogle();
		}

		
	}

	public function __test(){

		Session::init();
		$google = new Google();
		$client = $google->client;

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

		// $google = new Google();
        $this->view->setData('colors', $this->model->query('system')->listEventColors() );

        $this->view->setPage('path', 'Forms/events');
		$this->view->render("add");
	}

	public function get($eventId=null)
	{
		$calendarId = 'thaipropertyguide.com_i43s582pm9ttup8lcad2la4o2c@group.calendar.google.com';
		$google = new Google();
		$gCalendar = $google->app('calendar');

		$event = $gCalendar->getEvent($calendarId, $eventId);
		// print_r( $event ); die;

		echo json_encode($event);
	}
	// public function edit($eventId=null)
	// {
	// 	// $calendarId = 'thaipropertyguide.com_i43s582pm9ttup8lcad2la4o2c@group.calendar.google.com';

	// 	/*$item = 
	// 	print_r($item); die;*/
		
	// 	$this->view->setData('id', $eventId );
	// 	$this->view->setData('colors', $this->model->query('system')->listEventColors() );
	// 	$this->view->setPage('path', 'Forms/events');
	// 	$this->view->render("edit");
	// }
	public function updateEvent()
	{
		if( empty($_POST) ) $this->error();

		try {
            $form = new Form();
            $form   ->post('summary')->val('is_empty')
                    ->post('description')
            		->post('location')
                    ->post('colorId');

            $form->submit();
            $postData = $form->fetch();

            $startTime = !empty($_POST['start_time']) ? $_POST['start_time'].':00' : '00:00:00';
        	$endTime = !empty($_POST['end_time']) ? $_POST['end_time'].':00' : '00:00:00';

            $postData['start'] = $_POST['start_date'].' '.$startTime;
            $postData['end'] = $_POST['end_date'].' '.$endTime;
            $postData['allday'] = isset($_POST['allday']) ? 1 : 0;

            $calendarId = isset($_POST['calendarId']) ? $_POST['calendarId']:'';
			$eventId = isset($_POST['eventId']) ? $_POST['eventId']:'';

            if( empty($arr['error']) ){
            	$google = new Google();
				$gCalendar = $google->app('calendar');

                $gCalendar->updateEvent($calendarId, $eventId, $postData );
                
                $arr['message'] = 'Event updated';
                // $arr['data'] = $postData;
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

		echo json_encode($arr);
	}
	public function deleteEvent($calendarId=null, $eventId=null) 
	{

		if( !empty($_POST) ){

			$calendarId = isset($_POST['calendarId']) ? $_POST['calendarId']:'';
			$eventId = isset($_POST['eventId']) ? $_POST['eventId']:'';
			if( !empty($calendarId) && !empty($eventId) ){
				$google = new Google();
				$gCalendar = $google->app('calendar');

				$gCalendar->deleteEvent($calendarId, $eventId);
				$arr['message'] = 'Event deleted';
			}
			else{
				$arr['message'] = 'Error';
			}

			echo json_encode($arr);
		}
		else{
			$this->view->setPage('path', 'Forms/events');
			$this->view->render("deleteEvent", array(
				'calendarId' => $calendarId,
				'eventId' => $eventId
			) );
		}
		
	}


	public function _color()
	{
		$google = new Google();
		$gCalendar = $google->app('calendar');



		$results = $gCalendar->listColors();
		print_r($results); die;
	}
	public function __sample() {

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

		$eventId = 'goji9mmc5o2de9o15bsitj8vbs';

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