<?php $dev = false; if($_SERVER['SERVER_NAME'] == 'rms.dev') $dev = true; ?>
<body>
	<div data-role="page" data-theme="a">
		<div data-role="header">
			<a href="/auth/logout/" class="ui-btn ui-btn-left" rel="external" data-ajax="false"><i class="zmdi zmdi-power-off zmd-2x"></i></a>
		<h1>
			<div class="row">
				<div class="col-xs">
					<div class="box">
					<form action="/" method="POST">
						<select name="bus" class="ui-btn-left" onchange="this.form.submit()">
						<? foreach ($bus_list as $bu) { ?>
			  				<option value="<?=$bu->id?>" <? if($bu_id == $bu->id) echo "selected"; ?>><?=$bu->name?></option>
						<? } ?>
						</select>
					</form>
					</div>
				</div>
				<div class="col-xs">
					<div>
						RMS Hank | <?=$bu_name?> | <?=$username?> - <?=$user_groups->name?>
					</div>
				</div>
				<div class="col-xs">
				</div>
			</div>
			</h1>
		</div>
		<div data-role="content" data-theme="a">
			<?php 
			$last = $ca['last'];
			$date = new DateTime($ca['last']);
			$date->add(new DateInterval('PT01H'));
			$l = $user_groups->level;
			
			if($l >= 2) { ?>
				<?if($dev) { ?><div style="text-align: center;width:100%;background-color: #c3f59d;height:30px;border:2px solid #ccc;color:red">DEV MODE</div><br><? } ?>
			Bank balance: <?=number_format($bank_balance, 2, ',', ' ');?>€
			| <? } ?> CA: <?=number_format($ca['amount']/1000, 0, ',', ' ')?>€ | Last ticket: <?=$date->format('Y-m-d H:i:s')?> | Num: <?=$ca['num']?>
			
			<ul data-role="listview" data-inset="true" data-filter="true">
			<!-- Admin --> 
			<?php if($l >= 2) { ?>
			<li><a href="/cameras/">Cams</a></li>
			<?php if($l >= 3) { ?><!--<li><a rel="external" data-ajax="false" href="https://hmw.hankrestaurant.com/">Ajaxterm hank1</a></li> --><? } ?>
			<? } ?>
			<li><a rel="external" data-ajax="false" href="/news">News</a></li> 
			<li><a rel="external" data-ajax="false" href="http://hank.shiftplanning.com/app/">Shiftplanning</a></li>
			<li><a rel="external" data-ajax="false" href="/webcashier/">Cashier</a></li>
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="/posmessage/">Message caisse</a></li><? } ?>			
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="https://hank.recruiterbox.com/app/#candidates/overview">Recruiter Box (RB)</a></li><? } ?>
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="https://www.cashpad.net">Reporting Cashpad</a></li><? } ?>
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="https://secure.tiime.fr">Tiime (compta)</a></li><? } ?>
			<li><a rel="external" data-ajax="false" href="/checklist/">Checklist</a></li>
			<li><a rel="external" data-ajax="false" href="/discount/">Discount</a></li>
			<li><a rel="external" data-ajax="false" href="/order/">Order</a></li>
			<li><a rel="external" data-ajax="false" href="/reminder/">Reminder</a></li>
			<li><a rel="external" data-ajax="false" href="/sensors/">Sensors</a></li>
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="/auth/">Staff management</a></li><? } ?>
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="/auth/extra">Extra finder</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/reminder/admin">Reminder tasks management</a></li>
			<hr />
			<? } ?>
			<?php if($l >= 1) { ?><li><a rel="external" data-ajax="false" href="http://drive.google.com/">Google Drive</a></li><? } ?>
			<?php if($l >= 1) { ?><li><a rel="external" data-ajax="false" href="http://trello.com">Trello</a></li><? } ?>
			<?php if($l >= 1) { ?><li><a rel="external" data-ajax="false" href="http://mail.hankrestaurant.com">Email Hank (mail@hankrestaurant.com)</a></li><? } ?>
			<?php if($l >= 1) { ?><li><a rel="external" data-ajax="false" href="http://dropbox.com/home">Dropbox</a></li>
			<hr />
			<? } ?>
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="/crud/cklChecklistTasks/">Checklists tasks management</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/cklChecklists/">Checklists management</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/discount/">Discounts</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/products/">Products</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/productsUnit/">ProductsUnit</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/productsStock/">ProductsStock</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/productsCategory/">ProductsCategory</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/rmdMeta/">Reminder task management</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/rmdNotif/">Reminder notification management</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/rmdTasks/">Reminder management</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/sensors/">Sensors management</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/sensorsAlarm/">Sensors alarm management</a></li><? } ?>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/sensors/">Sensors management</a></li><? } ?>
			<?php if($l >= 2) { ?><li><a rel="external" data-ajax="false" href="/crud/suppliers/">Suppliers</a></li>
			<?php if($l >= 3) { ?><li><a rel="external" data-ajax="false" href="/crud/suppliersCategory/">SuppliersCategory</a></li><? } ?>
			<li><a rel="external" data-ajax="false" href="/reporting/">Reporting CA pasteque (old)</a></li>
			<? } ?>
		</ul>
	</div><!-- /content -->
	<br /><br />
	<div id="view"></div>
</div><!-- /page -->
<?php include('jq_footer.php'); ?>




