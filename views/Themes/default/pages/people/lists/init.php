<?php


$this->pageURL = URL.'people/';
$this->pageTitle = 'People';
$this->pageIcon = 'address-card';

$this->pagePermit = array(
	'add' => !empty($this->permit['people']['add']),
	'edit' => !empty($this->permit['people']['edit']),
	'del' => !empty($this->permit['people']['del']),
);


$title = array(
	0 =>   
	
	array('key'=>'check-box', 'text'=> '<label class="checkbox"><input id="checkboxes" type="checkbox"></label>' ),
	array('key'=>'name', 'text'=>$this->lang->translate('Name'),'sort'=>'name'),
	array('key'=>'express', 'text'=>$this->lang->translate('Contact') ),

	array('key'=>'company', 'text'=>$this->lang->translate('Company') ),
	
	array('key'=>'type', 'text'=>$this->lang->translate('Position') ),
	/*array('key'=>'type', 'text'=>$this->lang->translate('Country') ),
	array('key'=>'type', 'text'=>$this->lang->translate('Category') ),*/

	array('key'=>'actions', 'text'=>'' ),
	
);

// $this->titleStyle = 'row-2';

$this->tabletitle = $title;
$this->getURL =  URL.'people';