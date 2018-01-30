<?php

switch ($this->status) {
	case '1':
		$st_str = 'เปิดการใช้งาน';
		break;
	
	default:
		$st_str = 'ปิดการใช้งาน';
		break;
}

$arr['title'] = "{$st_str}";
$arr['form'] = '<form class="js-submit-form" action="'.URL.'accounts/change_enabled"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'status','value'=>$this->status);


$this->status = ucfirst($this->status);
$arr['body'] = "ยืนยัน การ{$st_str} <span class=\"fwb\"> {$this->item['fullname']}</span> หรือไม่?";

$arr['button'] = '<button type="submit" class="btn btn-blue btn-submit"><span class="btn-text">'.$this->lang->translate('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Cancel').'</span></a>';

echo json_encode($arr);