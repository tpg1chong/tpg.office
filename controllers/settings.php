<?php

class Settings extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index( ){
    	$this->my();
    }

    public function company( $tap='basic' ) {

        $this->view->setPage('on', 'settings' );
        $this->view->setData('section', 'company');
        $this->view->setData('tap', 'display');
        $this->view->setData('_tap', $tap);

        if( empty($this->permit['company']['view']) ) $this->error();
        // print_r($this->permit); die;

        if( $tap == 'dealer' ){

            $this->view->setData('paytype', $this->model->query('paytype')->lists());
            $this->view->setData('data', $this->model->query('dealer')->lists());
        }
        elseif( $tap != 'basic' ){

            $this->error();
        }

        if( !empty($_POST) && $this->format=='json' ){

            foreach ($_POST as $key => $value) {
                $this->model->query('system')->set( $key, $value);
            }

            $arr['url'] = 'refresh';
            $arr['message'] = 'บันทึกเรียบร้อย';

            echo json_encode($arr);
        }
        else{
            $this->view->render("settings/display");
        }
    }

    public function my( $tap='basic' ) {

        $this->view->setPage('on', 'settings' );
        $this->view->setData('section', 'my');
        $this->view->setData('tap', 'display');
        $this->view->setData('_tap', $tap);

        if( $tap=='basic' ){
            $this->view
            ->js(  VIEW .'Themes/'.$this->view->getPage('theme').'/assets/js/bootstrap-colorpicker.min.js', true)
            ->css( VIEW .'Themes/'.$this->view->getPage('theme').'/assets/css/bootstrap-colorpicker.min.css', true);

            $this->view->setData('prefixName', $this->model->query('system')->prefixName());
        }

        $this->view->render("settings/display");
    }

    /**/
    /* Manage employees */
    /**/
    public function accounts($tap='employees'){

        $this->view->setPage('on', 'settings' );
        $this->view->setData('section', 'accounts');
        $this->view->setData('tap', $tap);
        $render = 'settings/display';

        if($tap=='position'){
            $data = $this->model->query('employees')->position();
        }
        elseif($tap=='department'){
            $this->view->setData('access', $this->model->query('system')->roles());
            $data = $this->model->query('employees')->department();
        }
        elseif($tap=='employees'){

            if( $this->format=='json' ){
                // sleep(5);
                $this->view->setData('results', $this->model->query('employees')->lists());

                $render = 'settings/sections/accounts/employees/lists/json';
            }

            $this->view->setData('department', $this->model->query('employees')->department() );
            $this->view->setData('position', $this->model->query('employees')->position() );
            $this->view->setData('display', $this->model->query('employees')->display() );
            $data = array();
        }
        else{
            $this->error();
        }

        $this->view->setData('data', $data);
        $this->view->render( $render );
    }

    /**/
    /* Property */
    /**/
    public function property($tap='type') {

        $this->view->setPage('on', 'settings');
        $this->view->setData('section', 'property');
        $this->view->setData('tap', $tap);
        $render = 'settings/display';

        if( $tap=='type' ){
            $data = $this->model->query('property')->type();
        }
        elseif( $tap=='zone' ){
            $data = $this->model->query('property')->zone();            
        }elseif( $tap == 'near_type' ){
            $data = $this->model->query('property')->nearType();
        }
        elseif( $tap == 'near' ){
            $data = $this->model->query('property')->near();
        }
        else{
            $this->error();
        }

        $this->view->setData('data', $data);
        $this->view->render( $render );
    }

    /**/
    /* Data Management */
    /**/
    public function data_management($tap='') {
        
        $this->view->setPage('on', 'settings');
        $this->view->setData('section', 'data_management');
        $this->view->setData('tap', $tap);
        $this->view->render( 'settings/display' );
    }
    public function import() {
        $this->view->setPage('on', 'settings');
        $this->view->setData('section', 'data_management');
        $this->view->setData('tap', 'import');
        $this->view->render( 'settings/display' );
    }
    public function export() {
        $this->view->setPage('on', 'settings');
        $this->view->setData('section', 'data_management');
        $this->view->setData('tap', 'export');
        $this->view->render( 'settings/display' );
    }

}