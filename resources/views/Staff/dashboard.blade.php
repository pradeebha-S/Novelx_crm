@extends('Staff.layout')

<style>
#mandatoryPopup .modal-content {
    border: none;
    border-radius: 24px;
    overflow: hidden;
    background: linear-gradient(135deg, #0f172a, #111827);
    color: #fff;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
    animation: popupZoom 0.4s ease;
}

#mandatoryPopup .modal-body {
    padding: 40px 30px;
    text-align: center;
    position: relative;
}

#mandatoryPopup .popup-icon {
    width: 90px;
    height: 90px;
    margin: auto;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.08);
}

#mandatoryPopup .popup-icon i {
    font-size: 48px;
    color: #38bdf8;
}

#mandatoryPopup h2 {
    font-weight: 700;
    font-size: 30px;
    margin-bottom: 15px;
}

#mandatoryPopup p {
    color: #cbd5e1;
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 35px;
}

#mandatoryPopup .btn-area {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

#mandatoryPopup .btn-popup {
    min-width: 140px;
    border: none;
    border-radius: 14px;
    padding: 13px 20px;
    font-weight: 600;
    font-size: 15px;
    transition: 0.3s ease;
}

#mandatoryPopup .btn-noted {
    background: linear-gradient(135deg, #06b6d4, #3b82f6);
    color: #fff;
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.35);

    border: 1px solid rgba(255, 255, 255, 0.12);
}

#mandatoryPopup .btn-noted:hover {
    background: rgba(255, 255, 255, 0.18);
    transform: translateY(-2px);
}

#mandatoryPopup .btn-done {
    background-color: #198754;

    color: #fff;
}

#mandatoryPopup .btn-done:hover {
    transform: translateY(-2px) scale(1.02);
}

#mandatoryPopup .glow-circle {
    position: absolute;
    width: 180px;
    height: 180px;
    background: rgba(56, 189, 248, 0.12);
    border-radius: 50%;
    filter: blur(10px);
}

#mandatoryPopup .circle-1 {
    top: -60px;
    left: -60px;
}

#mandatoryPopup .circle-2 {
    bottom: -80px;
    right: -60px;
}

@keyframes popupZoom {
    from {
        opacity: 0;
        transform: scale(0.8);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width: 576px) {
    #mandatoryPopup h2 {
        font-size: 24px;
    }

    #mandatoryPopup .modal-body {
        padding: 30px 20px;
    }

    #mandatoryPopup .btn-popup {
        width: 100%;
    }
}

.modern-id-card {
    position: relative;
    overflow: hidden;
    border-radius: 22px !important;
    transition: 0.3s ease;
}

.modern-id-card:hover {
    transform: translateY(-4px);
}

.modern-id-card-body {
    position: relative;
    z-index: 3;
    padding: 18px;
    height: 100%;
}

.card-shape {
    position: absolute;
    top: 0;
    right: -40px;
    width: 180px;
    height: 100%;
    transform: skewX(-20deg);
}

.card-shape-one {
    background: linear-gradient(180deg, #d1122d, #7b0b1d);
    z-index: 1;
}

.card-shape-two {
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.55));
    width: 110px;
    right: -10px;
    z-index: 2;
}

.id-card-header {
    margin-bottom: 18px !important;
}

.company-logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #d1122d;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.logo-text h5 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    /* color: #000000; */
    letter-spacing: 0.5px;
}

.logo-text span {
    font-size: 8px;
    font-weight: 600;
    /* color: #000000; */
    letter-spacing: 2px;
}

/* USER DETAILS */
.employee-details {
    position: relative;
    z-index: 5;
}

.employee-name {
    font-size: 13px !important;
    font-weight: 700;
    /* color: #1f1f1f; */
    margin-bottom: 0px !important;
}

.employee-id {
    color: #d1122d;
    font-size: 18px !important;
    font-weight: 700;
    /* margin-bottom: 10px; */
}

.employee-designation {
    /* color: #000000; */
    font-size: 15px !important;
    font-weight: 500;
    /* margin-bottom: 16px; */
}

