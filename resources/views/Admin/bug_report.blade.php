@extends('Admin.layout')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<style>
    .dt-search {
        display: none !important;
    }
    #toast-container>.toast-success {
        background-color: #51A351 !important;
        color: #fff !important;
    }
    #toast-container>.toast-error {
        background-color: #BD362F !important;
        color: #fff !important;
    }
</style>
@section('content')
    <div class="row align-items-center justify-content-between mb-1 mt-0">
        <!-- Heading -->
        <div class="col-auto">
            <h5 class="mb-0"> <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('admin.dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>Bug Report</h5>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.add_bug', $project_id) }}">
                <button class="btn btn-primary">
                    <i class="ti tabler-bug"></i>&nbsp;Add Bug
                </button>
            </a>
        </div>
    </div>
    <div class="row g-2 mt-0 mb-2">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Bugs</p>
                        <h4 class="fw-bold mb-0">{{ $totalBugs }}</h4>
                    </div>
                    <div class="bg-label-primary rounded p-3">
                        <i class="ti tabler-bug text-danger fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Pending Bugs</p>
                        <h4 class="fw-bold mb-0">{{ $pendingBugs }}</h4>
                    </div>
                    <div class="bg-label-warning rounded p-3">
                        <i class="ti tabler-clock text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Developer Completed </p>
                        <h4 class="fw-bold mb-0">{{ $developerCompleted }}</h4>
                    </div>
                    <div class="bg-label-success rounded p-3">
                        <i class="ti tabler-code text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Testing Completed </p>
                        <h4 class="fw-bold mb-0">{{ $testingCompleted }}</h4>
                    </div>
                    <div class="bg-label-success rounded p-3">
                        <i class="ti tabler-circle-check text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-3 shadow-sm border-0 mb-2">
        <h6 class="d-flex align-items-center mb-3">
            <i class="ti tabler-filter text-primary me-2"></i>
            Filter
        </h6>
        <div class="row g-3">
            <div class="col">
                <label class="form-label d-flex align-items-center">
                    <i class="ti tabler-checklist text-primary me-1"></i>
                    Filter By Status
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti tabler-search"></i>
                    </span>
                    <select id="filterStatus" class="form-select">
                        <option value="">Select</option>
                        <option>Developer Completed</option>
                        <option>Testing Completed</option>
                        <option>Pending</option>
                        <option>Need Discussion</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <label class="form-label d-flex align-items-center">
                    <i class="ti tabler-category text-success me-1"></i>
                    Filter By Type
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti tabler-search"></i>
                    </span>
                    <select id="filterType" class="form-select">
                        <option value="">Select</option>
                        <option>Functionality</option>
                        <option>App</option>
                        <option>Design</option>
                        <option>UI Issue</option>
                        <option>Deploy</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <label class="form-label d-flex align-items-center">
                    <i class="ti tabler-user text-danger me-1"></i>
                    Filter By Employee
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti tabler-search"></i>
                    </span>
                    <select id="filterEmployee" class="form-select">
                        <option value="">Select</option>
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col">
                <label class="form-label d-flex align-items-center">
                    <i class="ti tabler-flag text-info me-1"></i>
                    Filter By Priority
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti tabler-search"></i>
                    </span>
                    <select id="filterPriority" class="form-select">
                        <option value="">Select</option>
                        <option>High</option>
                        <option>Medium</option>
                        <option>Low</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 mt-auto d-flex gap-2">
                <button id="filterBtn" class="btn btn-primary w-100">Filter</button>
                <button id="resetBtn" class="btn btn-label-secondary w-100">Reset</button>
            </div>
        </div>
    </div>
    <div class="card p-0 mt-3">
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-3">
                    <!-- Left: Page Length -->
                    <div style="width:90px;">
                        <select id="pageLength" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <!-- Right Side Controls -->
                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <!-- Search -->
                        <input type="text" id="toDate" class="form-control form-control-sm" placeholder="Search"
                            style="width:200px;">
                        <!-- Export -->
                        <button class="btn btn-label-secondary btn-sm d-flex align-items-center gap-1" id="btnexport">
                            <i class="ti tabler-upload"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive pt-0 pb-2">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 p-0">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table class="table table-hover align-middle mb-0" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">Sno</th>
                                    <th class="text-nowrap">Created AT</th>
                                    <th class="text-nowrap">Identified By</th>
                                    <th class="text-nowrap">Panel</th>
                                    <th class="text-nowrap">Priority</th>
                                    <th class="text-nowrap">Bug Type</th>
                                    <th class="text-nowrap">Bug Title</th>
                                    <th class="text-nowrap">Module</th>
                                    <th class="text-nowrap">Debug By</th>
                                    <th class="text-nowrap">Bug Status</th>
                                    <th class="text-nowrap">Solved By</th>
                                    <th class="text-nowrap">View</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 text-center p-4 py-5">
                <h5 class="fw-bold mb-2">Are you sure?</h5>
                <p class="text-muted">Confirm delete this bug?</p>
                <input type="hidden" id="deleteId">
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button class="btn btn-outline-primary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-danger" id="confirmDelete">
                        Yes Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            const table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true, // server-side for filters
                ajax: {
                    url: "{{ route('admin_bug_report_data', $project_id) }}",
                    data: function (d) {
                        d.status = $('#filterStatus').val();
                        d.type = $('#filterType').val();
                        d.employee = $('#filterEmployee').val();
                        d.priority = $('#filterPriority').val();
                        d.search = $('#toDate').val(); // search term
                    }
                },
                searching: false, // custom search
                paging: true,
                info: true,
                lengthChange: false,
                pageLength: 10,
                ordering: false,
                columns: [
                  {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },                       {
        data: 'date'
    },
                {
                    data: 'name',
                    render: function (name) {
                        return `<span class="text-nowrap">${name}</span>`;
                    }
                },
                {
                    data: 'panel'
                },
                {
                    data: 'priority',
                    render: function (priority) {
                        return `<span class="text-danger">${priority}</span>`;
                    }
                },
                {
                    data: 'type'
                },
                {
                    data: 'title'
                },
                {
                    data: 'module'
                },
                {
                    data: 'debug'
                },
                {
                    data: 'sts'
                },
                  {
                    data: 'solved_by'
                },
                                  
                {
                    data: 'id',
                    render: function (id) {
                        return `<div class="d-flex justify-content-center gap-2">
                             <a href="{{ url('admin/view_bug_details') }}/${id}">
                        <i class="ti tabler-eye menu-icon"></i>
                    </a>
                                <a href="{{ url('admin/edit_bug') }}/${id}">
                <i class="ti tabler-edit menu-icon"></i>
            </a>
                                                             <a href="javascript:void(0)" class="deleteBug" data-id="${id}" data-bs-toggle="modal" data-bs-target="#delete">
                    <i class="ti tabler-trash menu-icon text-danger"></i>
                </a>
                            </div>
            `;
                    }
                }
                ]
            });
            // Apply Filters
            $('#filterBtn').on('click', function () {
                table.ajax.reload();
            });
            // Reset Filters
            $('#resetBtn').on('click', function () {
                $('#filterStatus, #filterType, #filterEmployee, #filterPriority, #toDate').val('');
                table.ajax.reload();
            });
            // Search input
            $('#toDate').on('keyup change', function () {
                table.ajax.reload();
            });
            // Page length change
            $('#pageLength').on('change', function () {
                table.page.len(this.value).draw();
            });
        });
    </script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
    </script>
    <script>
        let deleteId = null;
        $(document).on('click', '.deleteBug', function () {
            deleteId = $(this).data('id');
            $('#deleteId').val(deleteId);
        });
        $('#confirmDelete').click(function () {
            let id = $('#deleteId').val();
            // change button text
            $('#confirmDelete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: "{{ route('delete_bug') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function (response) {
                    $('#delete').modal('hide');
                    // reset button
                    $('#confirmDelete').prop('disabled', false).text('Yes Delete');
                    if (response.status) {
                        toastr.success(response.message);
                        $('#usersTable').DataTable().ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    $('#confirmDelete').prop('disabled', false).text('Yes Delete');
                    toastr.error('Something went wrong');
                }
            });
        });
    </script>
    <script>
        document.getElementById('btnexport').addEventListener('click', function () {
            let table = $('#usersTable').DataTable();
            // Get ALL data from DataTable (not just visible page)
            let data = table.rows({ search: 'applied' }).data().toArray();
            // Define headers (exclude "View")
            let headers = [
    "Sno",
    "Created At",
    "Identified By",
    "Panel",
    "Priority",
    "Bug Type",
    "Bug Title",
    "Module",
    "Debug By",
    "Bug Status",
    "Solved By",
    "Testing Scenario",
    "Current Output",
    "Expected Output",
    "Reopen Count",
    "Suggestion"
];
            let csv = [];
            csv.push(headers.join(",")); // header row
            data.forEach((row, index) => {
               let rowData = [
    index + 1,
    row.date ?? '',
    row.name ?? '',
    row.panel ?? '',
    row.priority ?? '',
    row.type ?? '',
    row.title ?? '',
    row.module ?? '',
    row.debug ?? '',
    row.sts ?? '',
    row.solved_by ?? '',
    row.testing_scenario ?? '',
    row.current_output ?? '',
    row.expected_output ?? '',
    row.reopen_count ?? '',
    row.suggestion ?? ''
];
                // Escape commas & quotes
                let formatted = rowData.map(val => {
                    val = String(val).replace(/"/g, '""');
                    return `"${val}"`;
                });
                csv.push(formatted.join(","));
            });
            // Create CSV file
            let blob = new Blob([csv.join("\n")], { type: 'text/csv;charset=utf-8;' });
            let link = document.createElement("a");
            let url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", "bug_report.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
@endsection