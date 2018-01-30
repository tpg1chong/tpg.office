<?php

$url = URL.'property/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<span class="gbtn"><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_zone"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span>

</div>

<div class="setting-title"><?=$this->lang->translate('Zone')?></div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="number"><?=$this->lang->translate('Code')?></th>
			<th class="name"><?=$this->lang->translate('Name')?></th>
			<th class="status"><?=$this->lang->translate('Latitude')?></th>
			<th class="status"><?=$this->lang->translate('Longitude')?></th>
			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="number"><?=$item['code']?></td>
			<td class="name">
				<span class="fwb"><?=$item['name']?></span>
			</td>
			<td class="status"><?=$item['lat']?></td>
			<td class="status"><?=$item['lng']?></td>
			<td class="actions">
				<div class="group-btn whitespace">
					<a data-plugins="dialog" href="<?=$url?>edit_zone/<?=$item['id']?>" class="btn"><i class="icon-pencil"></i></a>
					<a data-plugins="dialog" href="<?=$url?>del_zone/<?=$item['id']?>" class="btn"><i class="icon-trash"></i></a>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>