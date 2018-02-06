<?php

class gCalendar extends Google {
	public $_error = array();


    private $_timeZone = 'Asia/Bangkok';
	public function __construct() {
		parent::__construct();

		$this->client->addScope(Google_Service_Calendar::CALENDAR);
    }

    public function upcoming( $options=array() ) {
    	Session::init();

    	$options = array_merge( array(
            'start' => isset($_REQUEST['start']) ? $_REQUEST['start']: date('c'),
            'calendarId' => 'primary',
        ), $options );

        if( empty($options['end']) ){
        	$options['end'] = date('c', strtotime("+15 day", strtotime($options['start'])) );
        }

        $arr = array();
        $results = $this->listEvents( $options );

        if( !empty($results['error']) ){
            $arr = $results;
        }
        else{
            $lists = array();
            foreach ($results['items'] as $event) {
            	$time = strtotime($event['start']);
            	$lists[ date('Y-m-d', $time) ][] = $event;
            }

            ksort($lists);
            $arr['items'] = $lists;
        }
        
        $arr['options'] = $options;
        return $arr;
    }


	public function listEvents( $options=array() ) {
        Session::init();

        $options = array_merge( array(
            'start' => isset($_REQUEST['start']) ? $_REQUEST['start']: date('Y-m-01'),
            'end' => isset($_REQUEST['end']) ? $_REQUEST['end']: date('Y-m-t'),
            'calendarId' => 'primary',
        ), $options );

        $optParams = array(
            'singleEvents' => TRUE,
            'timeMin'=> date('c', strtotime($options['start'])),
            'timeMax'=> date('c', strtotime($options['end'])),
            'showDeleted'=> false,
            'singleEvents'=> true,
            'orderBy'=> 'startTime',
            'timeZone' => $this->_timeZone,
        );

        $arr = array(); $list = array();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            // try {
            $this->client->setAccessToken($_SESSION['access_token']);
            $this->service = new Google_Service_Calendar($this->client);

            if( is_array($options['calendarId']) ){
                foreach ($options['calendarId'] as $id) {

                    $results = $this->loadListEvents( $id, $optParams );
                    
                    if( empty($results['error']) ){
                        $list = array_merge( $list, $results );
                    }
                }
            }
            else{
                $results = $this->loadListEvents( $options['calendarId'], $optParams );

                if( empty($results['error']) ){
                    $list = $results;
                }
            }

            function usortDate($a, $b) {
                return strtotime($a['start']) - strtotime($b['start']);
            }
            usort($list, "usortDate");

            $arr['items'] = $list;

        } else {

            $arr['error'] = 404;
            $arr['message'] = 'api disconnect';
            // return redirect()->route('oauthCallback');
        }

        $arr['options'] = $options;

