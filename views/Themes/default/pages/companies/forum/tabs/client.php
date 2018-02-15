<?php 

$i = 0;
foreach ($this->clientList as $key => $val) {
	$i++;

?>
<div class="section-item">
	<div class="sequence-float"><?=$i?></div>
	<header class="section-item-header clearfix">
		<div class="title clearfix">
			<div class="avatar lfloat mrm"></div>
			<div style="overflow: hidden;"><h3><?=$val['name']?></h3></div>
		</div>
		<div class="actions">
			<a class="btn-icon"><i class="icon-pencil"></i></a>
			<a class="btn-icon"><i class="icon-trash-o"></i></a>
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