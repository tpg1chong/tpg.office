<?php require 'init.php'; ?>
<div id="mainContainer" class="clearfix forum" data-plugins="main" data-ref="forumlists">

	<!-- left -->
	<div class="forum-left" role="left" data-width="400"">

		<div class="forum-left-header" role="leftHeader" style="">

			<form class="forum-left-form-wrap" data-action="search">

				<div class="forum-left-form has-add">
					<input id="keywords" type="text" name="keywords" class="inputtext forum-inputsearch" placeholder="Search.." autocomplete="off">
					<div class="forum-left-form-controls">
						<button class="control" type="button"><i class="icon-search"></i></button><button class="btn control toggle-filter" type="button" title="Filter"><i class="icon-filter"></i></button><button data-action-company="create" data-href="<?=URL?>companies/add" class="control plus btn btn-blue" type="button" title="Create company"><i class="icon-plus"></i></button>
					</div>
				</div>
				
				<ul class="forum-left-form-filters clearfix">
					<li style="display: none;"><span class="fwb" data-text="total">0</span> results</li>
					<li class="rfloat">
						<label class="label">Sort:</label> 
						<select class="inputtext" data-filter="sort">
							<option value="company.createdate">Register</option>
							<option value="company.updatedate">Last Update</option>
							<option value="company.name">A - Z</option>
						</select>
					</li>

				</ul>
			</form>
		</div>
		<!-- end: leftHeader -->

		<div class="forum-items-wrap has-loading" role="leftContent">

			<div class="forum-items" data-ref="listsbox"></div>


			<div class="forum-item-loader">Loading...</div>
			<div class="forum-item-empty">No Result</div>
			<div class="forum-item-error">Error</div>

			<!-- <div class="forum-items-footer">
				
			</div> -->
		</div>
	</div>
	<!-- end: left -->


	<!-- content -->
	<div class="forum-content hasLeft has-empty" role="content">

		<div role="toolbar" class="forum-toolbar">


			<div class="clearfix">
				<div class="forum-toolbar-title lfloat">
					<h2 class="title" data-profile="name">&nbsp;</h2>
					<div class="fsm mvs" style="margin-top: 2px">
						<div class="hidden_elem"><span data-profile="business_name"></span></div>
					</div>
				</div>
				<div class="rfloat">
					<a class="btn btn-red" data-action-profile="delete" data-href="<?=URL?>companies/del/"><i class="icon-remove"></i><span class="mls">Delate</span></a>
				</div>
			</div>


			<nav class="forum-toolbar-nav">
				<a class="forum-toolbar-navItem active" data-action-tab="about"><i class="icon-info-circle mrs"></i><span>Info</span></a>
				<a class="forum-toolbar-navItem" data-action-tab="contact"><i class="icon-address-book-o mrs"></i><span>Contact</span><span class="mls hidden_elem">[<span data-profile="contactTotal">0</span>]</span></a>
				<a class="forum-toolbar-navItem" data-action-tab="client"><i class="icon-user-circle-o mrs"></i><span>Client</span><span class="hidden_elem mls">[<span data-profile="clientTotal"></span>]</span></a>
			</nav>
		</div>

		<div class="forum-profile-wrap" role="main">

			<div class="forum-profile-sile" style="width: 198px">

				<div class="forum-profile-avatar">
					<i class="icon-building-o"></i>
				</div>

				<div class="uiBoxYellow phm mvm pvs hidden_elem">
					<p data-profile="note"></p>
				</div>
				<section>
					<table class="table-dataInfo">
						<tbody><tr>
							<td class="label">Expats:</td>
							<td class="data">
								<div class="hidden_elem"><span data-profile="expatTotal"></span></div>
							</td>
						</tr>

						<tr>
							<td class="label">Contacts:</td>
							<td class="data">
								<div class="hidden_elem"><span data-profile="contactTotal"></span></div>
							</td>
						</tr>

						<tr>
							<td class="label">Client:</td>
							<td class="data">
								<div class="hidden_elem"><span data-profile="clientTotal"></span></div>
							</td>
						</tr>
						
						<tr>
							<td class="label">Created Date:</td>
							<td class="data"><span data-profile="created_str"></span><div class="check-hide hidden_elem" style="font-size: 11px;">by <a data-profile="created_author_username"></a></div></td>
						</tr>

						<tr>
							<td class="label">Last Update:</td>
							<td class="data"><span data-profile="updated_str"></span><div class="check-hide hidden_elem" style="font-size: 11px;">by <a data-profile="updated_author_username"></a></div></td>
						</tr>

					</tbody></table>
				</section>
			</div>

			<div class="forum-profile-content has-loading" style="max-width: 860px">
				
				<div class="forum-profile-main" data-ref="content"></div>

				<div class="forum-profile-alert forum-alert">
					<div class="loading">Loading...</div>
					<div class="empty"></div>
				</div>
			</div>
		</div>
		<!-- end: main -->

		<div class="forum-content-alert forum-alert">
			<div class="loading">Loading...</div>
			<div class="empty">Choose company the left side entry.</div>
		</div>

	</div>
	<!-- end: content -->

</div>
<!-- end: mainContainer -->