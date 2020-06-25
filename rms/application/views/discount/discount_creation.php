</div>
		<div data-role="content">
				<?$attributes = array('id' => "discount", 'name' => "discount");
				echo form_open("discount/save", $attributes);?>
					<table width="100%" style="border: 1px solid #dedcd7; margin-top:10px" cellpadding="8">
						<tr>
							<td colspan="2" style="background-color: #fbf19e;">Discount information :
							</td>
						</tr>
						<tr>
							<td>
								<label for="client" id="label">Client:</label>
								<input id="client" type="text" name="client" value="<? if($discount) echo $discount[0]->client; ?>" data-clear-btn="true" />
								<label for="reason" id="label">Reason:</label>
								<input id="reason" type="text" name="reason" value="<? if($discount) echo $discount[0]->reason; ?>" data-clear-btn="true" />
								<label for="nature" id="label">Nature:</label>
								<input id="nature" type="text" name="nature" value="<? if($discount) echo $discount[0]->nature; ?>" data-clear-btn="true" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="user" id="label">User saving the discount:</label>
								<select style="background-color:#a1ff7c" name="user" id="user" data-inline="true" data-theme="a" required>
									<option value="0">User</option>
									<?foreach ($users as $user) {?>
										<option value="<?=$user->id?>"<? if(isset($form['user']) AND $form['user']==$user->id) { ?> selected <? } elseif(isset($discount[0]->id_user)) { ?> selected <? } ?>><?=$user->first_name?> <?=$user->last_name?></option>
									<? } ?>
								</select>
								
								<label for="persistent" id="label">Persistent:</label>
								<select style="background-color:#a1ff7c" name="persistent" id="persistent" data-inline="true" data-theme="a" required>
									<option value="0" <? if(isset($discount[0]->persistent) AND $discount[0]->persistent == 0) { echo "selected"; } ?>>No</option>
									<option value="1" <? if(isset($discount[0]->persistent) AND $discount[0]->persistent == 1) { echo "selected"; } ?>>Yes</option>
									</select>	
									<label for="allbu" id="label">Valid in all restaurants:</label>
									<select style="background-color:#a1ff7c" name="allbu" id="allbu" data-inline="true" data-theme="a" required>
										<option value="0" <? if(isset($discount[0]->allbu) AND $discount[0]->allbu == 0) { echo "selected"; } ?>>No</option>
										<option value="1" <? if(isset($discount[0]->allbu) AND $discount[0]->allbu == 1) { echo "selected"; } ?>>Yes</option>
										</select>
										<br />
										<? if($discount) { ?>
										<select style="background-color:#a1ff7c" id="used" name="used" data-inline="true" data-theme="a" required>
											<option value="0" <? if(($discount) AND ($discount[0]->used == 0)); { echo "selected"; } ?>>Utiliser : NON</option>
											<option value="1" <? if(($discount) AND ($discount[0]->used == 1)); { echo "selected"; } ?>>Utiliser : OUI</option>
										</select>
										<? } ?>
										<? echo form_submit($attributes, 'Save');?>
										<br />
										<label for="email" id="label">Contact email: <small>(An email will be sent if filled)</small></label>
										<input id="email" type="text" name="email" value="<? if($discount) echo $discount[0]->email; ?>" data-clear-btn="true" />
										<? if(!$discount) { ?><button type="button" style="background-color:#D7D7D7" OnClick="GenText();">Generate contact email text</button><? } ?>
										<br />
										<label for="email_text" id="label">Contact email text:</label>
<textarea id="email_text" name="email_text"><? if(isset($discount[0]->email_text) AND !empty($discount[0]->email_text)) { echo $discount[0]->email_text; } ?></textarea>	
<? echo form_submit($attributes, 'Save');?>
							</td>
						</tr>
					</table>
						<? if(isset($discount[0]->id)) { $type_id = $discount[0]->id; } else { $type_id = 'create'; } ?>
						<input type="hidden" name="id" value="<?=$type_id?>">
						<?$attributes = array('id' => "sub", 'name' => "submit");?>						
				</form>

						<script>
						$(document).ready(function() {

							var $form = $('#discount');

							$('#sub').on('click', function() {
								$form.trigger('submit');
								return false;
							});

							$form.on('submit', function() {

								var nature = $('#nature').val();
								var user = $('#user').val();
								var client = $('#client').val();
								var reason = $('#reason').val();

								if(nature == '') {
									alert('Please fill discount nature.');
								} else if(user == 0){
									alert('Please indicate who you are.');
								}else {
									$.ajax({
										url: $(this).attr('action'),
										type: $(this).attr('method'),
										data: $(this).serialize(),
										dataType: 'json',
										success: function(json) {
											if(json.reponse == 'ok') {
												//alert('Saved!');
											} else {
												alert('WARNING! ERROR at saving : '+ json.reponse);
											}
										}
									}).done(function(data) {
											window.location = "/discount/index/create";
									    }).fail(function(data) {
									    	alert('WARNING! ERROR at saving!');
									    });
								}
								return false;
							});
						});					
						
						function GenText() {
							
							var client  = '';
							var nature  = '';
							var text_bu = 'au restaurant';
							
							var client	= $('#client').val();
							var nature	= $('#nature').val();
							var allbu	= $('#allbu').val();
							if(allbu == 1) { text_bu = 'dans tous les restaurants'; }
							
							var email_text_init = 'Bonjour ' + client + '!\r\nVoici votre discount ' + nature + ' à utiliser ' + text_bu + ' Hank.\r\n Indiquer ' + client + ' à la caisse en précisant impérativement que vous avez un DISCOUNT.\r\n Nous espérons que vous allez vous régaler avec notre fabuleuse cuisine vegan!\r\n Et n\'hésitez pas à nous faire un retour sur votre expérience, les points sympas et surtout les points à améliorer (à l\'adresse contact@hankrestaurant.com) en nous précisant bien le restaurant (ville, arrondissement) ainsi que la date et l\'heure de votre venue car nous sommes très soucieux de la qualité de notre service.\r\n-- \r\nL\'équipe Hank (Have A Nice Karma)\r\n'+$("#user option:selected").text();
							<? if(!isset($discount[0]->email_text) AND empty($discount[0]->email_text)) { ?>
							$('#email_text').val(email_text_init);
							<? } ?>
						}

						$( document ).ready(function() {
							GenText();
						});
						
						</script>
				</div><!-- /content -->
			</div><!-- /page -->
