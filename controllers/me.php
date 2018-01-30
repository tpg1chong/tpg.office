<?php

class Me extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        
        // print_r($this->me); die;
        $this->error();
        // header('location:'.URL.'manage/products');
    }

    public function navTrigger() {
        if( $this->format!='json' ) $this->error();
        

        if( isset($_REQUEST['status']) ){

            Session::init();                          
            Session::set('isPushedLeft', $_REQUEST['status']);
        }
    }

    /* updated */
    /**/
    public function updated($avtive='') {

        if( empty($_POST) || empty($this->me) || $this->format!='json' || $avtive=="" ) $this->error();
        
        /**/
        /* account */
        if( $avtive=='account' ){
            try {
                $form = new Form();
                $form   ->post('emp_username')->val('username')
                        ->post('emp_lang');

                $form->submit();
                $dataPost = $form->fetch();

                if( $this->model->query('employees')->is_user( $dataPost['emp_username'] ) && $this->me['username']!=$dataPost['emp_username'] ){
                    $arr['error']['emp_username'] = 'ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว';
                }

                // Your username must be longer than 4 characters.

                if( empty($arr['error']) ){

                    $this->model->query('employees')->update( $this->me['id'], $dataPost );
  
                    $arr['url'] = 'refresh';
                    $arr['message'] = 'Thanks, your settings have been saved.';
                }
                
            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
            exit;
        }
        /**/
        /* basic */
        else if( $avtive=='basic' ){

            try {
                $form = new Form();
                $form   ->post('emp_prefix_name')
                        ->post('emp_first_name')->val('maxlength', 20)->val('is_empty')
                        ->post('emp_last_name')
                        ->post('emp_nickname')
                        ->post('emp_email')
                        ->post('emp_phone_number')
                        ->post('emp_line_id')
                        // ->post('emp_color')
                        ->post('emp_mode');

                $form->submit();
                $dataPost = $form->fetch();

                if( empty($arr['error']) ){

                    $this->model->query('employees')->update( $this->me['id'], $dataPost );
  
                    $arr['url'] = 'refresh';
                    $arr['message'] = 'Thanks, your settings have been saved.';
                }
                
            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
            exit;
        }

        /**/
        /* password */
        if( $avtive=='password' ){

            $data = $_POST;
            $arr = array();
            if( !$this->model->query('employees')->login($this->me['username'], $data['password_old']) ){
                $arr['error']['password_old'] = "รหัสผ่านไม่ถูกต้อง";
            } elseif ( strlen($data['password_new']) < 8 ){
                $arr['error']['password_new'] = "รหัสผ่านสั้นเกินไป อย่างน้อย 8 ตัวอักษรขึ้นไป";

            } elseif ($data['password_new'] == $data['password_old']){
                $arr['error']['password_new'] = "รหัสผ่านต้องต่างจากรหัสผ่านเก่า";

            } elseif ($data['password_new'] != $data['password_confirm']){
                $arr['error']['password_confirm'] = "คุณต้องใส่รหัสผ่านที่เหมือนกันสองครั้งเพื่อเป็นการยืนยัน";
            }

            if( !empty($arr['error']) ){
                $this->view->error = $arr['error'];
            }
            else{
                $this->model->query('employees')->update($this->me['id'], array(
                    'emp_password' => Hash::create('sha256', $_POST['password_new'], HASH_PASSWORD_KEY)
                ));

                $arr['url'] = 'refresh';
                $arr['message'] = 'Thanks, your settings have been saved.';
            }

            echo json_encode($arr);
            exit;
        }

        $this->error();
    }
    /*public function change_password() {
        
        if( empty($this->me) || $this->format!='json' ) $this->error();

        if( !empty($_POST) ){
            try {
                $form = new Form();
                $form   ->post('password_old')->val('password')
                        ->post('password_new')->val('password')
                        ->post('password_confirm')->val('password');

                $form->submit();
                $dataPost = $form->fetch();

                $old_pass = Hash::create('sha256', $dataPost['password_old'], HASH_PASSWORD_KEY );
                if( !$this->model->query('employees')->is_checkpass($this->me['id'], $old_pass) ) {
                    $arr['error']['password_old'] = 'รหัสผ่านเดิมผิด !';
                }

                if( $dataPost['password_new']!=$dataPost['password_confirm'] ){
                    $arr['error']['password_confirm'] = 'รหัสผ่านไม่ตรงกัน';
                }

                if( empty($arr['error']) ){

                    // update
                    $this->model->query('employees')->update($this->me['id'], array(
                        'emp_password' => Hash::create('sha256', $dataPost['password_new'], HASH_PASSWORD_KEY )
                    ));

                    $arr['message'] = "แก้ไขข้อมูลเรียบร้อย";
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $this->me );
            $this->view->render("Themes/crm/forms/my/change_password");
        }
    }*/

}