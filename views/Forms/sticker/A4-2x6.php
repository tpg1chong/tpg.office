<?php

$li = '';
$n = 0;
// for ($i=0; $i < 10; $i++) { 
foreach ($this->results as $key => $value) {
	$n++;

	$li .= '<li data-id="'.$value['id'].'">'.

		'<input type="hidden" id="id" name="id[]" value="'.$value['id'].'">'.
		'<input type="hidden" id="is_hide" name="is_hide[]">'.
		// '<input name="company_id[]" value="2191" type="hidden">'.

	'<div class="outer">'.
		'<div class="control">'.
			'<a type="button" class="hide" data-actions="hide">Hide</a>'.
			'<a type="button" class="undo" data-actions="hide">Undo</a>'.
		'</div>'.

		'<div class="number">'.$n.'</div>'.

		'<div class="actions clearfix">'.
			// '<a class="delete" data-actions="delete">Delete</a>'.
			'<a class="rfloat save" data-actions="save">save...</a>'.
		'</div>'.

		'<div class="inner">'.
			'<label class="textarea"><textarea class="title" data-plugins="autosize" name="title[]" rows="1">'.trim($value['title']).'</textarea></label>'.
			// '<label class="textarea"><textarea class="context" data-plugins="autosize" name="context[]" rows="1">'.trim($value['company_name']).'</textarea></label>'.
			'<label class="textarea"><textarea class="text" data-plugins="autosize" name="text[]" rows="1">'.trim($value['text']).'</textarea></label>'.
		'</div>'.

	'</div></li>';
}

$ul = '<ul class="ui-list-sticker clearfix">'.$li.'</ul>';

#form 
$arr['form']= '<form class="model-formSticker js-print" data-plugins="setsticker" target="_print" action="'.URL.'printer/sticker/A4-2x6" method="post"></form>';

# title
$arr['title']= 'Set Sticker';

#body
$arr['body']= '<div class="model-formSticker_body">'.$ul.'</div>';

# fotter: button
$arr['button'] = '<button role="submit" type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Print</span></button>';
$arr['bottom_msg'] = '<a role="dialog-close" class="btn"><span class="btn-text">Cancel</span></a>';

$arr['width'] = 1024;

echo json_encode($arr);