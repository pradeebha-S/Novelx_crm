@extends('Intern.layout')

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
            <a href="javascript:void(0)" onclick="history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" />
                    <path d="M15 6l-6 6l6 6" />
                </svg>
            </a>
        </button>
        Task Details
    </h5>
</div>



<div class="row g-2">

    <div class="col-lg-6">
        <div class="card shadow-sm border-2 rounded-3 p-3">
            <h5>{{ $task->chapter->chapter_name ?? 'No Chapter' }}</h5>

            <h6>Description:</h6>
            <p class="text-muted">{{ $task->chapter->description ?? '-' }}</p>
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
                    <div class="col-remark fw-bold">Remark</div>
                </div>

                <!-- Rows -->
                @foreach($task->history as $log)

                <div class="task-log-row border rounded-1 p-3 d-flex align-items-start mt-2">
                    <div class="col-date">{{ $log->created_at->format('d/m/Y H:i') }}</div>
                    <div class="col-type text-center">{{ ucfirst($log->status) }}</div>
                    <div class="col-remark">{{ $log->remark ?? '-' }}</div>
                </div>
                @endforeach

            </div>
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