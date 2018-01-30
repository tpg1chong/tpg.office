<?php

$f = new Form();
$form = $f->create()
	
	// set From
	->elem('div')
	->addClass('form-insert clearfix')

	->field("password_auto")
	->text('<label class="checkbox"><input type="checkbox" name="password_auto" id="password_auto">สร้างรหัสผ่านอัตโนมัติ</label>')

	->field("password_new")
		->label($this->lang->translate('New Password'))
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off")

	->field("password_confirm")
		->label($this->lang->translate('Confirm Password'))
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off");

/*	->field("password_reset")
		->text('<label class="checkbox"><input type="checkbox" name="reset_password">ต้องเปลี่ยนแปลงรหัสผ่านในการเข้าสู่ระบบครั้งถัดไป</label>');
*/

$arr['hiddenInput'][] = array('name'=>'id', 'value'=> $this->item['id']);
$arr['body'] = $form->html();
$arr['title'] = 'รีเซ็ตรหัสผ่าน';	

$arr['form'] = '<form class="form-reset-password" action="'.URL.'accounts/change_password"></form>';
$arr['bottom_msg'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Cancel').'</span></a>';
$arr['button'] = '<button type="submit" class="btn btn-blue btn-submit"><span class="btn-text">'.$this->lang->translate('Save').'</span></button>';

// $arr['width'] = 330;
echo json_encode($arr);