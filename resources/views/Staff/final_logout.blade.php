@extends('Staff.layout')
<!-- Tabler Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    :root {
        --primary-blue: #2f80ed;
        --primary-blue-dark: #1c5dc9;
        --primary-blue-light: #f4f8ff;
        --border-blue: #d6e6ff;
    }
    .dashboard-card {
        border-radius: 18px;
        overflow: hidden;
        border: 0;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
    }
    .dashboard-header {
        background: linear-gradient(135deg, #2b5382, #03377d);
        color: #fff !important;
    }
    .dashboard-header small {
        opacity: 0.9;
    }
    .stat-card {
        border: 1px solid var(--border-blue);
        border-radius: 14px;
        transition: 0.25s ease-in-out;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }
    .stat-icon {
        font-size: 22px;
        color: var(--primary-blue);
    }
    .info-box {
        border-radius: 14px;
        padding: 6px;
        border: 1px solid var(--border-blue);
        transition: 0.2s ease;
    }
    .info-title {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #6c757d;
    }
    .info-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-blue-dark);
    }
    .info-icon {
        font-size: 20px;
        margin-bottom: 6px;
        color: var(--primary-blue);
    }
</style>
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card dashboard-card">
            <div class="card-header dashboard-header py-3 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <div class="fw-bold fs-5">
                            {{ Auth::guard('staff')->user()->name }}
                        </div>
                    </div>
                    <div>
                        <span><small>Current Time :</small>&nbsp;<span class="fw-semibold" id="currentDateTime"></span></span>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="p-3 stat-card text-center">
                            <div class="stat-icon mb-2"><i class="ti tabler-list-check"></i></div>
                            <div class="text-muted small">New Tasks</div>
                            <h5 class="mb-0 fw-bold">{{ $newTasks }}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 stat-card text-center">
                            <div class="stat-icon mb-2"><i class="ti tabler-check"></i></div>
                            <div class="text-muted small">Completed</div>
                            <h5 class="mb-0 fw-bold">{{ $completedTasks }}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 stat-card text-center">
                            <div class="stat-icon mb-2"><i class="ti tabler-loader"></i></div>
                            <div class="text-muted small">Pending</div>
                            <h5 class="mb-0 fw-bold">{{ $pendingTasks }}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 stat-card text-center">
                            <div class="stat-icon mb-2"><i class="ti tabler-pause"></i></div>
                            <div class="text-muted small">Hold</div>
                            <h5 class="mb-0 fw-bold">{{ $holdTasks }}</h5>
                        </div>
                    </div>
                </div>
                <!-- CHECK INFO -->
                <div class="row g-3 mb-4 text-center">
                    <div class="col-md-3">
                        <div class="info-box">
                            <!-- <div class="info-icon"><i class="ti tabler-login"></i></div> -->
                            <div class="info-title">
                                Check-In
                                @if($loginEntry && $loginEntry->type)
                                <span id="lateBadge"
                                    class="badge {{ $loginEntry->type == 'late'
                                ? 'bg-label-danger'
                                : 'bg-label-success' }}">
                                    {{ ucfirst($loginEntry->type) }}
                                </span>
                                @endif
                            </div>
                            <div class="info-value">
                                {{ $loginEntry?->check_in
                            ? \Carbon\Carbon::parse($loginEntry->check_in)->format('h:i A')
                            : '—' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <!-- <div class="info-icon"><i class="ti tabler-clock-hour-4"></i></div> -->
                            <div class="info-title">Total&nbsp;Productive&nbsp;Hours</div>
                            <div class="info-value">
                                <span id="totalHours">{{ $totalProductiveHours }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <!-- <div class="info-icon"><i class="ti tabler-clock-hour-4"></i></div> -->
                            <div class="info-title">Total&nbsp;Break&nbsp;Hours</div>
                            <div class="info-value">
                                <span id="totalHours">{{ $totalBreakTime}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <!-- <div class="info-icon"><i class="ti tabler-logout"></i></div> -->
                            <div class="info-title">Check-Out</div>
                            <div class="info-value" id="checkoutTime">
                                {{ $loginEntry?->check_out
                    ? \Carbon\Carbon::parse($loginEntry->check_out)->format('h:i A')
                    : '—' }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Task</th>

                                <th>EH</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Time</th>

                                <th>Extra Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($completedTaskList as $row)
                            <tr>
                                <td>{{ $row['project'] }}</td>
                                <td>{{ $row['task'] }}</td>

                                <td>{{ $row['estimated_time'] ?? '-' }}</td>
                                <td>{{ $row['start_time'] }}</td>
                                <td>{{ $row['end_time'] }}</td>
                                <td class="task-hours">{{ $row['working_hours'] }}</td>

                                @php

                                $extraHours = 0;
                                if($row['start_time'] != '-' && $row['end_time'] != '-') {
                                $start = \Carbon\Carbon::parse($row['start_time']);

                                $end = \Carbon\Carbon::parse($row['end_time']);
                                $totalHours = $start->diffInMinutes($end) / 60;
                                $expectedHours = 8;
                                $extraHours = $totalHours > $expectedHours ? $totalHours - $expectedHours : 0;

                                }

                                @endphp
                                <td>{{ number_format($extraHours,2) }} hrs</td>
                                <td>
                                    @switch($row['status'])
                                    @case('new')
                                    <span class="badge bg-label-secondary w-100">Not Started</span>
                                    @break
                                    @case('inprogress')
                                    <span class="badge bg-label-warning w-100">In Progress</span>
                                    @break
                                    @case('complete')
                                    <span class="badge bg-label-success w-100">Completed</span>
                                    @break
                                    @case('hold')
                                    <span class="badge bg-label-danger w-100">Hold</span>
                                    @break
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No completed tasks found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ti tabler-coffee"></i> Break History
                            </h6>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-sm table-bordered mb-0 text-center">

                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Break Start</th>
                                        <th>Break End</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($breakHistory as $key => $break)

                                    @php
                                    $start = \Carbon\Carbon::parse($break->break_start_time);
                                    $end = $break->break_end_time ? \Carbon\Carbon::parse($break->break_end_time) : null;

                                    $duration = '-';

                                    if($end){
                                    $minutes = $start->diffInMinutes($end);
                                    $h = floor($minutes/60);
                                    $m = $minutes%60;
                                    $duration = "{$h}h {$m}m";
                                    }
                                    @endphp

                                    <tr>
                                        <td>{{ $key+1 }}</td>

                                        <td>{{ $start->format('h:i A') }}</td>

                                        <td>
                                            {{ $break->break_end_time ? $end->format('h:i A') : '-' }}
                                        </td>

                                        <td>{{ $duration }}</td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td colspan="4" class="text-muted">No break taken today</td>
                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
                <!-- CHECKOUT BUTTON -->
                <form action="{{ route('final_logout_form') }}" method="post" id="login_form" class="text-center mt-4">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="finalSubmit">
                        <i class="ti tabler-logout me-1"></i> Check Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Live Date Time
    function updateDateTime() {
        const now = new Date();
        document.getElementById('currentDateTime').innerText =
            now.toLocaleDateString('en-GB') + ' | ' +
            now.toLocaleTimeString('en-US');
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
    // Checkout
    document.getElementById('finalSubmit').addEventListener('click', function(e) {
        e.preventDefault();
        let btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="ti tabler-loader tabler-spin me-1"></i> Processing...';
        const now = new Date();
        document.getElementById('checkoutTime').innerText =
            now.toLocaleTimeString('en-US');
        document.getElementById('login_form').submit();
    });
    // Total Hours
    document.addEventListener("DOMContentLoaded", function() {
        let totalMinutes = 0;
        document.querySelectorAll('.task-hours').forEach(function(el) {
            let text = el.innerText.trim();
            let hoursMatch = text.match(/(\d+)h/);
            let minsMatch = text.match(/(\d+)m/);
            let hours = hoursMatch ? parseInt(hoursMatch[1]) : 0;
            let mins = minsMatch ? parseInt(minsMatch[1]) : 0;
            totalMinutes += (hours * 60) + mins;
        });
        // convert back to hours + minutes
        let finalHours = Math.floor(totalMinutes / 60);
        let finalMins = totalMinutes % 60;
        document.getElementById('totalHours').innerText =
            finalHours + "h " + finalMins + "m";
    });
</script>
@endsection