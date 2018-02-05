<?php require 'init.php'; ?>
<div id="mainContainer" class="clearfix forum" data-plugins="main">

	<div class="forum-left" role="left" data-width="400">


		<div class="forum-left-header" role="leftHeader" style="">

			<form class="forum-left-form-wrap">

				<div class="forum-left-form">
					<input type="text" name="keywords" class="inputtext forum-inputsearch" placeholder="Search.." autocomplete="off">
					<div class="forum-left-form-controls">
						<button class="control" type="button"><i class="icon-search"></i></button><button class="control toggle-filter" type="button"><i class="icon-filter"></i></button>
					</div>
				</div>
				
				<ul class="forum-left-form-filters clearfix">
					<li>Total: <span class="fwb">2,924</span> results</li>
					<li class="rfloat">
						<label class="label">Sort:</label> 
						<select class="inputtext">
							<option>Register</option>
							<option>Last Update</option>
							<option>A - Z</option>
						</select>
					</li>

				</ul>
			</form>
		</div>
		<!-- end: leftHeader -->

		<div class="forum-items-wrap" role="leftContent">

			<div class="forum-items">
			<?php for ($i=0; $i < 2; $i++) { ?>
				<div class="item">
					<div class="itemAvatar"></div>
					<div class="itemContent">
						<div class="fwb">Bangnatrad: Lake Side Villa 1</div>
						<div class="fss">Lakeside Villa I, Bangna Trad Road Km. 5, Bangna, Bankeaw, Samutprakan, 10260 THAILAND</div>
					</div>
				</div>
			<?php } ?>
			</div>

			<div class="forum-items-footer">
				
			</div>
		</div>

	</div>
	<!-- end: left -->

	<div class="forum-content hasLeft" role="content">

		<!-- <div role="toolbar" class="forum-toolbar" style="padding-left: 12px;">

			<div class="forum-toolbar-title">
				<h2 class="title" data-item="name">Lake Side Villa 1</h2>
				<div class="d"></div>
			</div>

			<nav class="forum-toolbar-nav" style="padding-left: 450px;">
				<a class="forum-toolbar-navItem active" data-action-tab="about"><i class="icon-info-circle mrs"></i><span>Info</span></a>
				<a class="forum-toolbar-navItem" data-action-tab="contact"><i class="icon-home mrs"></i><span>Property</span> [<span data-item="total_contact">0</span>]</a>
			</nav>
		</div> -->

		<div class="forum-profile-wrap" role="main">

			<div class="forum-profile-sile" style="width: 450px">

				
				<!-- <div class="forum-profile-avatar">
					<i class="icon-building-o"></i>
				</div> -->
				<div style="/*background-color: #fefefe; */overflow-y: scroll;position: absolute;top: 0;left: 0;right: 0;bottom: 0;padding: 12px;">
					<?php require_once 'tabs/info.php'; ?>
				</div>
			</div>

			<div class="forum-profile-content" style="margin-left: 450px">

				<div class="property-card">





					<div class="pp-item usp-highlighted">

						<div class="usp-box">
							<div class="usp-content-box">
								<!-- <div class="usp-circle-container"><p class="usp-circle-rate">#1</p></div> -->
								<div class="usp-description">Highest guest rating among available <strong>resorts</strong>!</div></div>
								<div class="usp-extra-box"></div>
							</div>

						
						<div class="pp-item-box clearfix" style="background-color: #99ff99">
							<div class="no"><?=$i?></div>

							<div class="property-card-image media-box thumbnail-gallery">
								<figure class="thumbnail-showcase">
									<img src="https://pix6.agoda.net/hotelImages/1031281/-1/b3dcb919b77a65774ea0bb547c07dee3.jpg?s=450x450" class="unfold media-object showcase-image" alt="King Rock Boutique Hotel">
								</figure>
								<div class="thumbnail-item-list clearfix">
									<div class="thumbnail-item"><div class="thumbnail-image" style="background-image: url(&quot;https://pix6.agoda.net/hotelImages/261/2616884/2616884_17102417220058263122.jpg?s=450x450&quot;);"></div></div>
									<div class="thumbnail-item"><div class="thumbnail-image" style="background-image: url(&quot;https://pix6.agoda.net/hotelImages/751652/-1/3ff50bbe4ee5b375401b9a7e36fa928e.jpg?s=450x450&quot;);"></div></div>
									<div class="thumbnail-item"><div class="thumbnail-image" style="background-image: url(&quot;https://pix6.agoda.net/hotelImages/445/44551/44551_16053010310042857300.jpg?s=450x450&quot;);"></div></div>
								</div>
							</div>
							<div class="property-info"></div>
						</div>
					
					</div>

				<?php for ($i=0; $i < 10; $i++) { ?>
					<div class="pp-item" data-id="733536">
						

						<div class="pp-item-box clearfix">
							<div class="no"><?=$i?></div>

							<div class="property-card-image media-box thumbnail-gallery">
								<figure class="thumbnail-showcase">
									<img src="https://pix6.agoda.net/hotelImages/1031281/-1/b3dcb919b77a65774ea0bb547c07dee3.jpg?s=450x450" class="unfold media-object showcase-image" alt="King Rock Boutique Hotel">
								</figure>
								<div class="thumbnail-item-list clearfix">
									<div class="thumbnail-item"><div class="thumbnail-image" style="background-image: url(&quot;https://pix6.agoda.net/hotelImages/261/2616884/2616884_17102417220058263122.jpg?s=450x450&quot;);"></div></div>
									<div class="thumbnail-item"><div class="thumbnail-image" style="background-image: url(&quot;https://pix6.agoda.net/hotelImages/751652/-1/3ff50bbe4ee5b375401b9a7e36fa928e.jpg?s=450x450&quot;);"></div></div>
									<div class="thumbnail-item"><div class="thumbnail-image" style="background-image: url(&quot;https://pix6.agoda.net/hotelImages/445/44551/44551_16053010310042857300.jpg?s=450x450&quot;);"></div></div>
								</div>
							</div>

							<!-- <div class="property-price-promo-container pull-right coupon-cor-container">
								
							</div> -->
							<div class="property-info">
								
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
				
			</div>


		</div>
		<!-- end: main -->


	</div>
	<!-- end: content -->
</div>
<!-- end: mainContainer -->


<style type="text/css">
	

	.pp-item-box{
		background-color: #fff;
	    border: 1px solid #ddd;
	    min-height: 202px;
	    max-width: 750px;
	    transition: box-shadow .2s ease-in-out;
	    position: relative;
	}

	.usp-box {
	    background: #357ae8;
	   	margin-left: -2px;
	    color: #fff;

	    width: 85%;
	    position: relative;
	    padding: 8px 12px;
	}
	.usp-box .usp-content-box {
	    /*width: calc(100% - 80%);*/
	    /*display: inline-block;*/
	}
	.usp-box .usp-extra-box {
	    
	    border-left: 10px solid #357ae8;
    	border-right: 10px solid transparent;
	    border-top: 25px solid #357ae8;
	    border-bottom: 25px solid transparent;
	    position: absolute;
	    left: 100%;
	    top: 0;

	}

	.property-card .property-card-image {
	    min-width: 225px;
	    min-height: 225px;

	    /*background-color: #fff;*/
	    float: left;

	}
	.property-card .property-card-image .thumbnail-showcase {
	    position: relative;
	    width: 234px;
	    height: 155px;
	    overflow: hidden;
	    background-color: #d6d6d6
	}
	.property-card .property-card-image .thumbnail-showcase .showcase-image {
	    position: absolute;
	    top: 0;
	    width: 100%;
	    height: auto;
	    min-height: 155px;
	    transition: opacity .2s ease-in;
	    -webkit-backface-visibility: hidden;
	    backface-visibility: hidden;
	}

	.property-card .thumbnail-item-list {
	    margin-top: 3px;
	}
	.property-card .thumbnail-item {
	    float: left;
	    width: 76px;
	    height: 67px;
	    overflow: hidden;
	}
	.property-card .thumbnail-item + .thumbnail-item {
		margin-left: 3px;
		/*border-left: 3px solid #fff;*/
	}

	.pp-item-box .property-price-promo-container {
	    background: #F8F8F9;
	    width: 200px;
	    padding: 10px;
	    position: absolute;
	    right: 0;
	    height: 100%;
	    min-height: 200px;
	}

	.pp-item + .pp-item{
		margin-top: 15px;
	}

	.property-card .thumbnail-item .thumbnail-image {
	    position: relative;
	    width: 100%;
	    height: 100%;
	    background-size: cover;
	    background-repeat: no-repeat;
	    background-position: 50% 50%;
	    -webkit-backface-visibility: hidden;
	    backface-visibility: hidden;
	    background-color: #d6d6d6;
	}

	.pp-item{
		position: relative;
	}
	.pp-item.usp-highlighted .pp-item-box:before{
		content: '';
	    position: absolute;
	    top: -3px;
	    left: -3px;
	    right: -3px;
	    bottom: -3px;
	    border: 3px solid #357ae8;
	    /*border-radius: 3px;*/
	}
	.pp-item-box > .no{
		position: absolute;
		top: 5px;
		left: 5px;
		font-size: 10px;
		z-index: 1;
		/*color: #fff*/
	}
	
	.input-large{
		height: 40px;
		line-height: 40px;
	}

	.forum-tap-from #property_name_fieldset .inputtext {
		height: 40px;
		line-height: 40px;
		font-size: 18px;
		font-weight: bold;
		color: #357ae8
	}
	.forum-tap-from{
		/*max-width: 450px;*/
	}
	.forum-profile-head{
		border-bottom: 1px dashed #ccc;
		/*border-style: ;*/
		font-weight: bold;
		margin-bottom: 8px;
		padding-bottom: 2px;
		margin-top: 15px;
		/*color: #666*/
	}
	.forum-profile-content .control-label{
		/*font-weight: bold;*/
		color: #555
	}
	.forum-profile-avatar{
		width: 198px;
		background-color: #aaa;
		height: 198px;
		line-height: 220px;
		text-align: center;
		position: relative;
		border-radius: 6px
	}
	.forum-profile-avatar>i{
		color: #fff;
		font-size: 50px;
	}
	
	.forum-content{
		position: relative;
	}
	.forum-content.hasLeft .forum-profile-sile{
		display: block;
	}
	.forum-content.hasLeft .forum-profile-content{
		margin-left: 220px;
	}
	.forum-content.hasLeft .forum-toolbar{
		padding-left: 220px;
	}


	.forum-profile-sile{
		position: absolute;
		width: 220px;
		padding: 12px;
		top: 0;
		left: 0;
		bottom: 0;
		display: none;
	}
	.forum-profile-content {
	    padding: 15px;
	}

	.forum-toolbar{
		background-color: #eee;
	    border-bottom: 1px solid #d8dfea;
	    padding: 20px;
	    padding-top: 24px;
	    padding-bottom: 0;
	    height: 85px;
	}
	.forum-toolbar-title{
		margin-bottom: 3px;
	}
	.forum-toolbar-navItem {
	    display: inline-block;
	    padding: 2px 10px;
	    border: 1px solid transparent;
	    border-bottom: none;
	    background-color: #d8dfea;
	    font-weight: bold;
	    position: relative;
	    color: #3b5998;
	    border-top-left-radius: 5px;
	    border-top-right-radius: 5px;
	}
	.forum-toolbar-navItem+.forum-toolbar-navItem {
	    margin-left: 2px;
	}
	.forum-toolbar-navItem:hover{
		text-decoration: none;
	}
	.forum-toolbar-navItem.active {
	    margin: -2px 0 -1px;
	    padding: 4px 10px 3px;
	    background-color: #f2f2f2;
	    color: #111;
	    border-color: #d8dfea;
	    cursor: default;
	}


	.forum-items .item{
		padding: 8px 12px;
	}
	.forum-items .item:hover{
		background-color: #bbb;
		cursor: pointer;
	}
	.forum-left{
		border-right: 1px solid #e6e6e6;
		background-color: #fff;
	}
	.forum-left-header{
		border-bottom: 1px solid #e6e6e6;
		padding: 8px 12px;
		background-color: #eee
	}

	
	.forum-left-form{
		position: relative;
	}
	.forum-left-form-controls{
		position: absolute;
		right: 0;
		top: 0;
	}
	.forum-left-form-controls .control{
		width: 30px;
		height: 40px;
		line-height: 40px;
		display: inline-block;
	}
	.forum-left-form-controls .control + .control{
		border-left: 1px solid #ccc;
	}
	.forum-inputsearch{
		width: 100%;
		padding-right: 60px;
		height: 40px;
		line-height: 40px;
		border-radius: 0
	}

	.forum-left-form-filters{
		margin-top: 2px;
	}
	.forum-left-form-filters>li{
		display: inline-block;
		float: left;
		line-height: 25px;
	}
	.forum-left-form-filters>li.rfloat{
		float: right;
	}
	.forum-left-form-filters .inputtext{
		display: inline-block;
		height: 25px;
		line-height: 25px;
		border-width: 0 0 1px;
		border-radius: 0;
		box-shadow: none;
		padding: 0;
		background-color: transparent;
	}
	.forum-left-form-filters .label{
		line-height: 25px;
		color: #777;
		font-size: 11px;
	}
</style>


<script type="text/javascript">
	


</script>