<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light"
    data-assets-path="{{ asset('/assets') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>NovelX</title>

    <meta name="description" content="">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img') }}/n.png">



    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/fonts/iconify-icons.css">

    <script src="{{ asset('/assets') }}/vendor/libs/@algolia/autocomplete-js.js"></script>

    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.css">

    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/pickr/pickr-themes.css">

    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/core.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/demo.css">


    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">


    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/pages/page-auth.css">

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
            font-size: 16px;
            padding: 16px 120px 16px 14px;
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

    <script src="{{ asset('/assets') }}/js/config.js"></script>
</head>

<body style="--bs-scrollbar-width: 0px;">

    <div class="authentication-wrapper authentication-cover">

        <div class="authentication-inner row m-0">
            <div class="d-flex col-12 col-xl-6 align-items-center p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-5 pt-5">
                    <h4 class="mb-4 text-center"><b>Create New Password</b></h4>
                    <div id="display_error" class="mt-2"></div>


                    <form action="{{ route('hr_login') }}" id="login_form">

                        <div class="mb-6 form-password-toggle form-control-validation fv-plugins-icon-container">
                            <label class="form-label" for="password" style="color: black;">Enter New Password</label>
                            <div class="input-group input-group-merge has-validation">
                                <input type="password" id="password" class="form-control p-3" name="password"
                                    placeholder="Enter your password" aria-describedby="password">
                                <span class="input-group-text cursor-pointer"><i
                                        class="icon-base ti tabler-eye-off"></i></span>
                            </div>

                        </div>
                        <div class="mb-6 form-password-toggle form-control-validation fv-plugins-icon-container">
                            <label class="form-label" for="password" style="color: black;">Confirm New Password</label>
                            <div class="input-group input-group-merge has-validation">
                                <input type="password" id="password_confirmation" class="form-control p-3"
                                    name="password_confirmation" placeholder="Confirm your password">

                                <span class="input-group-text cursor-pointer"><i
                                        class="icon-base ti tabler-eye-off"></i></span>
                            </div>
                        </div>

                        <button class="btn btn-primary d-grid w-100 waves-effect waves-light"
                            id="submit_btn">Submit</button>
                    </form>


                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->

            <div class="d-none d-xl-flex col-xl-6 p-0">
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


    <script src="{{ asset('/assets') }}/js/main.js"></script>

    <script src="{{ asset('/assets') }}/js/pages-auth.js"></script>



</body>

</html>