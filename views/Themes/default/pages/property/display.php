<?php

$topStatusStr = '';
foreach ($this->status as $key => $value) {
	$topStatusStr .= '<li class="clearfix"><i style="background-color:'.$value['color'].'"></i>'.$value['name'].'</li>';
}
$topStatusStr = '<ul class="uiList-propertyStatus">'.$topStatusStr.'</ul>';


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');
$form 	->field("q")
    	->label( 'Keyword' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$formTop = $form->html();


// 
$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("lease_type")
    	->label( 'Lease Type' )
        ->autocomplete('off')
        // ->addClass('inputtext')
        ->text( '<div class="group-btn" data-plugins="gender_selector">'.
        	'<div class="btn btn-blue active"><label class="radio hidden_elem"><input type="radio" name="service[gender]" value="0" checked></label><span>All</span></div>'.
        	'<div class="btn"><label class="radio hidden_elem"><input type="radio" name="service[gender]" value="1"></label><span>Rent</span></div>'.
        	'<div class="btn"><label class="radio hidden_elem"><input type="radio" name="service[gender]" value="2"></label><span>Sale</span></div>'.
        '</div>' );

$form 	->field("type")
    	->label( 'Type' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( array() )
        ->value('');

$form 	->field("zone")
    	->label( 'Zone' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( array() )
        ->value('');


$form 	->field('price')
    	->label( 'Price <i class="icon-question-circle" title="k=000 || 5k=5,000 || 30k=30,000
"></i>' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'rangeSlider')
        ->attr('data-options', $this->fn->stringify( array(
			'type'=>"double",
			'grid'=> true,
			'min'=> 10,
    		'max'=> 1000,
	    	'from'=> 60,
	   		'to'=> 350,
	   		'prefix'=> "฿",
	   		'postfix'=> "k",
	   		// 'step'=> 0.1,
	   		// 'values_separator'=> " → "
		) ) )
        ->value('');


$form 	->field("bedroom")
    	->label( 'Bedroom' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'rangeSlider')
        ->attr('data-options', $this->fn->stringify( array(
			'type'=>"double",
			'min'=> 1,
    		'max'=> 6,
		) ) )
        ->value('');

$plus = array();
$plus[] = array('id'=>'1','name'=>'1 (Study or Bed)');
$plus[] = array('id'=>'2','name'=>'2');
$plus[] = array('id'=>'3','name'=>'3');

$form 	->field("plus")
    	->label( 'Plus' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $plus )
        ->value('');


$pets = array();
$pets[] = array('id'=>'1','name'=>'Dog');
$pets[] = array('id'=>'2','name'=>'Cat');

$form 	->field("pets")
    	->label( 'Pets' )
    	->addClass('inputtext')
        ->autocomplete('off')
        ->select( $pets )
        ->value('');


$nationality = array();
$nationality[] = array('id'=>'1','name'=>'Indian');
$nationality[] = array('id'=>'2','name'=>'Chinese');
$nationality[] = array('id'=>'2','name'=>'Hungary');
$nationality[] = array('id'=>'2','name'=>'Arab');
$nationality[] = array('id'=>'2','name'=>'African');
$nationality[] = array('id'=>'2','name'=>'Israiel');
$nationality[] = array('id'=>'2','name'=>'Middle East');
$nationality[] = array('id'=>'2','name'=>'UN');
$nationality[] = array('id'=>'2','name'=>'Asia');

$form 	->field("nationality")
    	->label( 'Nationality' )
    	->addClass('inputtext')
        ->autocomplete('off')
        ->select( $nationality )
        ->value('');



$form 	->field('landarea')
    	->label( 'Land Area' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'rangeSlider')
        ->attr('data-options', $this->fn->stringify( array(
			'type'=>"double",
			'grid'=> true,
			'min'=> 10,
    		'max'=> 2500,
	    	// 'from'=> 60,
	   		// 'to'=> 350,
	   		'postfix'=> "sqm",
		) ) )
        ->value('');

$form 	->field('livingarea')
    	->label( 'Living Area' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'rangeSlider')
        ->attr('data-options', $this->fn->stringify( array(
			'type'=>"double",
			'grid'=> true,
			'min'=> 10,
    		'max'=> 2500,
	    	// 'from'=> 60,
	   		// 'to'=> 350,
	   		'postfix'=> "sqm",
		) ) )
        ->value('');

/*$form ->hr('<div class="clearfix">'.

	'<div class="lfloat">'.
		'<label class="checkbox"><input type="checkbox" name="show"><span class="mls">Full Results <i class="icon-question-circle" title="Show: Not rent, Sold, Secret, Wrong info, Not Approve, Blacklist"></i></span></label>'.
	'</div>'.
	'<div class="rfloat">
		<button type="submit" class="btn btn-primary btn-large btn-search"><span class="mrs">Search</span><i class="icon-arrow-right"></i></button>
	</div>'.

	'</div>');*/

$formSearch = $form->html();

?><div id="mainContainer" class="clearfix pageProperty" data-plugins="main">
	
	<!-- <div role="topbar" id="toolbar"></div> -->

	<div role="left" id="propertySearch" class="pagePropertyLeft" data-width="300">
		
		<div role="leftHeader">
			<div class="pam">
				<h3>Search Property</h3>
				<?=$formTop?>
			</div>
		</div>
		<div role="leftContent">
			<div class="phm pbl">
				<?=$formSearch?>
			</div>
		</div>
		
		<footer role="leftFooter" style="border-top:1px solid #ccc">
			<div class="pam clearfix">
				<div class="lfloat">
					<label class="checkbox"><input type="checkbox" name="show"><span class="mls">Full Results <i class="icon-question-circle" title="Show: Not rent, Sold, Secret, Wrong info, Not Approve, Blacklist"></i></span></label>
				</div>
				<div class="rfloat">
					<button type="submit" class="btn btn-primary btn-large btn-search"><span class="mrs">Search</span><i class="icon-arrow-right"></i></button>
				</div>
			</div>
		</footer>
		
	</div>

 
	<div role="content">
		
		<div role="tollbar">
			<div class="pvm phl fwb clearfix">
				<div class="lfloat">Results 11,226</div>

				<div class="rfloat">

					<?=''; //$topStatusStr; ?>
				</div>
			</div>
		</div>
		<div role="main">

			<div class="phl">

			

			<div class="property-lists-wrap">
				<div class="property-lists" data-ref="listbox"></div>

				<?php include 'layouts/loader.php'; ?>
			</div>
			</div>
		</div>
	</div>

</div>