<!doctype html>
<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default"
    data-bs-theme="light" data-assets-path="{{ asset('/assets') }}/" data-template="vertical-menu-template">
<script>
    (function () {
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
    <meta name="robots" content="noindex, nofollow">
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
    <link rel="stylesheet" href="{{ asset('/assets/css/demo.css') }}" />
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
    {{-- Toast --}}
    @if (session('success') || session('error') || $errors->any())
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; max-width: 100%;">
            <div id="liveToast"
                class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0 show"
                role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000" data-bs-autohide="true"
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
    {{-- End Toast --}}
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
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
                <ul class="menu-inner py-1">
                    <li class="menu-item  @if (Route::is('staff.dashboard')) active @endif">
                        <a href="{{ route('staff.dashboard') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-smart-home"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item  @if (Route::is('staff.report')) active @endif">
                        <a href="{{ route('staff.report') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-report"></i>
                            <div data-i18n="Daily Work Report">Daily Work Report</div>
                        </a>
                    </li>
                    <li class="menu-item @if (Route::is('staff_task')) active @endif">
                        <a href="{{ route('staff_task') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-rotate-rectangle"></i>
                            <div data-i18n="Task">Task</div>
                        </a>
                    </li>
                    <!-- @php
    $staff = Auth::guard('staff')->user();
@endphp

@if($staff && $staff->role == 'staff' && $staff->designation == 'Tester')
    <li class="menu-item @if(Route::is('developer_completed_task')) active @endif">
        <a href="{{ route('developer_completed_task') }}" class="menu-link">
            <i class="menu-icon icon-base ti tabler-circle-check"></i>
            <div data-i18n="developer_completed_task">Developer Completed Task</div>
        </a>
    </li>
@endif -->
@php
    $staff = Auth::guard('staff')->user();

    $taskCount = \App\Models\Task::where([
        ['task_status', '=', 'complete'],
        ['test_status', '=', 'incomplete']
    ])->count();
@endphp

@if($staff && strtolower(trim($staff->designation)) === 'tester')
    <li class="menu-item {{ Route::is('developer_completed_task') ? 'active' : '' }}">
        <a href="{{ route('developer_completed_task') }}" class="menu-link">
            <i class="menu-icon icon-base ti tabler-circle-check"></i>
            <div>
                Completed Task
                <span class="badge bg-danger ms-2">
                    {{ $taskCount }}
                </span>
            </div>
        </a>
    </li>
@endif
                    <li class="menu-item @if (Route::is('attendance_dashboard')) active @endif">
                        <a href="{{ route('attendance_dashboard') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-calendar"></i>
                            <div data-i18n="Attendance">Attendance</div>
                        </a>
                    </li>
                    <li class="menu-item @if (Route::is('wfh')) active @endif">
                        <a href="{{ route('wfh') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-mailbox"></i>
                            <div data-i18n="Request">Request</div>
                        </a>
                    </li>
                    @php

$unreadMailCount = \App\Models\Communication::where('user_id', Auth::guard('staff')->id())
                        ->where('is_viewed', 0)
                        ->count();

@endphp
                    <li class="menu-item @if (Route::is('table_mail')) active @endif">
                        <a href="{{ route('table_mail') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-mail"></i>
                            <div data-i18n="Mails">Mails</div>
                             @if($unreadMailCount > 0)
            <div class="badge text-bg-danger ms-auto">
                {{ $unreadMailCount }}
            </div>
        @endif
                        </a>
                    </li>
                    <li class="menu-item @if (Route::is('reminder')) active @endif">
                        <a href="{{ route('reminder') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-stopwatch"></i>
                            <div data-i18n="Reminder">Reminder</div>
                        </a>
                    </li>
                    <li class="menu-item @if(Route::is('feed_back_submit')) active @endif">
                        <a href="{{ route('feed_back_submit') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-star"></i>
                            <div data-i18n="Feedback">Feedback</div>
                        </a>
                    </li>
                  <li class="menu-item @if (Route::is('bank_details')) active @endif">
    <a href="{{ route('bank_details') }}" class="menu-link">
        <i class="menu-icon icon-base ti tabler-building-bank"></i>
        <div>Bank Details</div>
    </a>
</li>
                    <li class="menu-item @if (Route::is('staff_reset_password')) active @endif">
                        <a href="{{ route('staff_reset_password') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-lock"></i>
                            <div data-i18n="Reset Password">Reset Password</div>
                        </a>
                    </li>
                    <li class="menu-item
                        @if (Route::is('common_support') || Route::is('personal_request')) open active @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon icon-base ti tabler-headset"></i>
                            <div data-i18n="Support">Support</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @if (Route::is('common_support')) active @endif">
                                <a href="{{ route('common_support') }}" class="menu-link">
                                    <div data-i18n="Common Support">Common Support</div>
                                </a>
                            </li>
                            <li class="menu-item @if (Route::is('personal_request')) active @endif">
                                <a href="{{ route('personal_request') }}" class="menu-link">
                                    <div data-i18n="Personal Request">Personal Request</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link">
                            <i class="menu-icon red icon-base ti tabler-logout"></i>
                            <div data-i18n="Logout" class="out" data-bs-toggle="modal" data-bs-target="#logout">
                                Logout
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
                    <!-- <p class="mb-0 fw-bold text-nowrap d-none d-md-block">
                        Welcome Back,
                        <span class="text-danger">{{ Auth::guard('staff')->user()->name }}</span>
                    </p> -->
                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
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
                            <!-- / Style Switcher-->
                            <!-- Notification -->
                            <!-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                                <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    <span class="position-relative">
                                        <i class="icon-base ti tabler-bell icon-22px text-heading"></i>
                                        <span
                                            class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                            <div class="d-flex align-items-center h6 mb-0">
                                                <span class="badge bg-label-primary me-2">8 New</span>
                                                <a href="javascript:void(0)"
                                                    class="dropdown-notifications-all p-2 btn btn-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Mark all as read"><i
                                                        class="icon-base ti tabler-mail-opened text-heading"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('/assets') }}/img/avatars/1.png" alt
                                                                class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-1">Congratulation Lettie 🎉</h6>
                                                        <small class="mb-1 d-block text-body">Won the monthly best
                                                            seller gold badge</small>
                                                        <small class="text-body-secondary">1h ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">Charles Franklin</h6>
                                                        <small class="mb-1 d-block text-body">Accepted your
                                                            connection</small>
                                                        <small class="text-body-secondary">12hr ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('/assets') }}/img/avatars/2.png" alt
                                                                class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">New Message ✉️</h6>
                                                        <small class="mb-1 d-block text-body">You have new message from
                                                            Natalie</small>
                                                        <small class="text-body-secondary">1h ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-success"><i
                                                                    class="icon-base ti tabler-shopping-cart"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">Whoo! You have new order 🛒</h6>
                                                        <small class="mb-1 d-block text-body">ACME Inc. made new order
                                                            $1,154</small>
                                                        <small class="text-body-secondary">1 day ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('/assets') }}/img/avatars/9.png" alt
                                                                class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">Application has been approved 🚀</h6>
                                                        <small class="mb-1 d-block text-body">Your ABC project
                                                            application has been
                                                            approved.</small>
                                                        <small class="text-body-secondary">2 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-success"><i
                                                                    class="icon-base ti tabler-chart-pie"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">Monthly report is generated</h6>
                                                        <small class="mb-1 d-block text-body">July monthly financial
                                                            report is generated </small>
                                                        <small class="text-body-secondary">3 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('/assets') }}/img/avatars/5.png" alt
                                                                class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">Send connection request</h6>
                                                        <small class="mb-1 d-block text-body">Peter sent you connection
                                                            request</small>
                                                        <small class="text-body-secondary">4 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('/assets') }}/img/avatars/6.png" alt
                                                                class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">New message from Jane</h6>
                                                        <small class="mb-1 d-block text-body">Your have new message
                                                            from
                                                            Jane</small>
                                                        <small class="text-body-secondary">5 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-warning"><i
                                                                    class="icon-base ti tabler-alert-triangle"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 small">CPU is running high</h6>
                                                        <small class="mb-1 d-block text-body">CPU Utilization Percent
                                                            is
                                                            currently at
                                                            88.63%,</small>
                                                        <small class="text-body-secondary">5 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-read"><span
                                                                class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-notifications-archive"><span
                                                                class="icon-base ti tabler-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="border-top">
                                        <div class="d-grid p-4">
                                            <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                                                <small class="align-middle">View all notifications</small>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li> -->
                            <!--/ Notification -->
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        @php
                                            $staff = Auth::guard('staff')->user();
                                        @endphp
                                        <img src="{{ $staff && $staff->profile_image
    ? asset('storage/' . $staff->profile_image)
    : asset('assets/img/avatars/6.png') }}" class="rounded-circle" width="100%"
                                            height="100%">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item mt-0" href="{{ route('profile') }}">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar avatar-online">
                                                        @php
                                                            $staff = Auth::guard('staff')->user();
                                                        @endphp
                                                        <img src="{{ $staff && $staff->profile_image
    ? asset('storage/' . $staff->profile_image)
    : asset('assets/img/avatars/6.png') }}"
                                                            class="rounded-circle" width="100%" height="100%">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    @php
                                                        $admin = Auth::guard('staff')->user();
                                                    @endphp
                                                    <h6 class="mb-0">{{ $admin->name ?? 'Staff' }}</h6>
                                                    <small class="text-body-secondary">
                                                        {{ Auth::guard('staff')->user()->designation ?? 'Admin' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile') }}">
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
                                        <div class="d-grid px-2 pt-2 pb-1">
                                            <a class="btn btn-sm btn-danger d-flex" href="#" data-bs-toggle="modal"
                                                data-bs-target="#logout">
                                                <small class="align-middle">Logout</small>
                                                <i class="icon-base ti tabler-logout ms-2 icon-14px"></i>
                                            </a>
                                        </div>
                                    </li>
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
                    <form action="{{ route('staff_logout') }}" method="GET" id="logoutForm">
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
    <script src="{{ asset('/assets') }}/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('/assets') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/pickr/pickr.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('/assets') }}/vendor/js/menu.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/swiper/swiper.js"></script>
    <script src="{{ asset('/assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('/assets') }}/js/main.js"></script>
    <script src="{{ asset('/assets') }}/js/dashboards-analytics.js"></script>
    <script>
        document.getElementById('logout_btn').addEventListener('click', function () {
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('logoutForm').submit();
        });
        document.addEventListener('DOMContentLoaded', function () {
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