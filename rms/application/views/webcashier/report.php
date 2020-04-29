	<link rel="stylesheet" href="/public/receiptContent.css" />
	</div>
	<div data-role="content" data-theme="a">
		<h4>Current Cashpad cash: <?=$pos_cash?>€ | Safe cash: <?=number_format($safe_cash,  2, '.', ' ')?>€ |  Safe TR num: <?=$safe_tr?> | Monthly TO: <?=number_format($monthly_to,  2, '.', ' ')?>€</h4>
		<p>Daily Cashpad cash movements</p>
		<ul data-role="listview" data-inset="true">
		<? foreach ($live_movements as $lm):?>
		<li>Date: <?=$lm['DATE']?> | Amount: <? $am = $lm['AMOUNT']/1000; echo $am."€"; ?> | User: <?=$lm['USERNAME']?> |  Description: <?=$lm['DESCRIPTION']?> | Method: <?=$lm['PAYMENTNAME']?> | Customer: <?=$lm['CFIRSTNAME']?> <?=$lm['CLASTNAME']?></li>
		<?php endforeach; ?>
		<? if(empty($live_movements)) { ?>No movement<? } ?>
		</ul>
		<br>
		<form id="filter" name="filter" method="post" data-ajax="false" action="/webcashier/report/">
			<div class="row">
				<div class="col-md">
					<div class="box">
						<select name="type" id="filter-type">
							<option value="">TYPE</option>
							<option value="open" <?if ($filter['type'] == 'open') echo "selected";?>>OPEN</option>
							<option value="close" <?if ($filter['type'] == 'close') echo "selected";?>>CLOSE</option>
							<option value="safe_in" <?if ($filter['type'] == 'safe_in') echo "selected";?>>SAFE IN</option> 
							<option value="safe_out" <?if ($filter['type'] == 'safe_out') echo "selected";?>>SAFE OUT</option> 
						</select>
					</div>
				</div>
					<div class="col-md">
						<div class="box">
							<select name="user" id="filter-user">
								<option value="">USER</option>
								<? foreach ($users as $user) { ?>
									<option value="<?=$user['id']?>" <?if ($filter['user-id'] == $user['id']) echo "selected";?>><?=$user['username']?></option>
								<? } ?>
							</select>
						</div>
					</div>
					<div class="col-md">
						<div class="box">
							<label for="sdate" id="label">Movement date from the : </label>
							<input type="text" data-role="date" id="sdate" name="sdate" value="<?=$filter['sdate']?>" data-clear-btn="true" />
						</div>
					</div>
					<div class="col-md">
						<div class="box">
							<label for="edate" id="label">To the : </label>
							<input type="text" data-role="date" id="edate" name="edate" value="<?=$filter['edate']?>" data-clear-btn="true" />
						</div>
					</div>
					<div class="col-md">
						<div class="box">
							<label style="background-color: white;" for="status_ok" id="label">OK</label>
							<input type="checkbox" id="status_ok" name="status_ok" data-clear-btn="true" <?if (!empty($filter['status_ok'])) { echo 'checked'; }?>>
							<label style="background-color: white;" for="status_error" id="label">Error</label>
							<input type="checkbox" id="status_error" name="status_error" data-clear-btn="true" <?if (!empty($filter['status_error'])) { echo 'checked'; }?>>
							<label style="background-color: white;" for="status_validated" id="label">Validated</label>
							<input type="checkbox" id="status_validated" name="status_validated" data-clear-btn="true" <?if (!empty($filter['status_validated'])) { echo 'checked'; }?>>
						</div>
					</div>
			</div>
			<input type="submit" name="sub" value="FILTER" />
		</form>
		<br>
		<h2>Movements</h2>
		
		<?php foreach ($lines as $m): ?>
			<? $mov = '';
			$cash_amount = 0;
			if($m['mov']['movement'] == 'safe_in' OR $m['mov']['movement'] == 'safe_out') $mov = 'safe';
			if($m['mov']['movement'] == 'open') $mov = 'open';
			if($m['mov']['movement'] == 'close') $mov = 'close';
			?>
			<div id="<?=$m['mov']['id']?>" data-role="collapsible" style="background-color: <? if ($mov == 'close') { ?> 
				<?if ($m['mov']['status'] == 'ok') {echo "lightgreen";} else if ($m['mov']['status'] == 'error') { echo "#ec7470";} 
				else if ($m['mov']['status'] == 'validated') { echo "#d5ecd2";}} else { echo "rgb(220, 220, 220)";}?>" >
				<h2><a id="<?=$m['mov']['id']?>"></a>ID: <? $dateid = new DateTime($m['mov']['date']); echo date_format($dateid, 'Y-m-d'); echo " [".$m['mov']['id']."]";    ?> - <?=strtoupper($m['mov']['movement'])?><?if ($mov == 'close') { ?> -  <?=$m['mov']['username']?><? } ?> <?if ($mov == 'close' && (count($m['cancelledReceipts']) > 0)) { echo " - <strong>" . count($m['cancelledReceipts']) ." receipts cancelled</strong>";}?></h2>
				<ul data-role="listview" data-theme="d" data-divider-theme="d">
					<li>
						<h3>Date: <?=$m['mov']['date']?></h3>
						<h3>User: <?=$m['mov']['username']?> </h3>
						<p>Safe cash: <?=number_format($m['mov']['safe_cash_amount'],  2, '.', ' ')?>€ | Safe TR num: <?=$m['mov']['safe_tr_amount']?></p>
			
						<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
							<tr style="background-color: #bfbfbf; border: 1px solid #dedcd7;">
								<td>Payment type</td>
								<td>User amount</td>
								<?if($mov != 'safe') { ?><td>Cashpad amount</td><? } ?>
								<?if($mov != 'safe') { ?><td>Balance</td><? } ?>
							</tr>
								<tr style="border: 1px solid #dedcd7;">
								<td>Prélèvement billets</td>
								<td><?=$m['mov']['prelevement_amount']?>€</td>
								<?if($mov != 'safe') { ?> <td>-</td> <? } ?>
								<?if($mov != 'safe') { ?> <td>-</td> <? } ?>
							</tr>
							<?php $total = 0; $diff = (-$m['mov']['pos_cash_amount'] + $m['mov']['prelevement_amount']); foreach ($m['pay'] as $m2): ?>
								<? 
								$noreport = false;
								if(($m2['id'] == 7 OR $m2['id'] == 10 OR $m2['id'] == 12 OR $m2['id'] == 5 OR $m2['id'] == 14 OR $m2['id'] == 11 OR $m2['id'] == 16)) { $noreport = true; }
								$total += $m2['amount_pos'];
								if ($m2['id'] == 1) $diff = $diff + $m2['amount_user'];
								if ($m2['id'] == 2 OR $m2['id'] == 3 OR $m2['id'] == 4 OR $m2['id'] == 13) $diff = $diff + ($m2['amount_user']-$m2['amount_pos']);
								?>
								<? if($m2['id'] == 1) $cash_amount = number_format($m2['amount_user'],2); ?>
								<tr style="border: 1px solid #dedcd7;">
									<td><? if ($noreport) { ?><font color="#9B9B9B"><? } ?><?=$m2['name']?><? if ($noreport) { ?></font><? } ?></td>
									<td><? if(!$noreport) { echo $m2['amount_user']. "€"; } else { ?><font color="#9B9B9B">-</font><? } ?></td>
									<?if($mov != 'safe') { ?>
										<td><? if ($noreport) { ?><font color="#9B9B9B"><? } ?>
										<? $ca_display = "-"; 
										if($m2['id'] != 9) $ca_display = number_format($m2['amount_pos'],2)."€"; ?>
										<? if($m2['id'] == 1) { $fdc = $m['mov']['pos_cash_amount']-$m['mov']['prelevement_amount']; $ca_display = "FDC: ".$fdc."€ <br /> <small>(CA : ".number_format($m2['amount_pos'],2)."€)</small>"; } ?>										
										<?=$ca_display?>
										<? if ($noreport) { ?></font><? } ?>
										</td>
									<? } ?>
									<?if($mov != 'safe') { ?>
										<td>
										<? 
										if($m2['id'] == 1) $m2['amount_pos'] = $m['mov']['pos_cash_amount']-$m['mov']['prelevement_amount'];
										$bal_display =  number_format(($m2['amount_user']-$m2['amount_pos']), 2) ."€"; 
										if($noreport) $bal_display = "<font color='#9B9B9B'>-</font>"; 
										?>
										<?=$bal_display?>
										</td>
									<? } ?>
								</tr>						
							<?php endforeach; ?>
						</table>
						<? if($mov == 'close') { ?>
							<small>Total CA: <?=$total?>€</small>
							<? 
							$operand = ""; if($diff > 0) $operand = "+";
							if (number_format($diff, 3) != 0) { ?>
								<p style="color : red; font: 16px Arial, Verdana, sans-serif;"><b>ALERT DIFF:</b> <?=$operand?><?=number_format($diff, 2)?>€ <br />
								<? if(!empty($m['mov']['corrected'])) { ?> <span style="color : black; font: 16px Arial, Verdana, sans-serif;">Corrected DIFF: <b><?=$m['mov']['corrected']?>€</b></span></p> <? } ?>
								
						<? 	
						}
					} 
					?>
		<? if($mov == 'close') { ?><h2>Commentaire close: <?=stripslashes($m['mov']['comment'])?></h2><? } ?>
		<? if ($mov == 'safe') { ?><h2>Commentaire : <?=stripslashes($m['mov']['comment_report'])?></h2><? } ?>
