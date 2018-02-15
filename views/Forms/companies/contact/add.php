<?php

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-emp');



/*$statusSelector = array();
$statusSelector[] = array('name'=>'status', 'value'=>0, 'label'=>'Working');
$statusSelector[] = array('name'=>'status', 'value'=>1, 'label'=>'Resigned');
$statusSelector[] = array('name'=>'status', 'value'=>2, 'label'=>'Rotated');

$form 	->field("status")
    	->label( 'Status' )
        // ->autocomplete('off')
        // ->addClass('inputtext')
        ->text( $this->fn->q('form')->radioButtonGroup( $statusSelector, 0, 'status' ) );*/


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

$form   ->field("note")
		->type( 'textarea' )
        ->label( Translate::Val('Note') )
        ->addClass('inputtext')
        ->placeholder('Add note')
        ->value( !empty($this->item['position'])? $this->item['position']:'' );




# body
$arr['body'] = $form->html();


# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'companies/save"></form>';

$title = 'Create company contact';
$arr['title']= $title;

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-success btn-submit"><span class="btn-text">Create contact</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';


echo json_encode($arr);
