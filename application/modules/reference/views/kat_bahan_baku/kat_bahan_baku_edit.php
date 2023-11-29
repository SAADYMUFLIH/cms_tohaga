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
            <!-- form Object  start here -->
            <input type="hidden" value="<?php echo $dt_init->$key_edit ?>" id="<?php echo $id_edit_name ?>" name="<?php echo $id_edit_name ?>">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">
                        <?php echo generateStandardInputBox("Nama Kategori Bahan Baku", '', "name", $dt_init->name) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form object Start Here -->
    <!-- Button Start here -->
    <div class="modal-footer">
        <span style="display: none" id="img_mdl_loader"><img src="<?php echo base_url() . 'assets/images/89.gif'; ?>" /><small> Processing...</small> </span>
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Update</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-sign-out-alt"></i> Close</button>
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
            url: '<?php echo base_url() . $controller ?>/updateData',
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