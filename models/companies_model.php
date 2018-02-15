<?php

class Companies_model extends Model
{
    public function __construct() {
        parent::__construct();
    }

    private $_objName = "company";
    private $_table = "company 
        LEFT JOIN business ON company.idtypecompany=business.idbusiness
        LEFT JOIN users cUser ON cUser.iduser=company.idusercreate
        LEFT JOIN users uUser ON uUser.iduser=company.iduserupdate
    ";

    private $_select = "
          company.idcompany as company_id
        , company.name as company_name
        , company.address as company_address
        , company.source_note as note

        , company.expats as expatTotal

        , company.createdate
        , cUser.username as created_author_username

        , company.updatedate
        , uUser.username as updated_author_username

        , business.idbusiness as business_id
        , business.name as business_name
    ";

    // private $_table = "company";
    // private $_field = "*";

    private $_firstNamefield = "company_";

    private function _setDate($data){
        return $data;
    }
    public function insert(&$data) {
        if( empty($data['company_created']) ){
            $data['company_created'] = date('c');
        }

        if( empty($data['company_group_id']) ){
            $data['company_group_id'] = 1;
        }
        
        $data['company_updated'] = date('c');

        $this->db->insert( $this->_objName, $this->_setDate($data) );
        $data['id'] = $this->db->lastInsertId();
    }

    public function update($id, $data) {
        if( !isset($data['company_updated']) ){
            $data['company_updated'] = date('c');
        }
        $this->db->update( $this->_objName, $this->_setDate($data), "`company_id`={$id}" );
    }
    public function delete($id) {

        $this->db->delete( 'contact', "`idcompany`={$id}", $this->contactTotal($id) );
        $this->db->delete( $this->_objName, "`idcompany`={$id}" );
    }



