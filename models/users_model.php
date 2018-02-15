<?php

class Users_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "users";
    private $_table = "users u LEFT JOIN users_role r ON u.user_role_id=r.role_id";
    private $_field = "
          u.iduser as id
        , firstname
        , lastname
        , user_email
        , user_login
        , phone

        , username
        , enable
        , allowoutside
        , updatedate

        , role_id
    ";
    private $_cutNamefield = "user_";

    public function is_user($text){
        $c = $this->db->count('users', "(user_login=:txt AND user_login!='') OR (user_email=:txt AND user_email!='')", array(':txt'=>$text));
        
        return $c;
    }
    public function is_name($text) {
        return $this->db->count($this->_objType, "name='{$text}'");
    }


    /* -- actions -- */
    public function insert(&$data) {
        
        $data["{$this->_cutNamefield}created"] = date('c');
        $data["{$this->_cutNamefield}updated"] = date('c');

        if( isset($data["{$this->_cutNamefield}pass"]) ){
            $data["{$this->_cutNamefield}pass"] = Hash::create('sha256', $data["{$this->_cutNamefield}pass"], HASH_PASSWORD_KEY);
        }

        $this->db->insert($this->_objType, $data);
        $data['id'] = $this->db->lastInsertId();

        $data = $this->cut($this->_cutNamefield, $data);
    }
    public function update($id, $data) {
        $data["{$this->_cutNamefield}updated"] = date('c');
        $this->db->update($this->_table, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id) {
        $this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }

    
    /* -- find -- */
    public function get($id){
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE iduser=:id LIMIT 1");
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
    }
    public function find( $options=array() ) {

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'firstname',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            // 'enabled' => isset($_REQUEST['enabled'])? $_REQUEST['enabled']:1,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);
        
        $condition = "";
        $params = array();


        if( !empty($options['role']) ){
            $condition .= "role=:role";
            $params[':role'] = $options['role'];
        }

        $arr['total'] = $this->db->count($this->_table, $condition, $params);

        $limit = !empty($options['unlimit']) ? '': $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sql = "SELECT {$this->_field} FROM {$this->_table} {$where} {$orderby} {$limit}";
        // echo $sql; die;

        $arr['lists'] = $this->buildFrag( $this->db->select($sql, $params ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }


    /* -- convert data -- */
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function convert($data){

        $data['fullname'] = trim($data['firstname']);
        if( !empty($data['lastname']) ){
            $data['fullname'] .= ' '.trim($data['lastname']);
        }


        $data['access'] = $this->setAccess($data['role_id']);

        return $data;
    }
    public function setAccess($id)  {
        $access = array();
        if( $id == 1 ){
            $access = array(1);
        }

        return $access;
    }


    /* -- Login -- */
    public function login($user, $pess){

        $sth = $this->db->prepare("SELECT iduser as id FROM {$this->_objType} WHERE (user_login=:login AND user_pass=:pass) OR (user_email=:login AND user_pass=:pass)");

        $sth->execute( array(
            ':login' => $user,
            ':pass' => Hash::create('sha256', $pess, HASH_PASSWORD_KEY)
        ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        return $sth->rowCount()==1 ? $fdata['id']: false;
    }
    public function loginWithGoogle($id, $email) {

        $sth = $this->db->prepare("SELECT iduser as id FROM {$this->_objType} WHERE (user_google_id=:login AND user_email=:pass)");

        $sth->execute( array( ':login' => $id, ':pass' => $email ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        return $sth->rowCount()==1 ? $fdata['id']: false;
    }

    /* -- admin roles -- */
    public function roles($type='') {
        return $this->db->select("SELECT role_id as id, role_name as name FROM users_role ORDER BY role_name");
    }

}
