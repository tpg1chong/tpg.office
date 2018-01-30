	<div class="setting-header clearfix">
		<div class="rfloat">
			<a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add"><i class="icon-plus mrs"></i><span><?=Translate::Val('Add New')?></span></a>
		</div>
		<div class="lfloat">
			<div class="setting-title" style="line-height: 30px"><?=Translate::Val('Property Zone')?></div>
		</div>
		
	</div>
	<!-- end: .setting-header -->

	<section class="setting-section">
		<table class="settings-table admin"><tbody>
			<tr>
				<th class="name">Name</th>
				
				<th class="td-checkbox">Enable</th>
				
				<th style="width: 30px;"></th>
			</tr>

			<?php foreach ($this->dataList as $key => $item) { ?>
			<tr data-id="<?=$item['id']?>">
				<td class="name"><?php

				echo $item['name'];

				?></td>

				<td class="td-checkbox" style="width: 24px;text-align: center;">
					<label class="checkbox"><input type="checkbox" name=""<?=!empty($item['active']) ? ' checked':''?>></label>
				</td>

				<td class="whitespace">
					<?php

					$dropdown = array();

					$dropdown[] = array(
		                'text' => Translate::Val('Delete'),
		                'href' => $url.'del/'.$item['id'],
		                'attr' => array('data-plugins'=>'dialog'),
		                // 'icon' => 'remove'
		            );


		            if( !empty($dropdown) ){

		            	echo '<span class="group-btn">';

		            	echo '<a class="btn"><i class="icon-pencil mrs"></i>'.Translate::Val('Edit').'</a>';
						echo '<a data-plugins="dropdown" class="btn btn-no-padding" data-options="'.$this->fn->stringify( array(
	                        'select' => $dropdown,
	                        'settings' =>array(
	                            'axisX'=> 'right',
	                            'parent'=>'.setting-main'
	                        ) 
	                    ) ).'"><i class="icon-ellipsis-v"></i></a>';


	                    echo '</span>';

					}


					?>
						
				</td>

			</tr>
			<?php } ?>
		</tbody></table>
	</section>
	<!-- end: .setting-section -->


<script type="text/javascript">
$(function () {


});
</script>