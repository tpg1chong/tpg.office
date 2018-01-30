<?php

class Building extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->error();
	}

	public function add(){
		if( empty($this->me) || $this->format!='json' ) $this->error();

		$this->view->setData('type', $this->model->query('property')->type());
		$this->view->setData('zone', $this->model->query('property')->zone());

		$this->view->setPage('path', 'Forms/building');
		$this->view->render('add');
	}

	public function edit($id=null){
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
		if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

		$item = $this->model->get($id);
		if( empty($item) ) $this->error();

		$this->view->setData('type', $this->model->query('property')->type());
		$this->view->setData('zone', $this->model->query('property')->zone());

		$this->view->setData('item', $item);
		$this->view->setPage('path', 'Forms/building');
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
            		->post('type_id')
            		->post('zone_id');

            $form->submit();
            $postData = $form->fetch();

            if( empty($arr['error']) ){

            	if( !empty($item) ){
                    $this->model->update( $id, $postData );
                }
                else{
                	$postData['emp_id'] = $this->me['id'];
                    $this->model->insert( $postData );
                    $id = $postData['building_id'];
                }

                $arr['message'] = 'Saved !';
                $arr['url'] = URL.'manage/building/'.$id;
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
			$this->view->setPage('path', 'Forms/building');
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
            			->post('type_id')
            			->post('zone_id');

                $form->submit();
                $postData = $form->fetch();

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

            echo count($_FILES['picture']['name']);die;

            if( !empty($_FILES['picture']) ){

                for($i=0; $i<count($_FILES['picture']['name']); $i++){
                    print_r($_FILES['picture']['name'][$i]);
                }
                die;
            }

            if( !empty($_FILES['image_cover'])){
                $this->model->setImageCover( $id, $_FILES['image_cover'] );
            }
            else if( !empty($_POST['cropimage']) ){
                $this->model->cropImage( $id );
            }
            else{
                $arr['error'] = true;
            }

            if( empty($arr['error']) ){
                $arr['message'] = 'Saved !';
                $arr['url'] = 'refresh';
            }
        }
        //*-- End Picture --*//

        echo json_encode($arr);
	}
}