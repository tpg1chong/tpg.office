<?php

class Owner extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		// $this->error();

		print_r($this->model->query('owner')->lists()); die;
		if( $this->format=='json' ) {
            $this->view->setData('results', $this->model->query('owner')->lists() );
            $render = "owner/lists/json";
        }

        else{
        	$this->view->render('owner/lists/display');
        }

		
	}

}