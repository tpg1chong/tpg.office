<?php

class Search_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function results($objects, $q=''){

    	$results = array();
    	if( !empty($q) ){
        	foreach ($objects as $key => $object) {

	    		$result = $this->{$key}( $q );

	    		if(!empty($result)){
	    			$results[] = array(
			            'object_type'=>$object['type'],
			            'object_name'=>$object['name'],
			            'data'=>$this->convert($object['type'],$result)
			        );
	    		}
	    	}

        }

    	return $results;
    }

    public function convert($object_type, $result){
    	
    	$data = array();
		foreach ($result as $key => $value) {

	        if( $object_type =="customers") {

	        	$id = $value['id'];
	            $text = $value['fullname'];
	            $username = '';
	            $subtext = "";
	            $image_url =  '';
	            $url = '';
	        }
	        elseif( $object_type =="cars" ) {
	        	// $member_count = $self->db->count("SELECT COUNT(user_id) FROM users WHERE group_id=:gid AND display='enabled'", array( ":gid"=>$value['group_id'] ));

	        	// $member_count = $member_count>0?" ($member_count)":"";
	        	// $id = $value['group_id'];
	            // $text = $value['group_name']; //{$member_count}
	        }

	        $data[] = array(
	        	'id'=>isset($id)?$id:"",
	        	'username'=>isset($username)?$username:"",
	            'text'=>isset($text)?$text:"",
	            "url"=> isset($url)?$url:"",
	            "category"=>isset($category)?$category:"",
	            "subtext"=>isset($subtext)?$subtext:"",
	            "image_url"=>isset($image_url)?$image_url:""
	        );
	    }

        return $data;
    }

    public function cars($q=''){

    	return $this->query('cars')->buildFrag( $this->db->select(
    		"SELECT cus_id, cus_prefix_name, cus_first_name, cus_last_name, cus_nickname,  cus_created, cus_updated, cus_birthday, cus_card_id, cus_phone, cus_email, cus_lineID, cus_bookmark, cus_address, cus_zip, cus_city_id, city_name, cus_emp_id 

    		FROM cars car
        		LEFT JOIN (customers cus LEFT JOIN city ON cus.cus_city_id=city.city_id)
        			ON car.car_cus_id=cus.cus_id WHERE 

    		car_vin LIKE all OR car_vin=full
	        OR car_engine LIKE all OR car_engine=full
	        OR car_number LIKE all OR car_number=:full
	        OR cus_first_name LIKE :begin OR cus_first_name=:full
	        OR cus_phone=:full
	        OR cus_email=:full
	        OR cus_lineID=:full

    		ORDER BY car_updated LIMIT 5"
        , array( 
        	":begin"=>"{$q}%",
        	":all"=>"%{$q}%",
        	":full"=> $q,
        )) );

    }

    public function customers($q='') {

    	$where_str = "";
        $where_arr = array();


    	$arrQ = explode(' ', $q);
        $wq = '';
        foreach ($arrQ as $key => $value) {
            $wq .= !empty( $wq ) ? " OR ":'';
            $wq .= "cus_first_name LIKE :q{$key} OR cus_first_name=:f{$key} OR cus_last_name LIKE :q{$key} OR cus_last_name=:f{$key} OR cus_phone LIKE :s{$key} OR cus_phone=:f{$key} OR cus_email LIKE :s{$key} OR cus_email=:f{$key} OR cus_card_id=:f{$key}";
            $where_arr[":q{$key}"] = "%{$value}%";
            $where_arr[":s{$key}"] = "{$value}%";
            $where_arr[":f{$key}"] = $value;
        }

        if( !empty($wq) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "($wq)";
        }

    	return $this->query('customers')->buildFrag( $this->db->select(
    		"SELECT cus_id, cus_prefix_name, cus_first_name, cus_last_name, cus_nickname,  cus_created, cus_updated, cus_birthday, cus_card_id, cus_phone, cus_email, cus_lineID, cus_bookmark, cus_address, cus_zip, cus_city_id, city_name, cus_emp_id

    		FROM customers LEFT JOIN city ON customers.cus_city_id=city.city_id 

    		WHERE {$where_str}

    		ORDER BY cus_updated DESC LIMIT 5"

        , $where_arr ) );
    }
}