    /* -- find -- */
    public function find( $options=array() ) {

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,


            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'company.createdate',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

            'more' => true
        ), $options);

        if( isset($_REQUEST['view_stype']) ){
            $options['view_stype'] = $_REQUEST['view_stype'];
        }

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        # search
        if( isset($_REQUEST['q']) ){
            $options['q'] = trim( $_REQUEST['q'] );
        }
        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "company.name LIKE :s{$key} OR company.name LIKE :q{$key} OR company.name=:f{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
        }

        # period
        /*if( !empty($_REQUEST['period_start']) && !empty($_REQUEST['period_end']) ){
            $options['period_start'] = $_REQUEST['period_start'];
            $options['period_end'] = $_REQUEST['period_end'];
        }
        if( !empty($options['period_start']) && !empty($options['period_end']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "(co.company_created BETWEEN :startDate AND :endDate)";
            $where_arr[':startDate'] = $options['period_start'].' 00:00:00';
            $where_arr[':endDate'] = $options['period_end'].' 23:59:59';;
        }*/

        # status
        /*if( !empty($_REQUEST['status']) ){
            $options['status'] = $_REQUEST['status'];
        }
        if( !empty($options['status']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="company_status=:status";
            $where_arr[':status'] = $options['status'];
        }*/

        # group
        /*if( !empty($_REQUEST['group']) ){
            $options['group'] = $_REQUEST['group'];
        }
        if( !empty($options['group']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="company_group_id=:group";
            $where_arr[':group'] = $options['group'];
        }*/

        
        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );

        // echo "SELECT {$this->_select} FROM {$this->_table} {$where_str} {$orderby} {$limit}"; die;
        $arr['items'] = $this->buildFrag( $this->db->select("SELECT {$this->_select} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        // $object = (object) $arr;

        return $arr;
    }
    public function get($id, $options=array()){
        $select = $this->_select;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->_table} WHERE company.idcompany=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options )
            : array();
    }


    public function buildFrag($results, $options=array()) {
        
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert($value, $options);
        }
        return $data;
    }
    public function convert($data, $options=array()){

        $data = $this->_cutPrefixField($this->_firstNamefield, $data);
        $data['permit']['del'] = true;

        if( !empty($data['updatedate']) ){
            $data['updated_str'] = $this->fn->q('time')->stamp( $data['updatedate'] );
        }

        if( !empty($data['createdate']) ){
            $data['created_str'] = $this->fn->q('time')->stamp( $data['createdate'] );
        }

        $data['contactTotal'] = $this->contactTotal( $data['id'] );
        $data['clientTotal'] = $this->clientTotal( $data['id'] );

        $view_stype = !empty($options['view_stype']) ? $options['view_stype']:'';
        if( !in_array($view_stype, array('bucketed', 'convert')) ) $view_stype = '';

        return $view_stype == 'bucketed' 
               ? $this->bucketed( $data )
               : $data;
    }
    public function bucketed($data) {

        return array(
            'id'=> $data['id'],
            // 'created' => $data['created'],
            'text'=> $data['name'],
            "category"=> $data['group_name'],
            "subtext"=>isset($subtext)?$subtext:"",
            // "type"=>"customers",
            "image_url"=> !empty($data['image_url']) ? $data['image_url']: '',
            // 'status' => isset($status)?$status:"",
            'icon' => 'building-o',
        );
    }


    /**/
    /* groups */
    /**/
    public function groups($value='') {
        return $this->db->select("SELECT group_id as id, group_code as code,  group_name as name FROM companies_groups ORDER BY group_name DESC");
    }
    public function insertGroup(&$data) {
        $this->db->insert( 'companies_groups', $data );
        $data['id'] = $this->db->lastInsertId();
    }


    /* -- contact --*/
    private $_contactTable = "contact LEFT JOIN users as user_update ON contact.iduserupdate=user_update.iduser";

    private $_contactSelect = "
          contact.idcontact as id 
        , contact.name 
        , contact.prefixposition as position 
        , contact.email 
        , contact.phone 
        , contact.mobile 
        , contact.address 
        , contact.note 

        , contact.updatedate as update_date
        , user_update.iduser as user_update_id
        , user_update.username as user_update_username
    ";
    public function contactTotal($companyId)
    {
        return intval( $this->db->count($this->_contactTable, "contact.idcompany=:id", array(
            ':id'=>$companyId,
        ) ) );
    }
    public function contactList($companyId)
    {
        return $this->db->select("
            SELECT {$this->_contactSelect} 
            FROM {$this->_contactTable}
            WHERE contact.idcompany=:id 
            ORDER BY contact.createdate DESC
        ", array(
            ':id'=>$companyId,
        ) );
    }


    /* -- client --*/
    private $_clientTable = "customer 
        LEFT JOIN users as user_create ON customer.idusercreate=user_create.iduser
        LEFT JOIN users as user_update ON customer.iduserupdate=user_update.iduser

        LEFT JOIN status ON customer.idstatus=status.idstatus";

    private $_clientSelect = "
          customer.idcustomer as id 
        , customer.prefix
        , customer.firstname
        , customer.lastname

        , customer.budget

        , customer.email
        , customer.phone
        , customer.note

        , customer.createdate as create_date
        , user_create.iduser as user_create_id
        , user_create.username as user_create_username

        , customer.updatedate as update_date
        , user_update.iduser as user_update_id
        , user_update.username as user_update_username

        , status.idstatus as status_id
        , status.name as status_name
    ";
    public function clientTotal($companyId)
    {
        return intval( $this->db->count('customer', "customer.idcompany=:id", array(
            ':id'=>$companyId,
        ) ) );
    }
    public function clientList($companyId)
    {
        return $this->clientBuildFrag( $this->db->select("
            SELECT {$this->_clientSelect} 
            FROM {$this->_clientTable}
            WHERE idcompany=:id 
            ORDER BY createdate DESC
        ", array(
            ':id'=>$companyId,
        ) ) );
    }

    public function clientBuildFrag($results, $options=array()) {
        
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->clientConvert($value, $options);
        }
        return $data;
    }
    public function clientConvert($data)
    {
        $data['name'] = "{$data['prefix']}{$data['firstname']} {$data['lastname']}";
        return $data;
    }


    /* -- Source -- */
    public function sourceList()
    {
        return $this->db->select( "
            SELECT 
              idresource as id
            , name
            , description

            FROM resource 
            ORDER BY name ASC

        " );
    }


    /* -- business -- */
    public function businessList()
    {
        
        return $this->db->select( "
            SELECT 
              idbusiness as id
            , name

            FROM business 
            ORDER BY name ASC

        " );
    }
}