<?php

class Organizations_Model extends Model
{
    public function __construct() {
        parent::__construct();
    }

    private $_objName = "organizations";
    private $_table = "
        organizations AS o
            LEFT JOIN organizations_category AS c  ON o.agency_category_id=c.category_id
            LEFT JOIN country ON o.agency_country_id=country.country_id
            
    ";
    private $_field = "
          agency_id
        , agency_code
        , agency_name
        , agency_address
        , agency_email
        , agency_phone
        , agency_status
        , agency_image_id
        , agency_updated

        , c.category_id
        , c.category_code
        , c.category_name

        , country.country_id
        , country.country_name
    ";
    /*

         
       */
    private $_cutNamefield = "agency_";

    private function _setDate($data){
        return $data;
    }
    public function insert(&$data) {
        if( empty($data["{$this->_cutNamefield}created"]) ){
            $data["{$this->_cutNamefield}created"] = date('c');
        }

        if( empty($data["{$this->_cutNamefield}category_id"]) ){
            $data["{$this->_cutNamefield}category_id"] = 1;
        }
        
        $data["{$this->_cutNamefield}updated"] = date('c');

        $this->db->insert( $this->_objName, $this->_setDate($data) );
        $data['id'] = $this->db->lastInsertId();
    }

    public function update($id, $data) {
        if( !isset($data["{$this->_cutNamefield}updated"]) ){
            $data["{$this->_cutNamefield}updated"] = date('c');
        }
        $this->db->update( $this->_objName, $this->_setDate($data), "`{$this->_cutNamefield}id`={$id}" );
    }
    public function delete($id) {
        $this->db->delete( $this->_objName, "`{$this->_cutNamefield}id`={$id}" );
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
                $wq .= "{$this->_cutNamefield}name LIKE :s{$key} OR {$this->_cutNamefield}name=:f{$key}";
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
            $where_str .= "({$this->_cutNamefield}created BETWEEN :startDate AND :endDate)";
            $where_arr[':startDate'] = $options['period_start'].' 00:00:00';
            $where_arr[':endDate'] = $options['period_end'].' 23:59:59';;
        }

        # status
        if( !empty($_REQUEST['status']) ){
            $options['status'] = $_REQUEST['status'];
        }
        if( !empty($options['status']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="{$this->_cutNamefield}status=:status";
            $where_arr[':status'] = $options['status'];
        }

        # category
        if( !empty($_REQUEST['category']) ){
            $options['category'] = $_REQUEST['category'];
        }
        if( !empty($options['category']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="{$this->_cutNamefield}category_id=:category";
            $where_arr[':category'] = $options['category'];
        }

        # country
        if( !empty($_REQUEST['country']) ){
            $options['country'] = $_REQUEST['country'];
        }
        if( !empty($options['country']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .="{$this->_cutNamefield}country_id=:country";
            $where_arr[':country'] = $options['country'];
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
            "category"=> $data['category_name'],
            "subtext"=>isset($subtext)?$subtext:"",
            // "type"=>"customers",
            "image_url"=> !empty($data['image_url']) ? $data['image_url']: '',
            // 'status' => isset($status)?$status:"",
            'icon' => 'building',
        );
    }


    /**/
    /* Category */
    /**/
    public function category($value='') {

        $data = $this->db->select("SELECT category_id as id, category_code as code, category_name as name FROM organizations_category ORDER BY category_name DESC");

        $fdata = array();
        foreach ($data as $key => $value) {

            $value['count'] = $this->db->count('organizations', 'agency_category_id=:id', array(':id'=>$value['id']) );

            if( $value['id']==1 ){
                $other = $value;
                continue;
            }

            $fdata[] = $value;
        }

        if( !empty($other) ) $fdata[] = $other;
        return $fdata;
    }
    public function insertCategory(&$data) {
        $this->db->insert( 'organizations_category', $data );
        $data['id'] = $this->db->lastInsertId();
    }
    public function duplicateCategory($text) {
        return $this->db->count('organizations_category', "`category_name`=:text", array(':text'=>$text));
    }

    /**/
    /* country */
    /**/
    public function country() {

        $data = $this->db->select("SELECT country_id as id, country_code as code, country_name as name FROM country ORDER BY country_name ASC");

        $fdata = array();
        foreach ($data as $key => $value) {

            $count = $this->db->count('organizations', 'agency_country_id=:id', array(':id'=>$value['id']) );

            if( $count>0 ){
                $value['count'] = $count;
                $fdata[] = $value;
            }
        }

        return $fdata;
    }
}