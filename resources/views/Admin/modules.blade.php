@extends('Admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <!-- Left side -->
    <div class="d-flex align-items-center">
        <h5 class="mb-0">
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('project_table') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
            View Module
        </h5>
    </div>
    <div class="col-auto">
        <a href="{{ route('create_task', $project->id) }}">
            <button class="btn buttons-collection btn-primary" type="button">
                <span class="d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-plus icon-xs me-sm-1"></i>
                    <span class="d-sm-inline-block">Create task</span>
                </span>
            </button>
        </a>
    </div>
</div>
<h6 class="mt-2">Project Name : {{ $project->project_name }}</h6>
<h6>Module</h6>
<div class="card p-4 mb-4">
    <h6>Create Module</h6>
    <form action="{{ route('add_module') }}" method="post" id="module_form">
        @csrf
        <input type="hidden" name="project_id" id="" value="{{ $project->id }}">
        <div class="row">
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Module Type</label>
                <select name="module_type" id="module_type" class="form-select">
                    <option value="">-- Select Module Type --</option>
                    <option value="Admin" {{ old('module_type') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="User Web" {{ old('module_type') == 'User Web' ? 'selected' : '' }}>User Web</option>
                    <option value="User App" {{ old('module_type') == 'User App' ? 'selected' : '' }}>User App</option>
                    <option value="Website" {{ old('module_type') == 'Website' ? 'selected' : '' }}>Website</option>
                </select>
                @error('module_type')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Module</label>
                <input type="text" class="form-control" placeholder="Enter Module" name="module_name"
                    value="{{ old('module_name') }}">
                @error('module_name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-lg-2 mb-1">
            <label class="form-label"></label>
            <button type="button" class="btn btn-primary" id="finalSubmit">Create
                Module</button>
        </div>
</div>
</form>
<div class="card p-2 mt-4">
    <div class="col-12 col-md-4 col-lg-3 ms-auto text-md-end text-center">
        <button id="exp" class="btn buttons-collection btn-label-secondary dropdown-toggle me-4" tabindex="0"
            aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog" aria-expanded="false"><span><span
                    class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-upload icon-xs me-sm-1"></i>
                    <span class="d-sm-inline-block">Export</span></span></span></button>
    </div>
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th class="text-nowrap">CREATED DATE</th>
                                <th class="text-nowrap">MODULE NAME</th>
                                <th class="text-nowrap">MODULE TYPE</th>
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
<!-- <div class="modal fade" id="confirmSubmit" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 px-4 py-5 text-center">
            <h5 class="fw-bold mb-2">Are you sure?</h5>
            <p class="text-muted mb-4">Do you confirm to submit this form?</p>
            <div class="d-flex justify-content-center gap-3 mt-3">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                    Yes, Sure
                </button>
            </div>
        </div>
    </div>
</div> -->
<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 px-4 py-5 text-center">
            <h5 class="fw-bold mb-2">Are you sure?</h5>
            <p class="text-muted mb-4">Do you confirm to delete this module?</p>
            <div class="d-flex justify-content-center gap-3 mt-3">
                <form action="{{ route('delete_module') }}" method="post" id="delete_form">
                    @csrf
                    <input type="hidden" name="id" id="deleteId">
                    <!-- Cancel -->
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <!-- Final submit -->
                    <button type="button" class="btn btn-primary px-4 fw-semibold" id="deleteSubmit">
                        Yes, Sure
                    </button>
                </form>
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
            ajax: "{{ route('modules_data', $project->id) }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'module_name',
                    name: 'module_name'
                },
                {
                    data: 'module_type',
                    name: 'module_type'
                },
                {
                    data: 'actions',
                    name: 'actions',
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
                searchPlaceholder: "Search"
            }
        });
    });

    document.getElementById('finalSubmit').addEventListener('click', function(e) {
        e.preventDefault();
        let btn = this;
        btn.disabled = true;
        btn.innerText = 'Processing...';
        document.getElementById('module_form').submit();
    });
    document.addEventListener("click", function(e) {
        const btn = e.target.closest(".deleteBtn");
        if (!btn) return;
        const id = btn.getAttribute("data-id");
        document.getElementById("deleteId").value = id;
    });
    // Submit button with processing text
    document.getElementById('deleteSubmit').addEventListener('click', function(e) {
        e.preventDefault();
        let btn = this;
        btn.disabled = true;
        btn.innerText = 'Processing...';
        setTimeout(() => {
            document.getElementById('delete_form').submit();
        }, 100); // allow UI to update
    });
    document.getElementById("exp").addEventListener("click", function() {
        let table = document.getElementById("dept");
        let rows = Array.from(table.querySelectorAll("tr"));
        let csv = rows.map(row => {
            let cells = Array.from(row.querySelectorAll("th, td"));
            return cells.map(cell => `"${cell.innerText}"`).join(",");
        }).join("\n");
        let blob = new Blob([csv], {
            type: "text/csv"
        });
        let url = URL.createObjectURL(blob);
        let a = document.createElement("a");
        a.href = url;
        a.download = "modules.csv"; // File name
        a.click();
        URL.revokeObjectURL(url);
    });
</script>
@endsection