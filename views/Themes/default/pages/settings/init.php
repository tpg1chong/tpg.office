<?php

$this->pageURL = URL.'settings/';
$this->count_nav = 0;

/* System */
$sub = array();
$sub[] = array('text' => 'Company Preferences','key' => 'company','url' => $this->pageURL.'company');
$sub[] = array('text' =>'My Preferences','key' => 'my','url' => $this->pageURL.'my');

foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => '', 'url' => $this->pageURL.'company', 'sub' => $sub);
}

/* Accounts */
$sub = array();
$sub[] = array('text'=> $this->lang->translate('Department'),'key'=>'department','url'=>$this->pageURL.'accounts/department');
$sub[] = array('text'=> $this->lang->translate('Position'),'key' => 'position','url' => $this->pageURL.'accounts/position');
$sub[] = array('text'=> $this->lang->translate('Employees'),'key' => 'employees','url' => $this->pageURL.'accounts/');

foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> $this->lang->translate('Accounts'),'sub' => $sub, 'url' => $this->pageURL.'accounts/');
}

/* */
/* Property Menu */
/* */
$sub = array();
$sub[] = array('text'=>'Type','key'=>'type','url'=>$this->pageURL.'property/type');
$sub[] = array('text'=>'Zone','key'=>'zone','url'=> $this->pageURL.'property/zone');
$sub[] = array('text'=>'Near','key'=>'near_type','url'=> $this->pageURL.'property/near_type');
$sub[] = array('text'=>'School','key'=>'near_type','url'=> $this->pageURL.'property/near_type');
// $sub[] = array('text'=>$this->lang->translate('Near'),'key'=>'near','url'=> $this->pageURL.'property/near');
foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> $this->lang->translate('Property Management'),'sub' => $sub, 'url' => $this->pageURL.'property/');
}


#Data Management
$sub = array();
$sub[] = array('text'=>$this->lang->translate('Import Data'),'key'=>'import','url'=>$this->pageURL.'import');
$sub[] = array('text'=>$this->lang->translate('Export Data'),'key'=>'export','url'=> $this->pageURL.'export');
foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> $this->lang->translate('Data Management'),'sub' => $sub, 'url' => $this->pageURL.'data_management');
}
