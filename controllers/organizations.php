<?php

class Organizations extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index($id=null){
		$this->view->setPage('on', 'organization' );

        if( !empty($id) ){
            $this->error();
        }
        else{

            Session::init();
            if( $this->format=='json' ) {

                Session::set('organizations_settings', array(
                    'country' => isset($_REQUEST['country']) ? $_REQUEST['country']:'',
                    'category' => isset($_REQUEST['category']) ? $_REQUEST['category']:''
                ));

                $this->view->setData('results', $this->model->lists() );
                $render = "json";
            }
            else{

                $settings = Session::get('organizations_settings');
                $this->view->setData('pageSettings', !empty($settings) ? $settings:array() );

                $this->view->setData('country', $this->model->country() );
                $this->view->setData('category', $this->model->category() );
                $render = "display";
            }

            $this->view->render("organizations/lists/{$render}");

        }
	}
    public function add() {
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('country', $this->model->query('system')->country() );
        $this->view->setData('category', $this->model->category() );

        $this->view->setPage('path','Forms/organizations');
        $this->view->render("add");
    }
    public function edit($id='') {
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item );
        $this->view->setData('country', $this->model->query('system')->country() );
        $this->view->setData('category', $this->model->category() );
        
        $this->view->setPage('path','Forms/organizations');
        $this->view->render("add");
    }
    public function save() {
        
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('agency_name')->val('is_empty')
                    ->post('agency_country_id')->val('is_empty')
                    ->post('agency_category_id')->val('is_empty')
                    ->post('agency_address')
                    ->post('agency_phone')
                    ->post('agency_email')
                    ->post('agency_description');

            $form->submit();
            $postData = $form->fetch();

            if( empty($arr['error']) ){
                
                if( empty($item) ){
                    $this->model->insert( $postData );
                }
                else{
                    $this->model->update( $item['id'], $postData );
                }
                
                $arr['message'] = 'Saved.';
                // $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function del($id='') {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();
        
        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            if ($item['permit']['del']) {
                $this->model->delete($id);
                $arr['message'] = 'Already Removed.';
            } else {
                $arr['message'] = 'Data can not deleted.';
            }

            if( isset($_REQUEST['callback']) ){
                $arr['callback'] = $_REQUEST['callback'];
            }
            
            $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : URL.'organizations/';
            
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path','Forms/organizations');
            $this->view->render("del");
        }
    }
    public function dels($ids=null){
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids']: $ids;
        if( empty($this->me) || empty($ids) || $this->format!='json' ) $this->error();

        if (!empty($_POST)) {

            foreach ($ids as $id) {                
                $this->model->delete($id);
            }

            $arr['message'] = 'Already Removed.';

            if( isset($_REQUEST['callback']) ){
                $arr['data'] = $ids;
            }
            
            $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : URL.'organizations/';
            echo json_encode($arr);
        }
        else{

            $this->view->setData('ids', $ids);           
            $this->view->setPage('path','Forms/organizations');
            $this->view->render("dels");
        }
    }

    /**/
    /* Group */
    public function add_category() {
        
        $this->view->setPage('path','Forms/organizations');
        $this->view->render("add_category");
    }
    public function save_category() {
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('category_name')->val('is_empty')
                    ->post('category_description');

            $form->submit();
            $postData = $form->fetch();

            $old_name = false;
            if( $this->model->duplicateCategory( $postData['category_name'] ) && !$old_name ){
                $arr['error']['position_name'] = 'Someone already has that name.';
            }

            if( empty($arr['error']) ){
                $this->model->insertCategory( $postData );

                $arr['message'] = 'Saved';

                if( isset($_REQUEST['callback']) ){
                    $arr['data']['name'] = $postData['category_name'];
                    $arr['data']['id'] = $postData['id'];
                }
                else{
                    $arr['url'] = 'refresh';
                }
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }

    /**/
    /* invite */
    /**/
    public function invite() {
        
        if( $this->format!='json' ) $this->error();

        $data = $this->model->lists( array('view_stype'=>'bucketed', 'limit' => isset($_REQUEST['limit']) ? $_REQUEST['limit']: 20) );

        $results = array();
        $results[] = array(
            'object_type'=>'customers', 
            'object_name'=>'Add From Company',
            'data' => $data
        );

        echo json_encode($results);
    }
}