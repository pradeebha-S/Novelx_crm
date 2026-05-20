@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    .link {
        text-decoration: underline;
    }
</style>
@section('content')
<div class="row align-items-center mb-3">
    <!-- Left: Heading -->
    <div class="col">
        <h5 class="mb-0">Student Management</h5>
    </div>

    <!-- Right: Buttons -->
    <div class="col-auto">
        <div class="d-flex gap-2">
            <a href="{{ route('intern.login') }}" target="_blank" rel="noopener noreferrer" class="btn btn-label-secondary buttons-collection d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-login icon-xs me-sm-1"></i>
                <span>student Login</span>
            </a>
            <a href="{{ route('create_intern') }}">
                <button class="btn buttons-collection btn-primary" type="button">
                    <span class="d-flex align-items-center gap-2">
                        <i class="icon-base ti tabler-plus icon-xs me-sm-1"></i>
                        <span>Create Student</span>
                    </span>
                </button>
            </a>


        </div>
    </div>
</div>

<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="intern" class="table">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th>NAME</th>
                                <th>TASKS</th>

                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Domain</th>
                                <th>Duration</th>
                                <th>DOB</th>
                                <th>STATUS</th>
                                <th>Actions</th>
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
<div class="modal fade" id="delete_intern" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 text-center p-4 py-5">
            <h5 class="fw-bold mb-2">Are you sure!!</h5>
            <p class="text-muted">Are you confirm to delete?</p>
            <form id="delete_form" method="POST" action="{{'delete_intern'}}">
                @csrf
                <input type="hidden" name="id" id="delete_intern_id">
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger p-3 ms-2 fw-semibold" id="submit_btn">
                        Yes, Sure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- toggle status -->
<div class="modal fade" id="toggle_status" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 text-center p-4 py-5">
            <h5 class="fw-bold mb-2">Are you sure!!</h5>
            <p class="text-muted" id="toggle_status_text"></p>
            <form id="ToggleForm" method="POST" action="{{route('intern_toggle_status')}}">
                @csrf
                <input type="hidden" name="id" id="toggle_intern_id">
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold  me-3" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" id="submit_toggle" class="btn btn-gray p-3 fw-semibold">
                        Yes, Sure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script>
    var jq = jQuery.noConflict();
    jq(document).ready(function() {
        jq('#intern').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('intern_table_data')}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                 {
                    data: 'task',
                    name: 'task',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'designation',
                    name: 'designation'
                },
                {
                    data: 'intern_period',
                    name: 'intern_period'
                },
                {
                    data: 'dob',
                    name: 'dob'
                },
                {
                    data: 'is_active',
                    name: 'is_active'
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
                searchPlaceholder: "Search",
                lengthMenu: "_MENU_"
            }
        });
    });

    function setDeleteId(button) {
        document.getElementById('delete_intern_id').value = button.getAttribute('data-id');
    }
    document.getElementById('submit_btn').addEventListener('click', function(e) {
        e.preventDefault();
        this.disabled = true;
        this.innerText = 'Processing...';
        document.getElementById('delete_form').submit();
    });


    //toggle_status
    function setToggleId(button) {
        const internId = button.getAttribute('data-id');
        const isActive = button.getAttribute('data-active') === '1';

        document.getElementById('toggle_intern_id').value = internId;

        const statusText = document.getElementById('toggle_status_text');
        const submitBtn = document.getElementById('submit_toggle');

        if (isActive) {
            statusText.textContent = "Are you sure you want to block this Student?";

        } else {
            statusText.textContent = "Are you sure you want to unblock this Student?";

        }
        const icon = document.querySelector(`#toggle_icon_${internId}`);
        if (icon) {
            icon.setAttribute('fill', isActive ? '#FF0000' : '#28a745');
            icon.setAttribute('fill-opacity', '1');
        }
    }

    document.getElementById('ToggleForm').addEventListener('submit', function() {
        const btn = document.getElementById('submit_toggle');
        btn.disabled = true;
        btn.innerText = 'Processing...';
    });

    document.getElementById("exp").addEventListener("click", function() {
        let table = document.getElementById("intern");
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
        a.download = "modules.csv";
        a.click();
        URL.revokeObjectURL(url);
    });
</script>

@endsection