<?php

$url = URL.'property/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<span class="gbtn"><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_near"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span>

</div>

<div class="setting-title"><?=$this->lang->translate('Near')?></div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="email"><?=$this->lang->translate('Keyword')?></th>
			<th class="name"><?=$this->lang->translate('Name')?></th>
			<th class="status"><?=$this->lang->translate('Type')?></th>
			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="email"><?=$item['keyword']?></td>
			<td class="name">
				<span class="fwb"><?=$item['name']?></span>
			</td>
			<td class="status"><?=$item['type_name']?></td>

			<td class="actions">
				<div class="group-btn whitespace">
					<a data-plugins="dialog" href="<?=$url?>edit_near/<?=$item['id']?>" class="btn"><i class="icon-pencil"></i></a>
					<a data-plugins="dialog" href="<?=$url?>del_near/<?=$item['id']?>" class="btn"><i class="icon-trash"></i></a>
				</div>
			</td>

		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>