		</div>
		<div data-role="content">

<h1><?php echo lang('edit_user_heading');?></h1>
<p><?php echo lang('edit_user_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php
$attributes = array('rel' => 'external', 'data-ajax' => 'false');
echo form_open(uri_string(), $attributes);
?>
<div class="row">

  <div class="col-xs-12 col-sm-6 col-md-5">
    <div class="box">
	  <p>
      		<?php echo 'Username';?> <br />
      		<?php echo form_input($username);?>
	  </p>

	  <p>
            <?php echo lang('edit_user_fname_label', 'first_name');?>
            <?php echo form_input($first_name);?>
      </p>

      <p>
            <?php echo lang('edit_user_lname_label', 'last_name');?>
            <?php echo form_input($last_name);?>
      </p>

      <p>
            <?php echo lang('edit_user_phone_label', 'phone');?>
            <?php echo form_input($phone);?>
      </p>

      <p>
            Comment: <br />
            <?php echo form_input($comment);?>
      </p>

      <p>
            <?php echo lang('edit_user_email_label', 'email');?>
            <?php echo form_input($email);?>
      </p>

      <p>
            <?php echo lang('edit_user_password_label', 'password');?>
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?>
            <?php echo form_input($password_confirm);?>
      </p>

      <p>
            IBAN
            <?php echo form_input($iban);?>
      </p>
			<?if ($this->ion_auth_acl->has_permission('edit_first_shift_user')):?>
				<td><label for="sdate" id="label">First Shift Date :</label></td>
				<td><input type="text" data-role="date" id="sdate" name="sdate" value="<?if (isset($first_shift)) echo $first_shift;?>" data-clear-btn="true" /></td>
			<?endif;?>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-7">
    <div class="row">
      <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="box">
          <?php if ($this->ion_auth_acl->has_permission('edit_user_group')): ?>

          <h3><?= lang('edit_user_groups_heading') ?></h3>
          <?php foreach ($groups as $group): ?>
            <label class="checkbox">
              <?php
                $gID=$group['id'];
                $checked = null;
                $item = null;
                foreach($currentGroups as $grp) {
                  if ($gID == $grp->id) {
                    $checked = ' checked="checked"';
                    break;
                  }
                }

                if($this->ion_auth_acl->has_permission('edit_admin_user_group')) {
                  ?>
                  <input type="radio" name="groups[]" value="<?= $group['id'] ?>"<?= $checked ?>>
                  <?= htmlspecialchars($group['name'], ENT_QUOTES,'UTF-8') ?>
                <? } ?>
            </label>
          <?php endforeach ?>
        </div>
      </div>
      <div class="col-xs-4 col-sm-4 col-md-4"><div class="box">
        <h3><?= lang('edit_user_bus_heading') ?></h3>

    <?php foreach ($bus as $bu) { ?>
      <label class="checkbox">
        <?php
          $bID=$bu['id'];
          $checked = null;
          $item = null;
          foreach ($currentBus as $up) {
            if ($bID == $up->id) {
              $checked = ' checked="checked"';
              break;
            }
          }
        ?>
        <input type="checkbox" name="bus[]" value="<?php echo $bu['id'];?>"<?= $checked ?>>
        <?= htmlspecialchars($bu['name'],ENT_QUOTES,'UTF-8') ?>
      </label>
    <?php } ?>

      </div></div>

      <div class="col-xs-4 col-sm-4 col-md-4"><div class="box">
        <h3><?= lang('edit_user_notifications_heading') ?></h3>
		
        <?php
          $ids_users_notifications = array_map(function($notification) {
            return $notification->id;
          }, $currentNotifications);

          foreach ($notifications as $notification) { ?>
            <label class="checkbox">
              <?php
                $checked = in_array($notification['id'], $ids_users_notifications)
                  ? ' checked="checked"'
                  : '';
              ?>
              <input type="checkbox" name="notifications[]" value="<?= $notification['id'] ?>"<?= $checked ?>>
              <?= htmlspecialchars($notification['name'], ENT_QUOTES, 'UTF-8') ?>
            </label>
            <?php } ?>
			
      </div></div>

      <?php endif ?>

    <?= form_hidden('id', $user->id) ?>
    <?= form_hidden($csrf)  ?>
  </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12">
	
  <p><?= form_submit('submit', lang('edit_user_submit_btn')) ?></p>
</div>

</div>
</div>
<?= form_close() ?>
	</div><!-- /content -->
	<br /><br />
	<div id="view"></div>
</div><!-- /page -->
<script>
	$(document).ready(function() {
	$("#sdate").datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>
