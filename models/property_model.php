<?php

class Property_Model extends Model
{
    public function __construct() {
        parent::__construct();
    }

    private $_objName = "property";
    private $_table = "property p
        LEFT JOIN building b ON p.property_build_id = b.build_id
    ";
    private $_field = "
        p.*
        , b.build_name
        , b.build_type_id
        , b.build_zone_id
    ";
    private $_cutNamefield = "property_";

    public function lists( $options=array() ) {

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:'',

            'more' => true
            ), $options);

        if( isset($_REQUEST['view_stype']) ){
            $options['view_stype'] = $_REQUEST['view_stype'];
        }

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($options['not']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str = "{$this->_cutNamefield}id!=:not";
            $where_arr[':not'] = $options['not'];
        }

        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "cus_first_name LIKE :q{$key} OR cus_first_name=:f{$key} OR cus_last_name LIKE :q{$key} OR cus_last_name=:f{$key} OR cus_phone LIKE :s{$key} OR cus_phone=:f{$key} OR cus_email LIKE :s{$key} OR cus_email=:f{$key} OR cus_card_id=:f{$key} OR book_page=:f{$key} OR book_number=:f{$key} OR emp_first_name=:f{$key} OR emp_last_name=:f{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
        }

        if( !empty($_REQUEST['status']) ){
            $options['status'] = $_REQUEST['status'];

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "book_status=:status";
            $where_arr[':status'] = $options['status'];
        }

        if( !empty($_REQUEST['period_start']) && !empty($_REQUEST['period_end']) ){

            $options['period_start'] = date("Y-m-d 00:00:00", strtotime($_REQUEST['period_start']));
            $options['period_end'] = date("Y-m-d 23:59:59", strtotime($_REQUEST['period_end']));

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "book_date BETWEEN :startDate AND :endDate";
            $where_arr[':startDate'] = $options['period_start'];
            $where_arr[':endDate'] = $options['period_end'];
        }

        if( isset($_REQUEST['zone']) ){
            $options['zone'] = $_REQUEST['zone'];
        }

        if( isset($_REQUEST['type']) ){
            $options['type'] = $_REQUEST['type'];
        }

        if( !empty($options['zone']) ){

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "property_zone_id=:zone";
            $where_arr[':zone'] = $options['zone'];
        }

        if( !empty($options['type']) ){

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "property_type_id=:type";
            $where_arr[':type'] = $options['type'];
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

        // $select.="
        //     , b.book_pay_type, b.book_pay_type_options
        //     , b.book_deposit, b.book_deposit_type, b.book_deposit_type_options

        //     , b.book_net_price
        //     , b.book_pro_price AS product_price

        //     , b.book_accessory_price
        // ";  

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

        $view_stype = !empty($options['view_stype']) ? $options['view_stype']:'convert';
        if( !in_array($view_stype, array('bucketed', 'convert')) ) $view_stype = 'convert';

        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->{$view_stype}($value);
        }
        return $data;
    }
    public function bucketed($data){

        $prefix = '';
        foreach ($this->query('customers')->prefixName() as $key => $value) {
            if( $value['id']==$data['cus_prefix_name'] ){
                $prefix = $value['name'];
                break;
            }
        }

        $text = "{$prefix}{$data['cus_first_name']} {$data['cus_last_name']}";

        $status = array(
            'id' => $data['status_id'],
            'name' => $data['status_label'],
            'color' => $data['status_color'],
        );

        $category = $data['product_name'];

        $subtext = '';
        $a = array('cus_phone', 'cus_email', 'cus_lineID');
        foreach ($a as $key) {
            if( !empty($data[$key]) ) {
                $subtext .= !empty($subtext) ?', ':'';
                $subtext .= $data[$key];
            }
        }

        return array(
            'id'=> $data['book_id'],
            'created' => $data['book_created'],
            'options' => array(
                'time' => 'disabled'
                ),
            'text'=> $text,
            "category"=>isset($category)?$category:"",
            "subtext"=>isset($subtext)?$subtext:"",
            "image_url"=>isset($image_url)?$image_url:"",
            'status' => $status,
            // 'data' => $data,
         );
    }
    public function convert($data, $options=array()){

        $data =$this->cut($this->_cutNamefield, $data);
        // if( empty($data['is_convert']) ){
        //     $data = $this->_convert( $data );
        // }

        $data['type'] = $this->getType( $data['type_id'] );
        $data['zone'] = $this->getZone( $data['zone_id'] );

        $data['building']['name'] = $data['build_name'];
        $data['building']['type'] = $this->getType( $data['build_type_id'] );
        $data['building']['zone'] = $this->getZone( $data['build_zone_id'] );
        
        $data['permit']['del'] = true;
        return $data;
    }

    private function _modifyData($data){

        $data["updated"] = date('c'); // last update time

        $_data = array();
        foreach ($data as $key => $value) {
            $_data[ $this->_cutNamefield.$key ] = trim($value);
        }
        
        return $_data;
    }

    public function insert(&$data) {

        $data['created'] = date('c');
        $this->db->insert( $this->_objName, $this->_modifyData($data) );
        $data[$this->_cutNamefield.'id'] = $this->db->lastInsertId();

        // $data = $this->convert( $data );
    }

    public function update($id, $data){
        $this->db->update( $this->_objName, $this->_modifyData($data), "`{$this->_cutNamefield}id`={$id}" );
    }
    public function delete($id) {
        $this->db->delete( $this->_objName, "`{$this->_cutNamefield}id`={$id}" );
    }

    /**/
    /* Booking Check */
    /**/
    public function has_page($page, $number) {
        return $this->db->count($this->_objName, "{$this->_cutNamefield}page='{$page}' AND {$this->_cutNamefield}number='{$number}'");
    }
    

    /**/
    /*  Booking Status  */
    /**/
    // public function status(){
    //     // , , status_color as color
    //     return $this->db->select("SELECT status_id as id, status_label as name, status_lock as is_lock, status_enable as enable, status_color as color FROM book_status ORDER BY status_order ASC");
    // }
    public function get_status($id){
        $sth = $this->db->prepare("
            SELECT status_id as id, status_label as name, status_lock as is_lock, status_enable as enable, status_color as color
            FROM book_status 
            WHERE `status_id`=:id 
            LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ) : array();
    }
    public function insert_status(&$data) {
        $this->db->insert( 'book_status', $data );
        $data['status_id'] = $this->db->lastInsertId();
    }
    public function update_status($id, $data){
        $this->db->update( 'book_status', $data, "`status_id`={$id}" );
    }
    public function delete_status($id){
        $this->db->delete( 'book_status', "`status_id`={$id}" );
    }

    /**/
    /*  Booking Conditions  */
    /**/
    private $select_book_conditions = "
        condition_id as id,
        condition_name as name,
        condition_income as income,
        condition_lock as has_lock,
        condition_keyword as keyword
    ";
    public function conditions(){
        return $this->db->select("SELECT {$this->select_book_conditions} FROM book_conditions ORDER BY condition_order ASC"); 
    }
    public function get_condition($id){
        $sth = $this->db->prepare("SELECT {$this->select_book_conditions} FROM book_conditions WHERE `condition_id`=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ) : array();
    }
    public function insert_condition(&$data){
        $this->db->insert('book_conditions', $data);
        $data['condition_id'] = $this->db->lastInsertId();
    }
    public function update_condition($id, $data) {
        $this->db->update('book_conditions', $data, "`condition_id`={$id}");
    }

    /**/
    /*  dealer  */
    /**/
    public function dealer() {
        return $this->db->select("SELECT dealer_id as id, dealer_name as name FROM dealer");
    }

    /**/
    /*  models  */
    /**/
    public function models() {
        return $this->db->select("SELECT model_id as id, model_name as name FROM products_models");
    }

    /**/
    /*  products  */
    /**/
    public function get_product() {

        $where_str = '';
        $where_arr = array();

        if( isset($_REQUEST['model']) ){
            $where_str .= !empty($where_str) ? " AND ":'';
            $where_str .= "`pro_model_id`=:model";
            $where_arr[':model'] = trim($_REQUEST['model']);
        }

        $where_str = !empty($where_str) ? " WHERE {$where_str}": '';
        $data = $this->db->select("SELECT pro_id as id, pro_name as name, pro_price as price FROM products{$where_str}", $where_arr);

        return $data;
    }

    public function get_color() {

        $where_str = '';
        $where_arr = array();

        if( isset($_REQUEST['model']) ){
            $where_str .= !empty($where_str) ? " AND ":'';
            $where_str .= "`color_model_id`=:model";
            $where_arr[':model'] = trim($_REQUEST['model']);
        }

        $where_str = !empty($where_str) ? " WHERE {$where_str}": '';
        $data = $this->db->select("SELECT color_id as id, color_name as name, color_primary as code FROM products_models_colors{$where_str}", $where_arr);

        return $data;
    }

    /**/
    /* Accessory */
    /**/
    private $select_accessory = "
          book.option_value AS value
        , CASE book.option_has_etc
            WHEN 0 THEN acc.acc_name
            ELSE book.option_name
        END AS name

        , CASE book.option_has_etc
            WHEN 0 THEN acc.acc_id
            ELSE 0
        END AS acc_id

        , option_cost AS cost
        , option_rate AS rate
        , option_type AS type
        , option_has_etc AS has_etc
    ";
    public function accessory($id) {
        
        return $this->db->select("
            SELECT {$this->select_accessory}
            FROM booking_accessory book
                LEFT JOIN accessory acc ON acc.acc_id=book.option_name
            WHERE option_book_id=:id ORDER BY has_etc ASC", array(':id'=>$id));
        
    }
    public function get_accessory() {
        $where_str = '';
        $where_arr = array();

        if( isset($_REQUEST['model']) ){
            $where_str .= !empty($where_str) ? " AND ":'';
            $where_str .= "`acc_model_id`=:model";
            $where_arr[':model'] = trim($_REQUEST['model']);
        }

        $where_str = !empty($where_str) ? " WHERE {$where_str}": '';
        $data = $this->db->select("SELECT acc_id as id, acc_name as name, acc_price as price, acc_cost as cost FROM accessory{$where_str}", $where_arr);

        return $data;
    }

    /**/
    /* Customer */
    /**/
    public function search_customer($first_name, $last_name){

        $data = $this->db->select("SELECT * FROM customers WHERE cus_first_name='{$first_name}' AND cus_last_name='{$last_name}'");

        return $data;
    }

    public function set_customer(&$data,$cus_id=null){

        $post = array(
            'cus_prefix_name'=>$data['prefix_name'],
            'cus_first_name'=>$data['first_name'],
            'cus_last_name'=>$data['last_name'],
            'cus_nickname'=>$data['nickname'],
            'cus_address'=>$data['address'],
            'cus_city_id'=>$data['city_id'],
            'cus_zip'=>$data['zip'],
            'cus_emp_id'=>$data['emp_id'],
            );

        if( !empty($data['mobile_phone']) ){
            $post['cus_phone'] = $data['mobile_phone'];
        }

        if( !empty($data['line_id']) ){
            $post['cus_lineID'] = $data['line_id'];
        }

        $post['cus_updated'] = date('c');

        if( !empty($cus_id) ){
            $this->db->update('customers', $post, "`cus_id`={$cus_id}");
            $data['cus_id'] = $cus_id;
        }
        else{
            $post['cus_created'] = date('c');
            $this->db->insert('customers', $post);
            $data['cus_id'] = $this->db->lastInsertId();
        }

        if( empty($cus_id) ){
            $p = array('mobile_phone','tel');
            $label = array('Mobile Phone', 'Work Phone');
            for($i=0;$i<count($p);$i++){
                if( !empty($data[$p[$i]]) ){
                    $options = array(
                        'cus_id'=>$data['cus_id'],
                        'type'=>'phone',
                        'label'=>$label[$i],
                        'value'=>$data[$p[$i]]
                        );
                    $this->query('customers')->set_option($options);
                }
            }

            $line = array(
                'cus_id'=>$data['cus_id'],
                'type'=>'social',
                'label'=>'Line ID',
                'value'=>$data['line_id'],
                );
            $this->query('customers')->set_option($line);
        }
        else{
            $item = $this->query('customers')->get($cus_id, array('options'=>1));
            if( !empty($item['options']['phone']) ){
                $option_id = $item['options']['phone'][0]['id'];
                $options = array(
                    'id'=>$option_id,
                    'cus_id'=>$data['cus_id'],
                    'type'=>'phone',
                    'label'=>'Mobile Phone',
                    'value'=>$data['mobile_phone'],
                    );
            }
            else{
               $options = array(
                'cus_id'=>$data['cus_id'],
                'type'=>'phone',
                'label'=>'Mobile Phone',
                'value'=>$data['mobile_phone'],
                );
           }

           $this->query('customers')->set_option($options);

           if( !empty($item['options']['social']) ){
            $option_line = $item['options']['social'][0]['id'];
            $line = array(
                'id'=>$option_line,
                'cus_id'=>$data['cus_id'],
                'type'=>'social',
                'label'=>'Line ID',
                'value'=>$data['line_id'],
                );
            }
            else{
                $line = array(
                    'cus_id'=>$data['cus_id'],
                    'type'=>'social',
                    'label'=>'Line ID',
                    'value'=>$data['line_id'],
                    );
            }

            $this->query('customers')->set_option($line);
        }
    }

    /**/
    /* pay_type */
    /**/
    public function get_pay_type($key) {
        $name = '';
        switch ($key) {
            case 'cash':
                $name = 'เงินสด';
                break;
            
            case 'hier':
                $name = 'เช่าซื้อ';
                break;
        }

        return array(
            'id' => $key,
            'name' => $name
        );
    }
    public function get_deposit_type($key) {
        $name = '';
        switch ($key) {
            case 'cash':
                $name = 'เงินสด';
                break;
            
            case 'credit':
                $name = 'บัตเครดิต';
                break;

            case 'check':
                $name = 'เช็คธนาคาร';
                break;
        }

        return array(
            'id' => $key,
            'name' => $name
        );
    }

    /**/
    /* Insurace */
    /**/
    public function set_insurance($data, $id){
        $data['ins_book_id'] = $id;
        $this->db->insert('booking_insurance', $data);
    }

    public function update_insurance($data, $id){

        $this->db->update('booking_insurance', $data, "`ins_book_id`={$id}");
    }

    /**/
    /* Accessory */
    /**/

    public function set_accessory($data, $id){

        $post = array(
            'option_book_id'=>$id,
            'option_name'=>$data['name'],
            'option_value'=>$data['value'],
            'option_cost'=>$data['cost'],
            'option_rate'=>$data['rate'],
            'option_type'=>$data['type'],
            'option_has_etc'=>$data['has_etc']
            );

        $this->db->insert('booking_accessory', $post);
    }

    public function del_accessory($id){

        $this->db->delete( 'booking_accessory', "`option_book_id`={$id}", $this->db->count('booking_accessory', '`option_book_id`={$id}') );
    }

    public function getAccessory($id=null) {
        $data = $this->db->select("SELECT acc_id as id, acc_name as name, acc_price as price FROM accessory WHERE acc_id={$id} LIMIT 1");
        return $data;
    }

    /**/
    /* Condition */
    /**/
    public function set_condition($data, $id){
        $post = array(
            'con_book_id'=>$id,
            'con_name'=>$data['name'],
            'con_value'=>$data['value'],
            'con_type'=>$data['type'],
            'con_has_etc'=>$data['has_etc']
            );

        $this->db->insert('booking_condition', $post);
    }

    public function del_condition($id){

        $this->db->delete( 'booking_condition', "`condition_book_id`={$id}", $this->db->count('booking_condition', '`condition_book_id`={$id}') );
    }

    /**/
    /* Zone */
    /**/
    private $selectZone = "
          idzone as id
        , name
        , code
        , active

        , lat
        , lng
    ";

    public function zone(){
        return $this->db->select("SELECT {$this->selectZone} FROM zone ORDER BY name ASC");
    }
    public function getZone($id){
        $sth = $this->db->prepare("SELECT {$this->selectZone} FROM property_zone WHERE `zone_id`=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ) : array();
    }
    public function updateZone($id, $data) {
        $this->db->update('property_zone', $data, "`zone_id`={$id}");
    }
    public function insertZone(&$data){

        // $data['refer_created'] = date('c');
        // $data['refer_updated'] = date('c');

        $this->db->insert('property_zone',$data);
        $data['zone_id'] = $this->db->lastInsertId();
    }
    public function deleteZone($id){
        $this->db->delete('property_zone', "`zone_id`={$id}");
    }
    

    /**/
    /* Type */
    /**/
    private $selectType = "
          idtype as id
        , name
        , code
        , active
    ";

    public function type(){
        return $this->db->select("SELECT {$this->selectType} FROM type ORDER BY name ASC"); 
    }
    public function getType($id){
        $sth = $this->db->prepare("SELECT {$this->selectType} FROM property_type WHERE `type_id`=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ) : array();
    }
    public function updateType($id, $data) {
        $this->db->update('property_type', $data, "`type_id`={$id}");
    }
    public function insertType(&$data){

        // $data['refer_created'] = date('c');
        $data['type_updated'] = date('c');

        $this->db->insert('property_type',$data);
        $data['type_id'] = $this->db->lastInsertId();
    }
    public function deleteType($id){
        $this->db->delete('property_type', "`type_id`={$id}");
    }
    public function is_code($text){
        return $this->db->count('property_type', "`type_code`=:code", array(':code'=>'$text'));
    }
    public function is_type($text){
        return $this->db->count('property_type', "`type_name`=:name", array(':name'=>'$text'));
    }


    /**/
    /* Near Type */
    /**/
    private $selectNearType = "
        type_id AS id,
        type_name AS name,
        type_keyword AS keyword
    ";
    public function nearType(){
        return $this->db->select("SELECT {$this->selectNearType} FROM property_near_type ORDER BY type_id ASC");
    }
    public function getNearType($id){
        $sth = $this->db->prepare("SELECT {$this->selectNearType} FROM property_near_type WHERE `type_id`=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ) : array();
    }
    public function updateNearType($id, $data) {
        $this->db->update('property_near_type', $data, "`type_id`={$id}");
    }
    public function insertNearType(&$data){

        $data['type_created'] = date('c');
        // $data['refer_updated'] = date('c');

        $this->db->insert('property_near_type',$data);
        $data['type_id'] = $this->db->lastInsertId();
    }
    public function deleteNearType($id){
        $this->db->delete('property_near_type', "`type_id`={$id}");
    }
    public function is_nearType_keyword( $text ){
        return $this->db->count("property_near_type", "type_keyword='{$text}'");
    }
    

    /**/
    /* Near */
    /**/
    private $selectNear = "
          near_id AS id
        , near_name AS name
        , near_keyword AS keyword
        , near_active AS active
        
        , near_type_id AS type_id
        , type_name
        , type_keyword
    ";
    public function near(){
        return $this->db->select("SELECT {$this->selectNear} FROM property_near LEFT JOIN property_near_type ON property_near.near_type_id = property_near_type.type_id ORDER BY near_id ASC");
    }
    public function getNear($id){
        $sth = $this->db->prepare("SELECT {$this->selectNear} FROM property_near WHERE `near_id`=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ) : array();
    }
    public function updateNear($id, $data) {
        $this->db->update('property_near', $data, "`near_id`={$id}");
    }
    public function insertNear(&$data){

        $data['near_created'] = date('c');
        // $data['refer_updated'] = date('c');

        $this->db->insert('property_near',$data);
        $data['near_id'] = $this->db->lastInsertId();
    }
    public function deleteNear($id){
        $this->db->delete('property_near', "`near_id`={$id}");
    }
    public function is_near_keyword($text){
        return $this->db->count("property_near", "near_keyword='{$text}'");
    }


    public function status() {
        
        $a = array();

        $a[] = array('id'=>'', 'name'=>'Recent Available','color'=>'#99ff99');
        $a[] = array('id'=>'', 'name'=>'Ready','color'=>'#ccffcc');
        $a[] = array('id'=>'', 'name'=>'AV 1 month','color'=>'#ffff99');
        $a[] = array('id'=>'', 'name'=>'AV 3 month','color'=>'#ffffcc');
        $a[] = array('id'=>'', 'name'=>'Not AV','color'=>'#ffcccc');
        $a[] = array('id'=>'', 'name'=>'Sold','color'=>'#00DEE8');
        $a[] = array('id'=>'', 'name'=>'Black List','color'=>'#cccccc');


        return $a;
    }
}