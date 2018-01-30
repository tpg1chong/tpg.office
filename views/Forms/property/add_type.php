<?php

# title
$title = $this->lang->translate('Type');
if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= $title;
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("type_code")
    	->label($this->lang->translate('Code').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['code'])? $this->item['code']:'' );

$form 	->field("type_name")
    	->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'property/save_type"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);