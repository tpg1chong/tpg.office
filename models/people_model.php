<?php

class People_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    private $_objName = "people";
    private $_table = "
        people
            LEFT JOIN (
                organizations AS agency
                    LEFT JOIN organizations_category AS category ON agency.agency_category_id=category.category_id
                    LEFT JOIN country ON agency.agency_country_id=country.country_id

            ) ON people.people_agency_id=agency.agency_id

            LEFT JOIN people_position AS position ON people.people_position_id=position.position_id
    ";

    private $_field = "
              people_code
            , people_bookmark

            , people_id
            , people_name
            , people_prefix_name
            , people_first_name
            , people_middle_name
            , people_last_name
            , people_nickname
            , people_card_id
            , people_birthday
            
            , people_address
            , people_phone
            , people_email
            , people_line

            , people_status

            , people_created
            , people_updated
            , people_emp_id

            , agency_id
            , agency_code
            , agency_name
            , agency_address

            , category.category_id
            , category.category_code
            , category.category_name

            , country.country_id
            , country.country_name

            , position.position_id
            , position.position_name
    ";

    private $_cutNamefield = "people_";

    public function insert(&$data) {
        
        if( empty($data["{$this->_cutNamefield}created"]) ){
            $data["{$this->_cutNamefield}created"] = date('c');
        }

        if( empty($data["{$this->_cutNamefield}status"]) ){
            $data["{$this->_cutNamefield}status"] = 'new';
        }
        
        $data["{$this->_cutNamefield}updated"] = date('c');

        $this->db->insert( $this->_objName, $data );
        $data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data) {

        $data["{$this->_cutNamefield}updated"] = date('c');
        $this->db->update( $this->_objName, $data, "`{$this->_cutNamefield}id`={$id}" );
    }
    public function delete($id) {
        $this->db->delete( $this->_objName, "`{$this->_cutNamefield}id`={$id}" );
    }
    public function lists( $options=array() ) {

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,


            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            // 'q' => isset($_REQUEST['q'])? $_REQUEST['q']:'',

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
                $wq .= "
                           {$this->_cutNamefield}name LIKE :q{$key} 
                        OR {$this->_cutNamefield}name=:f{$key} 
                        OR {$this->_cutNamefield}first_name LIKE :q{$key} 
                        OR {$this->_cutNamefield}first_name=:f{$key} 
                        OR {$this->_cutNamefield}last_name LIKE :q{$key} 
                        OR {$this->_cutNamefield}last_name=:f{$key} 
                        OR {$this->_cutNamefield}phone LIKE :s{$key} 
                        OR {$this->_cutNamefield}phone=:f{$key} 
                        OR {$this->_cutNamefield}email LIKE :s{$key} 
                        OR {$this->_cutNamefield}email=:f{$key} 
                        OR {$this->_cutNamefield}card_id=:f{$key} 
                        OR {$this->_cutNamefield}line=:f{$key} 
                        OR {$this->_cutNamefield}code=:f{$key}
                ";
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
        if( !empty($_REQUEST['period_start']) && !empty($_REQUEST['period_end']) ){
            $options['period_start'] = $_REQUEST['period_start'];
            $options['period_end'] = $_REQUEST['period_end'];
        }
        if( !empty($options['period_start']) && !empty($options['period_end']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "({$this->_cutNamefield}created BETWEEN :startDate AND :endDate)";
            $where_arr[':startDate'] = $options['period_start'].' 00:00:00';
            $where_arr[':endDate'] = $options['period_end'].' 23:59:59';;
        }

        #status
        if( !empty($_REQUEST['status']) ){
            $options['status'] = $_REQUEST['status'];
        }
        if( !empty($options['status']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="{$this->_cutNamefield}status=:status";
            $where_arr[':status'] = $options['status'];
        }

        #ids
        if( !empty($_REQUEST['ids']) ){
            $options['ids'] = $_REQUEST['ids'];
        }
        if( !empty($options['ids']) ){
            if( is_array($options['ids']) ){

                $w = '';
                foreach ($options['ids'] as $id) {
                    $w .= !empty($w) ? ' OR ':'';
                    $w .= "`{$this->_cutNamefield}id`={$id}";
                }
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .="({$w})";
            }
        }

        # join Country
        if( !empty($_REQUEST['country']) ){
            $options['country'] = $_REQUEST['country'];
        }
        if( !empty($options['country']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="`country.country_id`=:country";
            $where_arr[':country'] = $options['country'];
        }

        # join Category
        if( !empty($_REQUEST['category']) ){
            $options['category'] = $_REQUEST['category'];
        }
        if( !empty($options['category']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="category.category_id=:category";
            $where_arr[':category'] = $options['category'];
        }

        if( !empty($_REQUEST['agency']) ){
            $options['agency'] = $_REQUEST['agency'];
        }
        if( !empty($options['agency']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="`{$this->_cutNamefield}agency_id`=:agency";
            $where_arr[':agency'] = $options['agency'];
        }

        

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );


        // echo "SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}"; die;

        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );


        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function get($id, $options=array()){
        $select = $this->_field;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

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

        $data = $this->cut($this->_cutNamefield, $data);

        #name
        $data['prefix_name_str'] = $this->query('system')->getPrefixName( $data['prefix_name'] ) ;
        if( empty($data['prefix_name_str']) ){
            $data['prefix_name_str'] = '';
        }
        $data['fullname'] = "{$data['prefix_name_str']}{$data['first_name']} {$data['last_name']}";
        $data['initials'] = $this->fn->q('text')->initials( $data['first_name'] );

        #options
        if( !empty($options['options']) ){
            // $data['options'] = $this->getOptions($data['id']);
        }

        #birthday
        if( !empty($data['birthday']) ){
            if( $data['birthday']=='0000-00-00' ){
                $data['birthday'] = '';
            }
            else{
                $data['age'] = $this->fn->q('time')->age( $data['birthday'] );
            }
        }

        #address
        if( !empty($data['address']) ){   
            $data['address'] = json_decode($data['address'], true);
        }
        if( is_array($data['address']) ){
            $data['address_str'] = '';
            foreach ($data['address'] as $key => $value) {
                if( !empty($value) ){
                    $data['address_str'].=!empty($data['address_str'])? ' ':'';
                    $data['address_str'].= $this->lang->translate('address',$key) .": {$value}";
                }
                
            }
        }
        else{
            $data['address_str'] = $data['address'];
        }

        #status
        $data['status'] = $this->getStatus( $data['status'] );

        /*
        if( !empty($data['address']['city']) ){
            $data['address']['city_name'] = $this->query('system')->city_name($data['address']['city']);
        }*/

        #image
        if( !empty($data['image_id']) ){
            $image = $this->query('media')->get($data['image_id']);

            if( !empty($image) ){
                $data['image_arr'] = $image;
                $data['image_url'] = $image['quad_url'];
            }
        }

        #permit
        $data['permit']['del'] = true;

        $view_stype = !empty($options['view_stype']) ? $options['view_stype']:'convert';
        if( !in_array($view_stype, array('bucketed', 'convert')) ) $view_stype = 'convert';

        return $view_stype == 'bucketed' 
               ? $this->bucketed( $data )
               : $data;
    }
    public function bucketed($data) {

        $text = $data['fullname'];
        // $subtext = 'ทะเบียน: '.$data['plate'];
        // $category = $data['cus']['fullname'];
        //pro

        return array(
            'id'=> $data['id'],
            'created' => $data['created'],
            'text'=> isset($text)?$text:"",
            "category"=>isset($category)?$category:"",
            "subtext"=>isset($subtext)?$subtext:"",
            "type"=>"customers",
        );
    }


    /**/
    /* status */
    /**/
    public function status() {

        $a[] = array('id'=>'new', 'name'=>'REG', 'code'=>'REG');
        $a[] = array('id'=>'ql', 'name'=>'QL', 'code'=>'REG');
        $a[] = array('id'=>'show', 'name'=>'SH', 'code'=>'REG');
        $a[] = array('id'=>'inv', 'name'=>'INV', 'code'=>'INV');
        $a[] = array('id'=>'com', 'name'=>'COM', 'code'=>'COM');
        $a[] = array('id'=>'rej', 'name'=>'REJ', 'code'=>'REJ');
        $a[] = array('id'=>'lost', 'name'=>'Lost', 'code'=>'REG');
        return $a; 
    }
    public function getStatus($id) {

        $fStatus = $this->status();

        foreach ($fStatus as $key => $value) {
           if( $id==$value['id'] ){
                $status = $value; break;
           }
        }

        return !empty($status) ? $status: $fStatus[0];
    }


    /**/
    /* agency */
    /**/
    public function agency() {

        $w = '';
        $r = array();

        if( !empty($_REQUEST['country']) ){
            $w .= !empty($w) ? ' AND ':'';
            $w .= 'agency_country_id=:country';
            $r[':country'] = $_REQUEST['country'];
        }

        if( !empty($_REQUEST['category']) ){
            $w .= !empty($w) ? ' AND ':'';
            $w .= 'agency_category_id=:category';
            $r[':category'] = $_REQUEST['category'];
        }
    
        $w = !empty($w) ? " WHERE {$w}":'';
        $data = $this->db->select("SELECT agency_id as id, agency_code as code, agency_name as name FROM organizations{$w} ORDER BY agency_name ASC", $r);

        $fdata = array();
        foreach ($data as $key => $value) {

            $count = $this->db->count('people', '`people_agency_id`=:id', array(':id'=>$value['id']) );

            if( $count>0 ){
                $value['count'] = $count;
                $fdata[] = $value;
            }
        }

        return $fdata;
    }
    public function duplicateAgency($txt) {
        return $this->db->count('organizations', "`agency_name`=:text", array(':text'=>$txt));
    }


    /**/
    /* position */
    /**/
    public function position() {
        
        $data = $this->db->select("SELECT position_id as id, position_name as name FROM people_position ORDER BY position_name ASC");

        $fdata = array();
        foreach ($data as $key => $value) {

            $value['count'] = $this->db->count('people', '`people_position_id`=:id', array(':id'=>$value['id']) );
            $fdata[] = $value;
            
        }

        return $fdata;
    }
    public function insertPosition(&$data) {
        $this->db->insert( 'people_position', $data );
        $data['id'] = $this->db->lastInsertId();
    }
    public function duplicatePosition($text) {
        return $this->db->count('people_position', "`position_name`=:text", array(':text'=>$text));
    }
}