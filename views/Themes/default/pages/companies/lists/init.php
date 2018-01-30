<?php

$this->pageURL = URL.'companies/';
$this->pageTitle = 'Companies';
$this->pageIcon = 'building-o';

$this->pagePermit = array(
	'add' => !empty($this->permit['companies']['add']),
	'edit' => !empty($this->permit['companies']['edit']),
	'del' => !empty($this->permit['companies']['del']),
);


$title = array(
	0 =>   
	array('key'=>'check-box', 'text'=> '<label class="checkbox"><input id="checkboxes" type="checkbox"></label>' ),
	array('key'=>'name', 'text'=>$this->lang->translate('Name'),'sort'=>'name'),
	array('key'=>'express', 'text'=>$this->lang->translate('Contact') ),

	array('key'=>'address', 'text'=>$this->lang->translate('Address') ),
	
	array('key'=>'type', 'text'=>$this->lang->translate('Category') ),

	array('key'=>'actions', 'text'=>'' ),
	
);

// $this->titleStyle = 'row-2';

$this->tabletitle = $title;
$this->getURL =  URL.'companies';