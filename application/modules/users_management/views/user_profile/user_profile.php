<link href="<?php echo base_url() ?>assets/css/jquery.signaturepad.css" rel="stylesheet">
<script src="<?php echo base_url() ?>assets/js/numeric-1.2.6.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bezier.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.signaturepad.js"></script>

<script src="<?php echo base_url() ?>assets/js/dropzone.js"></script>
<link href="<?php echo base_url() ?>assets/css/dropzone.css" rel="stylesheet">
<script src="<?php echo base_url() ?>assets/js/html2canvas.js"></script>
<div class="row">
  <div class="col-md-3">

    <!-- Profile Image -->
    <div class="card card-success card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <?php
          $profile_pic = ($dtl_profile->profile_pic == '') ? 'no_profile.png' : $dtl_profile->profile_pic;
          ?>
          <div class="dropzone" style="border:0px">
            <div id="img_container">
              <?php $uid = substr(str_shuffle($profile_pic), 0, 10) ?>
              <img id="display_pic" class="profile-user-img img-fluid img-circle" style="height: 180px; width:200px" src="<?php echo base_url() . 'assets/images/profile/' . $profile_pic ?>?token=<?php echo $uid ?>" alt="User profile picture">
            </div>
            <div class="dz-message">
              <small>Untuk edit foto, klik atau Drop gambar disini</small>
            </div>
          </div>

          <script>
            Dropzone.autoDiscover = false;

            var foto_upload = new Dropzone(".dropzone", {
              url: "<?php echo base_url('users_management/user_profile/update_profile_pic') ?>",
              maxFilesize: 2,
              method: "post",
              acceptedFiles: "image/*",
              paramName: "pic_profile",
              dictInvalidFileType: "Type file ini tidak dizinkan",
              addRemoveLinks: false,
            });

            //Event ketika Memulai mengupload
            foto_upload.on("sending", function(a, b, c) {
              a.token = Math.random();
              c.append("token_foto", a.token); //Menmpersiapkan token untuk masing masing foto
            });

            //Event ketika Memulai mengupload
            foto_upload.on("success", function(file, response) {
              $("#img_container").hide().html(response).fadeIn("slow");
              this.removeFile(file);
            });


            //Event ketika foto dihapus
            // foto_upload.on("removedfile", function(a) {
            //   var token = a.token;
            //   $.ajax({
            //     type: "post",
            //     data: {
            //       token: token
            //     },
            //     url: "<?php echo base_url('index.php/upload/remove_foto') ?>",
            //     cache: false,
            //     dataType: 'json',
            //     success: function() {
            //       console.log("Foto terhapus");
            //     },
            //     error: function() {
            //       console.log("Error");

            //     }
            //   });
            // });
          </script>

        </div>

        <h3 class="profile-username text-center"><?php echo $this->session->userdata('full_name') ?></h3>
        <small class="form-text text-muted text-center">Sebagai</small>
        <p class="text-muted text-center"><?php echo $this->session->userdata('roles_name') ?></p>

        <ul class="list-group list-group-unbordered mb-3">
          <!-- <li class="list-group-item">
            <b>NIP</b> <a class="float-right"><?php //echo $dtl_profile->nip ?></a>

          </li>
          <li class="list-group-item">
            <b>Cakupan Area</b> <a class="float-right"><?php //echo $dtl_profile->nama_area ?></a>
          </li> -->
          <!-- <li class="list-group-item">
            <b>Email</b> <a class="float-right"><?php //echo $dtl_profile->email ?></a>
          </li> -->
        </ul>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- About Me Box -->
    <!-- <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">List Customer</h3>
      </div>
      <div class="card-body">

        <?php
        // if (is_array($list_customer)) {
        //   foreach ($list_customer as $dt) {
            
        //     echo '<div>' . $dt['nama_perusahaan'] . '</div>';
        //     if ($dt['alamat_perusahaan'] != '') {
        //       echo '<small class="text-muted font-italic">' . $dt['alamat_perusahaan'] . '</small>';
        //     }
        //     echo '<div class="border-bottom"></div>';
        //   }
        // }
       
        ?>
      </div>
    </div> -->
    <!-- /.card -->
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="card">
      <div class="card-header p-2">
        <ul class="nav nav-pills">
          <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
        </ul>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="tab-content">

          <div class="tab-pane active" id="settings">
            <form class="form-horizontal" id="frm_add">
              <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label">Full Name</label>
                <div class="col-sm-10">
                  <input type="text" name="full_name" value="<?php echo $dtl_profile->full_name ?>" class="form-control" id="inputName" placeholder="Name">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" name="email" value="<?php echo $dtl_profile->email ?>" class="form-control" id="inputEmail" placeholder="Email">
                </div>
              </div>
              <div class="form-group row">
                <label for="Password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" name="pwd" value="<?php echo $decrypt_pwd ?>" class="form-control" id="inputName2" placeholder="Name">
                </div>
              </div>
              <!-- <div class="form-group row">
                <label for="inputExperience" class="col-sm-2 col-form-label">Tanda Tangan</label>
                <div class="col-sm-6">
                  <table>
                    <tr>
                      <td>
                        <div id="signArea">
                          <div class="sig sigWrapper" style="height:auto; width:360px">

                            <canvas class="sign-pad" id="sign-pad" width="350px" height="100"></canvas>
                          </div>
                          <button type="button" class="btn btn-success btn-xs hapus">Clear</button>
                        </div>
                      </td>
                      <td><div id="saved_sign"> <?php //echo $sign_pic ?></div> </td>
                    </tr>
                  </table>
                  <script>
                    $(document).ready(function(e) {
                      $('#signArea').signaturePad({
                        drawOnly: true,
                        drawBezierCurves: true,
                        lineTop: 90,
                        clear: '.hapus',
                        lineWidth: 0,
                        bgColour: 'transparent',
                        required: false
                      });
                    });
                  </script>
                </div>
              </div> -->

              <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                <span style="display: none" id="img_mdl_loader"><img src="<?php echo base_url() . 'assets/images/89.gif'; ?>" /><small> Processing...</small> </span>
                  <button type="submit" class="btn btn-info btn-sm">Update Profile</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div><!-- /.card-body -->
    </div>
    <!-- /.nav-tabs-custom -->
  </div>
  <!-- /.col -->
</div>

<script>
  // var img_data;

  $("#frm_add").submit(function(e) {
    var form = $(this);
    var formdata = false;
    if (window.FormData) {
      formdata = new FormData(form[0]);
    }
    toastr.options = {
      timeOut: 3000,
      positionClass: "toast-bottom-right"
    };
    $("#img_mdl_loader").show();
    // html2canvas([document.getElementById('sign-pad')], {
    //   onrendered: function(canvas) {
    //     var canvas_img_data = canvas.toDataURL('image/png');
    //     var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
        //ajax call to save image inside folder
        $.ajax({
          url: '<?php echo base_url() . $controller ?>/update_user_info',
          data: {
            // img_data: img_data,
            user_info: form.serialize()
          },
          type: 'post',
          dataType: 'json',
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(xhr.responseText);
            console.log(thrownError);
          },
          success: function(response) {
            console.log(response);
            if (response['error'] == 'true') {
              toastr.options = {
                timeOut: 3000,
                positionClass: "toast-top-center"
              };
              toastr["error"](response['msg']);
              $("#img_mdl_loader").hide();
              return false;
            } else {
              // $("#saved_sign").hide().html(response['saved_pic']).fadeIn("slow");
              toastr['info'](response['msg']);
              $("#img_mdl_loader").hide();
            }
          }
        });
    //   }
    // });
    e.preventDefault();
  });
</script>