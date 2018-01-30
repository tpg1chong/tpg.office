<?php

$url = URL. "properties/";

$title = array(
	array('key'=>'name', 'text'=>'Name', 'sort'=>'name'),
	array('key'=>'email', 'text'=>'Type'),
	array('key'=>'email', 'text'=>'Zone'),
	array('key'=>'actions', 'text'=>'Actions'),
);

// $this->titleStyle = 'row-2';
$this->tabletitle = $title;
$this->getURL =  URL.'properties/building';