	<div class="setting-header clearfix">

		<div class="clearfix">
			<div class="rfloat">
				<a class="btn btn-blue" data-plugins="dialog" href="<?=URL?>accounts/add"><i class="icon-plus mrs"></i><span><?=Translate::Val('Add New')?></span></a>
			</div>

			<div class="lfloat">
				<div class="setting-title" style="line-height: 30px"><i class="icon-users mrs"></i><?=Translate::Val('Accounts')?></div>

				<select class="inputtext" id="Role" data-action="filter" name="enabled" style="display: inline-block;">
				<?php

				$this->role = isset($_REQUEST['role']) ? $_REQUEST['role']: '';

				echo '<option value="">-- All --</option>';
				foreach ($this->roles as $key => $value) {

					$sel = $this->role==$value['id'] ? ' selected':'';
					echo '<option'.$sel.' value="'.$value['id'].'">'. ucfirst($value['name']).'</option>';
				}

				?>

				</select>

				
			</div>

		</div>

		<div class="clearfix mts">

			<div class="lfloat">
				<label class="checkbox" for="EnabledOnly"><input type="checkbox" checked id="EnabledOnly"><span class="mls">Show Enabled Only</span></label>
			</div>

			<div class="rfloat">
				<input type="text" name="q" placeholder="Search.." class="inputtext" />
				
			</div>
		</div>
		
	</div>
	<!-- end: .setting-header -->

	<section class="setting-section">
		<table class="settings-table admin"><tbody>
			<tr>
				<th class="name">Name</th>
				
				<th class="status">Role</th>
				<th class="status">Enable</th>
				<th class="status">Outside</th>
				
				<th class="date">Last Visit</th>
				<th style="width: 24px;"></th>
				<th style="width: 24px;"></th>
				<th style="width: 30px;"></th>
			</tr>

			<?php foreach ($this->dataList as $key => $item) { ?>
			<tr data-id="<?=$item['id']?>" data-enable="<?=$item['enable']?>" data-outside="<?=$item['allowoutside']?>" data-role="<?=$item['role_id']?>" class="<?=empty($item['enable']) ? 'hidden_elem':''?>">
				<td class="name"><?php

				echo '<div class="anchor clearfix"><div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div><div class="content"><div class="spacer"></div><div class="massages"><div class="fullname">';

				echo $item['fullname'];

				echo ' <div class="subname fwb fcg">@'.$item['username'].'</div>';

				echo '</div> </div></div></div>';

				?></td>

				<td class="status"><?php 

					echo '<select class="inputtext selector" name="user_role_id">';

					foreach ($this->roles as $val) {

						$see = $item['role_id']==$val['id'] ? ' selected':'';
						echo '<option'.$see.' value="'.$val['id'].'">'.$val['name'].'</option>';
					}

					echo '</select>';

				?></td>

				<td class="td-checkbox" style="width: 24px;text-align: center;">
					<label class="checkbox"><input type="checkbox" name=""<?=!empty($item['enable']) ? ' checked':''?>></label>
				</td>
				<td class="td-checkbox" style="width: 24px;text-align: center;">
					<label class="checkbox"><input type="checkbox" name=""<?=!empty($item['allowoutside']) ? ' checked':''?>></label>
				</td>

				<td class="date" style="white-space: nowrap;"><?php

					if( !empty($item['updatedate']) ){

						echo $this->fn->q( 'time' )->stamp($item['updatedate']);
					}
					else{
						echo '-';
					}
				?></td>

				
				<td><a class="fcg link-hover-opacity" href="<?=URL?>accounts/change_password/<?=$item['id']?>" data-plugins="dialog"><img  src="<?=IMAGES?>reset-password-24.svg"></a></td>
				<td><a class="fcg link-hover-opacity" href="<?=URL?>accounts/edit/<?=$item['id']?>" data-plugins="dialog"><img src="<?=IMAGES?>edit-person-24.svg"></a></td>
				<td class="whitespace">
					<?php

					$dropdown = array();

					if( empty($item['is_owner']) ){

						/*if( !empty($item['enabled'])  ){
							$dropdown[] = array(
				                'text' => 'ปิดการใช้งาน',
				                'href' => URL.'accounts/change_enabled/'.$item['id'].'/0',
				                'attr' => array('data-plugins'=>'dialog'),
				                // 'icon' => 'remove'
				            );
						}
						else{
							$dropdown[] = array(
				                'text' => 'เปิดการใช้งาน',
				                'href' => URL.'accounts/change_enabled/'.$item['id'].'/1',
				                'attr' => array('data-plugins'=>'dialog'),
				                // 'icon' => 'remove'
				            );
						}*/
						

						$dropdown[] = array(
			                'text' => Translate::Val('Delete'),
			                'href' => URL.'accounts/delete/'.$item['id'],
			                'attr' => array('data-plugins'=>'dialog'),
			                // 'icon' => 'remove'
			            );

		            }

		            if( !empty($dropdown) ){

		            
						echo '<a data-plugins="dropdown" class="btn btn-no-padding" data-options="'.$this->fn->stringify( array(
	                        'select' => $dropdown,
	                        'settings' =>array(
	                            'axisX'=> 'right',
	                            'parent'=>'.setting-main'
	                        ) 
	                    ) ).'"><i class="icon-ellipsis-v"></i></a>';

					}


					?>
						
				</td>

			</tr>
			<?php } ?>
		</tbody></table>
	</section>
	<!-- end: .setting-section -->


