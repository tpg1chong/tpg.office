<?php

class Companies extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){
        $this->view->setPage('on', 'companies' );

        
        $this->view->js( VIEW .'Themes/'.$this->view->getPage('theme').'/assets/js/company.js', true);

        // $this->view->setData('groups', $this->model->query('companies')->groups() );
        $this->view->render("companies/forum/display");
    }
    public function getTab($action='about') {

        $id = isset($_REQUEST['id']) ?  $_REQUEST['id']: '';
        $item = $this->model->get( $id );
        
        if( $action=='contact' ){
            $this->view->setData('contactList', $this->model->contactList( $id ) );
        }
        elseif( $action=='client' ){
            $this->view->setData('clientList', $this->model->clientList( $id ) );
        }

        $this->view->setPage('path','Forms/companies/tabs');
        $this->view->render( $action, array(
            'item' => $item
        ));
    }

    public function add() {
        if( empty($this->me) || $this->format!='json' ) $this->error();

        // $this->view->setData('prefixName', $this->model->query('system')->prefixName());
        $this->view->setData('groups', $this->model->query('companies')->groups() );

        $this->view->setPage('path','Forms/companies');
        $this->view->render("add");
    }
    public function edit($id='') {
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $this->view->setData('groups', $this->model->query('companies')->groups() );
        $this->view->setData('item', $item );
        $this->view->setPage('path','Forms/companies');
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
            $form   ->post('company_name')->val('is_empty')
                    ->post('company_address')
                    ->post('company_phone')
                    ->post('company_email')
                    ->post('company_description')
                    ->post('company_group_id')->val('is_empty');

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
                $arr['url'] = 'refresh';
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

            if ( !empty($item['permit']['del']) && $item['clientTotal']==0 ) {
                $this->model->delete($id);
                $arr['message'] = 'Already Removed.';
            } else {
                $arr['message'] = 'Data can not deleted.';
            }

            if( isset($_REQUEST['callback']) ){
                $arr['callback'] = $_REQUEST['callback'];
            }
            
            $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : URL.'companies/';
            
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path','Forms/companies');
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
                // $arr['callback'] = $_REQUEST['callback'];
            }
            
            $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : URL.'companies/';
            echo json_encode($arr);
        }
        else{

            $this->view->setData('ids', $ids);           
            $this->view->setPage('path','Forms/companies');
            $this->view->render("dels");
        }
    }



    public function search() {
        
        if( $this->format=='json' ){

            $result = $this->model->find();
            echo json_encode($result);
            die;
        }


        $this->error();
        

        // echo $result->getPermit()->update; die;
        // print_r($result); die;
    }

    /**/
    /* Group */
    public function add_group() {
        
        $this->view->setPage('path','Forms/companies');
        $this->view->render("add_group");
    }
    public function save_group() {
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('group_name')->val('is_empty')
                    ->post('group_description');

            $form->submit();
            $postData = $form->fetch();

            if( empty($arr['error']) ){
                $this->model->insertGroup( $postData );

                $arr['message'] = 'Saved';

                if( isset($_REQUEST['callback']) ){
                    $arr['data']['name'] = $postData['group_name'];
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


    /* -- contact */
    public function contactAdd()
    {

        $this->view->setData('sourceList', $this->model->sourceList() );
        $this->view->setPage('path','Forms/companies/contact');
        $this->view->render("add");
    }
    public function contactEdit()
    {
        $this->view->setData('sourceList', $this->model->sourceList() );
        $this->view->setPage('path','Forms/companies/contact');
        $this->view->render("edit");
    }
}