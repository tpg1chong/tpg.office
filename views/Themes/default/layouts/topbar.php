<?php 

$this->nav = array();

$this->nav[] = array('id'=>'dashboard','name'=> Translate::val('Dashboard'), 'icon'=>'television','url'=>URL.'dashboard' );
$this->nav[] = array('id'=>'calendar','name'=> Translate::val('Calendar'), 'icon'=>'calendar-o','url'=>URL.'calendar' );
$this->nav[] = array('id'=>'customers','name'=> Translate::val('Customers'), 'icon'=>'user-circle-o','url'=>URL.'customers' );
$this->nav[] = array('id'=>'companies','name'=> Translate::val('Companies'), 'icon'=>'building-o','url'=>URL.'companies' );
$this->nav[] = array('id'=>'property','name'=> Translate::val('Property'), 'icon'=>'home','url'=>URL.'property' );
$this->nav[] = array('id'=>'listing','name'=> Translate::val('Listing'), 'icon'=>'file-text-o','url'=>URL.'property' );
$this->nav[] = array('id'=>'contacts','name'=> Translate::val('Contacts'), 'icon'=>'address-card-o','url'=>URL.'contacts' );
$this->nav[] = array('id'=>'reports','name'=> Translate::val('Reports'), 'icon'=>'line-chart','url'=>URL.'reports' );

$pageNav = '';
foreach ($this->nav as $key => $value) {

	$cls = '';

	if( $this->getPage('on')==$value['id'] ){
		$cls .= !empty($cls) ? ' ':'';
		$cls .= 'active';
	}
	
	$countVal = '';
	if( !empty($value['count']) ){
		$cls .= !empty($cls) ? ' ':'';
		$cls .= 'hasCount';

		$countVal = $value['count'];
	}

	$cls = !empty($cls) ? ' class="'.$cls.'"':'';

	// href="'.$value['url'].'"
	$dialog = '';
	if( isset($value['dialog']) ){
		$dialog =' data-plugins="dialog"';
	}

	$pageNav .= '<li id="global-nav-'.$value['id'].'" '.$cls.' data-global-action="'.$value['id'].'"><a data-nav="'.$value['id'].'" href="'.$value['url'].'"'.$dialog.'><i class="icon-'.$value['icon'].'"></i><strong>'.$value['name'].'</strong>'.'<span class="mls countVal">'.$countVal.'</span>'.'</a></li>';
}

$pageNavR = '';
/*$pageNavR .= '<li class="headerClock">'.
	'<div class="headerClock-inner" data-plugins="oclock" data-options="'.$this->fn->stringify( array('lang'=>$this->lang->getCode() ) ).'">'.
		'<div ref="time" class="time"></div>'.
		'<div ref="date" class="date"></div>'.
	'</div>'.
'</li>';*/

/*$pageNavR .= '<li class="lbtn">'.
	'<span class="gbtn"><a class="btn btn-red"><i class="icon-plus mrs"></i><span>New Order</span></a></span>'.
'</li>';

$pageNavR .= '<li class="divider"></li>';*/

$pageNavR .= '<li>'.
	'<a class="pageNavLabel"><i class="icon-bell-o mrs"></i></a>'.
'</li>';


$imageAvatar = '';
if( !empty($this->me['image_url']) ){
	$imageAvatar = '<div class="avatar lfloat size32 headerAvatar"><img class="img" src="'.$this->me['image_url'].'"></div>';
	$imageAvatarBig = '<div class="avatar lfloat headerAvatar mrm"><img class="img" src="'.$this->me['image_url'].'"></div>';
}
else{
	$imageAvatar = '<div class="avatar lfloat size32 no-avatar"><div class="initials"><i class="icon-user"></i></div></div>';
	$imageAvatarBig = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
}

$pageNavR .= '<li class="uiToggle headerAvatarWrap">'.
    '<a data-plugins="toggleLink">'.$imageAvatar.'</a>'.

    '<div class="uiToggleFlyout uiToggleFlyoutRight uiToggleFlyoutPointer" id="accountSettingsFlyout"><ul role="menu" class="uiMenu">'.

            '<li class="menuItem head"><a class="itemAnchor" href="#"><span class="itemLabel"><div class="clearfix"><div class="anchor"><div class="clearfix">'.$imageAvatarBig.'<div class="content"><div class="spacer"></div><div class="massages"><div class="fullname">'.$this->me['fullname'].'</div></div></div></div></div></div></span></a></li>'.

            /*<li class="menuItemDivider" role="separator"></li>

            <li class="menuItem"><a class="itemAnchor" href="http://localhost/events/manage/index.php"><span class="itemLabel">จัดการระบบ</span></a></li>*/

            '<li class="menuItemDivider" role="separator"></li>'.
            
            '<li class="menuItem"><a class="itemAnchor" href="'.URL.'settings"><span class="itemLabel">'.$this->lang->translate('menu','Settings').'</span></a></li>'.

            '<li class="menuItem"><a class="itemAnchor" data-plugins="dialog" href="'.URL.'accounts/logout/"><span class="itemLabel">'.$this->lang->translate('menu','Log Out').'</span></a></li>'.
        '</ul></div>'.

'</li>';

echo '<div id="header-primary" class="topbar">'.

'<div class="global-nav clearfix">';
		
		$imageLogo = $this->getPage('image_logo_url');
		if( !empty($imageLogo) ){
		
			echo '<h1 class="topbar-logo">'.
				'<img src="'.$imageLogo.'" />'.
				'<span class="visuallyhidden"></span>'.
			'</h1>';
		}


		echo '<div class="global-nav-left">';

			// echo '<div id="pageDate" class="lfloat hidden_elem"><input type="text" data-global="date" value="'.(!empty($this->date)? $this->date: date('Y-m-d')).'" /></div>';

			echo '<ul id="pageNav" class="clearfix lfloat js-global-actions">'.$pageNav.'</ul>';
		echo '</div>';

		echo '<ul class="clearfix rfloat nav mrl">'.$pageNavR.'</ul>';
		
echo '</div></div>';

if( !empty($this->topbar['back_url']) ){
	
	if( is_array($this->topbar['back_url']) ){
		echo '<a class="m-menu-toggle icon" href="'.$this->topbar['back_url']['url'].'"><i class="'.$this->topbar['back_url']['icon'].'"></i></a>';
	}
	else{

		echo '<a class="m-menu-toggle icon" href="'.$this->topbar['back_url'].'"><i class="icon-arrow-left"></i></a>';
	}
}
else{

	$theme_options = $this->getPage('theme_options');

	if( !empty($theme_options['has_menu']) ){
	echo '<a class="m-menu-toggle js-navigation-trigger"><span class="m-menuicon-bread m-menuicon-bread-top"><span class="m-menuicon-bread-crust m-menuicon-bread-crust-top"></span></span><span class="m-menuicon-bread m-menuicon-bread-bottom"><span class="m-menuicon-bread-crust m-menuicon-bread-crust-bottom"></span></span></a>';
	}
}