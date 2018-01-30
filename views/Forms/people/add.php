<?php

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert has-image form-people');


#Image 
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
    $options['setdata_url'] = URL.'customers/setdata/'.$this->item['id'].'/people_image_id/?has_image_remove';
}

$image_url = '';
$hasfile = false;
if( !empty($this->item['image_url']) ){
    $hasfile = true;
    $image_url = '<img class="img" src="'.$this->item['image_url'].'?rand='.rand(100, 1).'">';

    $options['remove_url'] = URL.'media/del/'.$this->item['image_id'];
}

$picture_box = '<div class="anchor"><div class="clearfix">'.

        '<div class="ProfileImageComponent size80 radius lfloat mrm is-upload'.($hasfile ? ' has-file':' has-empty').'" data-plugins="uploadProfile" data-options="'.$this->fn->stringify( $options ).'">'.
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


# Name
$form   ->field("people_name")
        ->label($this->lang->translate('Name'))
        ->addClass('inputtext')
        ->placeholder($this->lang->translate('Name'))
        ->autocomplete('off')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form->hr('<div class="clearfix"></div>');
$form   ->field("people_agency_id")
        ->label($this->lang->translate('Organization'))
        ->addClass('inputtext')
        ->placeholder('Add company')
        ->autocomplete('off')
        ->attr('data-plugins', 'addinput')
        ->attr('data-options', $this->fn->stringify(array(
            'url'=>URL.'organizations/invite',
            'id'=> !empty($this->item['agency_id'])? $this->item['agency_id']:''
        )))
        // ->select( $this->companies )
        ->value( !empty($this->item['agency_name'])? $this->item['agency_name']:'' );


$this->position[] = array('id'=>'__add', 'name' => '--- Add New ---');
$form   ->field("people_position_id")
        ->label($this->lang->translate('Position'))
        ->addClass('inputtext')
        ->placeholder('Add position')
        ->attr('data-plugins', 'addselect')
        ->attr('data-options', $this->fn->stringify(array('url'=>URL.'people/add_position')))
        ->select( $this->position )
        ->value( !empty($this->item['position_id'])? $this->item['position_id']:'' );

$form->hr('<div class="clearfix"></div>');
$birthday = array();
if( !empty($this->item['birthday']) ){
    if( $this->item['birthday'] != '0000-00-00' ){
        $birthday = $this->item;
    }
}
$form   ->field("birthday")
        ->label($this->lang->translate('Birthday'))
        ->text( $this->fn->q('form')->birthday( $birthday, array('field_first_name'=>'birthday') ) );

/*
$form   ->field("people_address")
        ->label($this->lang->translate('Address'))
        ->addClass('inputtext')
        ->type('textarea');*/


$form   ->field("people_phone")
        ->label($this->lang->translate('Phone'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add phone')
        ->value( !empty($this->item['phone'])? $this->item['phone']:'' );

$form   ->field("people_email")
        ->label($this->lang->translate('Email'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add email')
        ->value( !empty($this->item['email'])? $this->item['email']:'' );

$form   ->field("people_line")
        ->label('Line ID')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add line ID')
        ->value( !empty($this->item['line'])? $this->item['line']:'' );

$form   ->field("people_bio")
        ->label($this->lang->translate('Bio'))
        ->type('textarea')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'autosize')
        ->placeholder('')
        ->value( !empty($this->item['bio'])? $this->fn->q('text')->textarea($this->item['bio']):'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'people/save"></form>';

# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ){
    $arr['title']= 'Edit People';
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= 'Add People';
}

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

$arr['width'] = 550;

echo json_encode($arr);