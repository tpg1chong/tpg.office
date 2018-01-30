<?php require_once 'init.php'; ?>

<div id="mainContainer" class="clearfix" data-plugins="main">
	
	<div role="left" id="left" data-w-percent="25" style="background-color:#fff">
		
		<div role="leftHeader">
			<h4 class="pas fcg fss">Building Data:</h4>
			<div class="" style="padding: 10px 15px;border-bottom: 1px solid #ccc">
			<?php require_once 'sections/buildingInfo.php'; ?>
			</div>
		</div>
		<div role="leftContent">
			<div class="" style="padding: 10px 15px">
			<?php require_once 'sections/buildingData.php'; ?>
			</div>
		</div>
	</div>

	<div role="content">

		<div role="main">

			<div class="" style="height: calc(100vh - 48px);width: 25%;position: absolute;;float: left;background-color:#fefefe;border-left: 1px solid #ddd;border-right: 1px solid #ddd;position: relative;overflow: hidden;">

				<div style="position: relative;z-index: 10;background-color: #fff;">
					<h4 class="pas fcg fss">Property Total: <span class="uiColorGreen">15</span> | Rent: 4 | Sale: 5 | Sold: -</h4>

					<div class="mas hidden_elem" >
						<ul class="stats clearfix">
							
							<li><a>
								<strong>11</strong>
								Available
							</a></li>

							<li><a>
								<strong>4</strong>
								Not Available
							</a></li>
						</ul>

						<div class="clearfix" style="">
							<div class="rfloat">
								<label class="checkbox"><input type="checkbox" name=""><span class="mls">Available Only</span></label>
							</div>
						</div>
					</div>

				</div>

				<div class="" style="position: absolute;top: 23px;left: 0;right: 0;bottom: 0;overflow-y: auto;border-top: 1px solid #e8e8e8">
					<div class="propertyItems clearfix">
						<!-- <div class="propertyItemHead">Floor: 4</div> -->

						<?php for ($i=0; $i < 2; $i++) { ?>
						<div class="propertyItem horizontal clearfix status-1">

								<div class="propertyItemStatus">Sale</div>
							<figure class="propertyItemImage">
								<div class="propertyItemPic"></div>
								<div class="propertyItemCode">cosu3444</div>
							</figure>
							<div class="item-content"><div class="pas fsm">
								<table class="propertyItemMeta">
									<tr>
										<td class="label">Unit: </td>
										<td>55/5, Fl: 5</td>
									</tr>

									<tr>
										<td class="label">Price: </td>
										<td><?=number_format(1000000)?> THB/M</td>
									</tr>

									<tr>
										<td class="label">L/Call: </td>
										<td>2 days (Chong)</td>
									</tr>
								</table>
								
							</div></div>

						</div>
						<?php } ?>

						<?php for ($i=0; $i < 3; $i++) { ?>
						<div class="propertyItem horizontal clearfix status-2">
								<div class="propertyItemStatus">Rent/Sale</div>

							<figure class="propertyItemImage">
								<div class="propertyItemPic"></div>
								<div class="propertyItemCode">cosu3444</div>
							</figure>
							<div class="item-content"><div class="pas fsm">
								<table class="propertyItemMeta">
									<tr>
										<td class="label">Unit: </td>
										<td>55/5, Fl: 5</td>
									</tr>

									<tr>
										<td class="label">Price: </td>
										<td><?=number_format(1000000)?> THB/M</td>
									</tr>

									<tr>
										<td class="label">L/Call: </td>
										<td>2 days (Chong)</td>
									</tr>
								</table>
								
							</div></div>

						</div>
						<?php } ?>

						<div class="propertyItem horizontal clearfix status-6 active">

							<div class="propertyItemStatus">Sold</div>
							<figure class="propertyItemImage">
								<div class="propertyItemPic"></div>
								
								<div class="propertyItemCode">cosu3444</div>
							</figure>
							<div class="item-content"><div class="pas fsm">
								<table class="propertyItemMeta">
									<tr>
										<td class="label">Unit: </td>
										<td>55/5, Fl: 5</td>
									</tr>

									<tr>
										<td class="label">Price: </td>
										<td><?=number_format(1000000)?> THB/M</td>
									</tr>

									<tr>
										<td class="label">L/Call: </td>
										<td>2 days (Chong)</td>
									</tr>
								</table>
								
							</div></div>

						</div>

						<div class="propertyItem horizontal clearfix status-7">

							<div class="propertyItemStatus">Sold</div>
							<figure class="propertyItemImage">
								<div class="propertyItemPic"></div>
								
								<div class="propertyItemCode">cosu3444</div>
							</figure>
							<div class="item-content"><div class="pas fsm">
								<table class="propertyItemMeta">
									<tr>
										<td class="label">Unit: </td>
										<td>55/5, Fl: 5</td>
									</tr>

									<tr>
										<td class="label">Price: </td>
										<td><?=number_format(1000000)?> THB/M</td>
									</tr>

									<tr>
										<td class="label">L/Call: </td>
										<td>2 days (Chong)</td>
									</tr>
								</table>
								
							</div></div>

						</div>

					</div>
				</div>
				
			</div>
			
			<div class="" style="margin-left: 25%;">
				ksdfjdsf
				sdfgldflg

				<div>dfgkdfgkfd dfgdf</div>
			</div>

		</div>
	</div>
</div>