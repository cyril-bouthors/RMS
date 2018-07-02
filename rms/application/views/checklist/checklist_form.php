<?php

$checklist_id = property_exists($checklist, 'id') ? $checklist->id : 'create';

?>

<form class="checklist-update" id="checklist-<?= $checklist_id ?>" name="checklist-<?= $checklist_id ?>" method="post" action="/checklist/save">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box" style="background-color: #fbf19e;">
        <h3 style="padding: 0.5em;">Checklist</h3>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
      <div class="box">
        <label for="name-<?= $checklist_id ?>">Name:</label>
        <input id="name-<?= $checklist_id ?>" type="text" name="name" value="<?= stripslashes($checklist->name) ?>" data-clear-btn="true" />
      </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
      <div class="box">
        <label for="type-<?= $checklist_id ?>">Type:</label>
        <select id="type-<?= $checklist_id ?>" name="type">
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
        <select id="active-<?= $checklist_id ?>" name="active">
          <option value="1" <? if($checklist->active == 1) echo "selected"; ?>>Yes</option>
          <option value="0" <? if($checklist->active == 0) echo "selected"; ?>>No</option>
        </select>
      </div>
    </div>
  </div>

  <!--<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box" style="background-color: #fbf19e;">
        <h3 style="padding: 0.5em;">Tasks</h3>
      </div>
    </div>
  </div>-->

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box">
        <input type="submit" value="Save">
      </div>
    </div>
  </div>

  <?php
    if (property_exists($checklist, 'id') && !empty($checklist->id))
      echo '<input type="hidden" name="id" value="' . $checklist_id . '">';
  ?>
</form>
