<?php


class Schools extends Controller {

	public function __construct() {
		parent::__construct();
	}


	public function index(){


		
		$this->view->render('schools/lists/display');
	}

}