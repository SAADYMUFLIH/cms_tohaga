<script src="<?php echo base_url() ?>assets/js/choosen_auto_direction.js"></script>
<script type="text/javascript">
var tree_edit;
  $(document).ready(function() {
    $('.chosen-select', this).chosen({
      width: "100%"
    });
    $('#mdl_edit').on('show.bs.modal', function(e) {

    });

      tree_edit = $('#tree<?php echo $prefix_edit ?>').tree({
      primaryKey: 'idmenu_edit',
      uiLibrary: 'bootstrap',
      dataSource: <?php echo json_encode($arr_init_menu_selected); ?>,
      checkboxes: true,
      cascadeCheck: false,
      lazyloading: true
    });

  });
</script>


<form id="frm_edit" enctype="multipart/form-data" role="form">
  <div class="modal-body" id="scroll_div_edit">
    <input type="hidden" value="<?php echo $dt_init->$key_edit ?>" id="<?php echo $id_edit_name ?>" name="<?php echo $id_edit_name ?>">
    <!--modal content start here-->
    <div class="form-group">
      <div class="row">
        <div class="col-md-4">
          <?php echo generateStandardInputBox("Nama User Group", '', "roles_name$prefix_edit", $dt_init->roles_name, "", true) ?>
        </div>
        <div class="col-md-8">
          <?php echo generateStandardInputBox("Keterangan", '', "roles_desc$prefix_edit", $dt_init->roles_desc, "", false) ?>
        </div>
      </div>

      <div class="container text-sm">
        <input type="hidden" id="selected_menu<?php echo $prefix_edit ?>" name="selected_menu<?php echo $prefix_edit ?>">
        <h4>List Menu</h4>
        <div class="row text-sm">
          <div id="tree<?php echo $prefix_edit ?>"></div>
        </div>
      </div>


    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>
        Update</button>
      <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>
        Close</button>
    </div>
    <!-- modal add role end -->
    <script language="javascript">

      function fill_selected_menu_edit() {
        var checkedIds = tree_edit.getCheckedNodes();
        $("#selected_menu<?php echo $prefix_edit ?>").val(checkedIds);
      }


      $('#frm_edit').submit(function(e) {

        fill_selected_menu_edit()

        var form = $(this);
        var formdata = false;
        if (window.FormData) {
          formdata = new FormData(form[0]);
        }

        var formAction = form.attr('action');
        $.ajax({
          type: 'POST',
          url: '<?php echo base_url() . $controller ?>/update_data',
          cache: false,
          data: formdata ? formdata : form.serialize(),
          dataType: 'json',
          contentType: false,
          processData: false,
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(xhr.responseText);
            console.log(thrownError);
          },
          success: function(response) {
            toastr.options = {
              timeOut: 3000,
              positionClass: "toast-bottom-right"
            };
            if (response['error'] == 'true') {
              toastr["error"](response['msg']);
            } else {
              toastr['info'](response['msg']);
            }
            searchFilter(document.getElementById('page_pos').value);
            $('#mdl_edit').modal('hide');
          }
        });
        e.preventDefault();
      });
    </script>

</form>