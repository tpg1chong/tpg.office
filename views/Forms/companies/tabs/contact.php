<div class="phl">

	<form class="mbl" data-action-contact="search">
		<table>
			<tr>
				<td style="width: 100%">
					<!-- <div class="" style="position: relative;">
					<input type="text" name="q" class="inputtext" autocomplete="off" placeholder="Company contact search.." style="width: 100%;height: 30px;line-height: 30px; padding-right: 30px">
					<button type="submit" style="position: absolute;right: 0;top: 0;width: 30px;height: 30px;line-height: 30px;text-align: center;"><i class="icon-search"></i></button>
					</div> -->
					<h3 class="fwn">Company contact</h3>
					<div class="fsm" style="margin-top: 2px"><?=empty($this->contactList) ? 'No result': count($this->contactList).' results'?></div>
				</td>
				<td><a data-action-contact="add" data-href="<?=URL?>companies/contactAdd/" class="btn btn-blue"><i class="icon-plus mrs"></i><span>New Contant</span></a></td>
			</tr>
		</table>
	</form>


	<div class="section-item-wrap">
		<?php 

		$i = 0;
		foreach ($this->contactList as $key => $contact) {
			$i++;

		?>
		<div class="section-item">
			<div class="sequence-float"><?=$i?></div>
			<header class="section-item-header clearfix">
				<div class="title clearfix">
					<div class="avatar lfloat mrm"></div>
					<div style="overflow: hidden;"><h3><?=$contact['name']?></h3></div>
				</div>
				<div class="actions group-btn">
					<a class="btn" data-action-contact="edit" data-href="<?=URL?>companies/contactEdit/"  data-action-contact="edit" data-id="<?=$contact['id']?>"><i class="icon-pencil"></i></a><a class="btn" data-action-contact="trash" data-id="<?=$contact['id']?>"><i class="icon-trash-o"></i></a>
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
	</div>

</div>