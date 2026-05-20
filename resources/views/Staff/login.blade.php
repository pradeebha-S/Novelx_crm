<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light"

    data-assets-path="{{asset('/assets')}}/" data-template="vertical-menu-template">

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

    <link rel="stylesheet" href="{{asset('/assets')}}/vendor/fonts/iconify-icons.css">

    <script src="{{asset('/assets')}}/vendor/libs/@algolia/autocomplete-js.js"></script>

    <!-- Core CSS -->

    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{asset('/assets')}}/vendor/libs/node-waves/node-waves.css">

    <link rel="stylesheet" href="{{asset('/assets')}}/vendor/libs/pickr/pickr-themes.css">

    <link rel="stylesheet" href="{{asset('/assets')}}/vendor/css/core.css">

    <link rel="stylesheet" href="{{asset('/assets')}}/css/demo.css">

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{asset('/assets')}}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">

    <!-- endbuild -->

    <!-- Vendor -->

    <link rel="stylesheet" href="{{asset('/assets')}}/vendor/libs/@form-validation/form-validation.css">

    <!-- Page CSS -->

    <!-- Page -->

    <link rel="stylesheet" href="{{asset('/assets')}}/vendor/css/pages/page-auth.css">

    <!-- Helpers -->

    <script src="{{asset('/assets')}}/vendor/js/helpers.js"></script>

    <style type="text/css">

        .authentication-wrapper .authentication-bg {

            background-color: #FEFBFB;

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

        input#otp {

            padding-left: 120px !important;

        }

        .authentication-wrapper .authentication-inner .auth-cover-bg {

            background: url('{{ asset(path: '/assets') }}/img/login1.png');

            background-size: cover;

        }

        .form-label {

            font-size: 18px;

        }

        .input-group {

            position: relative;

            width: 100%;

        }

        .input-group input {

            width: 100%;

            /* border-radius: 10px ; */

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

    <script src="{{asset('/assets')}}/js/config.js"></script>

</head>

<body style="--bs-scrollbar-width: 0px;">

    <!-- Content -->

    @if (session('success') || session('error') || $errors->any())

        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; max-width: 100%;">

            <div id="liveToast"

                class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0 show"

     role="alert"

     aria-live="assertive"

     aria-atomic="true"

     data-bs-delay="3000"

     data-bs-autohide="true"

     style="min-width: 250px; max-width: 90vw;">

                <!-- max-width prevents overflow on small screens -->

                <div class="d-flex">

                    <div class="toast-body">

                        @if (session('success'))

                            {{ session('success') }}

                        @elseif (session('error'))

                            {{ session('error') }}

                        @elseif ($errors->any())

                            {{ $errors->first() }}

                        @endif

                    </div>

                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"

                        aria-label="Close"></button>

                </div>

            </div>

        </div>

    @endif

    <div class="authentication-wrapper authentication-cover">

        <div class="authentication-inner row m-0">

            <!-- /Left Text -->

            <div class="d-flex col-12 col-xl-7 align-items-center authentication-bg p-sm-12 p-6">

                <div class="w-px-400 mx-auto mt-12 pt-5">

                    <h4 class="mb-1 text-center"><b>Welcome Back!</b></h4>

                    <p id="liveTime" class="fw-bold text-info text-center"></p>

                    <div id="display_error" class="mt-2"></div>

                    <form method="POST" action="{{ route('staff_login_verifyotp') }}" id="login_form">

                        @csrf

                        <!-- Email -->

                        <div class="mb-6 fv-plugins-icon-container">

                            <label for="email" class="form-label">Email ID</label>

                            <input type="email" class="form-control p-3 bg-white" value="{{ old('email') }}" id="email"

                                name="email" placeholder="Enter your email id" autofocus required>

                            @error('email')

                                <div class="invalid-feedback d-block">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>

                        <div class="mb-1 form-password-toggle form-control-validation fv-plugins-icon-container">

                            <label class="form-label" for="password">Password</label>

                            <div class="input-group input-group-merge has-validation">

                                <input type="password" id="password" class="form-control p-3 bg-white" name="password"

                                    placeholder="Enter your password" aria-describedby="password" required>

                                <span class="input-group-text cursor-pointer bg-white">

                                    <i class="icon-base ti tabler-eye-off"></i>

                                </span>

                                @error('password')

                                    <div class="invalid-feedback d-block">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>

                            <div

                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">

                            </div>

                            <label class="form-label mt-3" for="otp" style="color: black;">Verify OTP</label>

                            <div class="input-group mb-9">

                                <button type="button" class="send-otp btn btn-primary btn-sm" id="sendOtpBtn">

                                    Send OTP

                                </button>

                                <input type="text" id="otp" class="form-control p-3" name="otp" placeholder="Enter OTP"

                                    style="color: #000;">

                            </div>

                        </div>

                        <div id="otp-timer" class="mt-2 text-center text-danger" style="display:none;">

                            Resend OTP in <span id="timer-count">60</span> seconds

                        </div>

                        <div class="my-8">

                            <div class="d-flex justify-content-between">

                                <div class="form-check mb-0 ms-2">

                                    <input class="form-check-input" type="checkbox" id="remember-me">

                                    <label class="form-check-label" for="remember-me"> Remember Me </label>

                                </div>

                                <a href="{{ route('forget_password') }}">

                                    <p class="mb-0 text-decoration-underline text-danger">Forgot Password?</p>

                                </a>

                            </div>

                        </div>

                        <!-- Submit -->

                        <button type="submit" id="finalSubmit"

                            class="btn btn-primary d-grid w-100 waves-effect waves-light">

                            Login

                        </button>

                    </form>

                </div>

            </div>

            <div class="d-none d-xl-flex col-xl-5 p-0">

                <div class="auth-cover-bg d-flex justify-content-center align-items-center">

                    <!-- <img src="{{ asset('assets/img') }}/logo.png" alt="auth-login-cover" class="my-5 auth-illustration"

                        style="visibility: visible;"> -->

                </div>

            </div>

        </div>

    </div>

    <script src="{{asset('/assets')}}/vendor/libs/jquery/jquery.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/popper/popper.js"></script>

    <script src="{{asset('/assets')}}/vendor/js/bootstrap.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/pickr/pickr.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/hammer/hammer.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/i18n/i18n.js"></script>

    <script src="{{asset('/assets')}}/vendor/js/menu.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/@form-validation/popular.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/@form-validation/bootstrap5.js"></script>

    <script src="{{asset('/assets')}}/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->

    <script src="{{asset('/assets')}}/js/main.js"></script>

    <!-- Page JS -->

    <script src="{{asset('/assets')}}/js/pages-auth.js"></script>

    <script>

        $(document).ready(function () {

            // Ensure csrf token header for all AJAX requests (optional but useful)

            $.ajaxSetup({

                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }

            });

            $("#sendOtpBtn").on('click', function (e) {

                e.preventDefault();                // important

                const $btn = $(this);

                let email = $("#email").val().trim();

                let password = $("#password").val().trim();

                if (email === "") {

                    showMessage("Please enter email first", "error");

                    return;

                }

                if (password === "") {

                    showMessage("Please enter password", "error");

                    return;

                }

                // disable to prevent multiple clicks

                $btn.prop("disabled", true).text("Sending...");

                $.ajax({

                    url: "{{ route('staff_login_sendotp') }}",

                    type: "POST",

                    dataType: "json",

                    data: {

                        email: email,

                        password: password

                    },

                    success: function (response) {

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

                    error: function (xhr, status, error) {

                        console.error('OTP send error', xhr, status, error);

                        let msg = "Error sending OTP";

                        // try to show server message if available

                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {

                            msg = xhr.responseJSON.message;

                        } else if (xhr && xhr.responseText) {

                            // sometimes Laravel returns HTML error page; show short version

                            msg = xhr.responseText.substring(0, 200);

                        }

                        showMessage(msg, "error");

                        $btn.prop("disabled", false).text("Send OTP");

                    },

                    complete: function () {

                        // optionally re-enable after a timeout if you want

                        // setTimeout(() => $btn.prop("disabled", false).text("Send OTP"), 60000);

                    }

                });

            });

            // Function to display message in div

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

        function updateDateTime() {

            const now = new Date();

            const day = String(now.getDate()).padStart(2, '0');

            const month = String(now.getMonth() + 1).padStart(2, '0');

            const year = now.getFullYear();

            let hours = now.getHours();

            const minutes = String(now.getMinutes()).padStart(2, '0');

            const seconds = String(now.getSeconds()).padStart(2, '0');

            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12 || 12;

            document.getElementById('liveTime').innerText =

                `${day}-${month}-${year} | ${hours}:${minutes}:${seconds} ${ampm}`;

        }

        updateDateTime();

        setInterval(updateDateTime, 1000);

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

<script>

    document.getElementById('finalSubmit').addEventListener('click', function (e) {

            e.preventDefault();

            let btn = this;

            btn.disabled = true;

            btn.innerText = 'Processing...';

            document.getElementById('login_form').submit();

        });

</script>

</body>

</html>