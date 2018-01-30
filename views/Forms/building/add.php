<?php

# title
$title = $this->lang->translate('Building');
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

$form 	->field("name")
    	->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$type = '<option value="">-- select a type --</option>';
foreach ($this->type as $key => $value) {
	
	$sel = '';
	if( !empty($this->item) ){
		if( $value['id'] == $this->item['type_id'] ){
			$sel = ' selected="1"';
		}
	}

	$type .= '<option'.$sel.' value="'.$value['id'].'">['.$value['code'].'] '.$value['name'].'</option>';
}

$type = '<select name="type_id" class="inputtext">'.$type.'</select>';

$form 	->field("type_id")
		->label( $this->lang->translate('Type').'*' )
		->text( $type );

$zone = '<option value="">-- select a zone --</option>';
foreach ($this->zone as $key => $value) {
	
	$sel = '';
	if( !empty($this->item) ){
		if( $value['id'] == $this->item['zone_id'] ){
			$sel = ' selected="1"';
		}
	}

	$zone .= '<option'.$sel.' value="'.$value['id'].'">['.$value['code'].'] '.$value['name'].'</option>';
}

$zone = '<select name="zone_id" class="inputtext">'.$zone.'</select>';

$form 	->field("zone_id")
		->label( $this->lang->translate('Zone').'*' )
		->text( $zone );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'building/save"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);