.employee-email {
    display: flex;
    align-items: center;
    gap: 8px;
    /* color: #000000; */
    font-size: 14px !important;
    font-weight: 500;
}

.employee-email i {
    font-size: 18px;
}

.profile-box {
    position: absolute;
    right: 28px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
}

.profile-inner {
    width: 68px;
    height: 68px;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.2),
        0 10px 25px rgba(0, 0, 0, 0.18);
}

.profile-inner i {
    font-size: 42px;
    color: white;
}

@media(max-width: 768px) {

    .modern-id-card {
        min-height: auto;
    }

    .employee-name {
        font-size: 12px;
    }

    .profile-inner {
        width: 68px;
        height: 68px;
    }

    .profile-inner i {
        font-size: 34px;
    }
}
</style>
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@section('title', 'Dashboard')
@section('content')
<div class="row align-items-stretch mb-3 mt-0">
    <!-- Reminder Section -->
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card custom-card border-0 shadow-sm h-100 rounded-4 p-3"
            style="font-family: Poppins; box-shadow: 0px 6px 14px rgba(0,0,0,0.08);">
            <div class="reminder-list">
                @if ($today_reminders->count() > 0)
                <h6 class="text-primary fw-semibold mb-3">
                    <i class="ti tabler-bell me-1"></i> Today's Reminders
                </h6>
                @foreach ($today_reminders as $reminder)
                <div class="d-flex align-items-center p-2 mb-2 bg-light rounded-3">
                    <span class="me-2 fs-5">🔔</span>
                    <div class="d-flex align-items-center w-100">
                        <!-- Scrolling title -->
                        <div class="flex-grow-1 overflow-hidden">
                            <marquee behavior="scroll" direction="left" scrollamount="4">
                                <span class="fw-medium text-secondary">
                                    {{ $reminder->title }}
                                </span>
                            </marquee>
                        </div>
                        <!-- Badges -->
                        <span class="badge bg-info-subtle text-info ms-2">
                            {{ $reminder->remind_to }}
                        </span>
                        <span class="badge bg-secondary-subtle text-secondary ms-2">
                            Added by: {{ $reminder->added_by ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                @endforeach
                @else
                <div class="text-muted small">
                    No reminders for today.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="row g-4 mb-3">

    <div class="col-xl-3 col-md-6">

        @php
        $user = Auth::guard('staff')->user();
        @endphp

        @if ($user)

        <div class="modern-id-card card">

            {{-- BACKGROUND SHAPES --}}
            <div class="card-shape card-shape-one"></div>
            <div class="card-shape card-shape-two"></div>

            <div class="modern-id-card-body">

                {{-- TOP LOGO AREA --}}
                <div class="id-card-header">

                    <div class="company-logo">

                        <div class="logo-icon">
                            N
                        </div>

                        <div class="logo-text">
                            <h5>NovelX</h5>
                            <span>TECHNOLOGIES</span>
                        </div>

                    </div>

                </div>

                {{-- USER DETAILS --}}
                <div class="employee-details">

                    <h4 class="employee-name">
                        {{ $user->name }}
                    </h4>

                    <div class="employee-id">
                        {{ $user->emp_id ?? $user->user_id }}
                    </div>

                    <div class="employee-designation">
                        {{ $user->designation }}
                    </div>

                    <!-- <div class="employee-email">

                                <i class="ti tabler-mail"></i>

                                <span>
                                    {{ $user->email }}
                                </span>

                            </div> -->

                </div>

                {{-- PROFILE ICON --}}
                <div class="profile-box">

                    <div class="profile-inner">

                        <i class="ti tabler-user"></i>

                    </div>

                </div>

            </div>

        </div>

        @else

        <div class="alert alert-danger">
            User not logged in
        </div>

        @endif

    </div>

    <div class="col-12 col-md-6">

        <div class="dashboard-summary-card metric-card metric-primary">

            <div class="dashboard-summary-body">

                <div class="metric-card-header">

                    <h5 class="metric-card-title">
                        Month Status
                    </h5>

                    <div class="metric-icon">
                        <i class="ti tabler-calendar-stats"></i>
                    </div>

                </div>

                <!-- <div class="metric-data-grid2">

                        <div class="metric-data-box d-flex justify-content-between align-items-center">
                            <div>
                                <div class="metric-label">
                                    WFH
                                </div>

                                <div class="metric-value">
                                  {{ $data['thisMonthWfh'] }} </div>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="text-danger ti tabler-home fs-3"></i>
                            </div>
                        </div>

                        <div class="metric-data-box d-flex justify-content-between align-items-center">
                            <div>
                                <div class="metric-label">
                                    Leave
                                </div>

                                <div class="metric-value">
                                 {{ $data['thisMonthLeave'] }} </div>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="text-danger ti tabler-send fs-3"></i>
                            </div>
                        </div>
                        <div class="metric-data-box d-flex justify-content-between align-items-center">
                            <div>
                                <div class="metric-label">
                                    Permission
                                </div>

                                <div class="metric-value">
                                   {{ $data['thisMonthPermission'] }} </div>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="text-danger ti tabler-user-star fs-3"></i>
                            </div>
                        </div>

                    </div> -->
                <div class="metric-data-grid2">

                    <a href="{{ route('wfh') }}" class="text-decoration-none text-dark">
                        <div class="metric-data-box d-flex justify-content-between align-items-center">
                            <div>
                                <div class="metric-label">
                                    WFH
                                </div>

                                <div class="metric-value">
                                    {{ $data['thisMonthWfh'] }}
                                </div>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="text-danger ti tabler-home fs-3"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('leave_request') }}" class="text-decoration-none text-dark">
                        <div class="metric-data-box d-flex justify-content-between align-items-center">
                            <div>
                                <div class="metric-label">
                                    Leave
                                </div>

                                <div class="metric-value">
                                    {{ $data['thisMonthLeave'] }}
                                </div>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="text-danger ti tabler-send fs-3"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('permission') }}" class="text-decoration-none text-dark">
                        <div class="metric-data-box d-flex justify-content-between align-items-center">
                            <div>
                                <div class="metric-label">
                                    Permission
                                </div>

                                <div class="metric-value">
                                    {{ $data['thisMonthPermission'] }}
                                </div>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="text-danger ti tabler-user-star fs-3"></i>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

        </div>

    </div>




    <div class="col-xl-3 col-md-6">

        <div class="dashboard-summary-card metric-card metric-danger">

            <div class="dashboard-summary-body">

                <div class="metric-card-header">

                    <h5 class="metric-card-title">
                        Late Logins
                    </h5>

                    <div class="metric-icon">
                        <i class="ti tabler-alarm"></i>
                    </div>

                </div>
                <div class="metric-data-grid">

                    <a href="{{ route('attendance_dashboard') }}" class="text-decoration-none text-dark">
                        <div class="metric-data-box">
                            <div class="metric-label">
                                On Time
                            </div>

                            <div class="metric-value">
                                {{ $data['thisMonthontimeLogin'] }}
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('attendance_dashboard') }}" class="text-decoration-none text-dark">
                        <div class="metric-data-box">
                            <div class="metric-label">
                                Late Logins
                            </div>

                            <div class="metric-value">
                                {{ $data['thisMonthLateLogin'] }}
                            </div>
                        </div>
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- <div class="row mb-3">
            <div class="col-lg-7">
                <p class="mb-0 fw-bold text-nowrap mb-2">
                    Welcome Back,
                    <span class="text-danger">{{ Auth::guard('staff')->user()->name }}</span>
                </p>
            </div>

        </div> -->
