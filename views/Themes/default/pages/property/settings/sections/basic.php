<?php

$form = new Form();
$form = $form->create()
	// set From
	// ->elem('div')
	->url( URL.'property/update/'.$this->section )
    ->method('post')
	->addClass('js-submit-form');

$building = '<option value="">-- select a building --</option>';
foreach ($this->building['lists'] as $key => $value) {
    
    $sel = '';
    if( !empty($this->item) ){
        if( $value['id'] == $this->item['build_id'] ){
            $sel = ' selected="1"';
        }
    }

    $building .= '<option'.$sel.' value="'.$value['id'].'">'.$value['name'].'</option>';
}

$building = '<select name="build_id" class="inputtext">'.$building.'</select>';

$form   ->field("build_id")
        ->label( $this->lang->translate('Build').'*' )
        ->text( $building );

$form   ->field("name")
        ->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$star = '';
for($i=0; $i<=5; $i++){

    $sel = '';
    if( !empty($this->item) ){

        if( $this->item['star'] == $i ){
            $sel = ' selected="1"';
        }
    }

    $star .= '<option'.$sel.' value="'.$i.'">'.$i.'</option>';
}

$star = '<select name="star" class="inputtext">'.$star.'</select>';

$form   ->field("star")
        ->label( $this->lang->translate('Star').'*' )
        ->text( $star );

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value('บันทึก');

$form ->hr( '<input type="hidden" autocomplete="off" class="hiddenInput" value="'.$this->item['id'].'" name="id">' );

echo $form->html();