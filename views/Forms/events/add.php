<?php

$this->has_invite = isset($_REQUEST['invite']) ? $_REQUEST['invite']: 1;
if( !empty($this->item) ){
	$this->has_invite = $this->item['has_invite'];
}

if( !empty($_REQUEST['obj_type']) && !empty($_REQUEST['obj_id']) && !isset($_REQUEST['invite']) ) $this->has_invite = 0;


$startDate = '';
if( !empty($this->item['start']) ){
	$startDate = $this->item['start'];
}
elseif( isset($_REQUEST['date']) ){
	$startDate = $_REQUEST['date'];
}

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert');

$form   ->field("event_title")
        ->label('Title')
        ->addClass('inputtext')
        ->placeholder('Add title')
        ->attr( !empty($this->item['title']) ? 'autoselect':'autofocus' , 1)
        ->autocomplete('off')
        ->value( !empty($this->item['title']) ? $this->item['title']:'' );



$form 	->field("event_start")
		// ->label('วันที่')
		->text( '<div style="min-height: 100px;" data-plugins="eventdate" data-options="'.$this->fn->stringify( array(

			'lang' => $this->lang->getCode(),
			'startDate' => $startDate,
			'endDate' => !empty($this->item['end']) ? $this->item['end']:'',
			'allday' => !empty($this->item['allday']) ? $this->item['allday']:true,
		) ).'"></div>' );

$form 	->field("event_location")
		->label('Location')
		->addClass('inputtext')
		->placeholder('Add location')
		->autocomplete('off')
		->value( !empty($this->item['location']) ? $this->item['location']:'' );


$form 	->field("event_color_code")
		->label('Color')
		->addClass('inputtext')
		->attr('data-plugins', 'colors')
		->placeholder('')
		->autocomplete('off')
		->value( !empty($this->item['color_code']) ? $this->item['color_code']:'' );

$form 	->field("event_text")
		->label('Descripion')
		->addClass('inputtext')
		->type('textarea')
		->placeholder('Add descripion')
		->autocomplete('off')
		->value( !empty($this->item['text']) ? $this->item['text']:'' );

$formDetail = $form->html();

if( $this->has_invite ){

	$form = new Form();
	$form = $form->create()->elem('div')->addClass('form-insert');
	$form 	->field("event_invite")
			->label('Guests')
			->text( //'<div class="">'.

	'<div class="ui-invite-content">'.

	'<div class="ui-invite-header" ref="header">'.

			'<div ref="actions">'.
		'<div class="form-search"><input type="text" name="q" class="inputtext input-search" act="inputsearch" placeholder="Search.."><button type="button" class="btn-search"><i class="icon-search"></i></button></div>'.

		/*'<table class="table-search form-search" ref="actions"><tr>'.
			'<td></td>'.
			'<td class="td-input"></td>'.
			// '<td></td>'.
		'<tr></table>'.*/
		
		'<header class="ui-invite-listsbox-header clearfix">'.
			'<div class="lfloat ui-invite-actions">'.
				'<select class="inputtext" act="selector" name="objects">'.
					// '<option value="">ทั้งหมด</option>'.
					'<option selected value="employees">Thai Property Guide</option>'.
					'<option value="customers">Clients</option>'.
				'</select>'.
			'</div>'.
			'<div class="rfloat"><a class="js-selected-all">Choose all</a></div>'.
		'</header>'.

		'</div>'.
	'</div>'.

	'<div class="ui-invite-listsbox has-loading">'.
		'<ul class="ui-list ui-list-user ui-list-checked" ref="listsbox"></ul>'.
		'<a class="ui-more btn">Load move</a>'.
		'<div class="ui-alert">'.
			'<div class="ui-alert-loader">
				<div class="ui-alert-loader-icon loader-spin-wrap"><div class="loader-spin"></div></div>
				<div class="ui-alert-loader-text">Loading...</div> 
			</div>

			<div class="ui-alert-error">
				<div class="ui-alert-error-icon"><i class="icon-exclamation-triangle"></i></div>
				<div class="ui-alert-error-text">Can not connect.</div> 
			</div>

			<div class="ui-alert-empty">
				<div class="ui-alert-empty-text">ไม่มีไฟล์ <a class="js-upload">เพิ่มไฟล์ใหม่</a></div> 
			</div>'.
		'</div>'.
	'</div>'.	

	'</div>'.
	// end: ui-invite-content

	'<div class="ui-invite-selected">'.
		'<header class="ui-invite-selected-header clearfix">'.
			'<div class="lfloat">Selected (<span class="js-selectedCountVal">0</span>)</div>'.
		'</header>'.
		'<div class="ui-invite-selected-listsbox">'.
			'<ul class="ui-list ui-list-token ui-list-horizontal" ref="tokenbox"></ul>'.
		'</div>'.
	'</div>'.

	'');

	$formInvite = $form->html();
	$optionsInvite = array(
		'url' => URL.'events/invite',
	);

	if( !empty( $this->item['invite'] ) ){
		$optionsInvite['invite'] = $this->item['invite'];
	}

	$arr['hiddenInput'][] = array('name'=>'has_invite','value'=>1);
	

# body
$arr['body'] = '<div class="table-plan-wrap"><div class="table-plan">'.
	'<div class="td-plan-detail">'. $formDetail .'</div>'.
	'<div class="td-plan-invite ui-invite" data-plugins="invite" data-options="'.$this->fn->stringify($optionsInvite).'">'.$formInvite.'</div>'.
'</div></div>';
$arr['width'] = 950; //-180  770 - 400 370

}
else{
	$arr['body'] = $formDetail;

	$arr['hiddenInput'][] = array('name'=>'has_invite','value'=>0);

	if( !empty($this->item) ){

	}
	else{
		$obj_type = $_REQUEST['obj_type'];
		$obj_id = $_REQUEST['obj_id'];
		if( is_array($obj_type) ){
			for ($i=0; $i < count($obj_type); $i++) { 
				$arr['hiddenInput'][] = array('name'=>'invite[type][]','value'=>$obj_type[$i]);
				$arr['hiddenInput'][] = array('name'=>'invite[id][]','value'=>$obj_id[$i]);
			}
		}
		else{
			$arr['hiddenInput'][] = array('name'=>'invite[type][]','value'=>$obj_type);
			$arr['hiddenInput'][] = array('name'=>'invite[id][]','value'=>$obj_id);

		}
	}
}

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'events/save"></form>';

$arr['button'] = '';
if( !empty($this->item) ){
    $arr['title']= "Edit event";
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);

}
else{
    $arr['title']= "Create event";
}

$has_callback = '';
if( isset( $_REQUEST['callback'] ) ){
    $arr['hiddenInput'][] = array('name'=>'callback','value'=>$_REQUEST['callback']);
    $has_callback = ' role="submit"';
}

$arr['button'] .= '<button type="submit"'.$has_callback.' class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';

echo json_encode($arr);