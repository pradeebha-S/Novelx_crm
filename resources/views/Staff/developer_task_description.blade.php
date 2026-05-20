@extends('Staff.layout')

<style>
    .task-log-row {

        min-height: 80px;

    }

    .col-date {

        width: 20%;

        font-weight: 500;

    }

    .col-type {

        width: 20%;

        text-align: center;

        font-weight: 500;

    }

    .col-time {

        width: 15%;

        font-weight: 500;

    }

    .col-remark {

        width: 60%;

        font-size: 14px;

        line-height: 1.5;

        padding-right: 6px;

        word-break: break-word;

    }

    .col-remark::-webkit-scrollbar {

        width: 4px;

    }

    .col-remark::-webkit-scrollbar-thumb {

        background: #ccc;

        border-radius: 4px;

    }
</style>

@section('content')

<div class="col-auto">

    <h5>

        <button type="button" class="btn btn-icon bg-white waves-effect me-2"

            style="box-shadow: 0px 9px 12px -2px #66328E1F;">

            <a href="{{ route('developer_completed_task') }}"> <svg xmlns="http://www.w3.org/2000/svg"

                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"

                    stroke-linecap="round" stroke-linejoin="round">

                    <path stroke="none" d="M0 0h24v24H0z" />

                    <path d="M15 6l-6 6l6 6" />

                </svg>

            </a>

        </button>

        Task Details

    </h5>

</div>

<div class="row align-items-stretch mb-4 g-2">

    <!-- Reopen -->

    <div class="col-lg-3 col-md-6 d-flex">

        <div class="card shadow-sm border-2 rounded-3 p-3 h-100 w-100">

            <div class="row align-items-center">

                <div class="col-6">

                    <h6 class="mb-0">Reopen</h6>

                </div>

                <div class="col-6 d-flex justify-content-between align-items-center">

                    <p class="mb-0">{{ $task->reopen_count }}</p>

                    <a href="{{ route('view_reopen_history', ['task_id' => $task->id]) }}"

                        class="fw-bold text-decoration-none">

                        View

                    </a>

                </div>

            </div>

            <div class="mt-auto text-center pt-3">

                <a href="#" class="text-danger fw-bold" data-bs-toggle="modal" data-bs-target="#reopenStatusModal"

                    data-task-id="{{ $task->id }}">

                    Add Reopen

                </a>

            </div>

        </div>

    </div>

    <!-- Hold -->

    <div class="col-lg-3 col-md-6 d-flex">

        <div class="card shadow-sm border-2 rounded-3 p-3 h-100 w-100">

            <div class="row mt-5 align-items-center">

                <div class="col-6">

                    <h6 class="mb-0">Hold</h6>

                </div>

                <div class="col-6 d-flex justify-content-between align-items-center">

                    <p class="mb-0">{{ $task->hold_count }}</p>

                    <a href="{{ route('view_hold_history', ['task_id' => $task->id]) }}"

                        class="fw-bold text-decoration-none">

                        View

                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- Status -->

    <div class="col-lg-3 col-md-6 d-flex">

        <div class="card shadow-sm border-2 rounded-3 p-3 h-100 w-100">

            <div class="row mt-5 align-items-center">

                <div class="col-6">

                    <h6 class="mb-0">Status</h6>

                </div>

                <div class="col-6 d-flex justify-content-end align-items-center">

                    <p class="mb-0 text-danger">{{ ucfirst($task->task_status) }}</p>

                </div>

            </div>

        </div>

    </div>

    <!-- Test Status -->

    <div class="col-lg-3 col-md-6 d-flex">

        <div class="card shadow-sm border-2 rounded-3 p-3 h-100 w-100">

            <div class="row mt-5 align-items-center">

                <div class="col-6">

                    <h6 class="mb-0">Test Status</h6>

                </div>

                <div class="col-6 d-flex justify-content-end align-items-center">

                    <a class="text-danger" data-task-id="{{ $task->id }}" data-bs-toggle="modal"

                        data-bs-target="#TestStatusModal">

                        {{ $task->test_status === 'complete' ? 'Complete' : 'In Progress' }}

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row g-2">

    <div class="col-lg-6">

        <div class="card shadow-sm border-2 rounded-3 p-3">

            <h6 class="mt-2">Project : {{ $task->project->project_name }}</h6>

            <h6>Task Description</h6>

            <div class="text-muted">

                {!! $task->task_description !!}

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card shadow-sm border-2 rounded-3 p-2 h-100 overflow-auto" style="max-height:403px;">

            <div class="card-body">

                <p class="text-black mb-2">Task Log</p>

                <!-- Header -->

                <div class="border rounded-1 p-2 w-100 d-flex align-items-start mt-2">

                    <div class="col-date fw-bold">Date</div>

                    <div class="col-type fw-bold">Type</div>

                    <div class="col-time fw-bold">SH</div>

                    <div class="col-remark fw-bold">Remark</div>

                </div>

                <!-- Rows -->

                @foreach ($task->histories as $history)

                <div class="task-log-row border rounded-1 p-3 d-flex align-items-start mt-2">

                    <div class="col-date">

                        {{ $history->created_at->format('d/m/Y h:i A') }}

                    </div>

                    <div class="col-type">

                        {{ ucfirst($history->status) }}

                    </div>

                    <div class="col-time">

                        {{ ucfirst($history->spending_hour ?? '-') }}

                    </div>

                    <div class="col-remark">

                        @if($history->status == 'reopen')
                        <span class="text-danger fw-semibold">
                            {{ $history->reopen_type }}-
                        </span>
                        {{ $history->remark }}



                        @else

                        {{ $history->display_remark ?? '-' }}

                        @endif

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

