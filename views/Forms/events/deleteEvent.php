<?php

$arr['title'] = 'Delete event';
$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

// if( !empty($this->item['permit']['del']) ){
	
	$arr['form'] = '<form action="'.URL.'calendar/deleteEvent'.$next.'"></form>';
	$arr['hiddenInput'][] = array('name'=>'calendarId','value'=>$this->calendarId);
	$arr['hiddenInput'][] = array('name'=>'eventId','value'=>$this->eventId);
	$arr['body'] = "You confirm to delete this event?";
	
	$arr['button'] = '<button type="submit" role="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::Val('Delete').'</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';
// } else{

// 	$arr['body'] = "You can't delete this event";
// 	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">'.Translate::Val('Close').'</span></a>';
// }


echo json_encode($arr);