<?php 

$_year = '';
foreach ($this->item['customers'] as $val) {


	if( !empty($val['create_date']) ){
		$year = date('Y', strtotime($val['create_date']));
	}

	if( !empty($year) ){

		if( $year != $_year){
			$_year = $year;
			echo '<div class="section-item-bookmark"><span>'.$_year.'</span></div>';
		}
	}
	
	switch ($val['prefix']) {
		case 0: $prefix_str = 'Mr.'; break;
		case 1: $prefix_str = 'Ms.'; break;
		case 2: $prefix_str = 'Mrs.'; break;
		case 3: $prefix_str = 'Dr.'; break;
		
		default: $prefix_str = ''; break;
	}

	$name = "{$prefix_str}{$val['firstname']} {$val['lastname']}";
?>
<div class="section-item">
	<header class="section-item-header clearfix">
		<div class="title clearfix">
			<div class="avatar lfloat mrm"></div>
			<div style="overflow: hidden;"><h3><?=$name?></h3></div>
		</div>
		<div class="actions">
			<!-- <a class="button-icon"><i class="fa fa-pencil"></i></a> -->
			<!-- <a class="button-icon"><i class="fa fa-trash-o"></i></a> -->
		</div>
	</header>
	<div class="section-item-desc">
		<table><tbody>
			<tr>
				<td style="width: 33.33%">
					<label>Register:</label>
					<p><?php
					if( !empty($val['create_date']) ){

						echo '<span>'. date('F j, Y', strtotime($val['create_date'])).'</span>';
						if( !empty($val['user_create_username']) ){
							echo '<span class="fcg"> - By '.$val['user_create_username'].'</span>';
						}
					}
					else{
						echo '-';
					}
					
					?></p>
				</td>
				<td style="width: 33.33%;white-space: nowrap;">
					<label>Budget:</label>
					<p><?= !empty($val['budget']) ? number_format($val['budget']).' ฿': '-' ?></p>
				</td>
				<td style="width: 33.33%">
					<label>Status:</label>
					<p><?= !empty($val['status_name']) ? $val['status_name']: '-' ?></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label>Email:</label>
					<p><?= !empty($val['email']) ? $val['email']: '-' ?></p>
				</td>
				<td>
					<label>Phone:</label>
					<p><?= !empty($val['phone']) ? $val['phone']: '-' ?></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label>Note:</label>
					<p><?= !empty($val['note']) ? $val['note']: '-' ?></p>
				</td>
				<td>
					<label>Last Update:</label>
					<div>
						<?php
						if( !empty($val['update_date']) ){

							echo '<p>'.$val['update_date'].'</p>';
							if( !empty($val['user_update_username']) ){
								echo '<div style="font-size: 11px;">By '.$val['user_update_username'].'</div>';
							}
						}
						else{
							echo '<p>-</p>';
						}
						
						?>
					</div>
				</td>
			</tr>
		</tbody></table>
	</div>
</div>

<?php } ?>



<?php 
if( count($item['customers'])==0 ){ ?>
	<div class="section-empty"><h2>No Result</h2></div>
<?php } ?>
