<?php
$checklist_id = property_exists($checklist, 'id') ? $checklist->id : 'create';

$checklist_readonly = $can_edit_checklists ? '' : 'readonly disabled';
$task_readonly = $can_edit_tasks ? '' : 'readonly disabled';
?>

<form class="checklist-update" id="checklist-<?= $checklist_id ?>" name="checklist-<?= $checklist_id ?>" method="post" action="/checklist/save">
  <?php if ($can_edit_checklists) { ?>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="box">
          <label for="name-<?= $checklist_id ?>">Name:</label>
          <input id="name-<?= $checklist_id ?>" type="text" name="name" value="<?= stripslashes($checklist->name) ?>" data-clear-btn="true" <?= $checklist_readonly ?>/>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <div class="box">
          <label for="type-<?= $checklist_id ?>">Type:</label>
          <select id="type-<?= $checklist_id ?>" name="type" <?= $checklist_readonly ?>>
            <?php foreach ($types as $type) { ?>
              <option value="<?= $type ?>"<?php if ($type === $checklist->type) echo 'selected'; ?>>
                <?= $type ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <div class="box">
          <label for="active-<?= $checklist_id ?>">Active: (on or off)</label>
          <select id="active-<?= $checklist_id ?>" name="active" <?= $checklist_readonly ?>>
            <option value="1" <? if($checklist->active == 1) echo "selected"; ?>>Yes</option>
            <option value="0" <? if($checklist->active == 0) echo "selected"; ?>>No</option>
          </select>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if ($can_edit_tasks) { ?>
    <div class="row">
      <div id="tasks-<?= $checklist_id ?>" data-role="collapsible-set" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box tasks-sorting" id="checklist-tasks-<?= $checklist_id ?>" data-id="<?= $checklist_id ?>">
          <div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if ($can_edit_checklists || $can_edit_tasks) { ?>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box">
          <input type="submit" value="Save">
        </div>
      </div>
    </div>
  <?php } ?>

  <?php
    if (property_exists($checklist, 'id') && !empty($checklist->id))
      echo '<input type="hidden" name="id" value="' . $checklist_id . '">';
  ?>
</form>
