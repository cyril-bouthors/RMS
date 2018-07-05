<?php
$task_id = property_exists($task, 'id') ? $task->id : $checklist_id . '-create';
?>

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
    <div class="box">
      <label for="task-name-<?= $task_id ?>">Name:</label>
      <input id="task-name-<?= $task_id ?>" name="task-name-<?= $task_id ?>" type="text" value="<?= stripslashes($task->name) ?>" data-clear-btn="true" />
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <div class="box">
      <label for="task-priority-<?= $task_id ?>">Priority:</label>
      <select id="task-priority-<?= $task_id ?>" name="task-priority-<?= $task_id ?>">
        <?php foreach ($priorities as $index => $priority) { ?>
          <option value="<?= $index ?>"<?php if ($index === $task->priority) echo 'selected'; ?>>
            <?= $priority ?>
          </option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <div class="box">
      <label for="task-active-<?= $task_id ?>">Active: (on or off)</label>
      <select id="task-active-<?= $task_id ?>" name="task-active-<?= $task_id ?>">
        <option value="1" <? if($task->active == 1) echo "selected"; ?>>Yes</option>
        <option value="0" <? if($task->active == 0) echo "selected"; ?>>No</option>
      </select>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="box">
      <label for="task-comment-<?= $task_id ?>">Comment:</label>
      <textarea id="task-comment-<?= $task_id ?>" rows="8" name="task-comment-<?= $task_id ?>"><?= stripslashes($task->comment) ?></textarea>
    </div>
  </div>
</div>

<?php
  $order = $task_id !== $checklist_id . '-create'
    ? $task->order
    : count($checklist->tasks);
?>

<input type="hidden" name="task-order-<?= $task_id ?>" value="<?= $order ?>" class="task-order">
<input type="hidden" name="task-id-<?= $task_id ?>" value="<?= $task_id ?>">
