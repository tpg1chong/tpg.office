<?php

# title
$title = $this->lang->translate('Zone');
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

$form 	->field("zone_code")
    	->label($this->lang->translate('Code').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['code'])? $this->item['code']:'' );

$form 	->field("zone_name")
    	->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("zone_sort")
    	->label($this->lang->translate('Sort').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['sort'])? $this->item['sort']:'' );

$form 	->field("zone_lat")
    	->label($this->lang->translate('Latitude').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['lat'])? $this->item['lat']:'' );

$form 	->field("zone_lng")
    	->label($this->lang->translate('Longitude').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['lng'])? $this->item['lng']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'property/save_zone"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);