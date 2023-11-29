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

    function notifUpdate() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() . $controller ?>/ajaxNotifUpdate',
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            success: function(html) {
                $(".notif_menu-15").text(' ('+html+')');
                console.log(html)
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
<form id="theform" enctype="multipart/form-data" role="form">
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?php echo $form_input_title ?></h5>
    <button type="submit" class="close" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>

    <!-- Form object Start Here -->
    <div class="modal-body">
        <div class="modal-body" id="scroll_div_add">
            <!-- form content start here -->
            <input type="hidden" value="<?php echo isset($dt_init->$key_edit) ? $dt_init->$key_edit : '' ?>" id="<?php echo $id_edit_name ?>" name="<?php echo $id_edit_name ?>">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Judul", '', "title", isset($dt_init->title) ? $dt_init->title : '', true, '', '', '', '', true) ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Informasi Kontak", '', "kontak", isset($dt_init->kontak) ? $dt_init->kontak : '', true, '', '', '', '', true) ?>
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
                            'readonly' => 'readonly',
                            'style'       => 'width:100%',
                        );

                        echo form_textarea($data);
                        echo '</div>';
                        ?>

                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="roles_id">Foto</label><br>
                            <img class="img img-fluid" src="<?php echo HOSTNAMEAPI . '/assets/images/img_aspirasi/'.$dt_init->img; ?>">
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
        <!-- <button type="submit" id="btnsave" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Save</button> -->
        <button type="submit" class="btn btn-danger btn-sm" id="closeModal"><i class="fas fa-sign-out-alt"></i> Close</button>
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
        // var formdata = false;
        // if (window.FormData) {
        //     formdata = new FormData(form[0]);
        // }
        // toastr.options = {
        //     timeOut: 3000,
        //     positionClass: "toast-bottom-right"
        // };

        $("#img_mdl_loader").show();
        var formAction = form.attr("action");
        notifUpdate();
        searchFilter(document.getElementById("page_pos").value);
        $("#img_mdl_loader").hide();
        $("#mdl_add").modal("hide");
        
    
        e.preventDefault();
    });
</script>