<div>		
	<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
		<tr><td>
			
		<h3>Movement comments</h3>
		<?
		$id_form = "report".$m['mov']['id'];
		$attributes = array('id' => $id_form, 'name' => $id_form);
		echo form_open("webcashier/save_report_comment", $attributes);?>
			<input maxlength="255" type="text" name="comment-<?=$m['mov']['id']?>" id="comment-<?=$m['mov']['id']?>" data-clear-btn="true" data-inline="true" data-theme="a" />
			 <? if ( $mov == 'close') { ?>
			<? if ($this->ion_auth_acl->has_permission('set_corrected_diff')) { ?>
				<div class="box">
						 <p><small>CORRECTED DIFF:</small> <input data-role="none" data-enhance="false" type="text" name="corrected-<?=$m['mov']['id']?>" id="corrected-<?=$m['mov']['id']?>" style="width: 100px;" value="<?=$m['mov']['corrected']?>" />€ </p>
						</div>
			<? } ?>	
			<? if ($this->ion_auth_acl->has_permission('quittance')) { ?>
				<div class="box">
					 <input type="checkbox" name="validate-<?=$m['mov']['id']?>" id="validate-<?=$m['mov']['id']?>" class="custom" <?if ($m['mov']['status'] == 'validated') echo 'checked';?> />
					 <label style="background-color: white;" for="validate-<?=$m['mov']['id']?>" id="label-<?=$m['mov']['id']?>">Quittance controleur</label>
			 </div>
			<? } ?>
		<? } ?>
			<input type="submit" id="sub<?=$m['mov']['id']?>" onclick="validate(<?=$m['mov']['id']?>)" name="submit" value="Save" data-mini="true" data-clear-btn="true" />
			<input type="hidden" name="id" value="<?=$m['mov']['id']?>">
			<input type="hidden" name="diff-<?=$m['mov']['id']?>" id="diff-<?=$m['mov']['id']?>" value="<?=$diff?>">
		</form>
	</td></tr>
	<? foreach ($m['comments'] as $comment) { ?>
			<tr>
				<td style="white-space: normal;">
					<b><?=$comment['username']?></b> | <b><?=$comment['date']?></b> : <b><?=$comment['content']?></b>
				</td>
			</tr>
	<? } ?>
	</table>
