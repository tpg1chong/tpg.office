<?php

$options = array(
	'url'=> URL.'calendar/events',
	'weekDayStart' => 1
);

if( !empty($this->permit['events']['add']) ){
	$options['add_url'] = URL.'events/add';
}

?><div id="mainContainer" class="clearfix" data-plugins="main">

	<div role="content">
		<div role="main" class="calendarGridRootContainer"><div class="pal">
			<div data-plugins="calendar" data-options="<?=$this->fn->stringify( $options  )?>"></div>
		</div></div>
	
	</div>
</div>