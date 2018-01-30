<?php

$this->pageURL = URL.'organizations/';
$this->pageTitle = 'Organizations';
$this->pageIcon = 'building';

$this->pagePermit = array(
	'add' => !empty($this->permit['organization']['add']),
	'edit' => !empty($this->permit['organization']['edit']),
	'del' => !empty($this->permit['organization']['del']),
);

# title
$title = array(
	0 => array('key'=>'check-box', 'text'=> '<label class="checkbox"><input id="checkboxes" type="checkbox"></label>' ),
	  
	array('key'=>'name', 'text'=>$this->lang->translate('Name'),'sort'=>'name'),

	array('key'=>'address', 'text'=>$this->lang->translate('Address') ),

	array('key'=>'type', 'text'=>$this->lang->translate('Country'),'sort'=>'country_name' ),
	array('key'=>'type', 'text'=>$this->lang->translate('Category'),'sort'=>'category_name' ),

	array('key'=>'actions', 'text'=>'' ),
	
);

// $this->titleStyle = 'row-2';

$this->tabletitle = $title;
$this->getURL =  URL.'organizations';