@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    .card {
        border: 1px solid #f05176 !important;
    }
</style>
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div>
            <h4 class="fw-bold mb-2">
                <a href="{{'staff_table'}}" class="btn btn-icon bg-white p-2 shadow-sm" onclick="window.history.back(); return false;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
                Daily Log
            </h4>
            <small class="text-muted">
                Employee performance summary & productivity insights
            </small>
        </div>
    </div>
    <button class="btn btn-primary shadow-sm px-4">
        <i class="fas fa-file-export me-1"></i> Export Report
    </button>
</div>
<div class="card p-3 rounded-4">
    <div class="row align-items-center g-3">
        {{-- Employee --}}
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-light rounded-circle p-2 flex-shrink-0">
                    <i class="fas fa-user text-secondary"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Employee</small>
                    <span class="fw-semibold text-truncate d-block">
                        {{ $employee->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>
        {{-- Check In --}}
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-success bg-opacity-10 rounded-circle p-2 flex-shrink-0">
                    <i class="fas fa-sign-in-alt text-success"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Check In</small>
                    <span class="fw-semibold text-success">
                        {{ $checkin ?? 'Not Checked In' }}
                    </span>
                </div>
            </div>
        </div>
        {{-- Productive Hours --}}
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary bg-opacity-10 rounded-circle p-2 flex-shrink-0">
                    <i class="fas fa-clock text-primary"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Productive Hours</small>
                    <span id="productiveHours" class="fw-semibold text-primary">
                        {{ $productiveHours ?? '00:00' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Break Hours --}}

        <div class="col-6 col-md-3">

            <div class="d-flex align-items-center gap-2">
                <div class="bg-warning bg-opacity-10 rounded-circle p-2 flex-shrink-0">

                    <i class="fas fa-coffee text-warning"></i>

                </div>
                <div>

                    <small class="text-muted d-block">Break Hours</small>
                    <span class="fw-semibold text-warning">

                        {{ $totalBreakHours ?? '00:00:00' }}

                    </span>

                </div>
            </div>

        </div>
        {{-- Check Out --}}
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-danger bg-opacity-10 rounded-circle p-2 flex-shrink-0">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Check Out</small>
                    <span class="fw-semibold text-danger">
                        {{ $checkOut ?? 'Not Checked Out' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-header border-0 pt-4 pb-2 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-semibold mb-0">
                <i class="fas fa-tasks text-primary me-2"></i>
                Task Details
            </h5>
            <span class="badge bg-primary-subtle text-primary px-3 py-2">
                {{ $report->unique('task')->count() }} Tasks
            </span>
        </div>
        <hr>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>SNO</th>
                        <th>Project</th>
                        <th>Task</th>
                        <th>Estimated Time</th>

                        <th>Start&nbsp;Time</th>
                        <th>End&nbsp;Time</th>
                        <th>Time&nbsp;Taken</th>
                        <th>Extra Time</th>
                        <th>Status</th>
                        <th>View Task</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report as $index => $row)
                    <tr>
                        <td class="fw-semibold text-muted">
                            {{ $index + 1 }}
                        </td>
                        <td class="text-nowrap">
                            <span class="fw-semibold">
                                {{ $row['project'] }}
                            </span>
                        </td>
                        <td class="text-muted text-nowrap">
                            {{ $row['task'] }}
                        </td>
                        <td>{{ $row['estimated_time'] }}</td>

                        <td class="text-nowrap">
                            <i class="fas fa-clock text-secondary me-1"></i>
                            {{ $row['start_time'] }}
                        </td>
                        <td class="text-nowrap">
                            <i class="fas fa-clock text-secondary me-1"></i>
                            {{ $row['end_time'] }}
                        </td>
                        <td class="text-nowrap">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                {{ $row['working_hours'] }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">{{ $row['extra_time'] }}</span>
                        </td>
                        <td class="text-nowrap">
                            @switch($row['status'])
                            @case('not_assigned')
                            <span class="badge bg-label-secondary">
                                <i class="fas fa-user-slash me-1"></i>
                                Not Assigned
                            </span>
                            @break
                            @case('new')
                            <span class="badge bg-label-info">
                                <i class="fas fa-hourglass-start me-1"></i>
                                Not Started
                            </span>
                            @break
                            @case('inprogress')
                            <span class="badge bg-label-warning">
                                <i class="fas fa-spinner me-1"></i>
                                In Progress
                            </span>
                            @break
                            @case('complete')
                            <span class="badge bg-label-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Completed
                            </span>
                            @break
                            @case('hold')
                            <span class="badge bg-danger">
                                <i class="fas fa-pause-circle me-1"></i>
                                Hold
                            </span>
                            @break
                            @default
                            <span class="badge bg-dark">-</span>
                            @endswitch
                        </td>
                        <td>

                            <a href="{{ url('admin/task_description/'.$row['task_id']) }}"

                                class="btn btn-sm btn-primary">

                                <i class="fas fa-eye"></i> View

                            </a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-header">
        <h5 class="fw-semibold">
            <i class="fas fa-coffee text-warning me-2"></i>
            Break History
        </h5>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>SNO</th>
                    <th>Break Start</th>
                    <th>Break End</th>
                    <th>Break Duration</th>
                </tr>
            </thead>

            <tbody>

                @forelse($breaks as $index => $break)

                @php
                $start = \Carbon\Carbon::parse($break->break_start_time);
                $end = \Carbon\Carbon::parse($break->break_end_time);

                $duration = $break->break_end_time
                ? $start->diff($end)->format('%H:%I:%S')
                : '-';
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ $break->break_start_time ?? '-' }}
                    </td>

                    <td>
                        {{ $break->break_end_time ?? '-' }}
                    </td>

                    <td>
                        {{ $duration }}
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No Break Records Found
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>
</div>
</div>
<script>
    let minutes = {

        {

            $totalMinutes ?? 0

        }

    };
    let hasCheckout = "{{ $checkOut }}" !== "";
    if (!hasCheckout) {
        setInterval(function() {
            minutes++; // add 1 minute
            let hrs = Math.floor(minutes / 60);
            let mins = minutes % 60;
            document.getElementById('productiveHours').innerText =
                String(hrs).padStart(2, '0') + ":" +
                String(mins).padStart(2, '0');
        }, 60000); // every 1 minute
    }
</script>
@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>