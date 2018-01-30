<?php
$gallery = '';

$input = '<input class="inputtext" type="file" name="picture[]" multiple="multiple">';

$form = new Form();
$form = $form->create()
	// set From
	// ->elem('div')
	->url( URL.'property/update/'.$this->section )
    ->method('post')
	->addClass('js-submit-form');

$form 	->field("gallery")
		->label('Upload')
		->text( $input );

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value('บันทึก');

$form 	->hr( '<input type="hidden" autocomplete="off" class="hiddenInput" value="'.$this->item['id'].'" name="id">' );

echo $form->html();