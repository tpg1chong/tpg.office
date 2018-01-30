<?php



$form = new Form();
$form = $form->create()->elem('div')->addClass('form-mini form-insert');


$form 	->field("facilities")
    	->label( 'Facilities Service:')
        ->text('<ul class="uiList uiListMinCell-4">'.

        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Sauna</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Pool</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Tennis Court</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Parking</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Squash</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Gym</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Shuttle bus</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Security</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">Children Play Area</span></label></li>'.
        '<ul>');

$form 	->field("address")
    	->label( 'Address:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->type('textarea')
        ->attr('data-plugins', 'autosize')
        ->placeholder('-')
        ->value('Millennium Residence 114 Sukhumvit 20 Klongtoey Khet Klongtoey Bangkok 10110 Thailand.');

$form 	->field("map")
    	->label( 'Map:')
        /*->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('-')*/
        ->text('<div class="mapWrap"></div>');

$form 	->hr('<hr>');

$form 	->field("taxid")
    	->label( 'Tax ID:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('-');

$form 	->field("Internet")
    	->label( 'Internet:')
        ->text('<ul class="uiList uiListMinCell-4">'.

        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">True</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">TOT</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">3BB</span></label></li>'.
        	'<li><label class="checkbox"><input type="checkbox"><span class="mls">AIS</span></label></li>'.
        '<ul>');

echo $form->html();