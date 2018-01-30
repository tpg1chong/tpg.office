<?php

class Property_Model extends Model
{
    public function __construct() {
        parent::__construct();
    }

    private $_objName = "booking";
    private $_table = "booking b

    LEFT JOIN (employees sale 
        LEFT JOIN emp_department dep ON sale.emp_dep_id=dep.dep_id 
        LEFT JOIN emp_position position ON sale.emp_pos_id=position.pos_id
        LEFT JOIN city ON sale.emp_city_id=city.city_id )
    ON b.book_sale_id=sale.emp_id

    LEFT JOIN book_cus_refer refer ON refer.refer_id=b.book_cus_refer
    LEFT JOIN products_models_colors color ON color.color_id=b.book_color

    LEFT JOIN (
        customers c 
            LEFT JOIN city c1ty ON c.cus_city_id=c1ty.city_id
        )
    ON b.book_cus_id=c.cus_id

    LEFT JOIN (
        products p 
            LEFT JOIN (
            products_models model 
                LEFT JOIN products_brands brand ON model.model_brand_id=brand.brand_id
                LEFT JOIN dealer ON model.model_dealer_id=dealer.dealer_id
            )
        ON p.pro_model_id=model.model_id) 
    ON b.book_pro_id=p.pro_id
    ";
    private $_field = "
          b.book_id
        , b.book_created
        , b.book_cus_refer
        , b.book_number
        , b.book_page
        , b.book_date
        , b.book_status
        , b.book_updated

        , b.book_sale_id AS sale_id
        , sale.emp_prefix_name AS sale_prefix_name
        , sale.emp_first_name AS sale_first_name
        , sale.emp_last_name AS sale_last_name
        , sale.emp_nickname AS sale_nickname
        , sale.emp_phone_number AS sale_phone_number
        , sale.emp_email AS sale_email
        , sale.emp_line_id AS sale_line_id
        , dep.dep_is_sale AS sale_dep_is_sale
        , city.city_name as sale_city_name

        , c.cus_id
        , c.cus_prefix_name
        , c.cus_first_name
        , c.cus_last_name
        , c.cus_nickname
        , c.cus_email
        , c.cus_phone
        , c.cus_lineID
        , c.cus_address
        , c1ty.city_name as cus_city_name

        , p.pro_name as product_name
        , p.pro_mfy as product_mfy
        , p.pro_cc as product_cc

        , model_id, model_name
        , brand_id, brand_name
        , dealer_id, dealer_name
        , pro_id, pro_name

        , refer_id, refer_name
        , color.color_id, color.color_name
    ";
    private $_cutNamefield = "book_";

    public function lists( $options=array() ) {

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'date',
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

        $select.="
            , b.book_pay_type, b.book_pay_type_options
            , b.book_deposit, b.book_deposit_type, b.book_deposit_type_options

            , b.book_net_price
            , b.book_pro_price AS product_price

            , b.book_accessory_price
        ";  

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
        if( empty($data['is_convert']) ){
            $data = $this->_convert( $data );
        }

        $data['status'] = $this->getStatus( $data['status'] );

        if( !empty($data['pay_type']) ){
            $data['pay_type'] = $this->get_pay_type( $data['pay_type'] );

            if( !empty($data['pay_type_options']) ){
                $data['pay_type']['options'] = json_encode($data['pay_type_options'], true);
            }

            unset($data['pay_type_options']);
        }

        if( !empty($data['deposit_type']) ){
            $data['deposit_type'] = $this->get_pay_type( $data['deposit_type'] );

            if( !empty($data['deposit_type_options']) ){
                $data['deposit']['options'] = json_encode($data['deposit_type_options'], true);
            }

            unset($data['deposit_type_options']);
        }


        if( !empty($options['accessory']) ){

            // print_r($this->accessory( $data['id'] )); die;

            $data['accessory'] = $this->accessory( $data['id'] );
        }
        
        $data['permit']['del'] = true;
        return $data;
    }

    private function _setDate($data){
        $data['book_created'] = date('c');
        if( !isset($data['book_updated']) ){
            $data['book_updated'] = date('c');
        }

        return $data;
    }
    public function insert(&$data) {

        $products = $this->query('products')->get($data['book_pro_id']);
        //$pro['pro_balance'] = $products['balance'] - 1;
        $pro['pro_booking'] = $products['booking'] + 1;
        if( !empty($products['balance']) ){
            $pro['pro_subtotal'] = $pro['pro_balance'] + $pro['pro_booking'];
        }
        else{
            $pro['pro_order_total'] = $products['order_total'] + 1;
        }

        $this->db->update('products', $pro, "`pro_id`={$data['book_pro_id']}");

        $this->db->insert( $this->_objName, $this->_setDate($data) );
        $data['book_id'] = $this->db->lastInsertId();
    }

    public function update($id, $data){
        $this->db->update( $this->_objName, $this->_setDate($data), "`book_id`={$id}" );
    }
    public function delete($id) {
        $this->db->delete( $this->_objName, "`cus_id`={$id}" );
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
        refer_id as id,
        refer_name as name,
        refer_note as note
    ";

    public function zone(){
        return $this->db->select("SELECT {$this->selectZone} FROM property_zone ORDER BY zone_id ASC");
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
    /* Zone */
    /**/
    private $selectType = "
        refer_id as id,
        refer_name as name,
        refer_note as note
    ";

    public function type(){
        return $this->db->select("SELECT {$this->selectType} FROM property_type ORDER BY type_id ASC"); 
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
        // $data['refer_updated'] = date('c');

        $this->db->insert('property_type',$data);
        $data['type_id'] = $this->db->lastInsertId();
    }
    public function deleteType($id){
        $this->db->delete('property_type', "`type_id`={$id}");
    }
}