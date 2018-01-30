<?php



$form = new Form();
$form = $form->create()->elem('div')->style('horizontal')->addClass('form-mini form-insert');

$form 	->field("type")
    	->label( 'Type:')
    	->addClass('inputtext')
    	->select( array() )
        ->value( '' );

$form 	->field("zone")
    	->label( 'Zone:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( array() )
        ->value( '' );

$form 	->field("main_soi")
    	->label( 'Main Soi:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('-');

/*$form 	->field("building_logo")
    	->label( 'Logo:')
        ->text('<div class="avatar"></div>');*/

$form 	->field("building_name")
    	->label( 'Name:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add Building Name')
        ->value( !empty($this->item['name'])? $this->item['name']:'Millennium Residence' );

$form 	->field("unit")
    	->label( 'Total Unit:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('-');

$form 	->field("floor")
		// ->parent()->addClass('')
    	->label( 'Total Floor:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('-');

$form 	->field("built")
		// ->parent()->addClass('')
    	->label( 'Built:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( array() )
        ->value( '' );


$form 	->field("sale_point")
		// ->parent()->addClass('')
    	->label( 'Sale Point:')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( '' );


echo $form->html();