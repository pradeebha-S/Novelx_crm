<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light"
  data-assets-path="{{ asset('/assets') }}/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>NovelX</title>

  <meta name="description" content="">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img') }}/n.png">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/fonts/iconify-icons.css">

  <script src="{{ asset('/assets') }}/vendor/libs/@algolia/autocomplete-js.js"></script>

  <!-- Core CSS -->
  <!-- build:css assets/vendor/css/theme.css  -->

  <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.css">

  <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/pickr/pickr-themes.css">

  <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/core.css">
  <link rel="stylesheet" href="{{ asset('/assets') }}/css/demo.css">

  <!-- Vendors CSS -->

  <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">

  <!-- endbuild -->

  <!-- Vendor -->
  <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/@form-validation/form-validation.css">

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/pages/page-auth.css">

  <!-- Helpers -->
  <script src="{{ asset('/assets') }}/vendor/js/helpers.js"></script>
  <style type="text/css">
    body {
      background-color: #F6FBFB !important;
    }

    .layout-menu-fixed .layout-navbar-full .layout-menu,
    .layout-menu-fixed-offcanvas .layout-navbar-full .layout-menu {
      top: 0px !important;
    }

    .layout-page {
      padding-top: 0px !important;
    }

    .content-wrapper {
      padding-bottom: 0px !important;
    }

    input#login_email {
      padding-left: 120px !important;
    }


  .authentication-wrapper .authentication-inner .auth-cover-bg {
            background: url('{{ asset(path: '/assets') }}/img/login1.png');
            background-size: cover;

        }

    .form-label {
      font-size: 16px;
      font-weight: 400;
    }

    .input-group {
      position: relative;
      width: 100%;
    }

    .input-group input {
      width: 100%;
      /* border-radius: 10px ; */
        font-size: 16px;
      padding: 16px 120px 16px 14px;
    }

    .form-control[type="text"] {
      border-radius: 10px !important;


    }

    .input-group .send-otp {
      position: absolute;
      left: 6px;
      top: 6px;
      bottom: 6px;
      padding: 0 16px;

      color: #fff;
      border: none;
      border-radius: 8px !important;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s ease;
    }
  </style>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

  <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
  <script src="{{ asset('/assets') }}/vendor/js/template-customizer.js"></script>

  <script src="{{ asset('/assets') }}/js/config.js"></script>
</head>

<body style="--bs-scrollbar-width: 0px;">
  <!-- Content -->

  <div class="authentication-wrapper authentication-cover">

    <div class="authentication-inner row m-0">
      <!-- /Left Text -->
      <div class="d-flex col-12 col-xl-7 align-items-center p-sm-12 p-6">
        <div class="w-px-400 mx-auto mt-5 pt-5">
          <h4 class="mb-4 text-center"><b>Create New Password</b></h4>


          <form id="formAuthentication" class="mb-6 mt-3 fv-plugins-bootstrap5 fv-plugins-framework" action="login"
            method="GET" novalidate="novalidate">
             <div class="mb-6 form-password-toggle form-control-validation fv-plugins-icon-container">
              <label class="form-label" for="password">Enter New Password</label>
              <div class="input-group input-group-merge has-validation">
                <input type="password" id="password" class="form-control p-3" name="password"
                  placeholder="Enter your password" aria-describedby="password">
                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
              </div>
              </div>
            <div class="mb-6 form-password-toggle form-control-validation fv-plugins-icon-container">
              <label class="form-label" for="password">Confirm New Password</label>
              <div class="input-group input-group-merge has-validation">
                <input type="password" id="password" class="form-control p-3" name="password"
                  placeholder="Confirm your password" aria-describedby="password">
                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
              </div>
              </div>

            <button class="btn btn-primary d-grid w-100 waves-effect waves-light">Submit</button>
            <input type="hidden">
          </form>

        </div>
      </div>
      <!-- /Left Text -->

      <!-- Login -->

      <div class="d-none d-xl-flex col-xl-5 p-0">
        <div class="auth-cover-bg d-flex justify-content-center align-items-center">
          <!-- <img src="{{ asset('assets') }}/Login.jpg" alt="auth-login-cover" class="my-5 auth-illustration" style="visibility: visible;"> -->
          <!-- <img src="file:///C:/Users/HAPPY/Downloads/full-version/assets/img/illustrations/bg-shape-image-light.png" alt="auth-login-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png" style="visibility: visible;"> -->
        </div>
      </div>
      <!-- /Login -->
    </div>
  </div>

  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/theme.js  -->

  <script src="{{ asset('/assets') }}/vendor/libs/jquery/jquery.js"></script>

  <script src="{{ asset('/assets') }}/vendor/libs/popper/popper.js"></script>
  <script src="{{ asset('/assets') }}/vendor/js/bootstrap.js"></script>
  <script src="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.js"></script>

  <script src="{{ asset('/assets') }}/vendor/libs/pickr/pickr.js"></script>

  <script src="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="{{ asset('/assets') }}/vendor/libs/hammer/hammer.js"></script>

  <script src="{{ asset('/assets') }}/vendor/libs/i18n/i18n.js"></script>

  <script src="{{ asset('/assets') }}/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="{{ asset('/assets') }}/vendor/libs/@form-validation/popular.js"></script>
  <script src="{{ asset('/assets') }}/vendor/libs/@form-validation/bootstrap5.js"></script>
  <script src="{{ asset('/assets') }}/vendor/libs/@form-validation/auto-focus.js"></script>

  <!-- Main JS -->

  <script src="{{ asset('/assets') }}/js/main.js"></script>

  <!-- Page JS -->
  <script src="{{ asset('/assets') }}/js/pages-auth.js"></script>



</body>

</html>