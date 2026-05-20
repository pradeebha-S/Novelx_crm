@extends('Staff.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    .link {
        text-decoration: underline;
    }
    .typing-text {
    display: inline-block;
    overflow: hidden;
    white-space: nowrap;
    border-right: 2px solid #dc3545;
    width: 0;
    animation: typing 6s steps(90, end) forwards, blink 0.7s infinite;
}
@keyframes typing {
    from { width: 0; }
    to { width: 100%; }
}
@keyframes blink {
    50% { border-color: transparent; }
}
</style>
@section('content')
<div class="row align-items-center justify-content-between mb-3">
    <!-- Heading -->
    <div class="col-auto d-flex align-items-center gap-2">

    <!-- Back Button -->
    <button type="button" class="btn btn-icon bg-white waves-effect me-2"
        style="box-shadow: 0px 9px 12px -2px #66328E1F;">

        <a href="{{ route('staff_task') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none"
                stroke="#000" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M15 6l-6 6l6 6" />
            </svg>
        </a>

    </button>

    <!-- Title -->
    <h5 class="mb-0">Testing Completed Tasks</h5>

    <!-- ✅ NEW BUTTON -->
    

</div>
</div>
<div class="card p-4">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th class="text-nowrap">Start Date</th>
                                <th class="text-nowrap">Due Date</th>
                                <th>PROJECT</th>
                                <th>MODULE</th>
                                <th>TASK</th>
                                <th>Developer Name</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="startTaskModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 px-4 py-5 text-center">
            <h5 class="fw-bold mb-3">Start Task</h5>
            <p>Are you sure you want to start this task?</p>
            <form action="{{ route('update_task_status') }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" id="startTaskId">
                <input type="hidden" name="action" value="start">
                <button type="submit" class="btn btn-success">Start</button>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 px-4 py-5 text-center">
            <h5 class="fw-bold mb-2">Update Task Status</h5>
            <form action="{{ route('update_task_status') }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" id="statusTaskId" value="{{ old('task_id') }}">
                <input type="hidden" name="action" value="update">
                <!-- Status -->
                <div class="col-lg-12">
                    <label class="form-label text-start d-block">Status</label>
                    <select name="task_status" id="taskStatus"
                        class="form-select @error('task_status') is-invalid @enderror" required>
                        <option value="">Select</option>
                        <option value="complete" {{ old('task_status') == 'complete' ? 'selected' : '' }}>
                            Complete
                        </option>
                        <option value="hold" {{ old('task_status') == 'hold' ? 'selected' : '' }}>
                            Hold
                        </option>
                        <option value="reassign" {{ old('task_status') == 'reassign' ? 'selected' : '' }}>
                            ReAssign
                        </option>
                    </select>
                    @error('task_status')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Complete fields -->
                <div id="completeHoldFields" class="col-lg-12 mt-3" style="display:none;">
                    <!-- Spending Hours -->
                    {{-- <label class="form-label text-start d-block">Spending Hours</label>
                    <input type="text"
                        name="spending_hour"
                        value="0.00"
                        class="form-control @error('spending_hour') is-invalid @enderror">
                    @error('spending_hour')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror --}}
                    <!-- Checklist/Remark -->
                     <span class="text-danger fw-semibold small typing-text mt-3">
Kindly provide details of what has been completed in this task before placing it on hold.</span>
                    <label class="form-label text-start d-block mt-3">Checklist/Remark</label>
                    <textarea name="remark"
                        class="form-control @error('remark') is-invalid @enderror"
                        rows="3">{{ old('remark') }}</textarea>
                    @error('remark')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                 <div>
                    
                 </div>
                <!-- Reassign fields -->
                <div id="reassignFields" class="col-lg-12 mt-3" style="display:none;">
                    <!-- Assign To -->
                    <label class="form-label text-start d-block">Assign To</label>
                    <select class="form-select" name="assign_to">
                        <option value="">Select Staff</option>
                        @foreach ($staffs as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                    @error('assign_to')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <!-- Spending Hours -->
                    <label class="form-label text-start d-block mt-3">Spending Hours</label>
                    <input type="text"
                        name="spending_hour"
                        value="{{ old('spending_hour') }}"
                        class="form-control @error('spending_hour') is-invalid @enderror">
                    @error('spending_hour')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <!-- Checklist/Remark -->
                    <label class="form-label text-start d-block mt-3">Reason</label>
                    <textarea name="remark"
                        class="form-control @error('remark') is-invalid @enderror"
                        rows="3">{{ old('remark') }}</textarea>
                    @error('remark')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script>
    $(function() {
        $('#dept').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('testing_task_table_data') }}",
            language: {
                search: "",
                searchPlaceholder: "Search Project",
                lengthMenu: "_MENU_"
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ["10", "25", "50", "100", "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'start_date'
                },
                {
                    data: 'due_date'
                },
                {
                    data: 'project'
                },
                {
                    data: 'module'
                },
                {
                    data: 'task'
                },
             {
    data: 'developer_name',
    orderable: false,
    searchable: false
},
                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $(document).on('click', '.startTaskBtn', function() {
            $('#startTaskId').val($(this).data('id'));
        });
        $(document).on('click', '.openStatusModal', function() {
            $('#statusTaskId').val($(this).data('id'));
        });
    });
</script>
<script>
    $(document).ready(function() {
        function disableAllFields() {
            $('#completeHoldFields').find('input, textarea').prop('disabled', true);
            $('#reassignFields').find('input, textarea, select').prop('disabled', true);
        }
        function toggleFields(status) {
            disableAllFields();
            if (status === 'complete' || status === 'hold') {
                $('#completeHoldFields').show();
                $('#reassignFields').hide();
                $('#completeHoldFields').find('input, textarea').prop('disabled', false);
            } else if (status === 'reassign') {
                $('#completeHoldFields').hide();
                $('#reassignFields').show();
                $('#reassignFields').find('input, textarea, select').prop('disabled', false);
            } else {
                $('#completeHoldFields').hide();
                $('#reassignFields').hide();
            }
        }
        $('#taskStatus').on('change', function() {
            toggleFields($(this).val());
        });
        toggleFields($('#taskStatus').val());
    });
</script>
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
        modal.show();
    });
</script>
@endif
@endsection