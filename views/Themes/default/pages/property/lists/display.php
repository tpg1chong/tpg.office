<?php require_once 'init.php'; ?>
<div id="mainContainer" class="clearfix properties-container" data-plugins="main">
	
	<div role="topbar" id="toolbar">
			
		<ul class="ui-list ui-list-tabs clearfix"><?php foreach ($this->tab as $key => $value) {

			echo '<li><a class="">'.$value['name'].'</a></li>';
			
		} ?></ul>
		
	</div>

	<!-- <div role="left" id="left" data-width="200">
		asdasd
		
	</div> -->
	<div role="content">

		<div role="tollbar" id="toolbar">
				
			<ul class="ui-list ui-demo-color clearfix"><?php foreach ($this->demoColor as $key => $value) {

				echo '<li>'.$value['name'].'</li>';
				
			} ?></ul>
			
		</div>

		<div role="main">
			ksdfjdsf

		</div>
		<!-- end: main -->
	</div>
	<!-- end: content -->
</div>
<!-- end: container -->