@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3 gap-3">

        <!-- LEFT SIDE: Back + Title + Week Selector -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 gap-md-3 mb-2">

            <!-- Back + Title -->
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <a href="{{ route('intern_table') }}" class="btn btn-icon bg-white p-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
                Tasks
            </h5>

           
        </div>

        <!-- RIGHT SIDE: Create Task Button -->
        <!-- <div>
            <a href="{{ route('course') }}">
                <button class="btn btn-primary d-flex align-items-center gap-2" type="button">
                    <i class="icon-base ti tabler-plus icon-xs"></i>
                    <span>View Course</span>
                </button>
            </a>
        </div> -->
    </div>
  

    <div class="card p-2 mt-5">
       

        <div class="card-datatable table-responsive pt-0">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
                <div class="d-flex justify-content-between align-items-center dt-layout-table ">
                    <div>
                        <h4>{{ $studentName ?? 'N/A' }}</h4>
                    </div>

                    <div>
                        <select id="statusFilter" class="form-select w-auto">
                            <option value="">All Status</option>
                            <option value="Not Started">Not Started</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Hold">Hold</option>
                        </select>
                    </div>
                </div>
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th>Course</th>
                                <th>Topic</th>
                                <th>Chapter</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- //test status modal -->
    <div class="modal fade" id="TestStatusModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-5 text-center">
                <h5 class="fw-bold mb-3">Verify task</h5>
                <p> Are you sure you want to complete this task?</p>
                <form id="verifyTaskForm" action="{{ route('verify_test_status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" id="testTaskId">
                    <input type="hidden" name="test_status" value="complete">
                    <div class="d-flex justify-content-center gap-3">


                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
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

    <div class="modal fade" id="reopenStatusModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-5 text-center">
                <h5 class="fw-bold mb-2">Reopen Task </h5>
                <form action="{{ route('submit_reopen_status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" id="task_id">

                    <div class="col-lg-12">
                        <label class="form-label text-start d-block">Status</label>
                        <select name="reopen_type" class="form-select" required>
                            <option value="">Select</option>
                            <option value="bug">Bug</option>
                            <option value="update">Update</option>
                            <option value="cr">CR</option>
                        </select>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label class="form-label text-start d-block">Remark</label>
                        <textarea name="remark" class="form-control" rows="3" placeholder="Enter remark"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
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
                    url: "{{ route('student_task_view_data', $studentId) }}",
                    data: function (d) {
                        d.status = jq('#statusFilter').val();

                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                   
                    { data: 'course' },
                    { data: 'topic' },
                    { data: 'chapter' },
                    { data: 'status' },
                ],
                order: [[1, 'desc']],
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

           

        });

        
    </script>
   


@endsection