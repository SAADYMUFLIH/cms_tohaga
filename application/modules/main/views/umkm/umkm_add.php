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
    <?php $data_bb ?>
    <!-- Form object Start Here -->
    <div class="modal-body">
        <div class="modal-body" id="scroll_div_add">
            <!-- form content start here -->
            <!-- <input type="hidden" value="<?php echo isset($dt_init->$key_edit) ? $dt_init->$key_edit : '' ?>" id="<?php echo $id_edit_name ?>" name="<?php echo $id_edit_name ?>"> -->
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Nama UMKM", '', "title", isset($dt_init->title) ? $dt_init->title : '', true, '', '', '', '', true) ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Informasi Kontak", '', "kontak", isset($dt_init->telp) ? $dt_init->telp : '', true, '', '', '', '', true) ?>
                    </div>
                    <div class="col-md-12">
                        <?php
                        echo '<div class="form-group">
                         <label for="">Sejarah</label>';

                        $data = array(
                            'name'        => 'desc',
                            'id'          => 'txt_area',
                            'value'       => isset($dt_init->sejarah) ? $dt_init->sejarah : '',
                            'rows'        => '7',
                            'cols'        => '10',
                            'readonly' => 'readonly',
                            'style'       => 'width:100%',
                        );

                        echo form_textarea($data);
                        echo '</div>';
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?php
                        echo '<div class="form-group">
                         <label for="">Alamat</label>';

                        $data = array(
                            'name'        => 'desc',
                            'id'          => 'txt_area',
                            'value'       => isset($dt_init->alamat) ? $dt_init->alamat : '',
                            'rows'        => '3',
                            'cols'        => '10',
                            'readonly' => 'readonly',
                            'style'       => 'width:100%',
                        );

                        echo form_textarea($data);
                        echo '</div>';
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Provinsi", '', "nama_provinsi", isset($dt_init->nama_provinsi) ? $dt_init->nama_provinsi : '', true, '', '', '', '', true) ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Kota/Kabupaten", '', "nama_kabupaten", isset($dt_init->nama_kabupaten) ? $dt_init->nama_kabupaten : '', true, '', '', '', '', true) ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Kecamatan", '', "nama_kecamatan", isset($dt_init->nama_kecamatan) ? $dt_init->nama_kecamatan : '', true, '', '', '', '', true) ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo generateStandardInputBox("Kelurahan/Desa", '', "nama_kelurahan", isset($dt_init->nama_kelurahan) ? $dt_init->nama_kelurahan : '', true, '', '', '', '', true) ?>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="roles_id">Foto</label><br>
                            <img class="img img-fluid" src="<?php echo HOSTNAMEAPI . '/assets/images/img_umkm/' . $dt_init->img; ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <table class="table table-bordered  table-hover table-striped">
                            <thead class=" thead-dark">
                                <tr>
                                    <!-- tbb.name , tbb.bulan , tbb.desc , tbb.jml ,su.title, ss.value_setting, tkbb.name as kat_bb -->
                                    <th>Bahan Baku</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if( !empty($data_bb) ) {
                                    foreach($data_bb as $val) {
                                        echo '<td>'.$val->name.'</td>';
                                        echo '<td>'.$val->bulan.'</td>';
                                        echo '<td align="right">'.$val->jmlh.'</td>';
                                        echo '<td>'.$val->value_setting.'</td>';
                                        echo '<td>'.$val->kat_bb.'</td>';
                                        echo '<td>'.$val->desc.'</td>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <label for="roles_id">Produk</label><br>
                        <table class="table table-bordered  table-hover table-striped">
                            <thead class=" thead-dark">
                                <tr>
                                    <!-- tbb.name , tbb.bulan , tbb.desc , tbb.jml ,su.title, ss.value_setting, tkbb.name as kat_bb -->
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Desc</th>
                                    <th>Action</th>
                                    <!-- <th>Satuan</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th> -->

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if( !empty($data_product) ) {
                                    foreach($data_product as $val) {
                                        // // echo '<tr id="row-'echo $val->id_product'">'.$val->name.
                                        
                                        // '</tr>';
                                        echo '<td>'.$val->bulan.'</td>';
                                        echo '<td align="right">'.$val->jmlh.'</td>';
                                        echo '<td>'.$val->value_setting.'</td>';
                                        echo '<td>'.$val->kat_bb.'</td>';
                                        echo '<td>'.$val->desc.'</td>';
                                    }
                                }
                                ?>
                                <?php
                                foreach ($data_product as $val) {
                                ?>
                                    <tr id="row-<?php echo $val['id_product']?>">
                                        <td><?= $val['name'] ?></td>
                                        <td align="right"><?= (null == $val['price'] ? "Belum Ada Harga" : "Rp. " . number_format($val['price'],0,',','.'))  ?></td>
                                        <td><?= $val['desc'] ?></td>
                                        <td style="text-align:center"><button class="btn btn-danger delprod" type="button" value="<?php echo $val['id_product']?>" _name="<?= $val['name'] ?>"><i class="fa fa-trash"></i> Hapus</button></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
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