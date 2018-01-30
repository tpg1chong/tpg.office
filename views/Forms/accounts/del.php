<?php

$arr['title'] = 'ยืนยันการลบข้อมูล';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

if( !empty($this->item['permit']['del']) ){
	
	$arr['form'] = '<form class="js-submit-form" action="'.URL.'accounts/del'.$next.'"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
	$arr['body'] = "คุณต้องการจะลบ <span class=\"fwb\">\"{$this->item['fullname']}\"</span> หรือไม่?";
	
	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">ลบ</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Cancel').'</span></a>';
}
else{

	$arr['body'] = "ไม่สามารถลบ <span class=\"fwb\">\"{$this->item['fullname']}\"</span> ได้!";	
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Close').'</span></a>';
}


echo json_encode($arr);