<!-- <div class="col-lg-5">
                                <div class="d-flex flex-column flex-lg-row gap-2">
                                    <div class="w-100 w-lg-auto">
                                        <a href="{{ route('daily_login') }}">
                                            <button class="btn btn-primary w-100 w-lg-auto">
                                                <i class="ti tabler-login me-1"></i> Check In
                                            </button>
                                        </a>
                                    </div>

                                    <div class="w-100 w-lg-auto">
                                        <a href="{{ route('final_logout') }}">
                                            <button class="btn btn-primary w-100 w-lg-auto">
                                                <i class="ti tabler-logout me-1"></i> Check Out
                                            </button>
                                        </a>
                                    </div>
                                    <div class="w-100 w-lg-auto">
                                        @if(!$break)
                                            <form id="startBreakForm" action="{{ route('break_start') }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="confirmBreakStart()" class="btn btn-primary w-100 w-lg-auto">
                                                    <i class="ti tabler-coffee"></i> Start Break
                                                </button>
                                            </form>
                                        @else
                                            <form id="endBreakForm" action="{{ route('break_end') }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="confirmBreakEnd()" class="btn btn-success w-100 w-lg-auto">
                                                    <i class="ti tabler-player-play"></i> End Break
                                                </button>
                                            </form>
                                            <div id="breakTimer" class="fw-bold text-danger mt-1"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        <div class="d-flex flex-wrap justify-content-center gap-2 task-status-count">

                            <div class="status-tooltip">
                                <a href="{{ route('task_inprogress') }}">

                                    <span class="badge bg-primary status-pill">
                                        <i class="ti tabler-stopwatch me-1"></i>In Progress - 5
                                    </span></a>
                                <span class="tooltip-box">Tasks In Progress</span>
                            </div>

                            <div class="status-tooltip">
                                <a href="{{ route('completed_task') }}">
                                    <span class="badge bg-success status-pill">
                                        <i class="ti tabler-check me-1"></i>Completed - 12
                                    </span>
                                </a>
                                <span class="tooltip-box">Completed Tasks</span>
                            </div>

                            <div class="status-tooltip">
                                <a href="{{ route('pending_task_table') }}">

                                    <span class="badge bg-warning status-pill">
                                        <i class="ti tabler-clock-hour-4 me-1"></i>Pending - 4
                                    </span></a>
                                <span class="tooltip-box">Pending Tasks</span>
                            </div>

                            <div class="status-tooltip">
                                <a href="{{ route('hold_tasks') }}">

                                    <span class="badge bg-danger status-pill">
                                        <i class="ti tabler-player-pause me-1"></i>Hold - 2
                                    </span></a>
                                <span class="tooltip-box">Tasks On Hold</span>
                            </div>

                        </div> -->


