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

        input#otp_input {
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
            font-weight: 400;
            padding: 16px 120px 16px 14px;
        }

        .form-control[type="text"] {
            border-radius: 10px !important;


        }

        button#sendOtpBtn {
            z-index: 10;
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
            font-size: 16px;
            font-family: DM Sans;
            font-weight: 400;
            cursor: pointer;
            transition: background .2s ease;
        }
    </style>

    <script src="{{ asset('/assets') }}/js/config.js"></script>
</head>

<body style="--bs-scrollbar-width: 0px;">

    <div class="authentication-wrapper authentication-cover">

        <div class="authentication-inner row m-0">
            <div class="d-flex col-12 col-xl-7 align-items-center p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-5 pt-5">
                    <h4 class="mb-1 text-center"><b>Forget Password</b></h4>
                    <div id="display_error" class="mt-2"></div>


                    <form action="{{ route('hr.create_password') }}" id="login_form">

                        <div class="mb-6 form-control-validation fv-plugins-icon-container">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control p-3" id="userid_email" name="email"
                                placeholder="Enter Email" required>

                        </div>



                        <label class="form-label mt-3">Verify OTP</label>

                        <div class="input-group mb-9">
                            <button type="button" class="send-otp btn btn-primary btn-sm" id="sendOtpBtn">Send
                                OTP</button>
                            <input type="text" id="otp_input" class="form-control p-3" name="otp"
                                placeholder="Enter OTP" required>
                        </div>
                        <div id="otp-timer" class="mt-2 text-center text-danger" style="display:none;">
                            Resend OTP in <span id="timer-count">60</span> seconds
                        </div>
                        <div id="display_error" class="mt-2"></div>

                        <button class="btn btn-primary d-grid w-100" type="submit" id="submit_btn">Submit</button>
                        <input type="hidden">
                    </form>
                    <p class="text-center">Can't Change Password? <a href="{{ route('hr_login') }}"><b>Login</b></a></p>

                </div>
            </div>


            <div class="d-none d-xl-flex col-xl-5 p-0">
                <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                </div>
            </div>
        </div>
    </div>



    <script src="{{ asset('/assets') }}/vendor/libs/jquery/jquery.js"></script>

    <script src="{{ asset('/assets') }}/vendor/libs/popper/popper.js"></script>


    <script src="{{ asset('/assets') }}/js/main.js"></script>

    <script src="{{ asset('/assets') }}/js/pages-auth.js"></script>




    <script>
        document.getElementById('submit_btn').addEventListener('click', function (e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('login_form').submit();
        });
    </script>
</body>

</html>