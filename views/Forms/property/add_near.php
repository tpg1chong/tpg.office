<?php

# title
$title = $this->lang->translate('Near Type');
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

$form   ->field("near_type_id")
        ->label($this->lang->translate('Near Shopping Malls') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $this->type )
        ->value( '' );

$form 	->field("near_name")
    	->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("near_keyword")
    	->label($this->lang->translate('Keyword').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['keyword'])? $this->item['keyword']:'' );


# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'property/save_near"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);