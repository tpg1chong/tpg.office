<?php 

?><div class="ui-calendar-fantastical has-left" data-plugins="calendar2" data-options="<?=$this->fn->stringify( array('lang' => $this->lang->getCode() ) )?>">

	<div class="ui-calendar-left">
		<div class="calendarLeft-calendarWrap" style="position: absolute;top: 0;left: 0;right: 0">
			
			<div class="mam" style="position: relative;">
				
				<div class="clearfix mbm ui-calendar-left-title" >
					
					<h2 class="lfloat">
						<strong ref="title_mini_month"></strong> <span ref="title_mini_year"></span>
					</h2>
					<div class="rfloat">
						<a class="btn-icon prevnext prev btn-no-padding" data-action-prevnext-mini="prev" title="Previous"><i class="icon-chevron-left"></i></a><a class="btn-icon prevnext prev btn-no-padding hidden_elem" data-action-mini="today" title="Today"><i class="icon-dot-circle-o"></i></a><a class="btn-icon prevnext next btn-no-padding" data-action-prevnext-mini="next"><i class="icon-chevron-right"></i></a>
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

	<div class="ui-calendar-content">
		<div ref="calendarHead" style="position: fixed;top: 48px;right: 17px;left: 300px;z-index: 100;background-color: #f2f2f2">

			<div class="pam clearfix">
				<div class="lfloat group-btn">
					<a class="btn prevnext prev btn-no-padding" data-action-prevnext="prev"><i class="icon-chevron-left"></i></a><a class="btn" data-action="today" title="Today"><?=Translate::val('Today')?></a><a class="btn prevnext next btn-no-padding" data-action-prevnext="next" title="Next"><i class="icon-chevron-right"></i></a>
				</div>
				<h2 ref="title" style="position: absolute;left: 50%;width: 300px;margin-left: -150px;text-align: center;line-height: 30px"></h2>

				<div class="rfloat group-btn mls">
					<a class="btn btn-blue" data-plugins="dialog" href="<?=URL?>calendar/add"><i class="icon-plus mrs"></i><?=Translate::val('Create')?></a><a class="btn btn-no-padding"><i class="icon-ellipsis-v"></i></a>
				</div>

				<div class="rfloat group-btn hidden_elem">
					<a class="btn active" data-action-format="month" style="min-width: 60px">เดือน</a><a class="btn" data-action-format="year" style="min-width: 60px">ปี</a>
				</div>
			</div>
			
			<!-- <table class="table-calendar" style="border-bottom: 1px solid #dfdfdf"><thead><tr><th>วันจันทร์</th><th>วันอังคาร</th><th>วันพุธ</th><th>วันพฤหัสบดี</th><th>วันศุกร์</th><th>วันเสาร์</th><th>วันอาทิตย์</th></tr></thead></table> -->
		</div>
		<div ref="calendar" style="margin-top: 50px"></div>

	</div>
</div>