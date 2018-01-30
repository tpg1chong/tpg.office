<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("user_name")
        ->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("user_login")
        ->label($this->lang->translate('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['login'])? $this->item['login']:'' );


# set form
$arr['hiddenInput'][] = array('name'=>'id', 'value'=> $this->item['id']);
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'accounts/save"></form>';

# body
$arr['body'] = $form->html();

# title
$arr['title']= 'เปลี่ยนชื่อผู้ใช้';


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.$this->lang->translate('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';


echo json_encode($arr);