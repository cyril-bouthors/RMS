  <?php

  $can_edit_checklists = $this->ion_auth_acl->has_permission('admin_panel_checklist');
  $can_edit_tasks = $this->ion_auth_acl->has_permission('admin_panel_checklist_task');

  if ($can_edit_checklists) { ?>
    <a href="#create" class="ui-btn ui-btn-right" rel="external" data-ajax="false" data-icon="plus"><i class="zmdi zmdi-plus zmd-2x"></i></a>
  <?php } ?>

</div>
<script type="text/javascript" src="/public/Sortable.min.js"></script>

<div data-role="content">
  <div id="creation-form" style="display: none;">
    <?php
      $checklist = $empty_checklist;
      require('checklist_form.php');
    ?>
  </div>

  <div id="update-forms">
    <?php foreach ($checklists as $checklist) {
      $bgcolor = $checklist->active ? '#eceeff' : '#bbbdbd';
    ?>
      <div data-id="<?= $checklist->id ?>" data-role="collapsible" style="background-color: <?= $bgcolor ?>;" class="collapsible-checklist">
        <h2>
          ID: <?= $checklist->id ?> | <?= $checklist->name ?> | <small> <?= $checklist->type ?></small>
          <?php if ($can_edit_checklists) { ?>
            <span class="sort-icon checklists-sort"><?= $checklist->order ?>&nbsp;<i class="fa fa-sort"></i></span>
          <?php } ?>
        </h2>
        <div data-role="listview" style="background-color: #fff;">
          <?php require('checklist_form.php'); ?>
        </div>
      </div>
    <?php } ?>
    <script type="text/javascript">
      $(document).ready(function() {
        'use strict';

        // set collapsible hash on expand
        setTimeout(function() {
          if (/#\d+/i.test(window.location.hash)) {
            var id = window.location.hash.split('#').slice(1).join('#');
            var elem = $('.collapsible-checklist[data-id="' + id + '"]');
            elem.collapsible({ collapsed: false });
          }

          var collapsibles = $('.collapsible-checklist');

          collapsibles.on('collapsibleexpand', function() {
            window.location.hash = '#' + this.dataset.id;
          });

          collapsibles.on('collapsiblecollapse', function() {
            if (window.location.hash === '#' + this.dataset.id)
              window.location.hash= '';
          });
        }, 0);

        // prepare creation form toggle
        var create = $('#creation-form');
        var update = $('#update-forms');
        function toggleCreationForm() {
          if (create.is(':visible')) {
            create.hide();
            update.show();
          } else {
            update.hide();
            create.show();
          }
        }
        $('a[href="#create"]').on('click', toggleCreationForm);

        // prepare forms ajax
        function submitForm(evt) {
          if (evt && evt.preventDefault)
            evt.preventDefault();

          var elem = $(this);

          var inputName = elem.find('input[name="name"]');
          var name = inputName.val().trim().toUpperCase();

          inputName.val(name);

          var idField = elem.find('input[name="id"]');
          var isUpdate = idField && idField.val();

          var tasks = {};
          var data = elem.serializeArray().reduce(function(data, entry) {
            var name = entry.name, value = entry.value;

            if (name.indexOf('task-') === -1) {
              data[name] = value;
            } else {
              var infos = name.split('-');
              var idStr = infos[infos.length - 1];

              var id = idStr !== 'create'
                ? parseInt(idStr, 10)
                : idStr;

              var field = id !== 'create'
                ? infos.slice(1, -1)[0]
                : infos.slice(1, -2)[0];

              if (!tasks[id])
                tasks[id] = {};

              if (field === 'id')
                tasks[id][field] = id;
              else
                tasks[id][field] = value;
            }

            return data;
          }, {
            tasks: []
          });

          if (tasks.create && !tasks.create.name && !tasks.create.comment)
            delete tasks.create;

          for (var i in tasks)
            data.tasks.push(tasks[i]);

          $.ajax({
            url: elem.attr('action'),
            type: elem.attr('method'),
            data: data,
            dataType: 'json'
          }).done(function(data) {
            if (!data.success)
              return alert(data.message || 'An unknown error occured, not saved');

            if (evt.reload !== false)
              window.location.reload(true);
          });
        }
        $('form.checklist-update').each(function(i, form) {
          form = $(form);

          form.find('input[type="submit"]').on('click', function(evt) {
            evt.preventDefault();
            form.trigger('submit');
          });

          form.on('submit', submitForm);
        });

        // sort checklists
        function saveOrder()
        {
          update.addClass('saving-sort');
          checklistsSort.option('disabled', true);

          var orderedIds = [];

          update.children().each(function(idx, elem) {
            var toUpdate = elem.dataset
              && elem.dataset.id !== undefined
              && elem.dataset.id !== null;

            if (toUpdate) {
              orderedIds.push(elem.dataset.id);

              var orderElem = elem.getElementsByClassName('checklists-sort')[0];
              setTimeout(function() {
                orderElem.innerHTML = idx + '&nbsp;<i class="fa fa-sort"></i>';
              }, 0);
            }
          });

          $.ajax({
            url: '/checklist/order',
            type: 'POST',
            data: { ids: orderedIds },
            dataType: 'json'
          }).done(function(data) {
            checklistsSort.option('disabled', false);
            update.removeClass('saving-sort');

            if (!data.success)
              return alert(data.message || 'An unknown error occured, not saved');
          });
        }
        var checklistsSort = new Sortable(update.get(0), {
          group: 'checklists',
          sort: true,
          handle: '.checklists-sort',
          onUpdate: saveOrder
        });

        // sort tasks
        function updateTasksOrder(parent)
        {
          parent.children().each(function (idx, elem) {
            if (!$(elem).hasClass('checklist-task'))
              return;

            elem.getElementsByClassName('sort-icon')[0].innerHTML = idx + '&nbsp;<i class="fa fa-sort"></i>';
            elem.getElementsByClassName('task-order')[0].value = idx;
          });

          submitForm.bind(parent.closest('form'))({ reload: false });
        }
        update.find('.tasks-sorting').each(function (idx, tasksSorting) {
          var id = tasksSorting.dataset.id;

          var tasksSort = new Sortable(tasksSorting, {
            group: id,
            sort: true,
            handle: '.tasks-sort',
            onUpdate: function() { updateTasksOrder($(tasksSorting)); }
          });
        });
      });
    </script>
  </div>
</div>
