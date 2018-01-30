<?php

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
    $options['setdata_url'] = URL.'customers/setdata/'.$this->item['id'].'/cus_image_id/?has_image_remove';
}

$image_url = '';
$hasfile = false;
if( !empty($this->item['image_url']) ){
    $hasfile = true;
    $image_url = '<img class="img" src="'.$this->item['image_url'].'?rand='.rand(100, 1).'">';

    $options['remove_url'] = URL.'media/del/'.$this->item['image_id'];
}

$picture_box = '<div class="anchor"><div class="clearfix">'.

        '<div class="ProfileImageComponent lfloat mrm is-upload'.($hasfile ? ' has-file':' has-empty').'" data-plugins="uploadProfile" data-options="'.$this->fn->stringify( $options ).'">'.
            '<div class="ProfileImageComponent_image">'.$image_url.'</div>'.
            '<div class="ProfileImageComponent_overlay"><i class="icon-camera"></i></div>'.
            '<div class="ProfileImageComponent_empty"><i class="icon-camera"></i></div>'.
            '<div class="ProfileImageComponent_uploader"><div class="loader-spin-wrap"><div class="loader-spin"></div></div></div>'.
            '<button type="button" class="ProfileImageComponent_remove"><i class="icon-remove"></i></button>'.
        '</div>'.
    '</div>'.

'</div>';

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-profile form-people');


$form   ->field("image")
        ->text( $picture_box );

$form   ->field("cus_prefix_name")
        ->label($this->lang->translate('Name'))
        ->addClass('inputtext')
        ->placeholder($this->lang->translate('Prefix Name'))
        ->autocomplete('off')
        ->attr('data-plugins', 'addinput')
        ->attr('data-options', $this->fn->stringify(array(
            'options'=> $this->prefixName,
            'id'=> !empty($this->item['prefix_name'])? $this->item['prefix_name']:''
        )))
        ->value( !empty($this->item['prefix_name'])? $this->item['prefix_name']:'' );

$form   ->field("cus_first_name")
        ->addClass('inputtext')
        ->placeholder($this->lang->translate('First Name'))
        ->autocomplete('off')
        ->value( !empty($this->item['first_name'])? $this->item['first_name']:'' );

$form   ->field("cus_last_name")
        ->addClass('inputtext')
        ->placeholder($this->lang->translate('Last Name'))
        ->autocomplete('off')
        ->value( !empty($this->item['last_name'])? $this->item['last_name']:'' );

$form   ->field("cus_nickname")
        ->addClass('inputtext')
        ->placeholder($this->lang->translate('Nickname'))
        ->autocomplete('off')
        ->value( !empty($this->item['nickname'])? $this->item['nickname']:'' );


$form->hr('<div class="clearfix fwb fcg fsm">'.$this->lang->translate('Places').'</div>');


$form   ->field("people_category_id")
        ->addClass('inputtext')
        ->autocomplete('off')
        ->attr('data-plugins', 'addinput')
        ->attr('data-options', $this->fn->stringify(array(
            'url'=>URL.'companies/invite',
            'id'=> !empty($this->item['company_id'])? $this->item['company_id']:''
        )))
        // ->select( $this->companies )
        ->value( !empty($this->item['company_name'])? $this->item['company_name']:'' );

$form   ->field("cus_places_id")
        ->label($this->lang->translate('Places'))
        ->addClass('inputtext')
        ->placeholder('Add company')
        ->autocomplete('off')
        ->attr('data-plugins', 'addinput')
        ->attr('data-options', $this->fn->stringify(array(
            'url'=>URL.'companies/invite',
            'id'=> !empty($this->item['company_id'])? $this->item['company_id']:''
        )))
        // ->select( $this->companies )
        ->value( !empty($this->item['company_name'])? $this->item['company_name']:'' );

/*
*/

$birthday = array();
if( !empty($this->item['birthday']) ){
    if( $this->item['birthday'] != '0000-00-00' ){
        $birthday = $this->item;
    }
}

$form   ->field("birthday")
        ->label($this->lang->translate('Birthday'))
        ->text( $this->fn->q('form')->birthday( $birthday, array('field_first_name'=>'birthday') ) );

$form   ->field("cus_phone")
        ->label($this->lang->translate('Phone'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add phone')
        ->value( !empty($this->item['phone'])? $this->item['phone']:'' );

$form   ->field("cus_email")
        ->label($this->lang->translate('Email'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add email')
        ->value( !empty($this->item['email'])? $this->item['email']:'' );

$form   ->field("cus_line_id")
        ->label('Line ID')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add line ID')
        ->value( !empty($this->item['line_id'])? $this->item['line_id']:'' );

$form   ->field("cus_bio")
        ->label($this->lang->translate('Bio'))
        ->type('textarea')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'autosize')
        ->placeholder('')
        ->value( !empty($this->item['bio'])? $this->fn->q('text')->textarea($this->item['bio']):'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'customers/save"></form>';

# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ){
    $arr['title']= 'Edit Customers';
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= 'New People';
}

if( isset($_REQUEST['status']) ){
    $arr['hiddenInput'][] = array('name'=>'status','value'=>$_REQUEST['status']);
}

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

$arr['width'] = 550;

echo json_encode($arr);