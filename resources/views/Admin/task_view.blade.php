@extends('Admin.layout')

<style>
    /* =========================================
   TASK SUMMARY SECTION
========================================= */

    .task-summary-wrapper {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        justify-content: center;
    }

    /* =========================================
   MINI TASK CARD
========================================= */

    .mini-task-card {
        min-width: 150px;
        padding: 14px 18px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ffffff;
        border: 1px solid rgba(229, 231, 235, 0.9);
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease;
    }

    .mini-task-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
    }

    .mini-task-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .mini-task-title {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 2px;
        letter-spacing: 0.3px;
    }

    .mini-task-count {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    /* =========================================
   CARD COLORS
========================================= */

    /* Pending */
    .pending-task {
        border-left: 4px solid #f59e0b !important;
    }

    .pending-task .mini-task-icon {
        background: rgba(245, 158, 11, 0.12);
        color: #f59e0b;
    }

    /* Hold */
    .hold-task {
        border-left: 4px solid #6366f1 !important;
    }

    .hold-task .mini-task-icon {
        background: rgba(99, 102, 241, 0.12);
        color: #6366f1;
    }

    /* Completed */
    .completed-task {
        border-left: 4px solid #10b981 !important;
    }

    .completed-task .mini-task-icon {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }

    /* Reopen */
    .reopen-task {
        border-left: 4px solid #ef4444 !important;
    }

    .reopen-task .mini-task-icon {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
    }

    /* =========================================
   DARK MODE SUPPORT
========================================= */

    [data-bs-theme="dark"] .mini-task-card,
    .dark-layout .mini-task-card,
    .dark-style .mini-task-card {
        background: #1f2937;
        border-color: rgba(255, 255, 255, 0.06);
        box-shadow: none;
    }

    [data-bs-theme="dark"] .mini-task-title,
    .dark-layout .mini-task-title,
    .dark-style .mini-task-title {
        color: #9ca3af;
    }

    [data-bs-theme="dark"] .mini-task-count,
    .dark-layout .mini-task-count,
    .dark-style .mini-task-count {
        color: #f9fafb;
    }

    [data-bs-theme="dark"] .export-btn,
    .dark-layout .export-btn,
    .dark-style .export-btn {
        border-color: rgba(255, 255, 255, 0.12);
        color: #e5e7eb;
    }

    /* =========================================
   RESPONSIVE DESIGN
========================================= */

    @media (max-width: 1200px) {

        .task-summary-wrapper {
            justify-content: flex-start;
        }
    }

    @media (max-width: 991px) {

        .task-summary-wrapper {
            width: 100%;
            justify-content: center;
        }

        .mini-task-card {
            flex: 1 1 calc(50% - 10px);
            min-width: 160px;
        }
    }

    @media (max-width: 576px) {

        .card-header {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .task-summary-wrapper {
            gap: 10px;
        }

        .mini-task-card {
            width: 100%;
            min-width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
        }

        .mini-task-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .mini-task-count {
            font-size: 18px;
        }

        .export-btn {
            width: 100%;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                <a href="{{ route('staff_table') }}">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                        <path d="M15 6l-6 6l6 6" />

                    </svg>

                </a>

            </button>

            <div>
                <h4 class="mb-0 fw-bold">View Tasks</h4>
                <small class="text-muted">Manage and monitor staff tasks</small>
            </div>
        </div>

        <a href="{{ route('project_table') }}" class="btn btn-primary shadow-sm px-4">
            <i class="ti tabler-plus me-1"></i> Create Task
        </a>

    </div>


    <!-- ================= FILTER CARD ================= -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">

            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" id="from_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" id="to_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="Not Started">Not Started</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Hold">Hold</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button id="filterBtn" class="btn btn-primary w-100">
                        <i class="ti tabler-filter me-1"></i> Filter
                    </button>
                    <button id="resetBtn" class="btn btn-outline-secondary w-100">
                        Reset
                    </button>
                </div>

            </div>

        </div>
    </div>


    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-header border-0 pt-4 pb-2 px-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <!-- LEFT -->
                <div class="employee-info">
                    <h5 class="fw-semibold mb-1 text-body">
                        {{ $staffName ?? 'N/A' }}
                    </h5>
                    <small class="text-muted">Task Overview</small>
                </div>

                <!-- CENTER TASK CARDS -->
              <div class="task-summary-wrapper">

    <!-- Pending -->
    <div class="mini-task-card pending-task">
        <div class="mini-task-icon">
            <i class="ti tabler-clock-hour-4"></i>
        </div>

        <div>
            <span class="mini-task-title">Pending</span>
            <h6 class="mini-task-count mb-0">
                {{ $pending_tasks_count }}
            </h6>
        </div>
    </div>

    <!-- Hold -->
    <div class="mini-task-card hold-task">
        <div class="mini-task-icon">
            <i class="ti tabler-player-pause"></i>
        </div>

        <div>
            <span class="mini-task-title">Hold</span>
            <h6 class="mini-task-count mb-0">
                {{ $hold_tasks_count }}
            </h6>
        </div>
    </div>

    <!-- Completed -->
    <div class="mini-task-card completed-task">
        <div class="mini-task-icon">
            <i class="ti tabler-check"></i>
        </div>

        <div>
            <span class="mini-task-title">Completed</span>
            <h6 class="mini-task-count mb-0">
                {{ $completed_tasks_count }}
            </h6>
        </div>
    </div>

    <!-- In Progress -->
    <div class="mini-task-card reopen-task">
        <div class="mini-task-icon">
            <i class="ti tabler-loader"></i>
        </div>

        <div>
            <span class="mini-task-title">In Progress</span>
            <h6 class="mini-task-count mb-0">
                {{ $inprogressTasks }}
            </h6>
        </div>
    </div>

    <!-- Reopen -->
    <div class="mini-task-card reopen-task">
        <div class="mini-task-icon">
            <i class="ti tabler-refresh"></i>
        </div>

        <div>
            <span class="mini-task-title">Reopen</span>
            <h6 class="mini-task-count mb-0">
                {{ $reopen_tasks_count }}
            </h6>
        </div>
    </div>

</div>

                <!-- RIGHT -->
                <div>
                    <button id="exp" class="btn btn-outline-secondary export-btn">
                        <i class="ti tabler-upload me-1"></i> Export
                    </button>
                </div>

            </div>

            <hr class="mt-4">

        </div>


        <div class="card-body pt-0">

            <div class="table-responsive">
                <table id="dept" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>SNO</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Project</th>
                            <th>Module</th>
                            <th>Task</th>
                            <th>EH</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>




    <!-- ================= VERIFY MODAL ================= -->
    <div class="modal fade" id="TestStatusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow">

                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="ti tabler-circle-check text-success fs-1"></i>
                    </div>

                    <h5 class="fw-bold">Verify Task</h5>
                    <p class="text-muted">Are you sure you want to complete this task?</p>

                    <form id="verifyTaskForm" action="{{ route('verify_test_status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" id="testTaskId">
                        <input type="hidden" name="test_status" value="complete">

                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="button" id="verifySubmitBtn" class="btn btn-success px-4">
                                Yes
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>


    <!-- ================= REOPEN MODAL ================= -->
    <div class="modal fade" id="reopenStatusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Reopen Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form action="{{ route('submit_reopen_status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" id="task_id">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="reopen_type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="bug">Bug</option>
                                <option value="update">Update</option>
                                <option value="cr">CR</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remark</label>
                            <textarea name="remark" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                Update
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

    <script>
        var jq = jQuery.noConflict();



        jq(document).ready(function () {



            var table = jq('#dept').DataTable({

                processing: true,

                serverSide: true,

                ajax: {
                    url: "{{ route('task_view_data', $staffId) }}",
                    data: function (d) {
                        d.status = jq('#statusFilter').val();
                        d.from_date = jq('#from_date').val();
                        d.to_date = jq('#to_date').val();
                    }
                },


                columns: [

                    {
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
                        data: 'estimated_time'
                    },

                    {
                        data: 'status'
                    },

                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],

                order: [
                    [1, 'desc']
                ],

                lengthMenu: [

                    [10, 25, 50, 100, -1],

                    ["10", "25", "50", "100", "All"]

                ],

                language: {

                    search: "",

                    searchPlaceholder: "Search"

                }

            });


            jq('#statusFilter').on('change', function () {

                table.ajax.reload();

            });
            jq('#filterBtn').on('click', function () {
                table.ajax.reload();
            });

            jq('#resetBtn').on('click', function () {
                jq('#from_date').val('');
                jq('#to_date').val('');
                jq('#statusFilter').val('');
                table.ajax.reload();
            });




            if (document.querySelector('#task_description')) {

                ClassicEditor

                    .create(document.querySelector('#task_description'))

                    .catch(error => {

                        console.error(error);

                    });

            }



        });



        document.addEventListener('DOMContentLoaded', function () {



            const testStatusModal = document.getElementById('TestStatusModal');

            const submitBtn = document.getElementById('verifySubmitBtn');

            const form = document.getElementById('verifyTaskForm');



            testStatusModal.addEventListener('show.bs.modal', function (event) {

                const button = event.relatedTarget;

                const taskId = button.getAttribute('data-id');

                document.getElementById('testTaskId').value = taskId;

            });



            submitBtn.addEventListener('click', function (e) {

                e.preventDefault();

                submitBtn.disabled = true;

                submitBtn.innerText = 'Processing...';

                form.submit();

            });



            const reopenModal = document.getElementById('reopenStatusModal');



            reopenModal.addEventListener('show.bs.modal', function (event) {

                const button = event.relatedTarget;

                const taskId = button.getAttribute('data-user-id');

                document.getElementById('task_id').value = taskId;

            });



        });
    </script>
@endsection