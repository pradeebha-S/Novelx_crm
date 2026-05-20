@extends('Admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <!-- Left side -->
    <div class="d-flex align-items-center">
        <h5 class="mb-0">
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('admin.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
            Completed Task
        </h5>
    </div>
</div>
<div class="card p-2 mt-5">
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
                                <th>ASSIGNED STAFF</th>
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
    <div class="modal fade" id="update" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-5 text-center">
                <h5 class="fw-bold mb-2">Are you sure?</h5>
                <form action="">
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Modulessss</label>
                            <input type="text" class="form-control" value="Staff">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Task</label>
                            <input type="text" class="form-control" value="Create Staff">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Due Date</label>
                            <input type="text" class="form-control" value="12 Nov 2025">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Assign To</label>
                            <input type="text" class="form-control" value="John">
                        </div>
                    </div>
                </form>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <!-- Cancel -->
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <!-- Final submit -->
                    <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script>
    var jq = jQuery.noConflict();
    jq(document).ready(function() {
        jq('#dept').DataTable({
            processing: true,
            serverSide: true,
         ajax: "{{ route('completed_staff_data', $staff_id) }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
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
                    data: 'module',
                    name: 'module'
                },
                {
                    data: 'task',
                    name: 'task'
                },
                {
                    data: 'assigned_staff',
                    name: 'assigned_staff'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [1, 'asc']
            ],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ["10", "25", "50", "100", "All"]
            ],
            language: {
                search: "",
                searchPlaceholder: "Search",
                lengthMenu: "_MENU_"
            }
        });
    });
</script>
@endsection