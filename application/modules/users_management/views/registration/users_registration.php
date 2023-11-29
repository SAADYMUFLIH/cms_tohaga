<script src="<?php echo base_url() ?>assets/js/choosen_auto_direction.js"></script>
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


  });

  function displayInputForm(id) {
    editdata_popup(id, '<?php echo base_url() . $controller ?>/displayInputForm', 'frmContainer', 'mdl_add');
  }

  function deleteData() {
    delete_record('<?php echo base_url() . $controller ?>/rowDelete', 'frm_list')
  }

  function editdata(id) {
    editdata_popup(id, '<?php echo base_url() . $controller ?>/editData', 'frmContainer', 'mdl_add');
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

<?php 
$view_button = isset($access) ? $access : false;
echo generateSearch_add_new_button($search_place_holder, $view_button);

// echo generateSearch_add_new_button($search_place_holder, true); 
?>
<div class="mt-3">
  <form id="frm_list">
    <?php echo $list_data; ?>
  </form>
</div>
<?php 
if($view_button){
  echo generateBottom_add_new_and_delete_button(); 
}
?>

<!-- Modal input data start here -->
<div class="modal fade" id="mdl_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div id="frmContainer">
        <!-- Modal content start here -->

        <!-- Modal content End Here -->
      </div><!-- form Container -->
    </div>
  </div>
</div>

<script>
  function processData(statusProcess, event) {
    var form = $("#theform");
    var formdata = false;
    if (window.FormData) {
      formdata = new FormData(form[0]);
    }
    toastr.options = {
      timeOut: 3000,
      positionClass: "toast-bottom-right"
    };

    $("#img_mdl_loader").show();
    var formAction = form.attr("action");
    $.ajax({
      type: "POST",
      url: '<?php echo base_url() . $controller ?>/' + statusProcess,
      cache: false,
      dataType: "json",
      data: formdata ? formdata : form.serialize(),
      contentType: false,
      processData: false,
      error: function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(xhr.responseText);
        console.log(thrownError);
        $("#img_mdl_loader").hide();
      },
      success: function(response) {
        if (response["error"] == "true") {
          toastr.options = {
            timeOut: 3000,
            positionClass: "toast-top-center"
          };
          toastr["error"](response["msg"]);
          $("#img_mdl_loader").hide();
          return false;
        } else {
          toastr["info"](response["msg"]);
          searchFilter(document.getElementById("page_pos").value);
          $("#img_mdl_loader").hide();
          $("#mdl_add").modal("hide");
        }
      }
    });
    //e.preventDefault();
  }
</script>