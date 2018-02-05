<?php

$this->count_nav = 0;
$menu = array();
$this->pageURL = URL.'admin/';


if( !empty($this->permit['business']['view'] ) ){
	$this->count_nav+=1;
	$menu[] = array('text' => 'Business', 'key'=>'business', 'url' => $this->pageURL.'business');
}


/* -- Site -- */
if( !empty($this->permit['site']['view'] ) ){
	$this->count_nav+=1;
	$menu[] = array('text' => 'Site', 'key'=>'site', 'url' => $this->pageURL.'site');
}


/* -- Accounts -- */
$sub = array();
$sub[] = array('text'=> Translate::Menu('Position'),'key'=>'admin_roles','url'=>$this->pageURL.'accounts/position');
$sub[] = array('text'=> Translate::Menu('Employees'),'key'=>'employees','url'=>$this->pageURL.'accounts/employees');

foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => 'Accounts', 'url' => '', 'sub' => $sub);
}



/* -- property -- */
$sub = array();
$sub[] = array('text'=> Translate::Menu('Type'),'key'=>'type','url'=>$this->pageURL.'property/type');
$sub[] = array('text'=> Translate::Menu('Zone'),'key'=>'zone','url'=>$this->pageURL.'property/zone');
$sub[] = array('text'=> Translate::Menu('Near'),'key'=>'near','url'=>$this->pageURL.'property/near');
foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => 'Property Management', 'url' => '', 'sub' => $sub);
}




/* -- property -- */
$sub = array();
$sub[] = array('text'=> Translate::Menu('Import Data'),'key'=>'type','url'=>$this->pageURL.'property/type');
$sub[] = array('text'=> Translate::Menu('Export Data'),'key'=>'zone','url'=>$this->pageURL.'property/zone');
foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => 'Data Management', 'url' => '', 'sub' => $sub);
}

