<?php

$this->count_nav = 0;
$menu = array();
$this->pageURL = URL.'admin/';


if( !empty($this->permit['business']['view'] ) ){
	$this->count_nav+=1;
	$menu[] = array('text' => 'Business', 'key'=>'business', 'url' => $this->pageURL.'business');
}


/* -- property -- */
$sub = array();
$sub[] = array('text'=> Translate::Menu('Type'),'key'=>'type','url'=>$this->pageURL.'property/type');
$sub[] = array('text'=> Translate::Menu('Zone'),'key'=>'zone','url'=>$this->pageURL.'property/zone');
$sub[] = array('text'=> Translate::Menu('Near Transport'),'key'=>'near','url'=>$this->pageURL.'property/near');
foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => 'Property', 'url' => '', 'sub' => $sub);
}


/* -- Accounts -- */

if( !empty($this->permit['accounts']['view'] ) ){
	$this->count_nav+=1;
	$menu[] = array('text' => 'Accounts', 'key'=>'accounts', 'url' => $this->pageURL.'accounts');
}

