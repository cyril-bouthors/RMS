		</div>
		<div data-role="content">
			<div data-theme="a" data-form="ui-body-a" class="ui-body ui-body-a ui-corner-all">
				<? if(empty($discounts)) { ?>
					<br />Nothing done so far...<br />
				<? }else{ ?>	
					<table data-role="table" id="table-custom-2" data-mode="reflow" data-filter="true" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-popup-theme="a" data-filter-placeholder="Filter discounts">
						<thead>
							<? if($auth_edit) { ?><th>Edit</th><? } ?>
							<th>Event</th>
							<th>date</th>
							<th>User</th>
							<th>Client</th>
							<th>Nature</th>
							<th>Reason</th>
							<th>Used?</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($discounts as $line):?>
							<? $bgcolor = ""; if($line->used == true) $bgcolor = "#eeeeee"; ?>
							<tr style="background-color: <?=$bgcolor?>">
								<? if($auth_edit) { ?><td><a href="/discount/creation/1/<?=$line->id_discount?>" class="ui-btn ui-shadow" rel="external" data-ajax="false">ID <?=$line->id_discount?></a></td><? } ?>
								<td><?=$line->event_type?></td>
								<td><?=$line->date?></td>
								<td><?=$line->username?></td>
								<td><?=$line->client?></td>
								<td><?=$line->nature?></td>
								<td><?=$line->reason?></td>
								<td><? if($line->used) { echo "YES"; } else { echo "NO"; } ?></td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>
				<?}?>
			</div><!-- /theme -->
		</div><!-- /content -->
	</div><!-- /page -->