</div>
<? if($mov =='close') { ?>
	<div data-role="collapsible">
		<h3>POS Movements</h3>
		<?if(!empty($m['cashmovements'])) { ?>
		<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
			<tr style="background-color: #bfbfbf;">
				<td>Date</td>
				<td>User</td>
				<td>Amount</td>
				<td>Payment type</td>
				<td>Description</td>
				<td>Customer</td>
			</tr>
		<?php foreach ($m['cashmovements'] as $mov): ?> 
			<tr>
				<td><?=$mov['date']?></td>
				<td><? if(empty($mov['username'])) { echo $mov['user']; } echo $mov['username']; ?></td>
				<td><?=$mov['amount']/1000?>€</td>
				<td><?=$mov['method_name']?></td>
				<td><?=$mov['description']?></td>
				<td><? if($mov['customer_first_name']) { echo $mov['customer_first_name'].".".$mov['customer_last_name']; } ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
		<? } ?>
	</div>
	<div data-role="collapsible">
		<h3>FDC Movements</h3>
		<?if(!empty($m['cashFdcMovements'])) { ?>
		<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
			<tr style="background-color: #bfbfbf;">
				<td>Amount</td>
			</tr>
		<?php foreach ($m['cashFdcMovements'] as $mov): ?> 
			<tr>
				<td><?=$mov['NAME']?>: <?=$mov['AMOUNT']/1000?></td>
			</tr>
		<?php endforeach; ?>
		</table>
		<? } ?>
	</div>
	<div data-role="collapsible">
		<h3>Cash Drawer Opened</h3>
		<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
			<tr style="background-color: #bfbfbf;">
				<td>Date</td>
				<td>User</td>
				<td>Terminal</td>
			</tr>
		<?php foreach ($m['cashDrawerOpened'] as $mov): ?> 
			<tr>
				<td><?=$mov['DATE']?></td>
				<td><?= $mov['USER']?></td>
				<td><?=$mov['TERMINAL']?></td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
<div data-role="collapsible">
	<h3>Cancelled Receipts</h3>
	<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
		<tr style="background-color: #bfbfbf;">
			<td>ID (period)</td>
			<td>Receipt Closure Date</td>
			<td>User (Created)</td>
			<td>User (Cancelled)</td>
			<td>Reason</td>
			<td>Total Amount</td>
			<td></td>
		</tr>
	<?php foreach ($m['cancelledReceipts'] as $mov): ?> 
		<tr id="tr_<?=$mov['ID']?>">
			<td><?=$mov['PERIOD_ID']?></td>
			<td><?=$mov['DATE_CLOSED']?></td>
			<td><?=$mov['OWNER']?></td>
			<td><?=$mov['USER_CANCEL']?></td>
			<td><?=$mov['CANCELLATION_REASON']?></td>
			<td><?=$mov['AMOUNT_TOTAL']?></td>
			<td><button onclick="receipt_content('<?=$mov['ID']?>')">CONTENT</button>
		</tr>
	<?php endforeach; ?>
	</table>
<?php foreach ($m['cancelledReceipts'] as $mov): ?>
<div id="cancelledReceiptsContent_<?=$mov['ID']?>" class="cancelledReceiptsContent">
	<span class="close" onclick="close_receipt_content('<?=$mov['ID']?>')">&times;</span>
	<table class="ui-responsive table table-bordered historytable">
		<thead>
			<tr>
				<th>Produit</th>
				<th>Quantité</th>
				<th>Prix Unitaire</th>
				<th>Prix Total</th>
			</tr>
		</thead>
		<tbody id="tbl-body_<?=$mov['ID']?>">
			<? foreach ($mov['CONTENT'] as $line) { ?>
				<tr>
					<td><?=$line['NAME']?></td>
					<td><?=$line['QUANTITY']?></td>
					<td><?=$line['PRICE_INCL_TAXES']?></td>
					<td><?=$line['PRICE_INCL_TAXES'] * $line['QUANTITY']?></td>
				</tr>
		<? } ?>
	</tbody>
</table>
<span>Note : <? if (isset($mov['NOTE'])) echo $mov['NOTE']; else echo "<strong>NO NOTE FOR THIS RECEIPT</strong>";?></span>
</div>
<?php endforeach;?>
</div>
<div data-role="collapsible">
	<h3>User Actions</h3>
<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
	<tr>
		<td colspan="6">Total : <?=$m['total_actions']?></td>
	</tr>
	<tr style="background-color: #bfbfbf;">
		<td>Receipt Closure Date</td>
		<td>User</td>
		<td>Nb</td>
		<td>Percent</td>
	</tr>
<?php foreach ($m['userActionStats'] as $mov): ?> 
	<tr>
		<td><?=$mov['date_closed']?></td>
		<td><?= $mov['owner']?></td>
		<td><?=$mov['count']?></td>
		<td><?=$mov['percent']?></td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<? if (isset($m['mov']['employees_sp']) && !empty($m['mov']['employees_sp'])) { ?>
<div data-role="collapsible">
	<h3>Users Planning</h3>
	<table style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="5" width="70%">
				<? foreach ($m['mov']['employees_sp'] as $emp) { ?>
			<tr>
				<td><?=$emp?></td>
			</tr> 
		<?
				} ?>
	</table>
</div>
<?  }
	} ?>	
				</li>
			</ul>
		</div> <!-- end collapsible -->
	<?php endforeach; ?>
	<div id="pages">
		<?= $this->pagination->create_links(); ?>
	</div>
