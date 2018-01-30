<?php

$form = new Form();
$form = $form->create()
	// set From
	// ->elem('div')
	->url( URL.'building/update/'.$this->section )
    ->method('post')
	->addClass('js-submit-form');

$form   ->field("name")
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

$form   ->field("type_id")
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

$form   ->field("zone_id")
        ->label( $this->lang->translate('Zone').'*' )
        ->text( $zone );

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value('บันทึก');

$form ->hr( '<input type="hidden" autocomplete="off" class="hiddenInput" value="'.$this->item['id'].'" name="id">' );

echo $form->html();