<?php

class Building_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "building";
    private $_table = "building";
    private $_field = "*";
    private $_cutNamefield = "build_";


    private function _modifyData($data){

        $data["updated"] = date('c'); // last update time

        $_data = array();
        foreach ($data as $key => $value) {
            $_data[ $this->_cutNamefield.$key ] = trim($value);
        }
        
        return $_data;
    }
    public function insert(&$data) {
        
        $data["created"] = date('c'); // new create time
        $this->db->insert($this->_objType, $this->_modifyData( $data ) );
        $data[$this->_cutNamefield.'id'] = $this->db->lastInsertId();
        $data = $this->convert($data);
    }
    public function update($id, $data) {
        $this->db->update($this->_objType, $this->_modifyData($data), "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id) {
        $this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }

    public function lists( $options=array() ) {
        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($_REQUEST['zone']) ){
        	$options['zone'] = $_REQUEST['zone'];
        }

        if( isset($_REQUEST['type']) ){
        	$options['type'] = $_REQUEST['type'];
        }

        if( !empty($options['zone']) ){

        	$where_str .= !empty( $where_str ) ? " AND ":'';
        	$where_str .= "build_zone_id=:zone";
        	$where_arr[':zone'] = $options['zone'];
        }

        if( !empty($options['type']) ){

        	$where_str .= !empty( $where_str ) ? " AND ":'';
        	$where_str .= "build_type_id=:type";
        	$where_arr[':type'] = $options['type'];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = !empty($options['unlimit']) ? '' : $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function buildFrag($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function get($id){
        
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) );
        } return array();
    }
    public function convert($data){

        $data = $this->cut($this->_cutNamefield, $data);
        $data['type'] = $this->query('property')->getType( $data['type_id'] );
        $data['zone'] = $this->query('property')->getZone( $data['zone_id'] );
        $data['emp'] = $this->query('employees')->get( $data['emp_id'] );

        $data['gallery'] = array();

        return $data;
    }
}