<div class="row g-4">


    <div class="col-12">
        <div class="dashboard-section-card">

            <div class="section-title d-flex justify-content-between">
                <div>
                    <i class="ti tabler-calendar-check text-primary"></i>
                    Attendance Management
                </div>
                <div><a href="{{ route('attendance_dashboard') }}">View</a></div>
            </div>

            <div class="attendance-grid">

                {{-- CHECK IN --}}
                <a href="{{ route('daily_login') }}" class="attendance-btn">
                    <div class="d-flex align-items-center"><i
                            class="ti tabler-login text-primary"></i>&nbsp;&nbsp;&nbsp;
                        <span>Check In</span>
                    </div>
                </a>

                {{-- CHECK OUT --}}
                <a href="{{ route('final_logout') }}" class="attendance-btn danger-card">
                    <div class="d-flex align-items-center"> <i
                            class="ti tabler-logout text-danger"></i>&nbsp;&nbsp;&nbsp;
                        <span>Check Out</span>
                    </div>
                </a>

                @if(!$break)

                <form id="startBreakForm" action="{{ route('break_start') }}" method="POST" class="h-100">
                    @csrf

                    <button type="button" onclick="confirmBreakStart()"
                        class="attendance-btn warning-card w-100 border-0">

                        <div class="d-flex align-items-center"><i
                                class="ti tabler-coffee text-warning"></i>&nbsp;&nbsp;&nbsp;
                            <span>Start Break</span>
                        </div>

                    </button>
                </form>

                @else

                <form id="endBreakForm" action="{{ route('break_end') }}" method="POST" class="h-100">
                    @csrf

                    <button type="button" onclick="confirmBreakEnd()"
                        class="attendance-btn success-card w-100 border-0">

                        <i class="ti tabler-player-play text-success"></i>
                        <span>End Break</span>

                    </button>

                    <div id="breakTimer" class="break-timer-box"></div>

                </form>

                @endif

                <a href="{{ route('break_report') }}" class="attendance-btn">
                    <div class="d-flex align-items-center">
                        <i class="ti tabler-history text-info"></i>&nbsp;&nbsp;&nbsp;
                        <span>Break History</span>
                    </div>
                </a>

            </div>

        </div>
    </div>



    <div class="col-12">
        <div class="dashboard-section-card">

            <div class="section-title d-flex justify-content-between">
                <div>
                    <i class="ti tabler-list-details text-success"></i>
                    Task Overview
                </div>
                <div><a href="{{ route('staff_task') }}">View</a></div>
            </div>

            <div class="task-status-grid">

                {{-- IN PROGRESS --}}
                <a href="{{ route('task_inprogress') }}" class="task-status-card primary-card">

                    <div class="task-status-content">
                        <div class="task-status-title">
                            In Progress
                        </div>

                        <div class="task-status-count">
                            {{ $data['inprogressTasks'] }}
                        </div>

                        <div class="task-status-sub">
                            Active working tasks
                        </div>
                    </div>

                    <div class="task-status-icon">
                        <i class="ti tabler-stopwatch"></i>
                    </div>

                </a>


                {{-- COMPLETED --}}
                <a href="{{ route('completed_task') }}" class="task-status-card success-card">

                    <div class="task-status-content">
                        <div class="task-status-title">
                            Completed
                        </div>

                        <div class="task-status-count">
                            {{ $data['completed_tasks_count'] }}
                        </div>

                        <div class="task-status-sub">
                            Successfully finished
                        </div>
                    </div>

                    <div class="task-status-icon">
                        <i class="ti tabler-check"></i>
                    </div>

                </a>


                {{-- PENDING --}}
                <a href="{{ route('pending_task_table') }}" class="task-status-card warning-card">

                    <div class="task-status-content">
                        <div class="task-status-title">
                            Pending
                        </div>

                        <div class="task-status-count">
                            {{ $data['pending_tasks_count'] }}
                        </div>

                        <div class="task-status-sub">
                            Waiting to start
                        </div>
                    </div>

                    <div class="task-status-icon">
                        <i class="ti tabler-clock-hour-4"></i>
                    </div>

                </a>


                {{-- HOLD --}}
                <a href="{{ route('hold_tasks') }}" class="task-status-card danger-card2">

                    <div class="task-status-content">
                        <div class="task-status-title">
                            Hold
                        </div>

                        <div class="task-status-count">
                            {{ $data['hold_tasks_count'] }}
                        </div>

                        <div class="task-status-sub">
                            Temporarily paused
                        </div>
                    </div>

                    <div class="task-status-icon">
                        <i class="ti tabler-player-pause"></i>
                    </div>

                </a>

            </div>

        </div>
    </div>

