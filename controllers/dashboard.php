<?php

class Dashboard extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){

		// $this->view->js('https://apis.google.com/js/api.js', true );
		// $this->view->js('google_apis/google_syns_calendar');

        $this->view->render("dashboard/display");
	}
}