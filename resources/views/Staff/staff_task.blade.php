@extends('Staff.layout')
<style>
    .card {
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        transition: all 0.25s ease;
    }
    .cls-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        border-radius: 14px 0 0 14px;
        background: linear-gradient(180deg, #ff2c54, #ff8a5c54);
    }
    .project-progress-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 18px;
    }
    /* Header */
    .project-title {
        font-weight: 600;
        font-size: 18px;
    }
    .project-status {
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        font-size: 13px;
        padding: 6px 14px;
        border-radius: 50px;
    }
    /* Layout Row */
    .progress-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    /* Percentage Badge */
    .progress-percent {
        font-size: 22px;
        font-weight: 700;
        background: linear-gradient(90deg, #ff2c54, #ff7b54);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        min-width: 70px;
    }
    /* Count */
    .progress-count {
        font-weight: 600;
        color: #6c757d;
        min-width: 90px;
        text-align: right;
    }
    /* Progress Bar Container */
    .custom-progress {
        flex: 1;
        height: 14px;
        background: #eef2f7;
        border-radius: 50px;
        overflow: hidden;
        position: relative;
    }
    /* Progress Fill */
    .custom-progress-bar {
        height: 100%;
        width: 0%;
        border-radius: 50px;
        background: linear-gradient(90deg, #ff2c54, #ff7b54);
        box-shadow: 0 8px 18px rgba(255, 44, 84, 0.35);
        transition: width 1.2s ease-in-out;
        position: relative;
    }
    /* Animated Shine */
    .custom-progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -40%;
        height: 100%;
        width: 40%;
        background: linear-gradient(120deg,
                transparent,
                rgba(255, 255, 255, 0.6),
                transparent);
    }
    /* Task Summary */
    .task-summary {
        display: flex;
        justify-content: center;
        gap: 28px;
        font-size: 14px;
        color: #6c757d;
        flex-wrap: wrap;
        margin-top: 22px;
    }
    .task-summary div b {
        color: #212529;
    }
</style>
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <!-- Existing Stat Cards (UNCHANGED Backend) -->
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card cls-card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="text-warning"><b>Pending Tasks</b></p>
                        <h4>{{ $pending_tasks }}</h4>
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('assets/img/list.png') }}" class="mb-2" width="40">
                        <a href="{{ route('pending_task_table') }}" class="d-block small">View</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card cls-card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="text-info"><b>New Tasks</b></p>
                        <h4>{{ $new_tasks }}</h4>
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('assets/img/list.png') }}" class="mb-2" width="40">
                        <a href="{{ route('today_task') }}" class="d-block small">View</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card cls-card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="text-success"><b>Completed Tasks</b></p>
                        <h4>{{ $completed_tasks }}</h4>
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('assets/img/list.png') }}" class="mb-2" width="40">
                        <a href="{{ route('completed_task') }}" class="d-block small">View</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card cls-card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="text-danger"><b>Hold Tasks</b></p>
                        <h4>{{ $hold_tasks }}</h4>
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('assets/img/list.png') }}" class="mb-2" width="40">
                        <a href="{{ route('hold_tasks') }}" class="d-block small">View</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            @foreach($projectData as $data)
                <div class="col-12 mt-3">
                    <div class="card project-progress-card p-4">
                       <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <!-- Left Section -->
    <div class="d-flex align-items-center gap-2">
        <i class="ti tabler-folder text-primary d-none d-sm-inline"></i>
        <span class="fw-semibold">Project :</span>
        <span class="text-primary fw-semibold">{{ $data['project']->project_name }}</span>
    </div>
    <!-- Right Section -->
    <div class="d-flex flex-wrap align-items-center gap-3">
        <div class="d-flex align-items-center gap-1">
            <i class="ti tabler-bug text-danger d-none d-sm-inline"></i>
            <h6 class="mb-0">
                <span class="text-danger">{{ $data['totalBugs'] }}</span>
                <span class="ms-1">Total</span>
            </h6>
        </div>
        <div class="d-flex align-items-center gap-1">
            <i class="ti tabler-rosette-number-0 text-info d-none d-sm-inline"></i>
            <h6 class="mb-0">
                 <span class="text-danger">{{ $data['pendingBugs'] }}</span>
                <span class="ms-1">Pending</span>
            </h6>
        </div>
        <div class="d-flex align-items-center gap-1">
            <i class="ti tabler-report text-warning d-none d-sm-inline"></i>
            <a href="{{ route('bug_report', $data['project']->id) }}" class="text-decoration-none">
                <h6 class="mb-0">
                    <span class="d-none d-sm-inline">Bugs </span>Report
                </h6>
            </a>
        </div>
    </div>
</div>
                        <!-- Progress Bar -->
                        <div class="progress-wrapper">
                            <div class="progress-percent">
                                <span>{{ $data['progressPercent'] }}</span>%
                            </div>
                            <div class="custom-progress">
                                <div class="custom-progress-bar"
                                    style="width: {{ $data['progressPercent'] }}%; background-color: #0d6efd;"></div>
                            </div>
                            <div class="progress-count">
                                {{ $data['completedTasks'] }} / {{ $data['totalTasks'] }}
                            </div>
                        </div>
                        <!-- Task Summary -->
                        <div class="task-summary d-flex gap-3 mt-3">
                            <div>Pending : <b>{{ $data['pendingTasks'] }}</b></div>
                            <div>Hold : <b>{{ $data['holdTasks'] }}</b></div>
                            <div>Completed : <b class="text-success">{{ $data['completedTasks'] }}</b></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.custom-progress-bar').forEach(function (bar) {
            const target = parseInt(bar.style.width);
            const percentText = bar.closest('.progress-wrapper').querySelector('.progress-percent span');
            const duration = 1200;
            const startTime = performance.now();
            function animate(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const value = Math.floor(progress * target);
                percentText.textContent = value;
                bar.style.width = value + "%";
                if (progress < 1) requestAnimationFrame(animate);
            }
            requestAnimationFrame(animate);
        });
    });
</script>