</div>
<div class="row">
    <!-- <div class="col-12 col-lg-6 mt-4">
        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h5 class="mb-0 fw-semibold">Task Overview</h5>
            </div>

            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="taskBarChart"></canvas>
                </div>
            </div>

        </div>
    </div> -->

    <!-- Chart JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('taskBarChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Tasks', 'Pending', 'Reopen', 'Completed'],
                datasets: [{
                    label: 'Tasks',
                    data: [120, 35, 12, 73], // Dynamic values here

                    backgroundColor: [
                        '#4e73df', // Total - Blue
                        '#f6c23e', // Pending - Yellow
                        '#e74a3b', // Reopen - Red
                        '#1cc88a'  // Completed - Green
                    ],

                    borderRadius: 10,
                    borderSkipped: false,
                    barThickness: 45,
                    maxBarThickness: 55
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#eef1f5'
                        },
                        ticks: {
                            precision: 0
                        }
                    },

                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script> -->
    <div class="col-12 col-lg-12 mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-semibold mb-0">
                        <i class="ti tabler-chart-line me-2 text-primary"></i>
                        Performance Overview
                    </h6>
                    <small class="text-muted"> To Track Your Performance&nbsp;<a
                            href="{{ route('performance_tracker') }}">Click Here...</a></small>
                </div>
                <div style="height: 350px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="row mt-3"> -->
