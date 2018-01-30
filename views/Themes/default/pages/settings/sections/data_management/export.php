<div class="pal" style="max-width: 772px">

	<div class="SettingsContent_title">Export Data</div>
	<div class="SettingsContent_description">Export data to an Excel file.</div>
	
	<div class="mvm uiBoxWhite">
		<?php

		$arr = array();
		$arr['Customer'][] = array('id'=>'','name'=>'Customer');
		$arr['Customer'][] = array('id'=>'','name'=>'Companie');

		$arr['People'][] = array('id'=>'','name'=>'People');
		$arr['People'][] = array('id'=>'','name'=>'People Position');
		$arr['People'][] = array('id'=>'','name'=>'Organization');
		$arr['People'][] = array('id'=>'','name'=>'Organization Category');

		$arr['Property'][] = array('id'=>'','name'=>'Property');
		$arr['Property'][] = array('id'=>'','name'=>'Property listing');
		$arr['Property'][] = array('id'=>'','name'=>'Property building');
		$arr['Property'][] = array('id'=>'','name'=>'Property Type');
		$arr['Property'][] = array('id'=>'','name'=>'Property zone');

		/*$arr['Accounts'][] = array('id'=>'','name'=>'Department');
		$arr['Accounts'][] = array('id'=>'','name'=>'Position');
		$arr['Accounts'][] = array('id'=>'','name'=>'Employees');*/
		?>
		
		<div class="pal">
			<?php foreach ($arr as $key => $obj) { 

				echo '<div class="clearfix">';
					echo '<div class="mbs fwb fcg fsm">'.$key.'</div>';
				foreach ($obj as $value) {
					echo '<div class="Import_formatBlock" data-id="'.$value['id'].'"><span class="text">'.$value['name'].'</span></div>';
				}

				echo '</div>';
			?>
			
			<?php } ?>


			<div class="pam uiBoxGray mtl hidden_elem">
				<h3>Tips For a Smooth Import</h3>
				<ul class="uiList uiListStandard mts">
			      <li>Import companies first, then people and then opportunities. Importing in this order will ensure that all records are related correctly. Leads can be imported at any time.</li>
			      <li>We recommend using our formatted Excel and CSV templates to ensure your data is formatted properly. You'll be given the option to download the templates during the import process. Learn more about the import process <a href="#" target="_blank">here</a>.</li>
			      <li>We support file sizes up to 3MB. If your file is larger than 3MB, we suggest you break it up into multiple files.</li>
			      <li>It's a good idea to do a test with a smaller import file first to make sure your file works.</li>
			      <li>Set up your <a id="ember5153" href="#" class="ember-view">custom fields</a> and <a id="ember5154" href="#" class="ember-view">contact types</a> before you import so you can import to those fields.</li>
			      <li>The first row of your import file must be the column headers you wish to import to.</li>
			      <li>If a column within the data table does not have a column header, your file will not import properly.</li>
			      <li>If there is data in a cell outside of the data table, your file will not import properly.</li>
			      <li>Importing tags with more than 50 characters will cause that import row to fail.</li>
			      <li>For additional support, visit our <a href="#" target="_blank">import support articles</a> or send an email to <a href="mailto:support@chong.com" target="_top">support@chong.com</a>.</li>
			    </ul>
			</div>
		</div>
		
		<!-- <div class="pam uiBoxGray uiBorderTop">
			<a class="btn">Cancel</a>
		</div> -->
	</div>


	
</div>