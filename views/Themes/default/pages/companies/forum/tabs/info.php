<?php

$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');
/*->style('horizontal')*/

$form   ->field("headOffice")
        // ->label( 'Head Office' )
        ->addClass('inputtext')
        ->text( '<label class="checkbox"><input type="checkbox" name="" value="" autocomplete="off"><span class="mls">Head Office</span></label>' );

$form   ->field("country")
        ->type( 'select' )
        ->label( 'Country of Origin' )
        ->addClass('inputtext')
        ->select( $this->countryList );

$form   ->field("name")
        ->label( Translate::Val('Name') )
        ->addClass('inputtext')
        ->placeholder('Add name')
        ->attr('autoselect', 1);


$form   ->field("bio")
        ->type( 'textarea' )
        ->label( Translate::Val('Company Profile') )
        ->addClass('inputtext')
        ->placeholder('Add address');

$form   ->field("building")
        ->label( Translate::Val('Building') )
        ->addClass('inputtext')
        ->placeholder('Add building');


$form   ->hr('<div class="ui-hr-text"><span>Contact</span></div>');

$form   ->field("location")
        ->type( 'textarea' )
        ->label( Translate::Val('Office Address') )
        ->addClass('inputtext')
        ->placeholder('Add address');

$form   ->field("zone")
        ->type( 'select' )
        ->label( 'Zone' )
        ->addClass('inputtext')
        ->select( $this->countryList );

$form   ->field("email")
        ->label( Translate::Val('Email') )
        ->text( $this->fn->q('form')->contacts('email') );

$form   ->field("phone")
        ->label( Translate::Val('Phone') )
        ->text( $this->fn->q('form')->contacts('phone') );

$form   ->field("fax")
        ->label( Translate::Val('Fax') )
        ->addClass('inputtext')
        ->placeholder('Add fax');

$form   ->field("social")
        ->label( Translate::Val('Social') )
        ->text( $this->fn->q('form')->contacts('social') );


$form   ->field("website")
        ->label( Translate::Val('Website') )
        ->addClass('inputtext')
        ->placeholder('Add website');


$form   ->field("headOfficeAddress")
        ->type( 'textarea' )
        ->label( Translate::Val('Head Office Address') )
        ->addClass('inputtext')
        ->placeholder('Add address');



// $form   ->hr('<div class="ui-hr-text"><span>Office</span></div>');


$form   ->hr('<div class="ui-hr-text"><span>Industry</span></div>');
$form   ->field("business")
        ->type( 'select' )
        ->label( 'Industry' )
        ->addClass('inputtext')
        ->select( $this->businessList );

$form   ->field("business_")
        ->type( 'textarea' )
        ->label( Translate::Val('Industry Address') )
        ->addClass('inputtext')
        ->placeholder('Add website');



$form   ->hr('<div class="ui-hr-text"><span>Ambassador</span></div>');

$form   ->field("ambassadorNew")
        ->label( Translate::Val('Ambassador New') )
        ->addClass('inputtext')
        ->placeholder('');

$form   ->field("ambassadorOld")
        ->label( Translate::Val('Ambassador Old') )
        ->addClass('inputtext')
        ->placeholder('');

/*$form   ->field("ambassador")
        ->text( '<label class="checkbox"><input type="checkbox" name="" value="" autocomplete="off"><span class="mls">Ambassador</span></label>' );*/


$form   ->hr('<div class="ui-hr-text"><span>Source</span></div>');
$form   ->field("source")
        ->type( 'select' )
        ->label( 'Source' )
        ->addClass('inputtext')
        ->select( $this->businessList );


$form   ->field("sourceNote")
        ->type( 'textarea' )
        ->label( Translate::Val('Note') )
        ->addClass('inputtext')
        ->placeholder('Source note');

$formLeft = $form->html();



$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');


$this->contactType = array();
$this->contactType[] = array('id'=>'','name'=>'Company');
$this->contactType[] = array('id'=>'','name'=>'Personal');


$form   ->field("contact_type")
        ->label( 'Contact Type' )
        ->addClass('inputtext')
        ->text( $this->fn->q('form')->checkboxList( $this->contactType ) );


$form   ->field("status")
        ->type( 'select' )
        ->label( 'Status' )
        ->addClass('inputtext')
        ->select( $this->statusList );


$form   ->field("budget")
        ->label( 'Budget' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'rangeSlider')
        ->attr('data-options', $this->fn->stringify( array(
            'type'=>"double",
            'grid'=> true,
            'min'=> 10,
            'max'=> 1500,
            'from'=> 60,
            'to'=> 350,
            'prefix'=> "฿",
            'postfix'=> "k",
            // 'step'=> 0.1,
            // 'values_separator'=> " → "
        ) ) );


$form   ->field("requirement")
        ->label( 'Requirement' )
        ->addClass('inputtext')
        ->text( $this->fn->q('form')->checkboxList( $this->requirementList ) );


$form   ->hr('<div class="ui-hr-text"><span>Note</span></div>');
$form   ->field("useRelocation")
        // ->label( 'Use Relocation' )
        ->text( '<div>'.
            '<label class="checkbox"><input type="checkbox" name="" value="" autocomplete="off"><span class="mls">Use Relocation</span></label>'.
            '<div class="mts"><input id="building" class="inputtext" placeholder="" type="text" name="building"></div>'.
        '</div>' );

$form   ->field("giveGift")
        // ->label( 'Use Relocation' )
        ->text( '<div>'.
            '<label class="checkbox"><input type="checkbox" name="" value="" autocomplete="off"><span class="mls">Can\'t Give Gift</span></label>'.
        '</div>' );


# body
echo '<div style="position:relative;">'.
    '<div class="pal" style="width:550px;vertical-align:top;">'. $formLeft.'</div>'.
    '<div class="pal" style="position:absolute;background-color:#eee;left:550px;top:0;bottom:0;right:0;overflow-y:auto">'.$form->html().'</div>'.
'<div>';