<!-- <div class="col-lg-3 col-sm-6 mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <div class="card b h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    @php
                                        $user = Auth::guard('staff')->user();
                                    @endphp
                                    @if ($user)
                                        <p class="mb-1 fw-semibold">EMP ID : {{ $user->emp_id ?? $user->user_id }}</p>
                                        <p class="mb-1 fw-semibold">{{ $user->name }}</p>
                                        <p class="mb-0 fw-semibold">{{ $user->designation }}</p>
                                    @else
                                        <p class="text-danger">User not logged in</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <div class="card p-3 d h-100">
                            <div class="text-center mb-2">
                                <h5 class="mb-0 fw-semibold text-black">
                                    This Month
                                </h5>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between px-2 mt-2">
                                <p class="mb-0 fw-semibold">Completed</p>
                                <p class="mb-0 fw-semibold">Pending</p>
                            </div>
                            <div class="d-flex justify-content-between px-2 mt-1">
                                <p class="mb-0">{{ $data['thisMonthCompleted'] }}</p>
                                <p class="mb-0">{{ $data['thisMonthPending'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <div class="card p-3 c h-100">
                            <div class="text-center mb-2">
                                <h5 class="mb-0 fw-semibold text-black">
                                    Last Month
                                </h5>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between px-2 mt-2">
                                <p class="mb-0 fw-semibold">Completed</p>
                                <p class="mb-0 fw-semibold">Pending</p>
                            </div>
                            <div class="d-flex justify-content-between px-2 mt-1">
                                <p class="mb-0">{{ $data['lastMonthCompleted'] }}</p>
                                <p class="mb-0">{{ $data['lastMonthPending'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <div class="card p-3 e h-100">
                            <div class="text-center mb-2">
                                <h5 class="mb-0 fw-semibold text-black">
                                    Late Logins
                                </h5>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between px-2 mt-2">
                                <p class="mb-0 fw-semibold">This Month</p>
                                <p class="mb-0 fw-semibold">Last Month</p>
                            </div>
                            <div class="d-flex justify-content-between px-2 mt-1">
                                <p class="mb-0">{{ $data['thisMonthLateLogin'] }}</p>
                                <p class="mb-0">{{ $data['lastMonthLateLogin'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-semibold mb-0">
                                        <i class="ti tabler-chart-line me-2 text-primary"></i>
                                        Performance Overview
                                    </h6>
                                    <small class="text-muted"> To Track Your Performance&nbsp;<a
                                            href="{{ route('performance_tracker') }}">Click Here...</a></small>
                                </div>
                                <div style="height: 350px;">
                                    <canvas id="performanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div> -->
<!-- </div> -->


<!-- <div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm feedback-dialog">
                                    <div class="modal-content feedback-modal border-0">


                                        <div class="modal-body text-center p-3 p-md-4">

                                            <div class="feedback-icon">
                                                <i class="ti tabler-message-star"></i>
                                            </div>

                                            <span class="feedback-badge">
                                                <i class="ti tabler-clock-hour-4"></i>
                                                Takes Only 2 Minutes
                                            </span>

                                            <h5 class="feedback-title mt-3">
                                                Submit April Feedback
                                            </h5>

                                            <p class="feedback-subtitle">
                                                Help us improve your workflow experience.
                                            </p>

                                            <div class="mt-3 d-grid gap-2">

                                                <a href="{{ route('feed_back_submit') }}" class="btn feedback-btn-primary">
                                                    <i class="ti tabler-arrow-right"></i>
                                                    Start Now
                                                </a>

                                                <button class="btn feedback-btn-secondary" data-bs-dismiss="modal">
                                                    Remind Me Later
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {

                                    setTimeout(function () {

                                        const modalEl = document.getElementById('feedbackModal');

                                        const modal = new bootstrap.Modal(modalEl, {
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        modal.show();

                                    }, 700);

                                });
                            </script> -->
<div class="modal fade" id="checkin" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 text-center p-4 py-5 shadow-sm">
            <p id="liveTime" class="fw-bold fs-5 mb-3"></p>
            <p class="mb-4 fs-6">Do you want to Check In now?</p>
            <form action="{{ route('check_in') }}" method="POST" id="checkinForm">
                @csrf
                <div id="lateSection" class="d-none text-start">
                    <label class="form fw-semibold">
                        Reason <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control mb-3" name="late_reason"
                        placeholder="Enter reason if you are late...">
                </div>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold" id="finalSubmit">
                        Yes, Sure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mandatory Modal -->
@if($popup)

<div class="modal fade" id="staffPopupModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body text-center">

                <div class="glow-circle circle-1"></div>
                <div class="glow-circle circle-2"></div>

                <div class="popup-icon">
                    <i class="ti tabler-mail"></i>
                </div>


                <h5 class="text-white"> {{ $popup->title ?? 'Important Notice' }}</h5>

                <p class="mb-3">
                    {{ $popup->message }}

                </p>

                <div class="btn-area">

                    <button type="button" class="btn btn-popup btn-noted" onclick="popupNoted({{ $popup->id }})">

                        <i class="ti tabler-eye-check me-1"></i>
                        Noted

                    </button>

                    <button type="button" class="btn btn-popup btn-done" onclick="popupDone({{ $popup->id }})">

                        <i class="ti tabler-circle-check me-1"></i>
                        Done

                    </button>

                </div>

            </div>

        </div>
    </div>
</div>

<script>
window.addEventListener('load', function() {

    const popupModal = new bootstrap.Modal(
        document.getElementById('staffPopupModal')
    );

    popupModal.show();

});
</script>

@endif
<!-- Mandatory Modal -->
<div class="modal fade" id="mandatoryPopup" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body text-center">

                <div class="glow-circle circle-1"></div>
                <div class="glow-circle circle-2"></div>

                <div class="popup-icon">
                    <i class="ti tabler-mail"></i>
                </div>

                <h5 class="text-white mb-3">
                    Unread Emails
                </h5>

                <p class="mb-3">
                    You have
                    <strong>{{ $unreadMails->count() }}</strong>
                    unread emails.
                </p>

                @if($unreadMails->count() > 0)

                <div class="text-start mb-4">

                    @foreach($unreadMails->take(3) as $mail)

                    <div class="mb-2 text-white">
                        • {{ $mail->subject }}
                    </div>

                    @endforeach

                </div>

                @endif

                <div class="btn-area">

                    @if($unreadMails->count() > 0)

                    <a href="{{ route('read_mail', $unreadMails->first()->id) }}" class="btn btn-popup btn-noted">

                        <i class="ti tabler-eye me-1"></i>
                        View Email

                    </a>

                    @endif

                    <button class="btn btn-popup btn-done" data-bs-dismiss="modal">

                        <i class="ti tabler-circle-check me-1"></i>
                        Close

                    </button>

                </div>

            </div>

        </div>
    </div>
</div>
<!-- 
    <script>
        window.addEventListener('load', function () {

            const mandatoryModal = new bootstrap.Modal(
                document.getElementById('mandatoryPopup')
            );

            mandatoryModal.show();

        });
    </script> -->
<script>
function popupNoted(id) {

    const btn = event.currentTarget;

    // prevent multiple clicks
    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-1"></span>
        Processing...
    `;

    fetch(`{{ route('popup.noted', ':id') }}`.replace(':id', id), {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            const modalEl = document.getElementById('staffPopupModal');

            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

        } else {

            btn.disabled = false;
            btn.innerHTML = `
                <i class="ti tabler-eye-check me-1"></i>
                Noted
            `;
        }

    })
    .catch(error => {

        console.error(error);

        btn.disabled = false;
        btn.innerHTML = `
            <i class="ti tabler-eye-check me-1"></i>
            Noted
        `;
    });
}

function popupDone(id) {

    fetch(`{{ route('popup.done', ':id') }}`.replace(':id', id), {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            const modalEl = document.getElementById('staffPopupModal');

            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

        }

    })
    .catch(error => console.error(error));
}
</script>
<script>
window.addEventListener('load', function() {

    let unreadCount = {
        {
            $unreadMails - > count()
        }
    };

    if (unreadCount > 0) {

        const mandatoryModal = new bootstrap.Modal(
            document.getElementById('mandatoryPopup')
        );

        mandatoryModal.show();
    }

});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const liveTime = document.getElementById('liveTime');
    const lateSection = document.getElementById('lateSection');
    const reasonInput = document.querySelector('input[name="late_reason"]');
    const lateBtn = document.getElementById('lateBtn'); // optional
    if (!liveTime || !lateSection || !reasonInput) return;
    const now = new Date();
    const lateTime = new Date();
    lateTime.setHours(9, 10, 0, 0);
    liveTime.innerText =
        now.toLocaleDateString('en-GB') + ' | ' +
        now.toLocaleTimeString('en-US');
    if (now <= lateTime) {
        liveTime.classList.add('text-success');
        liveTime.classList.remove('text-danger');
        lateSection.classList.add('d-none');
        reasonInput.removeAttribute('required');
    } else {
        liveTime.classList.add('text-danger');
        liveTime.classList.remove('text-success');
        lateSection.classList.remove('d-none');
        reasonInput.setAttribute('required', true);
        if (lateBtn) {
            lateBtn.classList.remove('btn-warning');
            lateBtn.classList.add('btn-danger');
            lateBtn.innerText = 'Late';
        }
    }
});
</script>
<script>
document.getElementById('finalSubmit').addEventListener('click', function() {
    const btn = this;
    const form = document.getElementById('checkinForm');
    btn.disabled = true;
    btn.innerText = 'Processing...';
    form.submit();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const gradientBlue = ctx.createLinearGradient(0, 0, 0, 350);
    gradientBlue.addColorStop(0, 'rgba(13,110,253,0.25)');
    gradientBlue.addColorStop(1, 'rgba(13,110,253,0.02)');
    const gradientRed = ctx.createLinearGradient(0, 0, 0, 350);
    gradientRed.addColorStop(0, 'rgba(220,53,69,0.25)');
    gradientRed.addColorStop(1, 'rgba(220,53,69,0.02)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                    label: 'Earned Points',
                    data: @json($earnedPoints),
                    borderColor: '#0d6efd',
                    backgroundColor: gradientBlue,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
                {
                    label: 'Reduced Points',
                    data: @json($reducedPoints),
                    borderColor: '#dc3545',
                    backgroundColor: gradientRed,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'nearest',
                intersect: true
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: '#111',
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressValue = progressBar.getAttribute('data-progress');
    progressBar.style.width = progressValue + '%';
    progressPercent.textContent = progressValue;
});
</script>
<meta name="vapid-key" content="{{ config('webpush.vapid.public_key') }}">
<script>
(async () => {
    if (!('serviceWorker' in navigator)) return;
    const registration = await navigator.serviceWorker.register('/service-worker.js');
    const permission = await Notification.requestPermission();
    if (permission !== 'granted') return;
    const vapidPublicKey = document.querySelector('meta[name="vapid-key"]').content;

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = atob(base64);
        return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
    }
    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
    });
    await fetch("{{ route('push.subscription.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(subscription.toJSON())
    });
    console.log("Push subscription saved!");
})();
</script>
<span id="breakTimer" class="fw-bold text-danger"></span>
<script>
let breakStart = @json($breakStartTime);
if (breakStart) {
    // today date + break start time
    let today = new Date().toISOString().split('T')[0];
    let start = new Date(today + "T" + breakStart);

    function updateBreakTimer() {
        let now = new Date();
        let diff = Math.floor((now - start) / 1000);
        if (diff < 0) diff = 0;
        let hours = Math.floor(diff / 3600);
        let minutes = Math.floor((diff % 3600) / 60);
        let seconds = diff % 60;
        document.getElementById("breakTimer").innerHTML =
            hours.toString().padStart(2, '0') + ":" +
            minutes.toString().padStart(2, '0') + ":" +
            seconds.toString().padStart(2, '0');
    }
    updateBreakTimer();
    setInterval(updateBreakTimer, 1000);
}
</script>

<script>
function confirmBreakStart() {
    Swal.fire({
        title: "Start Break?",
        text: "Are you sure you want to start your break?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Start Break"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("startBreakForm").submit();
        }
    });
}

function confirmBreakEnd() {
    Swal.fire({
        title: "End Break?",
        text: "Are you sure you want to end your break?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, End Break"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("endBreakForm").submit();
        }
    });
}
</script>
@endsection