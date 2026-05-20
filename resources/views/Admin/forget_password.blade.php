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
                    <h4 class="mb-1 text-center"><b>Forget Password</b></h4>
                    <!-- Add this under the OTP input -->
                    <div id="display_error" class="mt-2"></div>


                    <form id="formAuthentication" class="mb-6 mt-3 fv-plugins-bootstrap5 fv-plugins-framework"
                        action="{{ route('forget_verifyotp') }}" method="POST" novalidate="novalidate">
                        @csrf

                        <!-- Email -->
                        <div class="mb-6 form-control-validation fv-plugins-icon-container">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control p-3" id="userid_email" name="email"
                                placeholder="Enter Email" required>
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-6 form-control-validation fv-plugins-icon-container">
                            <label for="mobile" class="form-label">Phone</label>
                            <input type="tel" class="form-control p-3" id="userid_mobile" name="mobile"
                                placeholder="Enter Phone Number" minlength="10" maxlength="10" required>
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>

                        <label class="form-label mt-3" for="otp">Verify OTP</label>

                        <div class="input-group mb-9">
                            <button type="button" class="send-otp btn btn-primary btn-sm" id="sendOtpBtn">Send
                                OTP</button>
                            <input type="text" id="otp_input" class="form-control p-3" name="otp"
                                placeholder="Enter OTP" required>
                        </div>
<div id="otp-timer" class="mt-2 text-center text-danger" style="display:none;">
                            Resend OTP in <span id="timer-count">60</span> seconds
                        </div>
                        <!-- Message placeholder -->
                        <div id="display_error" class="mt-2"></div>

                        <button class="btn btn-primary d-grid w-100 waves-effect waves-light">Submit</button>
                        <input type="hidden">
                    </form>
                    <p class="text-center">Can't Change Password? <a href="{{ route('admin.login') }}"><b>Login</b></a></p>

                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->

            <div class="d-none d-xl-flex col-xl-5 p-0">
                <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                    <!-- <img src="{{ asset('assets/img') }}/login.png" alt="auth-login-cover" class="my-5 auth-illustration" style="visibility: visible;"> -->
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

    <!-- <script src="{{ asset('/assets') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/assets') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{ asset('/assets') }}/vendor/libs/pickr/pickr.js"></script>

    <script src="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ asset('/assets') }}/vendor/libs/hammer/hammer.js"></script>

    <script src="{{ asset('/assets') }}/vendor/libs/i18n/i18n.js"></script>

    <script src="{{ asset('/assets') }}/vendor/js/menu.js"></script> -->

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('/assets') }}/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->

    <script src="{{ asset('/assets') }}/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('/assets') }}/js/pages-auth.js"></script>


    <script>
        $(document).ready(function() {

            // Ensure csrf token header for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $("#sendOtpBtn").on('click', function(e) {
                e.preventDefault();
                const $btn = $(this);

                // read values from the correct inputs
                let email = $("#userid_email").val().trim();
                let mobile = $("#userid_mobile").val().trim();

                // basic client-side validation
                if (email === "") {
                    showMessage("Please enter email first", "error");
                    return;
                }
                if (mobile === "" || mobile.length < 10) {
                    showMessage("Please enter a valid 10-digit mobile number", "error");
                    return;
                }

                // disable to prevent multiple clicks
                $btn.prop("disabled", true).text("Sending...");

                $.ajax({
                    url: "{{ route('forget_sendotp') }}", // correct route for this page
                    type: "POST",
                    dataType: "json",
                    data: {
                        email: email,
                        mobile: mobile
                    },
                    success: function(response) {
                        console.log('OTP send success', response);
                       if (response.status === "success") {
                            showMessage(response.message || "OTP sent", "success");
                            $btn.prop("disabled", true).text("OTP Sent");
                            startOtpTimer(); // 🔥 START TIMER HERE
                        }
                        else {
                            showMessage(response.message || "Error", "error");
                            $btn.prop("disabled", false).text("Send OTP");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('OTP send error', xhr, status, error);
                        let msg = "Error sending OTP";

                        // show JSON error message if present
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr && xhr.responseText) {
                            // sometimes Laravel returns HTML error page; shorten it
                            msg = xhr.responseText.substring(0, 300);
                        }

                        showMessage(msg, "error");
                        $btn.prop("disabled", false).text("Send OTP");
                    }
                });
            });

            // Display function
            function showMessage(message, type) {
                let displayDiv = $("#display_error");
                displayDiv.text(message);
                displayDiv.removeClass("text-success text-danger");
                if (type === "success") {
                    displayDiv.addClass("text-success");
                } else {
                    displayDiv.addClass("text-danger");
                }
            }

        });
    </script>


    <script>
        let otpTimerInterval;
        let otpTimeLeft = 60;

        function startOtpTimer() {
            otpTimeLeft = 60;
            $("#timer-count").text(otpTimeLeft);
            $("#otp-timer").show();

            otpTimerInterval = setInterval(function () {
                otpTimeLeft--;

                $("#timer-count").text(otpTimeLeft);

                if (otpTimeLeft <= 0) {
                    clearInterval(otpTimerInterval);
                    $("#otp-timer").hide();
                    $("#sendOtpBtn").prop("disabled", false).text("Resend OTP");
                }
            }, 1000);
        }

    </script>
</body>

</html>