<script type="text/javascript">


	/* -- EnabledOnly -- */
	function EnabledOnly() {
		var is = $('#EnabledOnly').prop('checked');
		var box = $('[data-enable=0]');

		var role = $('#Role').val();
		
		if( is ){
			box.addClass('hidden_elem');

			if( role!='' ){
				
				$('[data-enable=1]').removeClass('hidden_elem').not('[data-role='+ role +']').addClass('hidden_elem');
			}
			else{
				$('[data-enable=1]').removeClass('hidden_elem');
			}
		}
		else{
			box.removeClass('hidden_elem');

			if( role!='' ){
				
				$('[data-role]').removeClass('hidden_elem').not('[data-role='+ role +']').addClass('hidden_elem');
			}
			else{
				$('[data-role]').removeClass('hidden_elem');
			}

		}

	}
	EnabledOnly();
	$('#EnabledOnly').change(function() { EnabledOnly(); });


	$('#Role').change(function() {
		EnabledOnly();
	});


	$(function () {

		/*$('[data-action=filter]').change(function() {
			window.location = Event.URL + 'admin/accounts?role=' + $(this).val();
		});*/

		$('body').delegate('#auto_password', 'change', function () {

			var is = $(this).prop('checked'),
				$fieldset = $('.form-emp-add #password_fieldset');

			$fieldset.toggle( !is );

			if( !is ){
				$fieldset.find('.inputtext').focus();
			}
		});

		var password;
		$('body').delegate('.form-emp-add', 'submit', function (e) {
			e.preventDefault();

			var $form = $(this);
			Event.inlineSubmit( $form ).done(function( resp ){

				Event.processForm($form, resp);

				password = resp.password;

				if( resp.error ){
					return false;
				}


				Dialog.open({
					'title': 'สร้างผู้ใช้ใหม่',
					'body': '<div class="form-vertical">'+

						'<i class="icon-check mrs"></i>'+ resp.data.name + ' เป็นผู้ใช้แล้ว <br><br>' + 
						'<fieldset class="control-group">'+
							'<label class="control-label">ชื่อผู้ใช้</label>' +
							'<div class="controls">' + resp.data.login + '</div>'+ 
						'</fieldset>'+
						'<fieldset class="control-group">' + 
							'<label class="control-label">รหัสผ่าน</label>' +
							'<div class="controls"><span id="show-password">******</span> <a class="show-password-toggle">แสดงรหัสผ่าน</a></div>'+ 
						'</fieldset>' +

					'</div>',
					'button': '<button type="button" role="dialog-close" class="btn js-close"><span class="btn-text">ปิด</span></button>',
					'form': '<div class="form-conf">',
				});

			});
		});

		$('body').delegate('.form-conf .js-close', 'click', function () {

			setTimeout(function() {
				window.location = window.location.href;
			}, 800);
		});
		

		$('body').delegate('.show-password-toggle', 'click', function () {

			var $this = $(this),
				box = $('#show-password');


			if( box.hasClass('show') ){
				$this.text('แสดงรหัสผ่าน');
				box.removeClass('show').text('******');
			}
			else{

				$this.text('ซ่อนรหัสผ่าน');
				box.addClass('show').text( password );
			}

		});


		$('body').delegate('.form-reset-password input[name=password_auto]', 'change', function () {

			var $form = $('.form-reset-password'),
				is = $(this).prop('checked');


			if( is ){
				$form.find('#password_new, #password_confirm').val('123456').prop('disabled', true).addClass('disabled');
			}
			else{
				$form.find('#password_new, #password_confirm').val('').prop('disabled', false).removeClass('disabled');
			}
		});
		
		$('body').delegate('.form-reset-password', 'submit', function (e) {
			e.preventDefault();

			var $form = $(this);
			Event.inlineSubmit( $form ).done(function( resp ){

				Event.processForm($form, resp);

				if( resp.error ){
					return false;
				}

				password = resp.password;
				Dialog.open({
					'title': 'รีเซ็ตรหัสผ่าน',
					'body': '<div class="form-vertical">'+

						'<fieldset class="control-group">' + 
							'<label class="control-label">รหัสผ่าน</label>' +
							'<div class="controls"><span id="show-password">******</span> <a class="show-password-toggle">แสดงรหัสผ่าน</a></div>'+ 
						'</fieldset>' +

					'</div>',
					'button': '<button type="button" role="dialog-close" class="btn"><span class="btn-text">ปิด</span></button>',
				});


			});
		});



		/*$('select.selector').change(function() {
			var $this = $(this);

			var id = $this.closest('tr').attr('data-id');

			$.get( Event.URL + 'accounts/update', {
				id: id,
				name: $this.attr('name'),
				value: $this.val()
			});
		});*/

	});
</script>