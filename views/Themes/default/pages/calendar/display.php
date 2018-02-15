<?php 

?><div class="ui-calendar-fantastical has-left" data-plugins="calendar2" data-options="<?=Fn::stringify( array(
	'lang' => $this->lang->getCode(),
	'email' => $this->me['user_email']
) )?>">


	<div class="ui-calendar-left">
		<div class="calendarLeft-calendarWrap" style="position: absolute;top: 0;left: 0;right: 0">
			
			<div class="mam" style="position: relative;">
				
				<div class="clearfix mbm ui-calendar-left-title" >
					
					<h2 class="lfloat">
						<strong ref="title_mini_month"></strong> <span ref="title_mini_year"></span>
					</h2>
					<div class="rfloat">
						<a class="btn-icon prevnext prev btn-no-padding" data-action-prevnext-mini="prev" title="Previous"><i class="icon-chevron-left"></i></a><a class="btn-icon prevnext prev btn-no-padding hidden_elem" data-action-mini="today" title="Today"><i class="icon-dot-circle-o"></i></a><a class="btn-icon prevnext next btn-no-padding" data-action-prevnext-mini="next" title="Next"><i class="icon-chevron-right"></i></a>
					</div>
				</div>

				<div class="" style="position: relative;">
					<!-- <div class="" style="background-color: rgba(255,255,255,.3);height: 25px;border-radius: 13px;position: absolute;left: 0;right: 0;top: 109px;"></div> -->
					<div ref="calendarMini"></div>
				</div>
			</div>
		</div>

		<div class="calendarLeft-listsboxWrap" ref="upcoming" style="overflow-y: auto;position: absolute;top:260px;left: 0;right: 0;bottom: 0">
			
			<ul class="ui-calendar-left-listsbox" role="listsbox"></ul>
		</div>

		<div class="calendarLeft-footer clearfix hidden_elem" style="position: absolute;bottom: 0;left: 0;right: 0;height: 30px;background-color: #000">
			<a class="rfloat button"><i class="icon-cog"></i></a>
		</div>
	</div>
	<!-- end: ui-calendar-left -->


	<!-- ui-calendar-content -->
	<div class="ui-calendar-content" ref="content">
		<div ref="calendarHead" style="position: fixed;top: 48px;right: 17px;left: 300px;z-index: 100;background-color: #f2f2f2">

			<div class="pam clearfix">

				<div class="lfloat "><button class="btn" type="button" data-action="refresh" title="Refresh"><i class="icon-refresh"></i></button></div>
				<h2 ref="title" style="position: absolute;left: 50%;width: 300px;margin-left: -150px;text-align: center;line-height: 30px"></h2>

				<div class="lfloat mlm group-btn">
					<a class="btn prevnext prev btn-no-padding" data-action-prevnext="prev" title="Previous"><i class="icon-chevron-left"></i></a><a class="btn" data-action="today"><?=Translate::val('Today')?></a><a class="btn prevnext next btn-no-padding" data-action-prevnext="next" title="Next"><i class="icon-chevron-right"></i></a>
				</div>


				<div class="rfloat mls">
					<a class="btn btn-blue" data-action="create" data-href="<?=URL?>calendar/add"><i class="icon-plus mrs"></i><?=Translate::val('Create event')?></a>
					<!-- <a class="btn btn-no-padding"><i class="icon-ellipsis-v"></i></a> -->
				</div>

				<div class="rfloat group-btn hidden_elem">
					<a class="btn active" data-action-format="month" style="min-width: 60px">เดือน</a><a class="btn" data-action-format="year" style="min-width: 60px">ปี</a>
				</div>
			</div>
			
			<!-- <table class="table-calendar" style="border-bottom: 1px solid #dfdfdf"><thead><tr><th>วันจันทร์</th><th>วันอังคาร</th><th>วันพุธ</th><th>วันพฤหัสบดี</th><th>วันศุกร์</th><th>วันเสาร์</th><th>วันอาทิตย์</th></tr></thead></table> -->
		</div>
		<div ref="calendar" style="margin-top: 50px"></div>

<?php


$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert');


$form   ->field("summary")
        ->label('Title')
        ->addClass('inputtext')
        // ->placeholder('Add title')
        ->autocomplete('off');
        // ->attr( !empty($_REQUEST['title']) ? 'autoselect':'autofocus', 1)
       ;


$form 	->field("event_start")
		// ->label('วันที่')
		->text( '<div data-plugins="eventdate2" data-options="'.$this->fn->stringify( array(

			'lang' => $this->lang->getCode(),
			'startDate' => !empty($_REQUEST['startDate']) ? $_REQUEST['startDate']:'',
			'startTime' => !empty($_REQUEST['startTime']) ? $_REQUEST['startTime']:'',

			'endDate' => !empty($_REQUEST['endDate']) ? $_REQUEST['endDate']:'',
			'endTime' => !empty($_REQUEST['endTime']) ? $_REQUEST['endTime']:'',

			'allday' => !empty($_REQUEST['allday']) ? $_REQUEST['allday']:true,
		) ).'"></div>' );

$form 	->field("description")
		->label('Descripion')
		->addClass('inputtext')
		->type('textarea')
		// ->placeholder('Add descripion')
		->autocomplete('off')
		// ->attr('style', 'height:25px')
		->attr('data-plugins', 'autosize');

$form 	->field("location")
		->label('Location')
		->addClass('inputtext')
		// ->placeholder('Add location')
		->autocomplete('off');

$form 	->field("colorId")
		->label('Color')
		->addClass('inputtext')
		->attr('data-plugins', 'colors')
		->attr('data-options', Fn::stringify( array('colors'=>$this->colors ) ) )
		->placeholder('')
		->autocomplete('off');


$formDetail = $form->html();

?>
		<div class="ui-calendar-popup" ref="popup">
			<form class="calendar-popup-form" data-action-popup="save" method="post" action="<?=URL?>calendar/updateEvent">
			<div class="arrow"></div>
			<a class="ui-calendar-popup-topClose btn-icon" title="<?=Translate::Val('Close')?>" data-action-popup="close"><i class="icon-remove"></i></a>
			<?php echo $formDetail; ?>

			<div class="clearfix mtm">
				<div class="lfloat"><a data-action-popup="remove" class="btn btn-link hidden_elem"><?=Translate::Val('Delete')?></a></div>
				<div class="rfloat">
					<a data-action-popup="close" class="btn btn-close"><?=Translate::Val('Close')?></a>
					<button data-action-popup="update" type="submit" class="btn btn-submit btn-primary hidden_elem"><?=Translate::Val('Save')?></button></div>
			</div>
			</form>
		</div>
		<!-- end: ui-calendar-popup -->
	</div>
	<!-- end: ui-calendar-content -->

	
</div>
<!-- end: ui-calendar-fantastical -->