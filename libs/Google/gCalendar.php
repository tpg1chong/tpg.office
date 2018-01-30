<?php

class gCalendar extends Google {
	public $_error = array();

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
            'timeZone' => 'Asia/Bangkok',
        );

        $arr = array(); $list = array();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            try {
                $this->client->setAccessToken($_SESSION['access_token']);
                $this->service = new Google_Service_Calendar($this->client);

                if( is_array($options['calendarId']) ){
                    foreach ($options['calendarId'] as $id) {

                        $list = array_merge( $list, $this->loadListEvents( $id, $optParams ) );
                    }
                }
                else{
                    $list = $this->loadListEvents( $options['calendarId'], $optParams );
                }

                function usortDate($a, $b) {
                    return strtotime($a['start']) - strtotime($b['start']);
                }
                usort($list, "usortDate");

                $arr['items'] = $list;

            } catch (Exception $e) {
            	$arr['error'] = 404;
                $arr['message'] = 'api disconnect';
            }

        } else {

            $arr['error'] = 404;
            $arr['message'] = 'api disconnect';
            // return redirect()->route('oauthCallback');
        }

        $arr['options'] = $options;

        return $arr;
    }
    public function loadListEvents( $id, $optParams, $options=array() ) {
        $results = $this->service->events->listEvents($id, $optParams);

        $listEvents = array();
        if (count($results->getItems()) > 0) {

            foreach ($results->getItems() as $event) {
                $listEvents[] = $this->convertListEvents($event, $options);
            }
        }

        return $listEvents;
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
    
    public function insertEvents( $options=array() ) {
        Session::init();

        $options = array_merge( array(
            'title' => '',
            'description' => '',
            'start' => isset($_REQUEST['start']) ? $_REQUEST['start']: date('Y-m-01'),
            'end' => isset($_REQUEST['end']) ? $_REQUEST['end']: date('Y-m-t'),
            'calendarId' => 'primary',
        ), $options );

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $event = new Google_Service_Calendar_Event([
                'summary' => $options['title'],
                'description' => $options['description'],
                'start' => ['dateTime' => $options['start']],
                'end' => ['dateTime' => $options['end']],
                'reminders' => ['useDefault' => true],
            ]);
            $results = $service->events->insert($options['calendarId'], $event);

            if (!$results) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            return response()->json(['status' => 'success', 'message' => 'Event Created']);
        } else {
            return redirect()->route('oauthCallback');
        }
    }
}