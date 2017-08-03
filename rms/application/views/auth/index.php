			<?php if($users['0']->groups['0']->level >= 2) {?>
				<a href="/auth/create_user/" class="ui-btn ui-btn-right" rel="external" data-ajax="false" data-icon="plus"><i class="zmdi zmdi-plus zmd-2x"></i></a><? } ?>
		</div>
		<div data-role="content">

<h1>Staff</h1>

<?if($message) { ?>
	<ul data-role="listview" data-inset="true" data-split-theme="a" data-divider-theme="a">
		<li style="background-color: #e8ffb9;"><?=$message?></li>
	</ul>
<? } ?>						
										
<div class="row">
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b>Username</b>
		</div>
	</div>
	
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b>Phone</b>
		</div>
	</div>

	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b><?php echo lang('index_email_th');?></b>
		</div>
	</div>
	
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b><?php echo lang('index_fname_th');?></b>
		</div>
	</div>	
	
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b><?php echo lang('index_lname_th');?></b>
		</div>
	</div>
	
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b>Comment</b>
		</div>
	</div>
	
	<? if($users['0']->groups['0']->level >= 2) { ?>
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b><?php echo lang('index_groups_th');?></b>
		</div>
	</div>
	
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b>BU</b>
		</div>
	</div>
	
	<? if($users['0']->groups['0']->level >= 3) { ?>
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b><?php echo lang('index_status_th');?> <? if($user_groups->level >= 10) { ?> | Delete <? } ?></b>
		</div>
	</div>
	<? } ?>
	
	<div class="col-md" style="margin: 3px;">
		<div class="box">
			<b><?php echo lang('index_action_th');?></b>
		</div>
	</div>
	<? } ?>
</div>
<?php foreach ($users as $user):?>
	<? $bgcolor = ""; if($user->active == 0) $bgcolor = "#aaaaaa"; ?>
	<div class="row" style="background-color: <?=$bgcolor?>; border: 1px solid silver; margin: 5px;">

		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php echo htmlspecialchars($user->username,ENT_QUOTES,'UTF-8');?>
			</div>
		</div>
		
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php echo htmlspecialchars($user->phone,ENT_QUOTES,'UTF-8');?>
			</div>
		</div>
				
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?>
			</div>
		</div>
		
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?>
			</div>
		</div>
		
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?>
			</div>
		</div>
		
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php echo htmlspecialchars($user->comment,ENT_QUOTES,'UTF-8');?>
			</div>
		</div>
		
		<? if($users['0']->groups['0']->level >= 2) { ?>
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?$test_real = $this->ion_auth->is_real_admin($current_user->id);?>
				<?$test_fake = $this->ion_auth->is_admin($current_user->id);?>
				<?php foreach ($user->groups as $group):?>
					<? if($user_groups->level >= 5) { ?><?php if($test_real || ($test_fake && $group->name != 'admin')) echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?> <? } else { ?> <?=htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')?><? } ?>
					<br />
                <?php endforeach?>
			</div>
		</div>
		
		<div class="col-md" style="margin: 3px;">
			<div class="box">
					<?php foreach ($user->bus as $bu):?>
						<?=htmlspecialchars($bu->name)?><br />
	                <?php endforeach?>
			</div>
		</div>
		
		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php $attributes = array('rel' => 'external', 'data-ajax' => 'false');
				if($test_real || ($test_fake && $group->name != 'admin')){
					echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link'), $attributes) : anchor("auth/activate/". $user->id, lang('index_inactive_link'), $attributes);?> <? if($user_groups->level >= 5) { ?> | <? echo anchor("auth/delete/".$user->id, 'Delete', $attributes);  ?><? }} ?>
			</div>
		</div>		

		<div class="col-md" style="margin: 3px;">
			<div class="box">
				<?php if($test_real || ($test_fake && $group->name != 'admin')) echo anchor("auth/edit_user/".$user->id, 'Edit', $attributes) ;?>
			</div>
		</div>
		<? } ?>
	</div>
<?php endforeach;?>


	</div><!-- /content -->
	<br /><br />
	<div id="view"></div>
</div><!-- /page -->
