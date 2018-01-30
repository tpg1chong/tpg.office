<?php

class Property extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
        
        $this->view->setData('status', $this->model->status() );
        $this->view->render('property/display');
	}
    
    public function add(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('building', $this->model->query('building')->lists());
        $this->view->setPage('path', 'Forms/property');
        $this->view->render('add');
    }

    public function edit($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $this->view->setData('building', $this->model->query('building')->lists());
        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/property');
        $this->view->render('add');
    }

    public function save(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->getType($id);
            if( empty($item) ) $this->error();
        }

        try {

            $form = new Form();
            $form   ->post('name')->val('is_empty')
                    ->post('build_id')->val('is_empty')
                    ->post('star');

            $form->submit();
            $postData = $form->fetch();

            $build = $this->model->query('building')->get( $postData['build_id'] );
            $postData['type_id'] = $build['type']['id'];
            $postData['zone_id'] = $build['zone']['id'];

            if( empty($arr['error']) ){

                if( !empty($item) ){
                    $this->model->update( $id, $postData );
                }
                else{
                    $postData['emp_id'] = $this->me['id'];
                    $this->model->insert( $postData );
                    $id = $postData['property_id'];
                }

                $arr['message'] = 'Saved !';
                $arr['url'] = URL.'manage/property/'.$id;
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }

    public function del($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $this->model->delete( $id );
            $arr['message'] = 'Deleted !';
            $arr['url'] = 'refresh';

            echo json_encode( $arr );
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/property');
            $this->view->render( 'del' );
        }
    }

    public function update($section=null){

        $id = isset($_POST['id'])? $_POST['id']:null;
        if( empty($this->me) || empty($_POST) || empty($section) || empty($id) || $this->format!='json') $this->error();

        if( $section == 'basic' ){

            try {
                $form = new Form();
                $form   ->post('name')->val('is_empty')
                        ->post('build_id')->val('is_empty')
                        ->post('star');

                $form->submit();
                $postData = $form->fetch();

                $build = $this->model->query('building')->get( $postData['build_id'] );
                $postData['type_id'] = $build['type']['id'];
                $postData['zone_id'] = $build['zone']['id'];

                if( empty($arr['error']) ){

                    $this->model->update( $id, $postData );

                    $arr['message'] = 'Saved !';
                    $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }
        }
        //*-- End Basic --*//

        if( $section=='picture' ){

            print_r(count($_FILES['picture']['name']));die;

            if( !empty($_FILES['picture'])){
                $this->model->setImageCover( $id, $_FILES['picture'] );
            }
            else if( !empty($_POST['cropimage']) ){
                $this->model->cropImage( $id );
            }
            else{
                $arr['error'] = true;
            }

            if( empty($arr['error']) ){
                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = 'refresh';
            }
        }
        //*-- End Picture --*//

        echo json_encode($arr);
    }
    
	//---- TYPE ----//
	//
	public function add_type(){

		if( empty($this->me) || $this->format!='json' ) $this->error();

		$this->view->setPage('path', 'Forms/property');
		$this->view->render('add_type');
	}

	public function edit_type($id=null){

		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
		if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

		$item = $this->model->getType( $id );
		if( empty($item) ) $this->error();

		$this->view->setData('item', $item);
		$this->view->setPage('path', 'Forms/property');
		$this->view->render('add_type');
	}

	public function save_type(){

		if( empty($_POST) ) $this->error();

		$id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->getType($id);
            if( empty($item) ) $this->error();
        }

        try {

        	$form = new Form();
            $form   ->post('type_code')->val('is_empty')
            		->post('type_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_code = true;
            if( $this->model->is_code( $postData['type_code'] ) && !empty($has_code) ){
            	$arr['error']['type_code'] = 'Code already exist !';
            }

            if( empty($arr['error']) ){

            	if( !empty($item) ){
                    $this->model->updateType( $id, $postData );
                }
                else{
                    $this->model->insertType( $postData );
                    $id = $postData['type_id'];
                }

                $arr['message'] = 'Saved !';
                $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());

            if( !empty($arr['error']['emp_first_name']) ){
                $arr['error']['name'] = $arr['error']['emp_first_name'];
            } else if( !empty($arr['error']['emp_last_name']) ){
                $arr['error']['name'] = $arr['error']['emp_last_name'];
            }
        }

        echo json_encode($arr);
	}

	public function del_type($id=null){

		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->getType($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            // if ($item['permit']['del']) {
                $this->model->deleteType($id);
                $arr['message'] = 'Delete Completed !';
            // } else {
            //     $arr['message'] = 'Can not delete !';
            // }

            $arr['url'] = isset($_REQUEST['next']) ? $_REQUEST['next']:'refresh';
            echo json_encode($arr);
        }
        else{
            $this->view->item = $item;
            $this->view->setPage('path','Forms/property');
            $this->view->render("del_type");
        }
	}
	//---------------------------//

	//----- ZONE -----//
	//
	public function add_zone(){

		if( empty($this->me) || $this->format!='json' ) $this->error();

		$this->view->setPage('path', 'Forms/property');
		$this->view->render('add_zone');
	}

	public function edit_zone($id=null){

		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
		if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

		$item = $this->model->getZone( $id );
		if( empty($item) ) $this->error();

		$this->view->setData('item', $item);
		$this->view->setPage('path', 'Forms/property');
		$this->view->render('add_zone');
	}

	public function save_zone(){

		if( empty($_POST) ) $this->error();

		$id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->getType($id);
            if( empty($item) ) $this->error();
        }

        try {

        	$form = new Form();
            $form   ->post('zone_code')->val('is_empty')
            		->post('zone_name')->val('is_empty')
            		->post('zone_sort')
            		->post('zone_lat')
            		->post('zone_lng');

            $form->submit();
            $postData = $form->fetch();

            $has_code = true;
            if( $this->model->is_code( $postData['zone_code'] ) && !empty($has_code) ){
            	$arr['error']['zone_code'] = 'Code already exist !';
            }

            if( empty($arr['error']) ){

            	if( !empty($item) ){
                    $this->model->updateZone( $id, $postData );
                }
                else{
                    $this->model->insertZone( $postData );
                    $id = $postData['zone_id'];
                }

                $arr['message'] = 'Saved !';
                $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());

            if( !empty($arr['error']['emp_first_name']) ){
                $arr['error']['name'] = $arr['error']['emp_first_name'];
            } else if( !empty($arr['error']['emp_last_name']) ){
                $arr['error']['name'] = $arr['error']['emp_last_name'];
            }
        }

        echo json_encode($arr);
	}

	public function del_zone($id=null){

		$id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : $id;
		if( empty($id) ) $this->error();

		$item = $this->model->getZone($id);
		if( empty($item) ) $this->error();

		if( !empty($_POST) ){

			// if ($item['permit']['del']) {
                $this->model->deleteZone($id);
                $arr['message'] = 'Delete Completed !';
            // } else {
            //     $arr['message'] = 'Can not delete !';
            // }

            $arr['url'] = isset($_REQUEST['next']) ? $_REQUEST['next']:'refresh';

            echo json_encode($arr);
		}
		else{

			$this->view->setData('item', $item);
			$this->view->setPage('path', 'Forms/property');
			$this->view->render('del_zone');
		}
	}

    /**/
    /* Near */
    /**/
    public function add_near(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('type', $this->model->nearType());

        $this->view->setPage('path', 'Forms/property');
        $this->view->render('add_near');
    }
    public function edit_near($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || $this->format!='json' || empty($id) ) $this->error();

        $item = $this->model->getNear( $id );
        if( empty($item) ) $this->error();

        $this->view->setData('type', $this->model->nearType());

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/property');
        $this->view->render('add_near');
    }
    public function save_near(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->getNear($id);
            if( empty($item) ) $this->error();
        }

        try {

            $form = new Form();
            $form   ->post('near_type_id')
                    ->post('near_name')->val('is_empty')
                    ->post('near_keyword');

            $form->submit();
            $postData = $form->fetch();

            $has_keyword = true;
            if( $this->model->is_near_keyword( $postData['near_keyword'] ) && !empty($has_keyword) ){
                $arr['error']['near_keyword'] = 'Keyword already exist !';
            }

            if( empty($arr['error']) ){

                if( !empty($item) ){
                    $this->model->updateNear( $id, $postData );
                }
                else{
                    $this->model->insertNear( $postData );
                    $id = $postData['near_id'];
                }

                $arr['message'] = 'Saved !';
                $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function del_near($id=null){
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($id) ) $this->error();

        $item = $this->model->getNear($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            // if ($item['permit']['del']) {
                $this->model->deleteNear($id);
                $arr['message'] = 'Delete Completed !';
            // } else {
            //     $arr['message'] = 'Can not delete !';
            // }

            $arr['url'] = isset($_REQUEST['next']) ? $_REQUEST['next']:'refresh';

            echo json_encode($arr);
        }
        else{

            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/property');
            $this->view->render('del_near');
        }
    }

    /**/
    /* Near Type */
    /**/
    public function add_near_type(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setPage('path', 'Forms/property');
        $this->view->render('add_near_type');
    }

    public function edit_near_type($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;

        if( empty($this->me) || $this->format!='json' || empty($id) ) $this->error();

        $item = $this->model->getNearType($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/property');
        $this->view->render('add_near_type');
    }
    public function save_near_type(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->getNearType($id);
            if( empty($item) ) $this->error();
        }

        try {

            $form = new Form();
            $form   ->post('type_name')->val('is_empty')
                    ->post('type_keyword');

            $form->submit();
            $postData = $form->fetch();

            $has_keyword = true;
            if( $this->model->is_nearType_keyword( $postData['type_keyword'] ) && !empty($has_keyword) ){
                $arr['error']['type_keyword'] = 'Keyword already exist !';
            }

            if( empty($arr['error']) ){

                if( !empty($item) ){
                    $this->model->updateNearType( $id, $postData );
                }
                else{
                    $this->model->insertNearType( $postData );
                    $id = $postData['type_id'];
                }

                $arr['message'] = 'Saved !';
                $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function del_near_type($id=null){
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($id) ) $this->error();

        $item = $this->model->getNearType($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            // if ($item['permit']['del']) {
                $this->model->deleteNearType($id);
                $arr['message'] = 'Delete Completed !';
            // } else {
            //     $arr['message'] = 'Can not delete !';
            // }

            $arr['url'] = isset($_REQUEST['next']) ? $_REQUEST['next']:'refresh';

            echo json_encode($arr);
        }
        else{

            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/property');
            $this->view->render('del_near_type');
        }
    }



    public function form() {
        

        $this->view->render('property/profile/display');
    }
}