<script type="text/javascript">
  $(document).ready(function() {
    $('.chosen-select', this).chosen({
      width: "100%"
    });
  });
</script>
<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel"><?php echo $form_input_title ?></h5>
  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
</div>

<form id="theform" enctype="multipart/form-data" role="form">
  <!-- Form object Start Here -->
  <div class="modal-body">
    <div class="modal-body" id="scroll_div_add">
      <!-- form content start here -->
      <div class="form-group">

        <div class="row">
          <div class="col-md-6">
            <?php echo generateStandardInputBox("Nama Lengkap *", '', "full_name", (!empty($dt_init->full_name) ? $dt_init->full_name : ''), "", true) ?>
          </div>
          <div class="col-md-6">
            <?php echo generateEmailInputBox("Email *", '', "email", (!empty($dt_init->email) ? $dt_init->email : ''), "", true) ?>
          </div>
        </div>

        <!-- <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="roles_id">Jabatan</label><br>
              <?php //echo $cbo_jabatan 
              ?>
            </div>
          </div>-->
      </div>

      <div class="row">
        <div class="col-md-6">
          <?php echo generatePasswordInputBox("Password *", '', "password", (!empty($dt_init->pwd) ?  $dt_init->pwd : ''), "", true); ?>
        </div>
        <!-- <div class="col-md-6">
          <div class="form-group">
            <label for="roles_id">Atasan Langsung</label><br>
            <?php //echo $cbo_spv ?>
          </div>
        </div> -->
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="roles_id">Hak Akses Sebagai *</label><br>
            <?php echo $cbo_role ?>
          </div>
        </div>
      </div>

    </div>
  </div>
  </div>
  <!-- Form object Start Here -->
  <!-- Button Start here -->
  <div class="modal-footer">
    <span style="display: none" id="img_mdl_loader"><img src="<?php echo base_url() . 'assets/images/89.gif'; ?>" /><small> Processing...</small></span>
    <button type="submit" id="btnsave" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Save</button>
    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i> Close</button>
  </div>
  <!-- Button end here -->
</form>

<script>
    $("#theform").submit(function(e) {
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        toastr.options = {
            timeOut: 3000,
            positionClass: "toast-bottom-right"
        };
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
            url: '<?php echo base_url() . $controller ?>/saveData',
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
        e.preventDefault();
    });
</script>