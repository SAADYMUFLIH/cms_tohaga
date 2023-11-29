<link href="<?php echo base_url() ?>assets/css/dropify.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/css/rounded-progress-bar.css" rel="stylesheet">
<script src="<?php echo base_url() ?>assets/js/dropify.js"></script>
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
        init_grid_data_manipulation();
        $('.chosen-select', this).chosen({
            width: "100%"
        });

        // $('#no_ktp').mask("0000000000000000");
        // $('.matauang').autoNumeric('init', {
        //     unformatOnSubmit: true
        // });

        $('.dropify').dropify();

        var pathURL = "file_path/";
        var dropifyElements = {};
        $('.dropify').each(function() {
            dropifyElements[this.id] = true;
        });

        var drEvent = $('.dropify').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            id = event.target.id;
            if (dropifyElements[id]) {
                $.confirm({
                    title: 'Konfirmasi Hapus Dokumen',
                    content: 'Yakin document akan dihapus?',
                    confirmButton: 'Process',
                    cancelButton: 'Cancel',
                    type: 'red',
                    columnClass: 'medium',
                    buttons: {
                        proses: function() {
                            var drEvent = $('#' + id + '').dropify();
                            drEvent = drEvent.data('dropify');
                            drEvent.resetPreview();
                            drEvent.settings.defaultFile = '';
                            rem_record(id);
                        },
                        cancel: function() {}
                    }
                });
                return false; //confirm
            }
        });


    });

    function rem_record(f_id) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . $controller ?>/remove_img',
            data: {
                the_id: $("#<?php echo $id_edit_name ?>").val(),
                fid: f_id
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            success: function(response) {
                $.alert({
                    title: 'Info',
                    type: 'green',
                    content: response,
                });
                searchFilter(document.getElementById('page_pos').value);
            }
        });
    }

    $(function() {

        $(".rounded-progress").each(function() {
            var value = $(this).attr('data-value');
            var left = $(this).find('.rounded-progress-left .rounded-progress-bar');
            var right = $(this).find('.rounded-progress-right .rounded-progress-bar');

            if (value > 0) {
                if (value <= 50) {
                    right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
                } else {
                    right.css('transform', 'rotate(180deg)')
                    left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
                }
            }
        })

        function percentageToDegrees(percentage) {
            return percentage / 100 * 360
        }

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
            <input type="hidden" value="<?php echo isset($dt_init->$key_edit) ? $dt_init->$key_edit : '' ?>" id="<?php echo $id_edit_name ?>" name="<?php echo $id_edit_name ?>">
            <div class="form-group">
                <div class="row">
                <div class="col-md-12">
                        <?php echo generateStandardInputBox("Judul", '', "title", isset($dt_init->title) ? $dt_init->title : '', true) ?>
                    </div>                    
                    <div class="col-md-12">
                        <?php echo generateStandardInputBox("Karya", '', "karya", isset($dt_init->karya) ? $dt_init->karya : '', true) ?>
                    </div>
                    <div class="col-md-12">
                        <?php
                        echo '<div class="form-group">
                         <label for="">Deskripsi</label>';

                        $data = array(
                            'name'        => 'desc',
                            'id'          => 'txt_area',
                            'value'       => isset($dt_init->desc) ? $dt_init->desc : '',
                            'rows'        => '11',
                            'cols'        => '10',
                            'style'       => 'width:100%',
                        );

                        echo form_textarea($data);
                        echo '</div>';
                        ?>

                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="roles_id">Upload file ebook</label><br>
                            <?php
                            $profile_pic =  empty($dt_init->file) ? '' : 'assets/images/file_ebook/' . $dt_init->file;
                            ?>
                            <input type="file" id="file" name="file" data-default-file="<?php echo $profile_pic  ?>" class="dropify" data-allowed-file-extensions="pdf" accept="application/pdf" data-height="150">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="roles_id">Upload gambar cover ebook</label><br>
                            <?php
                            $profile_pic =  empty($dt_init->img) ? '' : 'assets/images/file_ebook/' . $dt_init->img;
                            ?>
                            <input type="file" id="img" name="img" data-default-file="<?php echo $profile_pic  ?>" class="dropify" data-allowed-file-extensions="gif jpg jpeg png" data-height="150">
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
                    // foto_upload.processQueue();
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