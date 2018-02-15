<?php

$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form 	->field("Resource")
		->type( 'select' )
    	->label( 'Resource' )
    	->addClass('inputtext')
    	->select( $this->sourceList );


$form   ->field("name")
        ->label( Translate::Val('Name') )
        ->addClass('inputtext')
        ->placeholder('Add name')
        ->attr('autoselect', 1)
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("position")
        ->label( Translate::Val('Position') )
        ->addClass('inputtext')
        ->placeholder('Add position')
        ->value( !empty($this->item['position'])? $this->item['position']:'' );

$form   ->field("email")
		->label( Translate::Val('Email') )
		->text( $this->fn->q('form')->contacts('email') );

$form   ->field("phone")
		->label( Translate::Val('Phone') )
		->text( $this->fn->q('form')->contacts('phone') );

$form   ->field("social")
		->label( Translate::Val('Social') )
		->text( $this->fn->q('form')->contacts('social') );


$form   ->field("location")
		->type( 'textarea' )
        ->label( Translate::Val('Location') )
        ->addClass('inputtext')
        ->placeholder('Add address')
        ->value( !empty($this->item['address'])? $this->item['address']:'' );


$formLeft = $form->html();



$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$statusSelector = array();
$statusSelector[] = array('name'=>'status', 'value'=>0, 'label'=>'Working');
$statusSelector[] = array('name'=>'status', 'value'=>1, 'label'=>'Resigned');
$statusSelector[] = array('name'=>'status', 'value'=>2, 'label'=>'Rotated');

$form   ->field("status")
        ->label( 'Status' )
        ->text( $this->fn->q('form')->radioButtonGroup( $statusSelector, 0, 'status' ) );



# body
$arr['body'] = '<div style="position:relative;">'.
    '<div class="pal" style="width:450px;vertical-align:top;">'. $formLeft.'</div>'.
    '<div class="pal" style="position:absolute;background-color:#eee;left:450px;top:0;bottom:0;right:0;overflow-y:auto">'.$form->html().'</div>'.
'<div>';

# set form
$arr['form'] = '<form class="model-noPadding-body" method="post" action="'.URL. 'companies/save"></form>';


$title = 'Edit contact';
$arr['title']= $title;

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

$arr['width'] = 750;
echo json_encode($arr);
