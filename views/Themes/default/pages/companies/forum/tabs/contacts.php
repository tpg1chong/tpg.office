<?php 


for ($i=1; $i < 5; $i++) { ?>
<div class="section-item">
	<div class="sequence-float"><?=$i?></div>
	<header class="section-item-header clearfix">
		<div class="title clearfix">
			<div class="avatar lfloat mrm"></div>
			<div style="overflow: hidden;"><h3>AAA</h3></div>
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
					<label>Position:</label>
					<p><?= !empty($contact['position']) ? $contact['position']: '-' ?></p>
				</td>
				<td style="width: 33.33%;white-space: nowrap;">
					<label>Email:</label>
					<p><?= !empty($contact['email']) ? $contact['email']: '-' ?></p>
				</td>
				<td style="width: 33.33%">
					<label>Phone:</label>
					<p><?= !empty($contact['phone']) ? $contact['phone']: '-' ?></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label>Location:</label>
					<p><?= !empty($contact['address']) ? $contact['address']: '-' ?></p>
				</td>
				<td>
					<label>Mobile:</label>
					<p><?= !empty($contact['mobile']) ? $contact['mobile']: '-' ?></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label>Note:</label>
					<p><?= !empty($contact['note']) ? $contact['note']: '-' ?></p>
				</td>
				<td>
					<label>Last Update:</label>
					<div>
						<?php
						if( !empty($contact['update_date']) ){

							echo '<p>'.$contact['update_date'].'</p>';
							if( !empty($contact['user_update_username']) ){
								echo '<div style="font-size: 11px;">By '.$contact['user_update_username'].'</div>';
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