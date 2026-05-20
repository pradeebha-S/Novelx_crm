@extends('Admin.layout')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <!-- Left side -->
        <div class="d-flex align-items-center">
            <h5 class="mb-0">
                <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('task', $project->id) }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>
Create Task
            </h5>
        </div>
    </div>
    <div class="col-12 d-flex justify-content-between gap-2">
        <div>
            <h6 class="mt-2">Project Name : {{ $project->project_name }}</h6>
            <h6>Tasks</h6>
        </div>
        <a href="{{ route('modules', ['id' => $project->id]) }}">
            <button class="btn buttons-collection btn-primary" type="button" aria-haspopup="dialog" aria-expanded="false">
                <span class="d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-plus icon-xs me-sm-1"></i>
                    <span class="d-sm-inline-block">Create Module</span>
                </span>
            </button>
        </a>
    </div>
    <div class="card p-4">
        <h6>Create Tasks</h6>
        <form action="{{ route('add_task') }}" method="post" id="task_form">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="row">
                <div class="col-lg-6 mb-3 mt-2">
                    <label class="form-label">Task Type<span class="text-danger">*</span></label>
                    <select class="form-select" name="task_type">
                        <option value="">Select Type</option>
                        <option value="ip" selected>IP</option>
                        <option value="bug">Bug</option>
                        <option value="cr">CR</option>
                        <option value="update">Update</option>
                    </select>
                    @error('task_type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <label class="form-label">Module Type<span class="text-danger">*</span></label>
                    <select id="module_type" class="form-select" name="module_type">
                        <option value="">Select Module Type</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->module_type }}">{{ $module->module_type }}</option>
                        @endforeach
                    </select>
                    @error('module_type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <label class="form-label">Module Name<span class="text-danger">*</span></label>
                    <select id="module_name" name="module_id" class="form-select">
                        <option value="">Select Module Name</option>
                    </select>
                    @error('module_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <label class="form-label">Task<span class="text-danger">*</span></label>
                    <input class="form-control" name="task_name" placeholder="Enter Task"
                        rows="4">{{ old('task_name') }}</input>
                    @error('task_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-12 mb-3 mt-2">
                    <label class="form-label">Task Description<span class="text-danger">*</span></label>
                    <textarea class="form-control" id="task_description" name="task_description" rows="5"
                        placeholder="Enter Task Description">{{ old('task_description') }}</textarea>
                    @error('task_description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-4 mb-3 mt-2">
                    <label class="form-label">Estimated Hours</label>
                    <input type="text" class="form-control" name="estimated_time" value="{{ old('estimated_time', 1) }}">
                    @error('estimated_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Start Date -->
                <div class="col-lg-4 mb-3 mt-2">
                    <label class="form-label"> Start Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="start_date"
                        value="{{ old('start_date', date('Y-m-d')) }}">
                    @error('start_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Due Date -->
                <div class="col-lg-4 mb-3 mt-2">
                    <label class="form-label">Due Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="due_date"
                        value="{{ old('due_date', date('Y-m-d')) }}">
                    @error('due_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Assign To -->
                <div class="col-lg-6 mb-3 mt-2">
                    <label class="form-label">Assign To</label>
                    <select class="form-select" name="assign_to">
                        <option value="">Select Staff</option>
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                    @error('assign_to')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3 mt-2">
    <label class="form-label">Select Tester</label>

    <select name="tester_id" class="form-select">
        <option value="">Select Tester</option>

        @foreach($testers as $tester)
            <option value="{{ $tester->id }}">
                {{ $tester->name }}
            </option>
        @endforeach

    </select>

    @error('tester_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
                <div class="col-lg-6 mb-3 mt-2">
                    <label class="form-label">Priority<span class="text-danger">*</span></label>
                    <select class="form-select" name="priority">
                        <option value="">Select Type</option>
                        <option value="high" selected>High</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                    </select>
                    @error('task_type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Task Name -->
                <div class="mt-4">
                    <button class="btn btn-primary me-3" id="finalsubmit" type="submit">Create Task</button>
                    <button class="btn btn-secondary" type="reset">Discard</button>
                </div>
            </div>
        </form>
    </div>
    <!-- <div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content rounded-4 text-center p-4 py-5">
                        <h5 class="fw-bold mb-2">Are you sure!!</h5>
                        <p class="text-muted">Are you confirm to submit ?</p>
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                            <button type="button" class="btn btn-outline-primary p-3 fw-semibold me-3" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary p-3 fw-semibold " id="finalsubmit">
                                Yes, Sure
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize DataTable
            new DataTable('#task', {
                language: {
                    search: "",
                    searchPlaceholder: "Search",
                    lengthMenu: "_MENU_"
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1], // actual values
                    ["10", "25", "50", "100", "All"] // labels shown to user
                ]
            });
        });
        document.getElementById('finalsubmit').addEventListener('click', function(e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('task_form').submit();
        });
        document.getElementById("exp").addEventListener("click", function() {
            let table = document.getElementById("task");
            if (!table) {
                alert("Table not found!");
                return;
            }
            // Get all table rows
            let rows = Array.from(table.querySelectorAll("tr"));
            // Build CSV content
            let csv = rows.map(row => {
                let cells = Array.from(row.querySelectorAll("th, td"));
                return cells.map(cell => {
                    let text = cell.innerText.trim(); // Remove extra spaces
                    // Optional: handle quotes inside text
                    text = text.replace(/"/g, '""');
                    return `"${text}"`;
                }).join(",");
            }).join("\n");
            // Create a blob and download
            let blob = new Blob([csv], {
                type: "text/csv;charset=utf-8;"
            });
            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "tasks.csv"; // File name
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>
    <script>
        $(document).ready(function() {
            let $moduleTypeDropdown = $('#module_type');
            let $moduleNameDropdown = $('#module_name');
            let projectId = $('input[name="project_id"]').val();
            // Setup CSRF for all AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $moduleTypeDropdown.on('change', function() {
                const typeId = $(this).val();
                $moduleNameDropdown.html('<option>Loading...</option>');
                if (typeId && projectId) {
                    $.post("{{ route('getModuleName') }}", {
                        module_type: typeId,
                        project_id: projectId
                    }, function(response) {
                        $moduleNameDropdown.empty().append(
                            '<option value="">-- Select Module Name --</option>');
                        $.each(response, function(index, module) {
                            // Replace 'module_name' with the actual column name in DB
                            $moduleNameDropdown.append(
                                `<option value="${module.id}">${module.module_name}</option>`
                                );
                        });
                    }).fail(function(xhr) {
                        console.error(xhr.responseText);
                        alert("Error fetching modules.");
                        $moduleNameDropdown.html(
                            '<option value="">-- Select Module Name --</option>');
                    });
                } else {
                    $moduleNameDropdown.html('<option value="">-- Select Module Name --</option>');
                }
            });
        });
    </script>
  <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#task_description'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
