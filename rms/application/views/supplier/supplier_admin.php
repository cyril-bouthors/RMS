  <a href="#create" class="ui-btn ui-btn-right" rel="external" data-ajax="false" data-icon="plus"><i class="zmdi zmdi-plus zmd-2x"></i></a>
</div>

<div data-role="content">
  <div id="creation-form" style="display: none;">
    <?php
      $supplier = $empty_supplier;
      require('supplier_form.php');
    ?>
  </div>

  <div id="update-forms" data-role="collapsible-set">
    <? foreach ($suppliers as $supplier) {
      $bgcolor = $supplier->active ? '#eceeff' : '#bbbdbd';
    ?>
      <div data-role="collapsible" style="background-color: <?= $bgcolor ?>">
        <h2>ID: <?= $supplier->id ?> | <?= $supplier->name ?> | <small><?= $supplier->category_name ?></small></h2>
        <ul data-role="listview" data-theme="d" data-divider-theme="d">
          <li>
            <?php require('supplier_form.php'); ?>
          </li>
        </ul>
      </div>
    <?php } ?>
    <script type="text/javascript">
      $(document).ready(function() {
        // prepare creation form toggle
        var create = $('#creation-form');
        var update = $('#update-forms');
        $('a[href="#create"]').on('click', function() {
          if (create.is(':visible')) {
            create.hide();
            update.show();
          } else {
            update.hide();
            create.show();
          }
        });

        // prepare forms ajax
        $('form.supplier-update').each(function(i, form) {
          form = $(form);

          form.find('input[type="submit"]').on('click', function(evt) {
            evt.preventDefault();
            form.trigger('submit');
          });

          form.on('submit', function(evt) {
            evt.preventDefault();

            var inputName = $(form).find('input[name="name"]');
            var name = inputName.val().trim().toUpperCase();

            if (!name.length || !/^[A-Z0-9]+$/.test(name))
              return alert('Missing or invalid name (no space nor special characters)');

            inputName.val(name);

            $.ajax({
              url: form.attr('action'),
              type: form.attr('method'),
              data: form.serialize(),
              dataType: 'json'
            }).done(function(data) {
              if (!data.success)
                alert(data.message || 'An unknown error occured, not saved');
              else
                alert('Data has been saved');
            });
          });
        });
      });
    </script>
  </div>
</div>
