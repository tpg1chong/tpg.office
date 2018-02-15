<?php


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
        ->attr( !empty($_REQUEST['title']) ? 'autoselect':'autofocus', 1)
        ->value( !empty($_REQUEST['title']) ? $_REQUEST['title']:'' );


$form 	->field("event_start")
		// ->label('วันที่')
		->text( '<div style="min-height: 100px;" data-plugins="eventdate" data-options="'.$this->fn->stringify( array(

			'lang' => $this->lang->getCode(),
			'startDate' => !empty($_REQUEST['startDate']) ? $_REQUEST['startDate']:'',
			'startTime' => !empty($_REQUEST['startTime']) ? $_REQUEST['startTime']:'',

			'endDate' => !empty($_REQUEST['endDate']) ? $_REQUEST['endDate']:'',
			'endTime' => !empty($_REQUEST['endTime']) ? $_REQUEST['endTime']:'',

			'allday' => !empty($_REQUEST['allday']) ? $_REQUEST['allday']:true,
		) ).'"></div>' );


$form 	->field("event_location")
		->label('Location')
		->addClass('inputtext')
		->placeholder('Add location')
		->autocomplete('off')
		->value( !empty($_REQUEST['location']) ? $_REQUEST['location']:'' );


$form 	->field("event_color")
		->label('Color')
		->addClass('inputtext')
		->attr('data-plugins', 'colors')
		->attr('data-options', Fn::stringify( array('colors'=>$this->colors ) ) )
		->placeholder('')
		->autocomplete('off')
		->value( !empty($_REQUEST['colorId']) ? $_REQUEST['colorId']:'' );


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
		->value( !empty($_REQUEST['description']) ? $_REQUEST['description']:'' );

$formInvite = $form->html();
	

# body
$arr['body'] = '<div class="table-plan-wrap"><div class="table-plan">'.
	'<div class="td-plan-detail">'. $formDetail .'</div>'.
	'<div class="td-plan-invite ui-invite" style="overflow-y:auto">'.$formInvite.'</div>'.
'</div></div>';
$arr['width'] = 950; //-180  770 - 400 370


# set form
$arr['form'] = '<form method="post" action="'.URL. 'calendar/insertEvent"></form>';

$arr['button'] = '';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->id);
$arr['title']= "Edit event";

$has_callback = '';
if( isset( $_REQUEST['callback'] ) ){
    $arr['hiddenInput'][] = array('name'=>'callback','value'=>$_REQUEST['callback']);
    $has_callback = ' role="submit"';
}

$arr['button'] .= '<button type="submit"'.$has_callback.' class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';

echo json_encode($arr);