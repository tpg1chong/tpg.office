<?php

$arr['title'] = 'Delete company';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

if( !empty($this->item['permit']['del']) && $this->item['clientTotal']==0 ){
	
	$arr['form'] = '<form action="'.URL.'companies/del'.$next.'"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);

	$arr['body'] = '<div>Choose Company Name to delete:</div>'.
		'<ul>'.
			'<li><label class="checkbox"><input type="checkbox" name="confirm" value="'.$this->item['id'].'"><strong class="mls">'.$this->item['name'].'</strong></label></li>'.
		'<ul>';
	
	$arr['button'] = '<button type="submit" role="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::val('Delete').'</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';
}
else{

	$arr['body'] = "You can't delete Company Name <span class=\"fwb\">\"{$this->item['name']}\"</span>";	
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">'.Translate::val('Close').'</span></a>';
}


echo json_encode($arr);