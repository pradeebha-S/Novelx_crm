<!doctype html>
<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default"
    data-bs-theme="light" data-assets-path="{{ asset('/assets') }}/" data-template="vertical-menu-template">
<script>
(function() {
    const themeKey = 'templateCustomizer-vertical-menu-template--Theme';
    const savedTheme = localStorage.getItem(themeKey);
    if (savedTheme) {
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    }
})();
</script>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>NovelX</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img') }}/n.png" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/fonts/iconify-icons.css" />
    <script src="{{ asset('/assets') }}/vendor/libs/@algolia/autocomplete-js.js"></script>
    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/pickr/pickr-themes.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/core.css" />
    <link rel="stylesheet" href="{{asset('/assets/css/demo.css')}}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- endbuild -->
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('/assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/fonts/flag-icons.css" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/pages/cards-advance.css" />
    <!-- Helpers -->
    <script src="{{ asset('/assets') }}/vendor/js/helpers.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/assets') }}/js/config.js"></script>
    <style>
    .red {
        color: red;
    }

    .out {
        font-weight: 370;
        font-size: 15px;
        color: red;
    }
    </style>
</head>

<body>
    @if (session('success') || session('error') || $errors->any())
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; max-width: 100%;">
        <div id="liveToast"
            class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0 show"
            role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000" data-bs-autohide="true"
            style="min-width: 250px; max-width: 90vw;">
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
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo ">
                    <a href="https://novelx.in/" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <img src="{{ asset('/assets/img') }}/logo_sidebar.png" alt="Logo">
                            </span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
                        <i class="icon-base ti tabler-x d-block d-xl-none"></i>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                @php
                $wfhCount = \App\Models\Wfh::where('is_replied','0')->count();
                $leaveCount = \App\Models\Leave::where('is_replied','0')->count();
                $permissionCount = \App\Models\Permission::where('is_replied','0')->count();
                $totalRequest = $wfhCount + $leaveCount + $permissionCount;
                @endphp
                <ul class="menu-inner py-1">
                    <li class="menu-item  @if(Route::is('admin.dashboard')) active @endif">
                        <a href="{{ route('admin.dashboard') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-smart-home"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item @if(Route::is('project_table')) active @endif">
                        <a href="{{ route('project_table') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-report"></i>
                            <div data-i18n="Project">Project</div>
                        </a>
                    </li>
                    <li class="menu-item @if(Route::is('staff_table')) active @endif">
                        <a href="{{ route('staff_table') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-users"></i>
                            <div data-i18n="Staff Management">Staff Management</div>
                        </a>
                    </li>
                    <!-- <li class="menu-item @if(Route::is('staff_report')) active @endif">
                        <a href="{{ route('staff_report') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-file-analytics"></i>
                            <div data-i18n="Staff Report">Staff Report</div>
                        </a>
                    </li> -->
                    <!-- <li class="menu-item @if(Route::is('monthly_report')) active @endif">
                        <a href="{{ route('monthly_report') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-calendar-stats"></i>
                            <div data-i18n="Monthly Report">Monthly Report</div>
                        </a>
                    </li> -->
                    <!-- <li class="menu-item @if(Route::is('attendance_history')) active @endif">
                        <a href="{{ route('attendance_history') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-calendar-check"></i>
                            <div data-i18n="Staff Attendance">Staff Attendance</div>
                        </a>
                    </li> -->
                    <li class="menu-item @if(Route::is('attendance_history')) active @endif">
                        <a href="{{ route('attendance_history') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-calendar-check"></i>
                            <div data-i18n="Staff Attendance">Staff Attendance</div>
                            @if($totalRequest > 0)
                            <span class="badge bg-danger ms-auto">{{ $totalRequest }}</span>
                            @endif
                        </a>
                    </li>
                    <!-- <li class="menu-item @if(Route::is('transaction_details')) active @endif">
                        <a href="{{ route('transaction_details') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-percentage-25"></i>
                            <div data-i18n="PPS">PPS</div>
                        </a>
                    </li>
                     <li class="menu-item @if(Route::is('bill_form')||Route::is('bill_table')) active @endif">
                        <a href="{{ route('bill_table') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-report"></i>
                            <div data-i18n="Project Invoice">Project Invoice</div>
                        </a>
                    </li> -->
                    <li class="menu-item @if(Route::is('add_bank')) active @endif">
                        <a href="{{ route('add_bank') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-building-bank"></i>
                            <div data-i18n="Add Bank">Add Bank</div>
                        </a>
                    </li>
                    <li class="menu-item @if(Route::is('admin.candidates')) active @endif">
                        <a href="{{ route('admin.candidates') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-users"></i>
                            <div data-i18n="Candidates">Candidates</div>
                        </a>
                    </li>
                    @php

                    $adminUnreadReplyCount = \App\Models\CommunicationReply::where('reply_from', 'staff')
                    ->where('is_read', '0')
                    ->count();

                    @endphp
                    <li
                        class="menu-item @if (Route::is('create_communication')||Route::is('mail_table')||Route::is('reply')||Route::is('view_mail')) active @endif">
                        <a href="{{ route('mail_table') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-mail"></i>
                            <div data-i18n="Mails">Mails</div>
                            @if($adminUnreadReplyCount > 0)
                            <div class="badge text-bg-danger ms-auto">
                                {{ $adminUnreadReplyCount }}
                            </div>
                            @endif
                            <!-- <div class="badge text-bg-danger ms-auto">5</div> -->
                        </a>
                    </li>
                    <li
                        class="menu-item @if (Route::is('popup_manager')||Route::is('popup_manager_form')) active @endif">
                        <a href="{{ route('popup_manager') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-mail"></i>
                            <div data-i18n="Popup Management">Popup Management</div>
                        </a>
                    </li>
                    <!-- <li class="menu-item @if(Route::is('staff_table')) active @endif">
                        <a href="{{ route('staff_table') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-user-plus"></i>
                            <div data-i18n="Staff Management">Staff Management</div>
                        </a>
                    </li> -->

                    <!-- <li
                        class="menu-item @if (Route::is('leave_request_table') || Route::is('wfh_table') || Route::is('attendance_history') || Route::is('permission_table')) active @endif">
                        <a href="{{ route('attendance_history') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-mailbox"></i>
                            <div data-i18n="Attendance">Attendance</div>
                        </a>
                    </li> -->
                    <!-- <li class="menu-item @if(Route::is('intern_table') || Route::is('intern_attendance')) open active @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon icon-base ti tabler-user-plus"></i>
                            <div data-i18n="Students">Students</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @if(Route::is('intern_table')) active @endif">
                                <a href="{{ route('intern_table') }}" class="menu-link">
                                    <div data-i18n="Student Management">Student Management</div>
                                </a>
                            </li>
                            <li class="menu-item @if(Route::is('intern_attendance')) active @endif">
                                <a href="{{ route('intern_attendance') }}" class="menu-link">
                                    <div data-i18n="Student Attendance">Student Attendance</div>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- <li
                        class="menu-item @if(Route::is('intern_table') || Route::is('intern_attendance') || Route::is('student_dashboard') || Route::is('student_tasks') || Route::is('course')) open active @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon icon-base ti tabler-user-plus"></i>
                            <div data-i18n="Students">Students</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @if(Route::is('student_dashboard')) active @endif">
                                <a href="{{ route('student_dashboard') }}" class="menu-link">
                                    <div data-i18n="Student Dashboard">Student Dashboard</div>
                                </a>
                            </li>
                            <li class="menu-item @if(Route::is('intern_table')) active @endif">
                                <a href="{{ route('intern_table') }}" class="menu-link">
                                    <div data-i18n="Student Management">Student Management</div>
                                </a>
                            </li>
                            <li class="menu-item @if(Route::is('course')) active @endif">
                                <a href="{{ route('course') }}" class="menu-link">
                                    <div data-i18n="Course">Course</div>
                                </a>
                            </li>
                            <li class="menu-item @if(Route::is('student_tasks')) active @endif">
                                <a href="{{ route('student_tasks') }}" class="menu-link">
                                    <div data-i18n="student Tasks">student Tasks</div>
                                </a>
                            </li>
                            <li class="menu-item @if(Route::is('intern_attendance')) active @endif">
                                <a href="{{ route('intern_attendance') }}" class="menu-link">
                                    <div data-i18n="Student Attendance">Student Attendance</div>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <li class="menu-item @if(Route::is('admin.reminder')) active @endif">
                        <a href="{{ route('admin.reminder') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-report"></i>
                            <div data-i18n="Reminders">Reminders</div>
                        </a>
                    </li>
                    <li class="menu-item @if(Route::is('admin.common_expenses')) active @endif">
                        <a href="{{ route('admin.common_expenses') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-report"></i>
                            <div data-i18n="Expenses">Expenses</div>
                        </a>
                    </li>
                    <li
                        class="menu-item @if(Route::is('common_request_table') || Route::is('personal_request_table')) open active @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon icon-base ti tabler-headset"></i>
                            <div data-i18n="Support">Support</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @if(Route::is('common_request_table')) active @endif">
                                <a href="{{ route('common_request_table') }}" class="menu-link">
                                    <div data-i18n="Common Support">Common Support</div>
                                </a>
                            </li>
                            <li class="menu-item @if(Route::is('personal_request_table')) active @endif">
                                <a href="{{ route('personal_request_table') }}" class="menu-link">
                                    <div data-i18n="Personal Request">Personal Request</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item @if(Route::is('feed_back')) active @endif">
                        <a href="{{ route('feed_back') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-star"></i>
                            <div data-i18n="Feedback">Feedback</div>
                        </a>
                    </li>
                    <li class="menu-item @if(Route::is('reset_password')) active @endif">
                        <a href="{{ route('reset_password') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-lock"></i>
                            <div data-i18n="Reset Password">Reset Password</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link">
                            <i class="menu-icon red icon-base ti tabler-logout"></i>
                            <div data-i18n="Logout" class="out" data-bs-toggle="modal" data-bs-target="#logout">Logout
                            </div>
                        </a>
                    </li>
                </ul>
            </aside>
            <div class="menu-mobile-toggler d-xl-none rounded-1">
                <a href="javascript:void(0);"
                    class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                    <i class="ti tabler-menu icon-base"></i>
                    <i class="ti tabler-chevron-right icon-base"></i>
                </a>
            </div>
            <!-- / Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base ti tabler-menu-2 icon-md"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-md-start">
                            <li class="nav-item me-2">
                                <a class="nav-link position-relative" href="{{route('wfh_table')}}">
                                    <i class="icon-base ti tabler-home icon-22px text-heading"></i>
                                    <span class="badge bg-danger badge-notifications">{{ $wfhCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item me-2">
                                <a class="nav-link position-relative" href="{{route('leave_request_table')}}">
                                    <i class="icon-base ti tabler-calendar-event icon-22px text-heading"></i>
                                    <span class="badge bg-warning badge-notifications">{{ $leaveCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item me-2">
                                <a class="nav-link position-relative" href="{{ route('permission_table') }}">
                                    <i class="icon-base ti tabler-clock icon-22px text-heading"></i>
                                    <span class="badge bg-info badge-notifications">{{ $permissionCount }}</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- Style Switcher -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                                    id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-sun icon-22px theme-icon-active text-heading"></i>
                                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center active"
                                            data-bs-theme-value="light" aria-pressed="false">
                                            <span><i class="icon-base ti tabler-sun icon-22px me-3"
                                                    data-icon="sun"></i>Light</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center"
                                            data-bs-theme-value="dark" aria-pressed="true">
                                            <span><i class="icon-base ti tabler-moon-stars icon-22px me-3"
                                                    data-icon="moon-stars"></i>Dark</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center"
                                            data-bs-theme-value="system" aria-pressed="false">
                                            <span><i class="icon-base ti tabler-device-desktop-analytics icon-22px me-3"
                                                    data-icon="device-desktop-analytics"></i>System</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        @php
                                        $admin = Auth::guard('admin')->user();
                                        @endphp
                                        <img src="{{ $admin && $admin->profile_image
    ? asset('storage/' . $admin->profile_image)
    : asset('assets/img/avatars/6.png') }}" class="rounded-circle" width="100%" height="100%">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item mt-0" href="{{ route('admin.profile') }}">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar avatar-online">
                                                        @php
                                                        $admin = Auth::guard('admin')->user();
                                                        @endphp
                                                        <img src="{{ $admin && $admin->profile_image
    ? asset('storage/' . $admin->profile_image)
    : asset('assets/img/avatars/6.png') }}" class="rounded-circle" width="100%" height="100%">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    @php
                                                    $admin = Auth::guard('admin')->user();
                                                    @endphp
                                                    <h6 class="mb-0">{{ $admin->name ?? 'Admin' }}</h6>
                                                    <small class="text-body-secondary">
                                                        {{ Auth::guard('admin')->user()->role ?? 'Admin' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                            <i class="icon-base ti tabler-user me-3 icon-md"></i><span
                                                class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="icon-base ti tabler-settings me-3 icon-md"></i><span
                                                class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logout">
                                            <i class="icon-base ti tabler-logout me-3 icon-md text-danger"></i><span
                                                class="align-middle text-danger">Logout</span>
                                        </a>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body">
                                    &#169;
                                    <script>
                                    document.write(new Date().getFullYear());
                                    </script>
                                    , Technology Partner <a href="https://novelx.in/" target="_blank"
                                        class="footer-link text-danger"><b>&nbsp;NovelX</b></a>&nbsp;Team
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- logout -->
    <div class="modal fade" id="logout" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 text-center p-4 py-5">
                <h5 class="fw-bold mb-2">Are you sure!!</h5>
                <p class="text-muted">Are you confirm to logout?</p>
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <form action="{{ route('logout') }}" method="GET" id="logoutForm">
                        @csrf
                        <button type="button" class="btn btn-outline-primary p-3 fw-semibold me-3"
                            data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger p-3 fw-semibold" id="logout_btn">
                            Yes, Sure
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->
    <!-- <script src="{{ asset('/assets') }}/vendor/libs/jquery/jquery.js"></script> -->
    <script src="{{ asset('/assets') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/assets') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/pickr/pickr.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('/assets') }}/vendor/js/menu.js"></script>
    <!--
    <script src="{{ asset('/assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/swiper/swiper.js"></script> -->
    <script src="{{ asset('/assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('/assets') }}/js/main.js"></script>
    <!-- <script src="{{ asset('/assets') }}/js/dashboards-analytics.js"></script> -->
    <script>
    document.getElementById('logout_btn').addEventListener('click', function() {
        let btn = this;
        btn.disabled = true;
        btn.innerText = 'Processing...';
        document.getElementById('logoutForm').submit();
    });
    document.addEventListener('DOMContentLoaded', function() {
        const toastEl = document.getElementById('liveToast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
        }
    });
    </script>
    <!-- Title -->
    <script>
    const titles = [
        "Custom Web Development Company",
        "Innovative Web & Software Services ✔",
        "Web Development & Digital Solutions 🚀",
        "Welcome Back"
    ];
    let index = 0;
    setInterval(() => {
        document.title = titles[index];
        index = (index + 1) % titles.length;
    }, 2000);
    </script>
</body>

</html>