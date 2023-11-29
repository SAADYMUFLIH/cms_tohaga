<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Language" content="en">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel='shortcut icon' type='image/x-icon' href='assets/images/favicon.png' />
  <title>UMKM Tohaga | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

  <!-- Disable tap highlight on IE -->
  <meta name="msapplication-tap-highlight" content="no">

  <link href="assets/css/main.css?v=2" rel="stylesheet">
</head>

<body>
  <div class="app-container app-theme-white body-tabs-shadow">
    <div class="app-container">
      <div class="h-100">
        <div class="h-100 no-gutters row">
          <div class="d-none d-lg-block col-lg-4">
            <div class="slider-light">
              <div class="slick-slider">
                <div>
                  <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-gradient-danger " tabindex="-1">
                    <div style="background-image: url('assets/images/backFront.png');"></div>
                    <div class="ml-2 ">
                    <img style="width:70%;display: block;margin: 0 auto" src="assets/images/backFront.png" />
                      <h3>Tohaga System</h3>
                      <p>Jika sudah memiliki user name dan password, silahkan untuk login menggunakan user name dan password tersebut.
                        Jika belum memiliki user name dan password silahkan menghubungi Administrator.
                      </p>
                    </div>
                  </div>
                </div>


              </div>
            </div>
          </div>
          <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
              <div class="app-logo"></div>
              <h4 class="mb-0">
                <span class="d-block">Welcome back,</span>
                <span>Please sign in to your account.</span>
              </h4>
              <!-- <h6 class="mt-3">No account? <a href="javascript:void(0);" class="text-primary">Sign up now</a></h6> -->
              <div class="divider row"></div>
              <div>
                <form class="form-signin" action="<?php echo base_url(); ?>auth/login" method="post">
                  <div class="form-row">
                    <div class="col-md-6">
                      <div class="position-relative form-group">
                        <label for="exampleEmail" class="">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Email here...">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="position-relative form-group">
                        <label for="examplePassword" class="">Password</label>
                        <div class="input-group mb-3" id="show_hide_password">
                          <input type="password" class="form-control" name="password" id="password" placeholder="Password here..." class="form-control">
                          <div class="input-group-append">
                            <div class="input-group-text">
                              <a href="" class="pointer"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="position-relative form-check">
                    <!-- <input name="check" id="exampleCheck" type="checkbox" class="form-check-input"> -->
                    <!-- <label for="exampleCheck" class="form-check-label">Keep me logged in</label> -->
                    <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if ($error) {
                    ?>
                      <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $error; ?>
                      </div>
                    <?php }
                    $success = $this->session->flashdata('success');
                    if ($success) {
                    ?>
                      <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $success; ?>
                      </div>
                    <?php } ?>
                  </div>
                  <div class="divider row"></div>
                  <div class="d-flex align-items-center">
                    <div class="ml-auto">
                      <a href="forgotpassword" class="btn-lg btn btn-link">Recover Password</a>
                      <button class="btn btn-danger btn-lg">Login to System</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="./assets/js/main.js"></script>
  <script type="text/javascript" src="./assets/js/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("fa-eye-slash");
          $('#show_hide_password i').removeClass("fa-eye");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("fa-eye-slash");
          $('#show_hide_password i').addClass("fa-eye");
        }
      });
    });
  </script>
</body>

</html>