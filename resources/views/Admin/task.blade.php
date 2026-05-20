@extends('Admin.layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('project_table') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>
                    <div>
                        <h5 class="fw-bold mb-0">View Tasks                      |  <span class="fw-bold"> {{ $project->project_name }}</span>
</h5>
                        <!-- <span class="fw-bold">Project: {{ $project->project_name }}</span> -->
                    </div>
                </div>
                <a href="{{ route('create_task', ['project_id' => $project->id]) }}"
                   class="btn btn-primary px-4">
                    <i class="ti tabler-plus me-1"></i> Create Task
                </a>
            </div>
    {{-- ================= FILTER SECTION ================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">From Date</label>
                    <input type="date" id="from_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">To Date</label>
                    <input type="date" id="to_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Status</label>
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="Not Assigned">Not Assigned</option>
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
    <div class="card shadow-sm border-0">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Task List</h6>
            <button id="exp"
                    class="btn btn-outline-secondary">
                <i class="ti tabler-upload me-1"></i> Export
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dept" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SNO</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Project</th>
                            <th>Module</th>
                            <th>Task</th>
                            <th>Assigned Staff</th>
                            <th>Status</th>
                            <th class="text-center">View</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
{{-- ================= MODALS ================= --}}
{{-- Delete Modal --}}
<div class="modal fade" id="delete" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-body text-center p-5">
<h5>Confirm Delete</h5>
<p>Are you sure delete this task?</p>
<form action="{{ route('delete_task') }}" method="POST">
@csrf
<input type="hidden" name="id" id="deleteId">
<button type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">
Cancel
</button>
<button type="submit"
class="btn btn-danger">
Yes Delete
</button>
</form>
</div>
</div>
</div>
</div>
{{-- Task Description Modal --}}
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Task Description</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalTaskContent" class="text-muted mb-0"></p>
            </div>
        </div>
    </div>
</div>
{{-- Assign Task Modal --}}
<div class="modal fade" id="AssignedTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('assigned_task') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Reassign Task</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="task_id" id="task_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Assign To</label>
                        <select class="form-select" name="assign_to" required>
                            <option value="">Select Staff</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Estimated Hours</label>
                        <input type="text"
                               name="estimated_time"
                               class="form-control"
                               placeholder="Enter hours"
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#task_description'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#dept').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('task_data', $project_id) }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
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
                        data: 'project',
                        name: 'project'
                    },
                    {
                        data: 'module',
                        name: 'module'
                    },
                    {
                        data: 'task',
                        name: 'task',
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
                        data: 'view',
                        name: 'view',
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
                    [1, 'desc']
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
            $('#statusFilter').on('change', function() {
                table.ajax.reload();
            });
            $('#filterBtn').on('click', function() {
                table.ajax.reload();
            });
            $('#resetBtn').on('click', function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#statusFilter').val('');
                table.ajax.reload();
            });
        });
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        // Dropdown selectors
        let $moduleTypeDropdown = $('#module_type');
        let $moduleNameDropdown = $('#module_name');
        if ($moduleTypeDropdown.length && $moduleNameDropdown.length) {
            $moduleTypeDropdown.on('change', function() {
                const typeId = $(this).val();
                $moduleNameDropdown.html('<option>Loading...</option>');
                if (typeId) {
                    $.ajax({
                        url: "{{ route('getModuleName') }}",
                        type: 'POST',
                        data: {
                            module_type: typeId
                        },
                        success: function(response) {
                            $moduleNameDropdown.empty().append(
                                '<option value="">-- Select Module Name --</option>'
                            );
                            $.each(response, function(index, module) {
                                $moduleNameDropdown.append(
                                    `<option value="${module.id}">${module.module_name}</option>`
                                );
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert("Error fetching modules.");
                            $moduleNameDropdown.html(
                                '<option value="">-- Select Module Name --</option>'
                            );
                        }
                    });
                } else {
                    $moduleNameDropdown.html(
                        '<option value="">-- Select Module Name --</option>'
                    );
                }
            });
        }
        document.getElementById('finalsubmit').addEventListener('click', function(e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('login_form').submit();
        });
        document.getElementById("exp").addEventListener("click", function() {
            let table = document.getElementById("task");
            if (!table) {
                alert("Table not found!");
                return;
            }
            let rows = Array.from(table.querySelectorAll("tr"));
            let csv = rows.map(row => {
                let cells = Array.from(row.querySelectorAll("th, td"));
                return cells.map(cell => {
                    let text = cell.innerText.trim();
                    text = text.replace(/"/g, '""');
                    return `"${text}"`;
                }).join(",");
            }).join("\n");
            let blob = new Blob([csv], {
                type: "text/csv;charset=utf-8;"
            });
            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "tasks.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
        document.addEventListener("click", function(e) {
            const btn = e.target.closest(".deleteBtn");
            if (!btn) return;
            const id = btn.getAttribute("data-id");
            document.getElementById("deleteId").value = id;
        });
    </script>
    <script>
        //assigned task
        document.addEventListener('DOMContentLoaded', function() {
            const reopenModal = document.getElementById('AssignedTaskModal');
            reopenModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const taskId = button.getAttribute('data-id');
                document.getElementById('task_id').value = taskId;
            });
        });
    </script>
    <script>
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".deleteBtn");
    if(!btn) return;
    let id = btn.getAttribute("data-id");
    document.getElementById("deleteId").value = id;
});
</script>
@endsection