        return $arr;
    }
    public function loadListEvents( $id, $optParams, $options=array() ) {

        try {

            $results = $this->service->events->listEvents($id, $optParams);

            $listEvents = array();
            if (count($results->getItems()) > 0) {

                foreach ($results->getItems() as $event) {
                    $listEvents[] = $this->convertListEvents($event, $options);
                }
            }

            return $listEvents;
        } catch (Exception $e) {
            return json_decode($e->getMessage(), 1);
        }
        
    }
    public function convertListEvents( $event, $options=array() ) {

        $allday = false;
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
            $allday = true;
        }

        $end = $event->end->dateTime;
        if (empty($end)) {
            $end = $event->end->date;
        }

        $color = '';
        if( empty($this->_colorsEvent) ){
            $this->setColorsEvent();

            if( empty($this->_colorsEvent) ){
                $this->defaultColorsEvent();
            }
        }
        if( !empty($this->_colorsEvent[$event->colorId]) ){
            $color = $this->_colorsEvent[$event->colorId];
        }

        if( empty($color) ){
            $color = $this->_colorsEvent[1];
        }

        $fdata = array(
            'id' => $event->id,
            'summary' => $event->summary,
            'description' => $event->description,
            'location' => $event->location,

            'start' => $start,
            'end' => $end,

            'allday' => $allday,

            'creator' => json_decode(json_encode($event->creator), 1),

            'color' => $color,
        );

        if( !$allday ){
            $startTime = strtotime($start);
            $fdata['timeStr'] = date('H:i', $startTime);
        }
        return $fdata;
    }

    public function listColors()
    {
        Session::init();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);
            $colors = $service->colors->get();

            $data = array();
            foreach ($colors->getEvent() as $key => $color) {

                $data[ $key ] = array(
                    'background' => $color->getBackground(),
                    'foreground' => $color->getForeground(),
                );
            }

            return $data;
        }
    }
    public function setColorsEvent() {
        
        $colors = $this->service->colors->get();

        $this->_colorsEvent = array();
        foreach ($colors->getEvent() as $key => $color) {

            $this->_colorsEvent[ $key ] = array(
                'background' => $color->getBackground(),
                'foreground' => $color->getForeground(),
            );
        }
    }
    public function defaultColorsEvent() {
        $a = array();
        $a[] = array( 'background' => '#333',  'foreground' => '#fff' );
        $this->_colorsEvent = $a;
    }
    
    public function getEvent($calendarId, $eventId)
    {
        Session::init();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            try {
                $event = $service->events->get($calendarId, $eventId);
                return $event;
            } catch (Exception $e) {
                return json_decode($e->getMessage(), 1);
            }
        }
        else{
            return ['error' => 404];
        }        
    }

    public function insertEvent( $options=array() ) {
        Session::init();

        // https://developers.google.com/google-apps/calendar/v3/reference/events/insert
        $options = array_merge( array(
            'title' => '',
            'location' => '',
            'description' => '',

            'allday' => 1,
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d'),

            'calendarId' => 'primary',
            'timeZone' => $this->_timeZone,
        ), $options );


        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $post = array( 
                'summary' => $options['title'],
                'location' => $options['location'],
                'description' => $options['description'],
                'start' => array(
                    'timeZone' => $options['timeZone'],
                ),
                'end' => array(
                    'timeZone' => $options['timeZone'],
                ),

                'reminders' => array(
                    'useDefault' => false,
                ),
            );

            if( !empty($options['allday']) ){
                $post['start']['date'] = date('Y-m-d', strtotime($options['start']));
                $post['end']['date'] = date('Y-m-d', strtotime($options['end']));
            }
            else{
                $post['start']['dateTime'] = date('c', strtotime($options['start']));
                $post['end']['dateTime'] = date('c', strtotime($options['end']));
            }

            $event = new Google_Service_Calendar_Event( $post );

            // $event = new Google_Service_Calendar_Event([
            //     'summary' => $options['title'],
            //     // 'location' => isset($options['location']) ? $options['location']: '',
            //     'description' => $options['description'],
            //     'start' => [
            //         'dateTime' => $options['start'],
            //         'timeZone' => $options['timeZone'],
            //     ],
            //     'end' => [
            //         'dateTime' => $options['end'],
            //         'timeZone' => $options['timeZone'],
            //     ],
            //     'recurrence' => array(
            //         'RRULE:FREQ=DAILY;COUNT=2' เกิดซ้ไ
            //     ),

            //     // 
            //     /*'attendees' => array(
            //         array('email' => 'lpage@example.com'),
            //         array('email' => 'sbrin@example.com'),
            //     ),*/

            //     // การแจ้งเตือน
            //     'reminders' => [
            //         'useDefault' => true, // true | false
            //         /*'overrides' => [
            //             ['method' => 'email', 'minutes' => 24 * 60],
            //             ['method' => 'popup', 'minutes' => 10],
            //         ]*/
            //     ],
            // ]);
            try {
                $results = $service->events->insert($options['calendarId'], $event);
            } catch (Exception $e) {
                return json_decode($e->getMessage(), 1);
            }

            

            if ( empty($results) ) {
                return ['status' => 'error', 'message' => 'Something went wrong'];
                // return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }

            return ['status' => 'success', 'message' => 'Event Created'];
        } else {

            return ['error' => 404];
            // echo 'oauthCallback';

            // return redirect()->route('oauthCallback');
        }
    }

    public function updateEvent($calendarId, $eventId, $dataPost=array() )
    {
        Session::init();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            try {

                $event = $service->events->get($calendarId, $eventId);

                if( !empty($dataPost['title']) ){
                    $event->setSummary( $dataPost['title'] );
                }

                $updatedEvent = $service->events->update($calendarId, $event->getId(), $event);

                // Print the updated date.
                return $updatedEvent->getUpdated();
            } catch (Exception $e) {
                return json_decode($e->getMessage(), 1);
            }
        }
        else{
            return ['error' => 404];
        }        
    }
    public function deleteEvent($calendarId, $eventId)
    {
        Session::init();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            try {
                $service->events->delete($calendarId, $eventId);

            } catch (Exception $e) {
                return json_decode($e->getMessage(), 1);
            }
        }
        else{
            return ['error' => 404];
        } 
        
    }
}