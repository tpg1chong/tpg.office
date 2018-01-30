<?php require 'init.php'; ?>
<div id="mainContainer" class="clearfix" data-plugins="main">

	<div class="profile-left" role="left" data-width="300">

		<div class="profile-left-header" role="leftHeader">
			
			<div class="profile-left-title">
				<h2>Building Update</h2>
				<div class="fsm">Last Updated: <?= $this->fn->q('time')->live( $this->item['updated'] )?></div>
			</div>

			<!-- <div id="overviewProfileCompleteness">
                <div class="title">
                    <span id="profileCompletenessLabel" class="label" aria-hidden="true">Profile completeness</span>
                    <span id="profileCompletenessValue" class="value" aria-hidden="true">70%</span>
                </div>
                <div class="progress-bar medium">
                    <span class="progresBarValue" rel="70%" style="width: 70%;"></span>
                </div>
            </div> -->
		</div>
		<!-- end: .profile-left-header -->

		<div class="profile-left-details form-insert-people" role="leftContent">

	    <!--  -->
	    <ul class="nav" style="box-shadow: rgba(255, 255, 255, .5) 0px 1px 0px 0px;border-bottom: 1px solid rgb(211, 211, 211);"><?php  

	    	$section_name = '';

	    	foreach ($list as $key => $value) { 

	    		$cls = '';
	    		if( $this->section == $value['section'] ){
	    			$cls .= !empty($cls)? ' ':'';
	    			$cls .= 'active';

	    			$section_name = $value['label'];
	    		}


	    	if( !empty($cls) ){
	    		$cls = ' class="'.$cls.'"';
	    	}

	    	echo '<li'.$cls.'><a href="'.URL.'manage/building/'.$this->item['id'].'/'.$value['section'].'">'.$value['label'].'</a></li>';

	    } ?>

	    </ul>

	    <div style="box-shadow: rgba(255, 255, 255, .5) 0px 1px 0px 0px;border-bottom: 1px solid rgb(211, 211, 211);"></div>

	    <div class="mvm">
	    	<a style="width: 100%;text-align: left;" class="btn btn-red" href="<?=URL?>building/del/<?=$this->item['id']?>" data-plugins="dialog"><i class="icon-trash-o mrs"></i><span>Delete</span></a>
	    </div>

	    </div>
	    <!-- end: .profile-left-details -->
	</div>
	<div role="content">
		<div role="toolbar" class="cashier-toolbar">
			<div class="mhl phl ptl clearfix">
				
				<h1 style="display: inline-block;"><?=$this->item['name']?></h1>
				<div>ตั้งค่า -> <?=$section_name?></div>
			</div>
		</div>
		<!-- End: toolbar -->

		<div class="" role="main">
			<?php require 'main.php'; ?>
		</div>
		<!-- end: main -->

	</div>
	<!-- end: content -->
</div>