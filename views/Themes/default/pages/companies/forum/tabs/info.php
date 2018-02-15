<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
        // ->style('horizontal')
	->addClass('form-insert  forum-tap-from');

$form   ->hr('<div class="forum-profile-head">Info:</div>');

$form   ->field("type_id")
        ->label( Translate::Val('Type') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->type );


$form   ->field("zone_id")
        ->label( Translate::Val('Zone') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->zone );

$form   ->field("soi")
        ->label(Translate::Val(' Soi') )
        ->autocomplete('off')
        ->addClass('inputtext');

$form   ->field("property_name")
        ->label(Translate::Val('Name') )
        ->autocomplete('off')
        ->addClass('inputtext input-large')
        ->placeholder('')
        // ->attr('autofocus', '1')
        ->value('Lake Side Villa 1');


$form   ->field("Facilities")
        ->label(Translate::Val('Facilities Service') )
        ->text( '<ul class="uiList uiListMinCell-4"><li><label class="checkbox"><input type="checkbox"><span class="mls">Sauna</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Pool</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Tennis Court</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Parking</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Squash</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Gym</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Shuttle bus</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Security</span></label></li><li><label class="checkbox"><input type="checkbox"><span class="mls">Children Play Area</span></label></li><ul><div class="notification"></div></ul></ul>' );

$form   ->hr('<div class="forum-profile-head">Locations:</div>');


$form   ->field("address")
        ->type('textarea')
        ->label(Translate::Val('Address') )
        ->autocomplete('off')
        ->attr('data-plugins', 'autosize')
        ->addClass('inputtext');

$form   ->field("goolge_map")
        ->type('textarea')
        ->label(Translate::Val('Goolge Map') )
        ->autocomplete('off')
        ->attr('data-plugins', 'autosize')
        ->addClass('inputtext');


$form   ->hr('<div class="forum-profile-head">Near:</div>');
$form   ->field("near_transport")
        ->label(Translate::Val('Near Transport') )
        ->autocomplete('off')
        ->addClass('inputtext');

$form   ->field("near_things")
        ->label(Translate::Val('Near Things') )
        ->autocomplete('off')
        ->addClass('inputtext');

$LeftForm = $form->html();

echo $LeftForm;

/*$form = new Form();
$form = $form->create()
        // set From
        ->elem('div')
        ->addClass('form-insert forum-tap-from');

$form   ->field("near_transport")
        ->label(Translate::Val('Sale Point') )
        ->autocomplete('off')
        ->addClass('inputtext');

$form   ->hr('<div class="forum-profile-head">Map:</div>');
$form   ->field("near_things")
        ->label(Translate::Val('Near Things') )
        ->autocomplete('off')
        ->addClass('inputtext');

$rigthForm = $form->html();*/

/*echo '<div style="position: relative;">'.

        '<div style="width: 400px">'.$LeftForm.'</div>'.
        '<div style="position: absolute;top: 0;left: 500px;">'.$rigthForm.'</div>'.

'</div>';*/