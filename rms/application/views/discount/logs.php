<body>
	<div data-role="page">
		<div data-role="header">
			<a href="/discount/" data-ajax="false" data-icon="back">Back</a>
			<h1><?=$title?> | <?=$bu_name?> | <?=$username?></h1>
		</div>
		<div data-role="content">
			<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">
			<? if(empty($discounts)) { ?>
			<br />Nothing done so far...<br />
			<? } ?>	
				<table data-role="table" id="table-custom-2" data-mode="reflow" data-filter="true" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-popup-theme="a">
					<thead>
						<th></th>
						<th>Event</th>
						<th>date</th>
						<th>User</th>
						<th>Client</th>
						<th>Nature</th>
						<th>Used?</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($discounts as $line):?>
						<? $bgcolor = ""; if($line->used == true) $bgcolor = "#eeeeee"; ?>
						<tr style="background-color: <?=$bgcolor?>">
							<td>ID <?=$line->id_discount?></td>
							<td><?=$line->event_type?></td>
							<td><?=$line->date?></td>
							<td><?=$line->username?></td>
							<td><?=$line->client?></td>
							<td><?=$line->nature?></td>
							<td><? if($line->used) { echo "YES"; } else { echo "NO"; } ?></td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>

				
			</div><!-- /theme -->
		</div><!-- /content -->
	</div><!-- /page -->