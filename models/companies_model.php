<?php

class Companies_model extends Model
{
    public function __construct() {
        parent::__construct();
    }

    private $_objName = "companies";
    private $_table = "companies AS co
        INNER JOIN companies_groups AS g ON co.company_group_id=g.group_id";
    private $_field = "
          company_id
        , company_code
        , company_name
        , company_address
        , company_email
        , company_phone
        , company_status
        , company_image_id
        , company_updated

        , g.group_id
        , g.group_code
        , g.group_name
    ";
    /*
       */
    private $_cutNamefield = "company_";

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
        $this->db->delete( $this->_objName, "`company_id`={$id}" );
    }


    /**/
    /* lists */
    /**/
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
                $wq .= "company_name LIKE :s{$key} OR company_name=:f{$key}";
                // $where_arr[":q{$key}"] = "%{$value}%";
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
            $where_str .= "(co.company_created BETWEEN :startDate AND :endDate)";
            $where_arr[':startDate'] = $options['period_start'].' 00:00:00';
            $where_arr[':endDate'] = $options['period_end'].' 23:59:59';;
        }

        # status
        if( !empty($_REQUEST['status']) ){
            $options['status'] = $_REQUEST['status'];
        }
        if( !empty($options['status']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="company_status=:status";
            $where_arr[':status'] = $options['status'];
        }

        # group
        if( !empty($_REQUEST['group']) ){
            $options['group'] = $_REQUEST['group'];
        }
        if( !empty($options['group']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="company_group_id=:group";
            $where_arr[':group'] = $options['group'];
        }

        
        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );

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
        
        $data['initials'] = $this->fn->q('text')->initials( $data['name'] );


        if( !empty($data['image_id']) ){
            $image = $this->query('media')->get($data['image_id']);

            if( !empty($image) ){
                $data['image_arr'] = $image;
                $data['image_url'] = $image['quad_url'];
            }
        }

        $data['permit']['del'] = true;

        $data['address_str'] = $data['address'];

        $view_stype = !empty($options['view_stype']) ? $options['view_stype']:'convert';
        if( !in_array($view_stype, array('bucketed', 'convert')) ) $view_stype = 'convert';

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
}