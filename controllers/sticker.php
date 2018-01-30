<?php

class Sticker extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index( ){


    	$this->error();
    }

    public function customers() {
    	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids']: $ids;
        if( empty($this->me) || empty($ids) || $this->format!='json' ) $this->error();

        $this->view->setData('results', $this->model->query('customers')->lists( array('ids'=>$ids) ) );
    	
    	$this->view->setPage('path','Forms/sticker');

        $this->view->render("A4-2x6");
    }

    public function people() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids']: $ids;
        if( empty($this->me) || empty($ids) || $this->format!='json' ) $this->error();

        $data = $this->model->query('people')->lists( array('ids'=>$ids) );
        $results = array();

        foreach ($data['lists'] as $key => $value) {
            $results[] = array('id'=>$value['id'], 'title'=>$value['name'], 'text'=>$value['agency_address']);
        }

        $this->view->setData('results', $results);
        $this->view->setPage('path','Forms/sticker');

        $this->view->render("A4-2x6");
    }
}