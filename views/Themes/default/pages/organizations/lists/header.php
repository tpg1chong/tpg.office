<div ref="header" class="listpage2-header clearfix">

	<div ref="actions" class="listpage2-actions">

		<div class="clearfix ptm">

			<ul class="lfloat" ref="title">
				<li><h2><i class="icon-<?=$this->pageIcon?> mrs"></i><span><?=$this->lang->translate($this->pageTitle)?></span></h2></li>

				<li class="divider"></li>
			</ul>
			
			<ul class="lfloat" ref="actions">				

				<?php if( $this->pagePermit['add'] ) { ?>
	            <li><div class="rfloat"><a href="<?=$this->pageURL?>add" class="btn btn-blue" data-plugins="dialog"><i class="icon-plus mrs"></i><?=$this->lang->translate('Add New')?></a></div></li>
	            <?php } ?>

			</ul>
			
			<ul class="lfloat selection hidden_elem" ref="selection">
				<li class="countVal fwb"><span class="count-value"></span> selected</li>
				<li><span class="group-btn whitespace"><?php


	$dropdown = array();

	/*$dropdown[] = array(
	    'text' => $this->lang->translate('Listing'),
	    // 'href' => '',
	    'attr' => array('ajaxify'=>'dialog'),
	);*/

	$dropdown[] = array(
	    'text' => $this->lang->translate('Sticker'),
	    'attr' => array('ajaxify'=>URL.'sticker/customers?status=newcomers'),
	);

					// echo '<a class="btn"><i class="icon-download"></i></a>';
					echo '<a class="btn btn-blue" ajaxify="'.URL.'organizations/dels"><i class="icon-trash"></i></a>';
					/*echo '<a class="btn btn-blue" plugin="dropdown" data-options="'.$this->fn->stringify( array(
	                        'select' => $dropdown,
	                        'settings' =>array(
	                            'axisX'=> 'right',
	                            // 'parent'=>'.setting-main'
	                        ) 
	                    ) ).'"><i class="icon-print"></i><span class="mls">Print</span><i class="mls icon-angle-down"></i></a>';*/
				?></span></li>
				<li></li>
			</ul>

			<ul class="rfloat" ref="control">
				<li><label class="fwb fcg fsm" for="limit">Items per pages</label>
				<select ref="selector" id="limit" name="limit" class="inputtext"><?php
					echo '<option value="20">20</option>';
					echo '<option selected value="50">50</option>';
					echo '<option value="100">100</option>';
					echo '<option value="200">200</option>';
				?></select><span id="more-link">Loading...</span></li>
			</ul>
		</div>
		<!--  -->

		<div class="clearfix pbm">
			<ul class="lfloat">

				<li class="mt"><a class="btn js-refresh" data-plugins="tooltip" data-options="<?=$this->fn->stringify(array('text'=>'refresh'))?>"><i class="icon-refresh"></i></a></li>	

				<li><label for="closedate" class="label">Close date</label>
				<select ref="closedate" name="closedate" class="inputtext">
					<option value="daily"><?=$this->lang->translate('Today')?></option>
					<option value="weekly"><?=$this->lang->translate('Weekly')?></option>
					<option value="monthly"><?=$this->lang->translate('Monthly')?></option>
					<option divider></option>
					<option value="latest" selected><?=$this->lang->translate('Lastest')?></option>
					<option divider></option>
					<option value="custom"><?=$this->lang->translate('Custom')?></option>
				</select></li>
				
				<?php if( !empty($this->country) ) { ?>
				<li><label for="position" class="label">Country</label>
				<select ref="selector" name="group" class="inputtext"><?php
					$option = '';
					$countValTotal = 0;
					foreach ($this->country as $key => $value) {
						if( empty($value['count'])  ) continue;

						$selected = '';
						if( !empty($this->pageSettings['company']) ){
							if( $this->pageSettings['company']==$value['id'] ){
								$selected = ' selected';
							}
						}

						$countValTotal += !empty($value['count']) ? $value['count']:0;
						$countVal = !empty($value['count']) ? " ({$value['count']})":'';
						$option .= '<option'.$selected.' value="'.$value['id'].'">'.$value['name'].$countVal.'</option>';
					}
					
					echo '<option value="">All'. ( $countValTotal>0 ? " ({$countValTotal})":'' ) .'</option>'. $option;

				?></select></li>
				<?php } ?>
				
				<?php if( !empty($this->category) ) { ?>
				<li><label for="category" class="label">Category</label>
				<select ref="selector" id="category" name="category" class="inputtext"><?php
					$option = '';
					$countValTotal = 0;
					foreach ($this->category as $key => $value) {
						if( empty($value['count'])  ) continue;

						$selected = '';
						if( !empty($this->pageSettings['company']) ){
							if( $this->pageSettings['company']==$value['id'] ){
								$selected = ' selected';
							}
						}

						$countValTotal += !empty($value['count']) ? $value['count']:0;
						$countVal = !empty($value['count']) ? " ({$value['count']})":'';
						$option .= '<option'.$selected.' value="'.$value['id'].'">'.$value['name'].$countVal.'</option>';
					}
					
					echo '<option value="">All'. ( $countValTotal>0 ? " ({$countValTotal})":'' ) .'</option>'. $option;

				?></select></li>
				<?php } ?>
			</ul>

			<ul class="rfloat">
				<li class="mt"><form class="form-search" action="#">
					<input class="inputtext search-input" type="text" id="search-query" placeholder="<?=$this->lang->translate('search')?>" name="q" autocomplete="off">
					<span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span>
				</form></li>
			</ul>
		</div>
		
	</div>

</div>