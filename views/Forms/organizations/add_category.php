<?php

$title = 'Add Category';

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-emp');

$form   ->field("category_name")
        ->label($this->lang->translate('Category Name'))
        ->addClass('inputtext')
        ->placeholder('Add Name')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );


$form   ->field("category_description")
        ->label($this->lang->translate('Description'))
        ->type('textarea')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'autosize')
        ->placeholder('Add Description')
        ->value( !empty($this->item['description'])? $this->fn->q('text')->textarea($this->item['description']):'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'organizations/save_category"></form>';

# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= $title;
}

$submit = '';
if( isset($_REQUEST['callback']) ){
    $submit = ' role="submit"';
    $arr['hiddenInput'][] = array('name'=>'callback','value'=>$_REQUEST['callback']);
}

# fotter: button
$arr['button'] = '<button'.$submit.' type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);