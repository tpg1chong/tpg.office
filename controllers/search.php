<?php

class Search extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
    	
    	$q = isset( $_REQUEST['q'] )? $_REQUEST['q']: "";

        $objects[''] = array('type'=>'customer','name'=>'ลูกค้า');
        // $objects['cars'] = array('type'=>'car','name'=>'รถ');

        $results = $this->model->results($objects, $q);

        if( $this->format=='json' ){
            echo json_encode( $results );
        }
        else{
            $this->view->render('index/search');
        }
    }
}