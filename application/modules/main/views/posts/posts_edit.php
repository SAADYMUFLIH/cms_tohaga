<script>
    $(document).ready(function() {
        init_grid_data_manipulation();
        $('.chosen-select', this).chosen({
            width: "100%"
        });
    });
</script>
<script src="<?php echo base_url() ?>assets/js/dropzone.js"></script>
<link href="<?php echo base_url() ?>assets/css/dropzone.css" rel="stylesheet">
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
                    <div class="col-md-12">
                        <?php echo generateStandardInputBox("Judul Berita", '', "judul_berita", $dt_init->judul_berita, true) ?>
                        <?php echo generateStandardInputBox("Isi Berita", '', "isi_berita", $dt_init->isi_berita, true) ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="roles_id">Upload Gambar Berita</label><br>
                                    <div class="text-center">
                                        <?php
                                        $profile_pic = 'img_berita.png';
                                        ?>
                                        <div class="dropzone">
                                            <!-- <div id="img_container">
                                                <?php //$uid = substr(str_shuffle($profile_pic), 0, 10) 
                                                ?>
                                                <img id="display_pic" class="profile-user-img img-fluid img-circle" style="height: 180px; width:200px" src="<?php echo base_url() . 'assets/images/profile/' . $profile_pic ?>?token=<?php echo $uid ?>" alt="User profile picture">
                                            </div> -->
                                            <div class="dz-message">
                                                <small>Klik atau Drop gambar disini</small>
                                                <input type="hidden" id="token">
                                            </div>
                                        </div>

                                        <script>
                                            Dropzone.autoDiscover = false;

                                            var foto_upload = new Dropzone(".dropzone", {
                                                url: "<?php echo base_url('main/berita/upload_foto') ?>",
                                                maxFilesize: 50, //mb
                                                method: "post",
                                                acceptedFiles: "image/*",
                                                paramName: "foto",
                                                dictInvalidFileType: "Type file ini tidak dizinkan",
                                                addRemoveLinks: false,
                                                autoProcessQueue: false
                                            });

                                            foto_upload.on("addedfile", function(file) {
                                                $("#token").val(file.upload.uuid);
                                                if (this.files.length > 1) {
                                                    this.removeFile(this.files[0]);
                                                }
                                            });
                                            //Event ketika Memulai mengupload
                                            foto_upload.on("sending", function(a, b, c) {
                                                a.token = Math.random();
                                                c.append("token_foto", a.token); //Menmpersiapkan token untuk masing masing foto
                                                c.append("id_berita", <?= $dt_init->id_berita ?>);
                                                c.append("old_img", '<?= $dt_init->img_file ?>');
                                                // c.append("jp_diperiksa", $("#jp_diperiksa").val());
                                                // c.append("is_jentik", $("#is_jentik").val());
                                                // c.append("jp_ditemukan", $("#jp_ditemukan").val());
                                            });

                                            //Event ketika Memulai mengupload
                                            foto_upload.on("success", function(file, response) {
                                                $("#img_container").hide().html(response).fadeIn("slow");
                                                this.removeFile(file);
                                            });
                                            $("#btnsave").click(function(e) {
                                                // foto_upload.processQueue();
                                                // if ($("#is_jentik").val() == 1) {
                                                //     e.preventDefault();
                                                // } else {}

                                                // console.log('dari upload');
                                                // foto_upload.processQueue();
                                            });
                                        </script>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Form object Start Here -->
    <!-- Button Start here -->
    <div class="modal-footer">
        <span style="display: none" id="img_mdl_loader"><img src="<?php echo base_url() . 'assets/images/89.gif'; ?>" /><small> Processing...</small> </span>
        <button type="submit" id="btnsave" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Update</button>
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
        if ($("#token").val()) {
            foto_upload.processQueue();
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
                        // foto_upload.processQueue();
                        toastr["info"](response["msg"]);
                        searchFilter(document.getElementById("page_pos").value);
                        $("#img_mdl_loader").hide();
                        $("#mdl_add").modal("hide");
                    }
                }
            });
        } else {
            alert("Mohon upload gambar berita");
            $("#img_mdl_loader").hide();
            return false;
        }
        e.preventDefault();
    });
</script>