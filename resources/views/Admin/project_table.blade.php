@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    .link {
        text-decoration: underline;
    }
</style>
@section('content')
    <div class="row align-items-center justify-content-between mb-3">
        <!-- Heading -->
        <div class="col-auto">
            <h5>Project Management</h5>
        </div>
        <!-- Button -->
        <div class="col-auto">
            <a href="{{ route('create_project') }}"> <button class="btn buttons-collection btn-primary" type="button"
                    aria-haspopup="dialog" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                        <i class="icon-base ti tabler-plus icon-xs me-sm-1"></i>
                        <span class="d-sm-inline-block">Create Project</span>
                    </span>
                </button></a>
                 <a href="{{ route('bill_table') }}"> <button class="btn buttons-collection btn-primary" type="button"
                    aria-haspopup="dialog" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                        <span class="d-sm-inline-block">Invoice</span>
                    </span>
                </button></a>
               <a href="{{ route('view_doc') }}"> <button class="btn buttons-collection btn-primary" type="button"
                    aria-haspopup="dialog" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                        <span class="d-sm-inline-block">Document</span>
                    </span>
                </button></a>
                <a href="{{ route('view_credentials') }}"> <button class="btn buttons-collection btn-primary" type="button"
                    aria-haspopup="dialog" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                        <span class="d-sm-inline-block">Credentials</span>
                    </span>
                </button></a>
                 <a href="{{ route('monthly_report') }}"> <button class="btn buttons-collection btn-primary" type="button">
                    <span class="d-flex align-items-center gap-2">
                        <span class="d-sm-inline-block">Monthly Report</span>
                    </span>
                </button></a>
        </div>
    </div>
    <div class="card p-4">
        <div class="card-datatable table-responsive pt-0">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table id="dept" class="table">
                            <thead>
                                <tr>
                                    <th>SNO</th>
                                    <th class="text-nowrap">PROJECT NAME</th>
                                    <th class="text-nowrap">OVER ALL</th>
                                    <th>PENDING</th>
                                    <th>BUGS</th>
                                      <th>TESTER</th>
                                    <th>MODULE</th>
                                    <th>TASK</th>
                                    <th>LINKS</th>
                                    <th>Invoice</th>
                                    <th>Document</th>
                                    <th>Credentials</th>
                                    <th class="text-nowrap">CLIENT NAME</th>
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
    <div class="modal fade" id="delete" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 text-center p-4 py-5">
                <h5 class="fw-bold mb-2">Are you sure!!</h5>
                <p class="text-muted">Are you confirm to delete?</p>
                <form id="deleteForm" method="POST" action="{{ route('delete_project') }}">
                    @csrf
                    <input type="hidden" name="id" id="deleteId">
                    <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                        <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger p-3 ms-2 fw-semibold" id="finalSubmit">
                            Yes, Sure
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="testerModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-4">

            <h5 class="mb-3">Assign Tester</h5>

            <form action="{{ route('assign_tester') }}" method="POST">
                @csrf

<input type="hidden" name="project_id" id="testerProjectId">
                <label>Select Tester</label>
                <select name="tester_id" class="form-select mt-2" required>
                    <option value="">Select Tester</option>

                    @foreach($testers as $tester)
                        <option value="{{ $tester->id }}">
                            {{ $tester->name }}
                        </option>
                    @endforeach

                </select>

                <button class="btn btn-primary mt-3 w-100">
                    Save
                </button>

            </form>

        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script>
        var jq = jQuery.noConflict();
        jq(document).ready(function () {
            var table = jq('#dept').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('project_table_data') }}",
                columnDefs: [
                    {
                        targets: '_all',
                        className: 'text-nowrap'
                    }
                ],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'project_name',
                        name: 'project_name'
                    },
                    {
                        data: 'tasks_count',
                        searchable: false
                    },
                    {
                        data: 'pending_tasks',
                        searchable: false
                    },
                    {
                        data: 'bugs',
                        searchable: false
                    },
                    {
    data: 'tester',
    orderable: false,
    searchable: false
},
                    {
                        data: 'modules',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tasks',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'links',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data:'invoice'
                    },
                    {
                        data: 'doc'
                    },
                    {
                        data: 'credentials'
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[1, 'desc']],
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
    <script>
        jq(document).on('click', '.deleteBtn', function () {
            var id = jq(this).data('id');
            jq('#deleteId').val(id);
        });
        
    </script>
  <script>
var jq = jQuery.noConflict();

jq(document).on('click', '.openTesterModal', function () {
    let projectId = jq(this).attr('data-id');

    console.log("Project ID:", projectId);

    jq('#testerProjectId').val(projectId);
});

// optional safety reset
jq('#testerModal').on('hidden.bs.modal', function () {
    jq('#testerProjectId').val('');
});
</script>
@endsection