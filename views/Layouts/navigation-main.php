<?php

#info
$info[] = array('key'=>'dashboard','text'=>$this->lang->translate('menu','Dashboard'),'link'=>$url.'dashboard','icon'=>'home');
$info[] = array('key'=>'notifications','text'=>$this->lang->translate('menu','Notifications'),'link'=>$url.'notifications','icon'=>'bell-o');
$info[] = array('key'=>'calendar','text'=>$this->lang->translate('menu','Calendar'),'link'=>$url.'calendar','icon'=>'calendar');
foreach ($info as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($info[$key]);
}
if( !empty($info) ){
	echo $this->fn->manage_nav($info, $this->getPage('on'));
}


#Customer
$cus[] = array('key'=>'customers','text'=>$this->lang->translate('menu','Customers'),'link'=>$url.'customers','icon'=>'address-card-o');
$cus[] = array('key'=>'companies','text'=>$this->lang->translate('menu','Companies'),'link'=>$url.'companies','icon'=>'building-o');
// $cus[] = array('key'=>'embassy','text'=>$this->lang->translate('menu','Embassy'),'link'=>$url.'customers/newcomers','icon'=>'address-card');
foreach ($cus as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($cus[$key]);
}
if( !empty($cus)){
	echo $this->fn->manage_nav($cus, $this->getPage('on'));
}

#People
$people[] = array('key'=>'people','text'=>$this->lang->translate('menu','People'),'link'=>$url.'people','icon'=>'address-card');
$people[] = array('key'=>'organization','text'=>$this->lang->translate('menu','Organizations'),'link'=>$url.'organizations','icon'=>'building');
foreach ($people as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($people[$key]);
}
if( !empty($people)){
	echo $this->fn->manage_nav($people, $this->getPage('on'));
}

#Property
$PTY[] = array('key'=>'property','text'=>$this->lang->translate('menu','Properties'), 'link'=>$url.'properties','icon'=>'home');
// $PTY[] = array('key'=>'property_listing','text'=>$this->lang->translate('menu','Listing'),'link'=>$url.'properties/listing','icon'=>'file-text-o');
// $PTY[] = array('key'=>'property_building','text'=>$this->lang->translate('menu','Building'),'link'=>$url.'properties/building','icon'=>'building-o');
foreach ($PTY as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($PTY[$key]);
}
if( !empty($PTY)){
	echo $this->fn->manage_nav($PTY, $this->getPage('on'));
}


#booking
/*$bok[] = array('key'=>'orders','text'=> $this->lang->translate('menu','Invoice'),'link'=>$url.'order','icon'=>'file-text-o');
$bok[] = array('key'=>'booking','text'=> $this->lang->translate('menu','Booking'),'link'=>$url.'booking','icon'=>'address-book-o');
foreach ($bok as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($bok[$key]);
}
if( !empty($bok)){
	echo $this->fn->manage_nav($bok, $this->getPage('on'));
}*/


#reports
$reports[] = array('key'=>'projects','text'=>$this->lang->translate('menu','Projects'),'link'=>$url.'projects','icon'=>'book');
$reports[] = array('key'=>'tasks','text'=>$this->lang->translate('menu','Tasks'),'link'=>$url.'tasks','icon'=>'check-square-o');
$reports[] = array('key'=>'reports','text'=>$this->lang->translate('menu','Reports'),'link'=>$url.'reports','icon'=>'line-chart');
foreach ($reports as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($reports[$key]);
}
if( !empty($reports) ){
	echo $this->fn->manage_nav($reports, $this->getPage('on'));
}


$cog[] = array('key'=>'settings','text'=>$this->lang->translate('menu','Settings'),'link'=>$url.'settings','icon'=>'cog');
echo $this->fn->manage_nav($cog, $this->getPage('on'));