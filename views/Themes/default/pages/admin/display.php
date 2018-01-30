<?php require_once "inc/init.php"; ?>

<div id="mainContainer" class="Setting clearfix" data-plugins="main">

	<?php 

		if( $this->count_nav > 0 ){
			require_once 'inc/left.php';
		}
	?>

	<div class="setting-content" role="content">
		<div class="setting-main" role="main" style="position: relative;">

			<div class="setting-wrap pal mhl">
			<?php require_once "sections/{$this->section}.php"; ?>
			</div>
			<!-- end: .setting-wrap -->

		</div>
		<!-- end: .setting-main -->

	</div>
	<!-- end: .setting-content -->


</div>
<!-- end: #mainContainer -->