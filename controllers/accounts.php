<?php

class Accounts extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        // Counter
        // $this->model->query('counter')->set( 'company', $this->company['id'], 'view' );

        $this->error();
    }

    public function add() {
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('roles', $this->model->query('users')->roles());

        $this->view->setPage('path', 'Forms/accounts');
        $this->view->render("add");
    }
    public function edit($id=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || $this->format!='json' || empty($id) ) $this->error();

        $item = $this->model->query('users')->get($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);

        $this->view->setPage('path', 'Forms/accounts');
        $this->view->render("edit");
    }
    public function save() {
        if( empty($_POST) || empty($this->me) || $this->format!='json' ) $this->error();
        
        $id = isset($_POST['id']) ? $_POST['id']: null;
        $item = $this->model->query('users')->get($id);
        if( empty($item) ) $this->error();

        try {
            $form = new Form();
            $form   ->post('user_name')->val('is_empty')
                    ->post('user_login')->val('username');

            $form->submit();
            $postData = $form->fetch();


            if( $item['login']!=$postData['user_login'] ){

                if( $this->model->query('users')->is_user( $postData['user_login'] ) ){
                    $arr['error']['user_login'] = 'ไม่สามารถใช้ชื่อผู้ใช้นี้ได้';
                }
            }


            if( empty($arr['error']) ){

                $this->model->query('users')->update( $id, $postData );
                $postData['id'] = $id;
                
                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function delete($id=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->query('users')->get($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            if ( !empty($item['permit']['del']) ) {
                $this->model->query('users')->delete($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            $arr['url'] = 'refresh';
            echo json_encode($arr);
        }
        else{
            $this->view->item = $item;
            
            $this->view->setPage('path', 'Forms/accounts');
            $this->view->render("del");
        }
    }

    public function update($id=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || $this->format!='json' || empty($id) ) $this->error();

        $item = $this->model->query('users')->get($id);
        if( empty($item) ) $this->error();

        $name = isset($_REQUEST['name']) ? $_REQUEST['name']: '';
        $value = isset($_REQUEST['value']) ? $_REQUEST['value']: '';

        $dataPost = array();
        $dataPost[ $name ] = trim($value);

        $this->model->query('users')->update($id, $dataPost);
    }
    public function change_password($id='') {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->query('users')->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            
            $leg = 8;
            if( !empty( $_POST['password_auto'] ) ){
                $arr['password'] = $this->fn->q('user')->generateStrongPassword( $leg );
            }
            else{

                if( strlen($_POST['password_new']) < $leg ){
                    $arr['error']['password_new'] = "รหัสผ่านต้องมากกว่า {$leg} ตัว";
                }
                else if( $_POST['password_new']!=$_POST['password_confirm'] ){
                    $arr['error']['password_confirm'] = 'รหัสผ่านไม่ตรงกัน';
                }
                else{
                    $arr['password'] = $_POST['password_new'];
                }
            }

            if( empty($arr['error']) ){

                // update
                $this->model->query('users')->update($item['id'], array(
                    'user_pass' => Hash::create('sha256', $arr['password'], HASH_PASSWORD_KEY )
                ));

                $arr['message'] = "แก้ไขข้อมูลเรียบร้อย";
            }            

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path', 'Forms/accounts');
            $this->view->render("change_password");
        }
    }
    public function change_enabled($id=null, $status=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : $status;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->query('users')->get($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            $this->model->query('users')->update($id, array('user_enabled'=>$status));

            $arr['message'] = 'Saved.';
            $arr['url'] = 'refresh';
            echo json_encode($arr);
        }
        else{
            $this->view->item = $item;
            $this->view->status = $status;

            $this->view->setPage('path', 'Forms/accounts');
            $this->view->render("change_enabled");
        }
    }
}