</div>

{{-- Test Status Modal --}}

<!-- <div class="modal fade" id="TestStatusModal" tabindex="-1">

    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content rounded-4 px-4 py-5 text-center">

            <h5 class="fw-bold mb-3">Verify task</h5>

            <p>Are you sure you want to complete this task?</p>

            <form id="verifyTaskForm" action="{{ route('task_verify_test_status') }}" method="POST">

                @csrf

                <input type="hidden" name="task_id" id="testTaskId">

                <input type="hidden" name="test_status" value="complete">

                <div class="d-flex justify-content-center gap-3">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                    <button type="button" id="verifySubmitBtn" class="btn btn-success">Yes</button>

                </div>

            </form>

        </div>

    </div>

</div> -->
<div class="modal fade" id="TestStatusModal" tabindex="-1">

    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content rounded-4 px-4 py-5 text-center">

            <h5 class="fw-bold mb-3">Verify task</h5>

            <p>Are you sure you want to complete this task?</p>

            <form id="verifyTaskForm" action="{{ route('task_verify_test_status') }}" method="POST">

                @csrf

                <input type="hidden" name="task_id" id="testTaskId">
                <input type="hidden" name="test_status" value="complete">

                <!-- ✅ REMARK FIELD ADDED -->
                <div class="mb-3 text-start">
                    <label class="form-label">Remark</label>
                    <textarea name="remark" class="form-control" rows="3" placeholder="Enter remark..." required></textarea>
                </div>

                <div class="d-flex justify-content-center gap-3">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="verifySubmitBtn" class="btn btn-success">
                        Yes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Reopen Modal --}}

<div class="modal fade" id="reopenStatusModal" tabindex="-1">

    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content rounded-4 px-4 py-5 text-center">

            <h5 class="fw-bold mb-2">Reopen Task</h5>

            <form action="{{ route('task_submit_reopen_status') }}" method="POST" id="modelform">

                @csrf

                <input type="hidden" name="task_id" id="task_id">

                <div class="mb-3 text-start">

                    <label class="form-label">Status</label>

                    <select name="reopen_type" class="form-select" required>

                        <option value="">Select</option>

                        <option value="bug">Bug</option>

                        <option value="update">Update</option>

                        <option value="cr">CR</option>

                    </select>

                </div>

                <div class="mb-3 text-start">

                    <label class="form-label">Remark</label>

                    <textarea name="remark" class="form-control" rows="3"></textarea>

                </div>

                <button type="submit" class="btn btn-primary" id="finalsubmit">Update</button>

            </form>

        </div>

    </div>

</div>

<script>
    document.getElementById('reopenStatusModal').addEventListener('show.bs.modal', function(e) {

        document.getElementById('task_id').value = e.relatedTarget.dataset.taskId;

    });

    document.getElementById('TestStatusModal').addEventListener('show.bs.modal', function(e) {

        document.getElementById('testTaskId').value = e.relatedTarget.dataset.taskId;

    });

    document.getElementById('verifySubmitBtn').addEventListener('click', function() {

        this.disabled = true;

        this.innerText = 'Processing...';

        document.getElementById('verifyTaskForm').submit();

    });
</script>

<script>
    document.getElementById('finalsubmit').addEventListener('click', function() {

        this.disabled = true;

        this.innerText = 'Processing...';

        document.getElementById('modelform').submit();

    });
</script>

@endsection