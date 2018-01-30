<div class="pal" style="max-width: 980px">

	<div class="SettingsContent_title">Import Data</div>
	<div class="SettingsContent_description">Import data CSV files, or Excel files.</div>
	

	<div class="pam uiBoxYellow mvm hidden_elem">
		<h3>Past Imports</h3>
		<div class="mts">
			<p>You have no imports on record. Any imports initiated henceforth will show up here!</p>
		</div>
	</div>

	<div class="mvm uiBoxWhite uiStepList_Container" data-plugins="importdata">
		<?php

		$a = array();
		$a[] = array('text'=>'Type','name'=>1);
		// $a[] = array('text'=>'Where file','name'=>2);
		$a[] = array('text'=>'Choose file','name'=>2);
		$a[] = array('text'=>'Match data','name'=>3);
		echo $this->fn->stepList($a, 1, true, false, true);

		$arr = array();
		$arr['Customer'][] = array('id'=>'company','name'=>'Company Data');
		$arr['Customer'][] = array('id'=>'customer','name'=>'Customer Data');

		$arr['People'][] = array('id'=>'organization_category','name'=>'Organization Category');
		$arr['People'][] = array('id'=>'organization','name'=>'Organization Data');
		$arr['People'][] = array('id'=>'people_position','name'=>'People Position');
		$arr['People'][] = array('id'=>'people','name'=>'People Data');
		

		$arr['Property'][] = array('id'=>'property_type','name'=>'Property Type');
		$arr['Property'][] = array('id'=>'property_zone','name'=>'Property zone');
		$arr['Property'][] = array('id'=>'property_building','name'=>'Property building');
		$arr['Property'][] = array('id'=>'property','name'=>'Property Data');

		// $arr['Property'][] = array('id'=>'','name'=>'Property listing');

		/*$arr['Accounts'][] = array('id'=>'','name'=>'Department');
		$arr['Accounts'][] = array('id'=>'','name'=>'Position');
		$arr['Accounts'][] = array('id'=>'','name'=>'Employees');*/
		?>
		
		<div class="pal uiStepList_body">


			<div class="uiStepList_section">
			<?php foreach ($arr as $key => $obj) { 

				echo '<div class="clearfix">';
					echo '<div class="mbs fwb fcg fsm">'.$key.'</div>';
				foreach ($obj as $value) {
					echo '<div class="Import_formatBlock" data-action="'.$value['id'].'"><span class="text">'.$value['name'].'</span></div>';
				}

				echo '</div>';
			?>
			<?php } ?>
			</div>

			<!-- Choose File -->
			<div class="uiStepList_section hidden_elem" style="max-width: 750px">

				<div class="row-fluid clearfix">

					<div class="span8"><div class="FileDrop Import_dropZone">

						<div class="FileDrop_content">
							<i class="icon-cloud-upload"></i>
							<p class="u-darkText">Drag and drop your file here</p>
							<p class="u-grayText">- OR -</p>
							<label class="btn btn-primary btn-choosefile">Choose File<input type="file" id="file1" name="file1" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" /></label>
						</div>
						<div class="FileDrop_loading">
						  <div class="FileDrop_loadSpinner">
						    <label>Loading file...</label>
						    <div>This might take a while depending on the size of your file</div>
						  </div>
						</div>

					</div></div>
					
					<div class="span4">
						<div class="u-darkText u-bold mbm fwb">Need help formatting your Excel file?</div>
						<div class="u-grayText fcg">Download our <a href="#">pre-formatted Excel template</a>and follow the formatting for the best results.</div>
					</div>

				</div>
				<div class="mtl"><a class="btn" data-step="0">Back</a></div>
			</div>
			<!-- end: Choose File -->

			<div class="uiStepList_section Import_matchData hidden_elem">
				
				<div class="Import_matchData_header">
					<div class="ImportStats">
						<div class="ImportStats_stat u-ellipsis"><label>File Name:</label><span data-val="filename">2017-Monthly-Calendar.xlsx</span></div>
						<div class="ImportStats_stat"><label>Total Columns:</label><span data-val="column">15</span></div>
						<div class="ImportStats_stat"><label>Total Rows:</label><span data-val="row">38</span></div>
						<div class="ImportStats_spacer"></div>
					</div>

					<div class="Import_optionPanel mvm">
					    <span><label tabindex="0" class="checkbox"><input type="checkbox" tabindex="0" name="first_row" checked data-option="first_row"><span>Exclude the first row</span></label></span>
						
						<!-- <span><label tabindex="0" class="checkbox"><input type="checkbox" tabindex="0"><span>Ignore all unselected fields</span></label></span> -->
					</div>
				</div>

				<div class="Import_matchData_body" style="margin: 0 -20px;overflow-x: auto;overflow-y: hidden;border-bottom: 1px solid #ccc;">
					<div class="Import_filePanel" style="width: <?=180*5?>px;position: absolute;top: 0;left: 0;bottom: 0;">
						<div class="Import_tablePanel_header" style="position: absolute;left: 0;top: 0;right: 0;background-color: #fff;z-index: 10">
							<div class="Import_tablePanel-field" style="padding-right: 17px;">
								<table><tbody ref="listsfield"></tbody></table>
							</div>
							<div class="Import_tablePanel title" style="padding-right: 17px;">
								<table><tbody ref="listtitle"></tbody></table>
							</div>
						</div>
						<div class="Import_tablePanel-data" style="position: absolute;top:104px;left: 0;right: 0;bottom: 0;overflow-y:scroll;">
							<table><tbody ref="listsbox"></tbody></table>
						</div>
					</div>
				</div>

				<div class="Import_matchData_bottom clearfix" style="margin: 0 -20px -20px;padding: 10px;background-color: #eceef4;">
					<!-- <div class="lfloat">
						<div class="mtl"><a class="btn" data-step="0">Back</a></div>
						<div class="Import_actionPanel_message"></div>
					</div> -->
					<div class="rfloat">
						<a class="btn btn-link" data-step="1">Cancel</a>
						<button class="btn btn-primary">Import</button>
					</div>
				</div>

			</div>


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