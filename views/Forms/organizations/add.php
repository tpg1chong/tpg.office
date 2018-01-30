<?php

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert has-image');


# Image 

$options = array(
    'url' => URL.'media/set',
    'data' => array(
        'album_name'=>'my', 
        'minimize'=> array(128,128),
        'has_quad'=> true,
     ),
    'autosize' => true,
    'show'=>'quad_url',
    'remove' => true
);

if( !empty($this->item['id']) ){
    $options['setdata_url'] = URL.'organizations/setdata/'.$this->item['id'].'/agency_image_id/?has_image_remove';
}

$image_url = '';
$hasfile = false;
if( !empty($this->item['image_url']) ){
    $hasfile = true;
    $image_url = '<img class="img" src="'.$this->item['image_url'].'?rand='.rand(100, 1).'">';

    $options['remove_url'] = URL.'media/del/'.$this->item['image_id'];
    
}

$picture_box = '<div class="anchor"><div class="clearfix">'.

        '<div class="ProfileImageComponent lfloat size80 radius mrm is-upload'.($hasfile ? ' has-file':' has-empty').'" data-plugins="uploadProfile" data-options="'.$this->fn->stringify( $options ).'">'.
            '<div class="ProfileImageComponent_image">'.$image_url.'</div>'.
            '<div class="ProfileImageComponent_overlay"><i class="icon-camera"></i></div>'.
            '<div class="ProfileImageComponent_empty"><i class="icon-camera"></i></div>'.
            '<div class="ProfileImageComponent_uploader"><div class="loader-spin-wrap"><div class="loader-spin"></div></div></div>'.
            '<button type="button" class="ProfileImageComponent_remove"><i class="icon-remove"></i></button>'.
        '</div>'.
    '</div>'.

'</div>';

$form   ->field("image")
        ->text( $picture_box );

# name
$form   ->field("agency_name")
        ->label($this->lang->translate('Name'))
        ->addClass('inputtext')
        ->placeholder('Add Name')
        ->attr('autoselect', 1)
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form->hr('<div class="clearfix"></div>');

$form   ->field("agency_country_id")
        ->label($this->lang->translate('Country'))
        ->addClass('inputtext')
        ->select( $this->country )
        ->value( !empty($this->item['country_id'])? $this->item['country_id']:'' );


$this->category[] = array('id'=>'__add', 'name' => '--- Add New ---');
$form   ->field("agency_category_id")
        ->label($this->lang->translate('Category'))
        ->addClass('inputtext')
        ->attr('data-plugins', 'addselect')
        ->attr('data-options', $this->fn->stringify(array('url'=>URL.'organizations/add_category')))
        ->select( $this->category, 'id', 'name', false )
        ->value( !empty($this->item['category_id'])? $this->item['category_id']:1 );


$form   ->field("agency_address")
        // ->name('company[address]')
        ->label($this->lang->translate('Address'))
        ->addClass('inputtext')
        ->placeholder('Add Address')
        ->type('textarea')
        ->value( !empty($this->item['address'])? $this->item['address']:'' );

$form   ->field("agency_phone")
        ->label($this->lang->translate('Phone').'')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add Phone')
        ->value( !empty($this->item['phone'])? $this->item['phone']:'' );

$form   ->field("agency_email")
        ->label($this->lang->translate('Email'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add Email')
        ->value( !empty($this->item['email'])? $this->item['email']:'' );

$form   ->field("agency_description")
        ->label($this->lang->translate('Description'))
        ->type('textarea')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'autosize')
        ->placeholder('Add Description')
        ->value( !empty($this->item['description'])? $this->fn->q('text')->textarea($this->item['description']):'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'organizations/save"></form>';

# body
$arr['body'] = $form->html();



if( !empty($this->item) ){
    $title = 'Edit Organization';
}
else{

    $title = 'Add Organization';
}

# title
if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= $title;
}

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

$arr['width'] = 550;

echo json_encode($arr);