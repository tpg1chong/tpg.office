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
        ->autocomplete('off')
        ->attr( !empty($this->item['title']) ? 'autoselect':'autofocus', 1)
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


$form 	->field("event_color")
		->label('Color')
		->addClass('inputtext')
		->attr('data-plugins', 'colors')
		->attr('data-options', Fn::stringify( array('colors'=>$this->colors ) ) )
		->placeholder('')
		->autocomplete('off')
		->value( !empty($this->item['color_code']) ? $this->item['color_code']:'' );


$formDetail = $form->html();


$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form 	->field("event_text")
		->label('Descripion')
		->addClass('inputtext')
		->type('textarea')
		->placeholder('Add descripion')
		->autocomplete('off')
		->attr('style', 'min-height:380px')
		->attr('data-plugins', 'autosize')
		->value( !empty($this->item['text']) ? $this->item['text']:'' );

$formInvite = $form->html();
	

# body
$arr['body'] = '<div class="table-plan-wrap"><div class="table-plan">'.
	'<div class="td-plan-detail">'. $formDetail .'</div>'.
	'<div class="td-plan-invite ui-invite" style="overflow-y:auto">'.$formInvite.'</div>'.
'</div></div>';
$arr['width'] = 950; //-180  770 - 400 370


# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'calendar/insertEvent"></form>';

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