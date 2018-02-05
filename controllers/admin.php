<?php

class Admin extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->error();
	}


	/*public function business($session='info')
	{
		
	}*/

	/*public function building()
	{
		$this->view->render("admin/building");
	}*/

	public function property($session='type') {
		
		if( $session=='type' ){
	        $this->view->setData('dataList', $this->model->query('property')->type( array('active'=>'') ) );

		}
		elseif($session=='zone'){
			$this->view->setData('dataList', $this->model->query('property')->zone( array('active'=>'') ) );
		}
		elseif($session=='near'){

			$this->view->setData('types', $this->model->query('property')->nearType( array('active'=>'') ) );
			$this->view->setData('dataList', $this->model->query('property')->near( array('active'=>'') ) );
		}
		else{
			$this->error();
		}

		$this->view->render("admin/display", array(
        	'section' => $session
        ));
	}

	public function accounts()
	{
		$this->view->setPage('on', 'admin' );

		// print_r($this->permit); die;
        $results = $this->model->query('users')->lists( array(
        	'unlimit' => 1
        ) );


        $this->view->render("admin/display", array(
        	'section' => 'accounts',
        	'dataList' => $results['lists'],
        	'roles' => $this->model->query('users')->roles()
        ));
	}
}