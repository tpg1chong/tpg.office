<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("user_role_id")
        ->label( 'บทบาท*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->roles );

$form   ->field("user_name")
        ->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('autofocus', '1');

$form   ->field("user_login")
        ->label($this->lang->translate('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext');

/*$form   ->field("email")
        ->type('email')
        ->label($this->lang->translate('Email').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['email'])? $this->item['email']:'' );*/

$form   ->field('auto_password')
        ->text('<div class="fsm"><label class="checkbox" for="auto_password"><input type="checkbox" id="auto_password" name="auto_password" checked><span class="mls">กำหนดรหัสผ่านอัตโนมัติ</span></label></div>');


$form   ->field("password")
        ->label($this->lang->translate('Password').'*')
        ->type('password')
        ->maxlength(30)
        ->autocomplete('off')
        ->addClass('inputtext');


# set form
$arr['form'] = '<form class="form-emp-add" method="post" action="'.URL. 'auth/save"></form>';

# body
$arr['body'] = $form->html();

# title
$arr['title']= 'สร้างผู้ใช้ใหม่';


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.$this->lang->translate('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';


echo json_encode($arr);