</div> <!-- end content -->
</div> <!-- end page -->

						<script src="/public/jqv/dist/jquery.validate.min.js" type="text/javascript"></script>
						<script>
						
						function isNumeric(n) {
							return !isNaN(parseFloat(n)) && isFinite(n);
						}
						
						function resendFilters(page) {
							var form = document.getElementById("filter");
							if (typeof page !== 'undefined') {
								form.action = "/webcashier/report/" + page;
							}
							form.submit();
						}
						
						function validate(idl) {
							var $form = $('#report' + idl);
							var done = 0;
							
							$form.on('submit', function() {
																					
								var comment = $('#comment-' + idl).val();
							
								if (done >= 1) { return (false); }
								
								$.ajax({
									url: $(this).attr('action'),
									type: $(this).attr('method'),
									data: $(this).serialize(),
									dataType: 'json',
									success: function(json) {
										if(json.reponse == 'okcreate' && done == 0) {
											done = done + 1;
											if(done == 1) {
												//window.location = "/product_admin/index/create1";
												return false; 
											}
											
											return false;
										} else if(json.reponse == 'ok' && done == 0) {
											done = done + 1;
											if(done == 1) { 
												alert('Saved!');
												//location.reload(true);
												return false; 
											}
											return false;
										} else if(done == 0){
											alert('ERROR at saving : '+ json.reponse);
											return false;
										}
									}
								}).done(function(data) {
									return false;
								}).fail(function(data) {
									done = done + 1;
									if(done <= 1) { 
										alert('ERROR at saving!');
										return false; 
									}
								});
								return false;
							});
						}
						</script>
						<script>
							$(document).ready(function() {
							$("#edate").datepicker({ dateFormat: 'yy-mm-dd' });
							});
						</script>
						<script>
							$(document).ready(function() {
							$("#sdate").datepicker({ dateFormat: 'yy-mm-dd' });
							});
						</script>
						<script>
							var pages = document.getElementById("pages");
							for (var i = 0; i < pages.childNodes.length; i += 1) {
								if ('href' in pages.childNodes[i]) {
									var array = pages.childNodes[i].href.split('/');
									var pagenumber = array[(array.length - 1)];
									pages.childNodes[i].setAttribute('onclick','resendFilters(' + pagenumber + ')');
									
								}
							}
							</script>
							<!-- Modal Script -->
							<script>
							function receipt_content(id_receipt) {
								var id = 'cancelledReceiptsContent_'.concat(id_receipt);
								var div = document.getElementById(id);
								var id2 = 'tr_'.concat(id_receipt);
								var tr = document.getElementById(id2);
								var others = document.getElementsByClassName('cancelledReceiptsContent');
								
									for (i = 0; i < others.length; i += 1) {
										if (others[i].style.display == "block") {
											others[i].style.display = "none";
											otherId = others[i].id;
											otherIdReceipt = 'tr_' + otherId.split("_")[1];
											otherTr = document.getElementById(otherIdReceipt);
											otherTr.style.backgroundColor = "white";
										}
									}
									
								tr.style.backgroundColor = 'lightgreen';
								div.style.display = "block";
							}
							
							function close_receipt_content(id_receipt) {
								var id = 'cancelledReceiptsContent_'.concat(id_receipt);
								var div = document.getElementById(id);
								var id2 = 'tr_'.concat(id_receipt);
								var tr = document.getElementById(id2);
								tr.removeAttribute('style');
								div.style.display = "none";
							}
							
							</script>