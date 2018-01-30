<?php

$arr['title'] = 'Confirm deletion of Data.';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

if( $this->item['permit']['del'] ){
	
	$arr['form'] = '<form class="js-submit-form" action="'.URL.'organizations/del'.$next.'"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
	$arr['body'] = "You want to delete Organization <span class=\"fwb\">{$this->item['name']}</span>?";
	
	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">ลบ</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Cancel').'</span></a>';
}
else{

	$arr['body'] = "You can not delete <span class=\"fwb\">\"{$this->item['name']}\" organization.</span>";	
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Close').'</span></a>';
}


echo json_encode($arr);