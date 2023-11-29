<script src="<?php echo base_url() ?>assets/js/choosen_auto_direction.js"></script>
<link href="<?php echo base_url() ?>assets/css/glyph.css" rel="stylesheet">
<script>
  function searchFilter(page_num) {
    page_num = page_num ? page_num : 0;
    document.getElementById('page_pos').value = page_num;
    var keywords = $('#keywords').val();
    var sortBy = $('#sortBy').val();
    $('.loading').show();
    $.ajax({
      type: 'POST',
      url: '<?php echo base_url() . $controller ?>/ajaxPaginationData/' + page_num,
      data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy,
      error: function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(xhr.responseText);
        console.log(thrownError);
      },
      success: function(html) {
        $('#postList').html(html);
        $('.loading').hide();
      }
    });
  }
  var tree;
  $(document).ready(function() {
    $('.chosen-select', this).chosen({
      width: "100%"
    });

    $('.modal-dialog').draggable({
      handle: ".modal-header"
    });

    init_grid_data_manipulation();

    $(document).on('shown.bs.modal', function(e) {
      $('input:visible:enabled:first', e.target).focus();
    });

    $('[data-toggle="popover"]').popover();

    tree = $('#tree').tree({
      primaryKey: 'idmenu',
      uiLibrary: 'bootstrap',
      dataSource: '<?php echo base_url() . $controller ?>/get_tree_menu_array' ,
      checkboxes: true,
      cascadeCheck: false
    });
  });

  function deleteData() {
    delete_record('<?php echo base_url() . $controller ?>/row_delete', 'frm_list')
  }

  function editdata(id) {
    editdata_popup(id, '<?php echo base_url() . $controller ?>/edit_data', 'edit_container',
      'mdl_edit');
  }

  function exportToExcel() {
        var keywords = $('#keywords').val();
        //var rowNum = $('#sortBy').val();
        $(location).attr('href', '<?php echo base_url() . $controller ?>/exportToExcel?keywords=' + keywords);
    }

  $(function() {
    $('#mdl_add').on('hidden.bs.modal', function() {
      $(this).find("input,textarea,checkbox").val('').end();
    });
  });

  $(function() {
    $('#mdl_add').on('hidden.bs.modal', function() {
      $(this).find("input,textarea,checkbox").val('').end();
      $('select').children('option').first().prop('selected', true)
      $('select').trigger("chosen:updated");

    });
  });
</script>

<?php echo generateSearch_add_new_button($search_place_holder, false);
?>
<div class="mt-3">
  <form id="frm_list">
    <?php echo $list_data; ?>
  </form>
</div>
<?php //echo generateBottom_add_new_and_delete_button(false) ?>


<!-- Modal input data start here -->
<div class="modal fade" id="mdl_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $form_input_title ?></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form id="frm_add" enctype="multipart/form-data" role="form">
        <div class="modal-body">

          <div class="modal-body" id="scroll_div_add">
            <!--modal content start here-->
            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <?php echo generateStandardInputBox("Nama User Group", '', "roles_name", "", "", true) ?>
                </div>
                <div class="col-md-8">
                  <?php echo generateStandardInputBox("Keterangan", '', "roles_desc", "", "", false) ?>
                </div>
              </div>

              <div class="container text-sm">
              <input type="hidden" id="selected_menu" name="selected_menu">
                <h4>List Menu</h4>
                <div class="row text-sm">
                  <div id="tree"></div>
                </div>                
              </div>
            </div>

            <!--modal content end here-->
          </div>
          <div class="modal-footer">
            <span style="display: none" id="img_mdl_loader"><img src="<?php echo base_url() . 'assets/images/89.gif'; ?>" /><small> Processing...</small> </span>
            <button type="submit"  id="btnsave" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Save</button>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-close"></i>
              Close</button>
          </div>
      </form>
    </div>
  </div>
</div>
</div>
<!-- Modal input data end here -->


<script>

  function fill_selected_menu(){
    var checkedIds =tree.getCheckedNodes();
    $("#selected_menu").val(checkedIds);
  }

  $('#frm_add').submit(function(e) {

    fill_selected_menu()
    var form = $(this);
    var formdata = false;
    if (window.FormData) {
      formdata = new FormData(form[0]);
    }
    toastr.options = {
      timeOut: 3000,
      positionClass: "toast-bottom-right"
    };

    $('#btnsave').button('loading');
    var formAction = form.attr('action');
    $.ajax({
      type: 'POST',
      url: '<?php echo base_url() . $controller ?>/insert_data',
      cache: false,
      dataType: 'json',
      data: formdata ? formdata : form.serialize(),
      contentType: false,
      processData: false,
      error: function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(xhr.responseText);
        console.log(thrownError);
      },
      success: function(response) {
        $('#btnsave').button('reset');
        if (response['error'] == 'true') {
          toastr.options = {
            timeOut: 3000,
            positionClass: "toast-top-center"
          };
          toastr["error"](response['msg']);
          return false;
        } else {
          toastr['info'](response['msg']);
          searchFilter(document.getElementById('page_pos').value);
          $('#mdl_add').modal('hide');
        }
      }

    });
    e.preventDefault();
  });
</script>

<!-- Modal for Edit -->
<div class="modal fade" id="mdl_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $form_edit_title ?></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="edit_container">
        <!-- modal content start here -->
        <!-- Modal content end here -->
      </div>
    </div>
  </div>
</div>
<!-- Modal for Edit end here -->



<div class="modal fade" id="confirm_del" tabindex="-1" role="dialog" aria-labelledby="mdl_roleLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <h4 class="modal-title" id="mdl_roleLabel">Delete Confirmation</h4>
      </div>
      <div class="modal-body">
        Are you sure want to delete seleted data ?
      </div>
      <div class="modal-footer">
        <?php $hal = $this->uri->segment(4); ?>
        <button type="button" onclick="delete_data()" class="btn btn-success btn-sm"><i class="fa fa-check"></i>
          Yes</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-ban"></i>
          No</button>
      </div>
    </div>
  </div>
</div>