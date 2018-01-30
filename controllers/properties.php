<?php

class Properties extends Controller {

	public function __construct() {
		parent::__construct();
	}

    // Property //
	public function index($id=null, $section='basic'){

		$this->view->setPage('title', 'Properties');
		$this->view->setPage('on', 'property');

		if( !empty($id) ){

            $item = $this->model->query('property')->get( $id );
            if( empty($item) ) $this->error();

            $this->view->setData('item', $item);

            if( $section == 'basic' ){
                $this->view->setData('building', $this->model->query('building')->lists());
            }
            elseif( $section == 'picture' ){

            }
            else{
                $this->error();
            }

            $this->view->setData( 'section', $section );
            $render = "property/settings/display";
        }
        else{

            if( $this->format=='json' )
            {
                $this->view->setData('results', $this->model->query('property')->lists() );
                $render = "property/lists/json";
            }
            else{

                $this->view->setData('type', $this->model->query('property')->type());
                $this->view->setData('zone', $this->model->query('property')->zone());
                $render = "property/lists/display";
            }
        }

        $this->view->render( $render );
	}

    public function building($id=null, $section='basic'){

        $this->view->setPage('title', 'Building');
        $this->view->setPage('on', 'property_building');

        if( !empty($id) ){

            $item = $this->model->query('building')->get( $id );
            if( empty($item) ) $this->error();

            $this->view->setData('item', $item);

            if( $section == 'basic' ){
                $this->view->setData('type', $this->model->query('property')->type());
                $this->view->setData('zone', $this->model->query('property')->zone());
            }
            elseif( $section == 'picture' ){
                
            }
            else{
                $this->error();
            }

            $this->view->setData( 'section', $section );
            $render = "building/settings/display";
        }
        else{

            if( $this->format=='json' )
            {
                $this->view->setData('results', $this->model->query('building')->lists() );
                $render = "building/lists/json";
            }
            else{

                $this->view->setData('type', $this->model->query('property')->type());
                $this->view->setData('zone', $this->model->query('property')->zone());
                $render = "building/lists/display";
            }
        }

        $this->view->render( $render );
    }

    public function listing($id=null){

        $this->view->setPage('on', 'property_listing');
        $this->view->setPage('title', 'Listing');
        $this->error();
    }
}