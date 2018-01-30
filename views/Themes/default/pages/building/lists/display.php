    <div id="mainContainer" class="clearfix listpage2-container" data-plugins="main">

	<div role="content">
		<div role="main">

<?php require_once 'init.php'; ?>

<div class="listpage2 has-loading offline listpage2-mg" data-plugins="listpage2" data-options="<?= $this->fn->stringify( array(
		'url' => $this->getURL,
		'lists' => array(),
		'options' => array()
	) )?>">

	<!-- header -->
	<?php require 'header.php'; ?>

	<!-- table -->
	<div ref="table" class="listpage2-table table-mg">
		<div ref="tabletitle"><?php require 'tabletitle.php'; echo $tabletitle; ?></div>
		<div ref="tablelists"></div>
                

		<!-- <div class="listpage2-table-overlay"></div> -->
		<div class="listpage2-table-empty">
	        <div class="empty-icon"><i class="icon-users"></i></div>
	        <div class="empty-title">ไม่พบข้อมูล</div>
		</div>
		
	</div>

	<div class="listpage2-table-overlay-warp">
		<div class="listpage2-table-overlay"></div>
		<div class="listpage2-alert">
			<div class="listpage2-loading">
				<div class="listpage2-loading-icon loader-spin-wrap"><div class="loader-spin"></div></div>
				<div class="listpage2-loading-text">กำลังโหลด...</div> 
			</div>
		</div>
	</div>
</div>

		</div>
		<!-- end: main -->
	</div>
	<!-- end: content -->
</div>
<!-- end: container -->