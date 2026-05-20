@extends('Admin.layout')
@section('title', 'Dashboard')
@section('content')
    <div class="row align-items-center mb-4">
        <!-- Left Section -->
        <div class="col-md-6">
            <h5 class="fw-bold mb-0">
                Welcome Back,
                <span class="text-danger">
                    {{ Auth::guard('admin')->user()->name }}
                </span>
            </h5>
        </div>
        <!-- Right Section -->
       <div class="col-md-6">

    <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2 mt-3 mt-md-0">

        <a href="https://www.novelx.in/sales/public/admin"
           class="btn btn-primary"
           target="_blank">
            Sales Admin
        </a>

        <a href="https://www.novelx.in/sales/public/staff"
           class="btn btn-primary"
           target="_blank">
            Sales Staff
        </a>

        <a href="http://64.227.170.206/novelx_hr/public/hr/hr_login"
           class="btn btn-primary"
           target="_blank">
            HR Login
        </a>

        <a href="http://64.227.170.206/novelx_accounts/public/admin/login"
           class="btn btn-primary"
           target="_blank">
            Accounts
        </a>

    </div>

</div>
    </div>
    <div class="card shadow-sm border-0 border-start border-4 border-danger mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 text-danger">
                    <i class="ti tabler-bell me-2"></i> Today's Reminders
                </h6>
                <a href="{{ route('admin.reminder') }}" class="fw-semibold small">
                    View All
                </a>
            </div>
            <div style="max-height:120px; overflow-y:auto;">
                @if ($today_reminders->count() > 0)
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
                                    Added by: {{ $reminder->added_by }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted small">No reminders for today.</div>
                @endif
            </div>
        </div>
    </div>
        <div class="row g-3 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow-sm border-0 border-start border-4 border-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="ti tabler-list-check me-1"></i> Today Task
                        </h6>
                        <a href="{{ route('today_tasks') }}" class="small fw-semibold">View</a>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <small class="text-muted">Task</small>
                            <div class="fw-bold fs-5">{{ $todaytask }}</div>
                        </div>
                        <div>
                            <small class="text-muted">Complete</small>
                            <div class="fw-bold fs-5 text-success">
                                <a href="{{ route('today_complete') }}" class="text-success text-decoration-none">
                                    {{ $todayCompletedCount }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="ti tabler-user-check me-1"></i> Today
                        </h6>
                        <a href="{{ route('attendance_history') }}" class="small fw-semibold">View</a>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <small class="text-muted">Present</small>
                            <div class="fw-bold fs-5">
                                <a href="{{ route('today_present') }}" class="text-decoration-none">
                                    {{ $presentCount }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Not Ip</small>
                            <div class="fw-bold fs-5">
                                <a href="{{ route('not_inprogress') }}" class="text-decoration-none">
                                    {{ $notinprogress }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">In Progress</small>
                            <div class="fw-bold fs-5">
                                <a href="{{ route('today_in_progress') }}" class="text-decoration-none">
                                    {{ $inprogressCount }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow-sm border-0 border-start border-4 border-success h-100 text-center">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="ti tabler-users me-1"></i> Employees
                        </h6>
                        <a href="{{ route('staff_table') }}" class="small fw-semibold">View</a>
                    </div>
                    <!-- <a href="{{ route('staff_table') }}" class="text-decoration-none">
                                        <h6 class="fw-bold mb-2">
                                            <i class="ti tabler-users me-1"></i> Employees
                                        </h6>            </a> -->
                    <!-- <div class="display-6 fw-bold text-success">
                                            {{ $staffCount }}
                                        </div> -->
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <small class="text-muted">Total</small>
                            <div class="fw-bold">
                                <a href="{{ route('staff_table') }}"
                                    class="text-decoration-none text-danger fs-5">{{ $staffCount }}</a>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Inactive</small>
                            <div class="fw-bold">
                                <a href="{{ route('inactive_employees') }}" class="text-decoration-none text-danger fs-5">
                                   {{ $inactiveCount }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Absent</small>
                            <div class="fw-bold">
                                <a href="{{ route('absent_staff') }}" class="text-decoration-none text-danger fs-5">
                                    {{ $absentCount }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">WFH</small>
                            <div class="fw-bold">
                                <a href="{{ route('wfh_staff') }}" class="text-decoration-none text-danger fs-5">
                                      {{ $wfhCount }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="ti tabler-briefcase me-1"></i> Total Project
                        </h6>
                        <a href="{{ route('project_table') }}" class="small fw-semibold">View</a>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <small class="text-muted">Project</small>
                            <div class="fw-bold text-danger">{{ $projectCount }}</div>
                        </div>
                        <div>
                            <small class="text-muted">Hold</small>
                            <div class="fw-bold">
                                <a href="{{ route('admin.hold_tasks') }}" class="text-decoration-none">
                                    {{ $holdcount }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Pending</small>
                            <div class="fw-bold">
                                <a href="{{ route('pending_task') }}" class="text-decoration-none">
                                    {{ $pendingCount }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ti tabler-chart-bar me-1"></i> Project Task Status</h6> <button
                        class="btn btn-sm"><i class="ti tabler-search" id="searchIcon"></i><input type="text" name="search"
                            id="searchInput" placeholder="Search projects..." class="form-control form-control-sm"></button>
                </div>
                <div class="card-body mt-3" id="projectContainer">
                    @foreach ($projects as $project)
                        {{-- <div class="border rounded p-3 mb-3"> --}}
                            <div class="border rounded p-3 mb-3 project-card" data-name="{{ strtolower($project['name']) }}">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>{{ $project['name'] }}</strong>
                                <a href="{{ route('task', $project['id']) }}" class="fw-semibold text-decoration-underline">
                                    View
                                </a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="fw-semibold text-muted">Progress</small>
                                <small class="fw-bold text-success">
                                    {{ $project['percentage'] }}%
                                </small>
                            </div>
                            <div class="progress bg-label-success" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $project['percentage'] }}%;" aria-valuenow="{{ $project['percentage'] }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <small class="fw-semibold">
                                    Tasks :
                                    <a href="{{ route('task', $project['id']) }}">
                                        {{ $project['completed'] }} / {{ $project['total'] }}
                                    </a>
                                    | Modules :
                                    <a href="{{ route('modules', $project['id']) }}">
                                        {{ $project['modules'] }}
                                    </a>
                                    | Reopened :
                                    <a href="{{ route('project_reopen', $project['id']) }}" class="text-danger">
                                        {{ $project['reopen'] }}
                                    </a>
                                </small>
                            </div>
                            <div class="d-flex justify-content-center mt-3 align-items-center mb-4">
                                <!-- Right : Actions -->
                                <div class="d-flex align-items-center gap-4">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ti tabler-bug text-danger d-none d-sm-inline"></i>
                                        <h6 class="mb-0">Total <span class="d-none d-sm-inline">Bugs</span> : &nbsp;<span
                                                class="text-danger">{{ $project['total_bugs'] }}</span></h6>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ti tabler-bug text-info d-none d-sm-inline"></i>
                                        <h6 class="mb-0">Pending <span class="d-none d-sm-inline">Bugs</span> : &nbsp;<span
                                                class="text-danger">{{ $project['pending_bugs'] }}</span></h6>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ti tabler-report text-warning d-none d-sm-inline"></i>
                                        <a href="{{ route('admin.bug_report', $project['id']) }}">
                                            <h6 class="mb-0"><span class="d-none d-sm-inline">Bug</span> Report</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ti tabler-user me-1"></i> Staff Task Status</h6> <button
                        class="btn btn-sm"><i class="ti tabler-search" id="search"></i><input type="text" name="search"
                            id="searchInp" placeholder="Search..." class="form-control form-control-sm"></button>
                </div>
                <div class="card-body mt-3" id="staffContainer">
                    @foreach ($staffTaskStatus as $staff)
                        {{-- <div class="border rounded p-3 mb-3"> --}}
                            <div class="border rounded p-3 mb-3 staff-card" data-name="{{ strtolower($staff['name']) }}">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>{{ $staff['name'] }}</strong>
                                <a href="{{ route('task_view', $staff['id']) }}"
                                    class="fw-semibold text-decoration-underline">View</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="fw-semibold text-muted"> {{ $staff['completed'] }} / {{ $staff['total'] }}</small>
                                <small class="fw-bold text-danger">
                                    {{ $staff['percentage'] }}%
                                </small>
                            </div>
                            <div class="progress bg-label-danger" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style="width: {{ $staff['percentage'] }}%;" aria-valuenow="{{ $staff['percentage'] }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <span class="fw-semibold text-center">
                                    Completed :<a href="{{ route('completed_staff', $staff['id']) }}" class="text-danger">
                                        {{ $staff['completed'] }}</a>
                                           New :<span class="text-danger">
                                        {{ $staff['new'] }} </span>|
                                    Pending :<a href="{{ route('pending_task_employee') }}" class="text-danger">
                                        {{ $staff['pending'] }}</a> |
                                    Hold : <a href="{{ route('hold_task_staff') }}" class="text-danger">
                                        {{ $staff['hold'] }}</a> |
                                    Inprogress : <a href="#"
                                        class="text-danger">{{ ($staff['inprogress'] ?? 0) > 0 ? 1 : 0 }}</a> |
                                    Reopen :
                                    <a href="{{ route('reopen', $staff['id']) }}" class="text-danger">
                                        {{ $staff['reopen'] }}
                                    </a>
                                    </small>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div>Report: <a href="{{ route('weekly_report', $staff['id']) }}" class="fw-semibold text-danger">View</a></div>
                               <div>
                                 <small class="text-end mt-2 fw-semibold">PPS :
                                    <a href="{{ route('pps_data', $staff['id']) }}" class="text-danger fw-semibold">
                                        Click to View...
                                    </a>
                                </small>
                               </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".bar").forEach(bar => {
                let g = 0;
                const percentEl = bar.closest(".d-flex").querySelector(".percent");
                const target = parseInt(percentEl.dataset.target);
                const ball = bar.querySelector(".ball");
                const hole = bar.querySelector(".hole");
                const bw = bar.offsetWidth;
                const hx = bw - hole.offsetWidth - 4;
                const interval = setInterval(() => {
                    g++;
                    const progressX = (hx * g) / 100;
                    ball.style.left = progressX + "px";
                    percentEl.textContent = g + "%";
                    if (g >= target) {
                        clearInterval(interval);
                        percentEl.textContent = target + "%";
                    }
                }, 30);
            });
            /* ---------- RED (STOP AT 80%) ---------- */
            document.querySelectorAll(".progressbar").forEach((bar) => {
                const filled = bar.querySelector(".filled");
                const goal = bar.querySelector(".goal");
                const percentage = bar.closest(".d-flex").querySelector(".percentage");
                const target = parseInt(percentage.dataset.percent); // from HTML
                const pw = bar.offsetWidth;
                let r = 0;
                const red = setInterval(() => {
                    r++;
                    filled.style.width = r + "%";
                    goal.style.left = (pw * r) / 100 + "px";
                    percentage.textContent = r + "%";
                    if (r >= target) {
                        clearInterval(red);
                        filled.style.width = target + "%";
                        percentage.textContent = target + "%";
                    }
                }, 30);
            });
        });
    </script>
    <script>
        const searchIcon = document.getElementById("searchIcon");
        const searchInput = document.getElementById("searchInput");
        searchInput.style.display = "none";
        searchIcon.addEventListener("click", function () {
            if (searchInput.style.display === "none") {
                searchInput.style.display = "block";
                searchIcon.style.display = "none";
            } else {
                searchInput.style.display = "none";
            }
        });
    </script>
    <script>
        const search = document.getElementById("search");
        const searchInp = document.getElementById("searchInp");
        searchInp.style.display = "none";
        search.addEventListener("click", function () {
            if (searchInp.style.display === "none") {
                searchInp.style.display = "block";
                search.style.display = "none";
            } else {
                searchInp.style.display = "none";
            }
        });
    </script>
<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    let search = this.value.toLowerCase();
    let projectCards = document.querySelectorAll(".project-card");
    projectCards.forEach(function(card){
        let name = card.getAttribute("data-name");
        if(name.includes(search)){
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
});
</script>
<script>
document.getElementById("searchInp").addEventListener("keyup", function () {
    let search = this.value.toLowerCase();
    let staffCards = document.querySelectorAll(".staff-card");
    staffCards.forEach(function(card){
        let name = card.getAttribute("data-name");
        if(name.includes(search)){
            card.style.display = "block";
        }else{
            card.style.display = "none";
        }
    });
});
</script>
@endsection