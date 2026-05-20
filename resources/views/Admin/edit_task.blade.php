@extends('Admin.layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<h5>  <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ url()->previous() }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>Edit Task</h5>
<h6 class="mt-2">Project Name : {{ $project->project_name }}</h6>
<div class="card p-4">
    <form action="{{ route('update_task', $task->id) }}" method="POST" id="update_task">
        @csrf
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="id" value="{{ $task->id }}">
        <div class="row">
            {{-- Task Type --}}
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Task Type</label>
                <select class="form-select" name="task_type">
                    <option value="">Select Type</option>
                    <option value="ip" {{ old('task_type', $task->task_type) == 'ip' ? 'selected' : '' }}>IP</option>
                    <option value="bug" {{ old('task_type', $task->task_type) == 'bug' ? 'selected' : '' }}>Bug</option>
                    <option value="cr" {{ old('task_type', $task->task_type) == 'cr' ? 'selected' : '' }}>CR</option>
                    <option value="update" {{ old('task_type', $task->task_type) == 'update' ? 'selected' : '' }}>Update</option>
                </select>
                @error('task_type')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
           {{-- Module Type --}}
<div class="col-lg-6 mb-3 mt-2">
    <label class="form-label">Module Type</label>
    <select id="module_type" class="form-select" name="module_type">
        <option value="">Select Module Type</option>
        @foreach ($modules as $module)
            <option value="{{ $module->module_type }}"
                {{ old('module_type', $task->module_type) == $module->module_type ? 'selected' : '' }}>
                {{ $module->module_type }}
            </option>
        @endforeach
    </select>
    @error('module_type')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

{{-- Module Name --}}
<div class="col-lg-6 mb-3 mt-2">
    <label class="form-label">Module Name</label>
    <select id="module_name" name="module_id" class="form-select">
        <option value="">Select Module Name</option>
    </select>
    @error('module_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


            {{-- Task Name --}}
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Task</label>
                <input type="text" class="form-control" name="task_name"
                    value="{{ old('task_name', $task->task_name) }}">
                @error('task_name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            {{-- Task Description (CKEditor) --}}
            <div class="col-lg-12 mb-3 mt-2">
                <label class="form-label">Task Description</label>
                <textarea class="form-control" id="task_description" name="task_description" rows="4">
                {{ old('task_description', $task->task_description) }}
                </textarea>
                @error('task_description')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
             <div class="col-lg-4 mb-3 mt-2">
                <label class="form-label">Estimated Hours</label>
                <input type="text" class="form-control" name="estimated_time"
                    value="{{ old('estimated_time', $task->estimated_time) }}">
                @error('estimated_time')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            {{-- Start Date --}}
            <div class="col-lg-4 mb-3 mt-2">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date"
                    value="{{ old('start_date', $task->start_date) }}">
                @error('start_date')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            {{-- Due Date --}}
            <div class="col-lg-4 mb-3 mt-2">
                <label class="form-label">Due Date</label>
                <input type="date" class="form-control" name="due_date"
                    value="{{ old('due_date', $task->due_date) }}">
                @error('due_date')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            {{-- Assign To --}}
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Assign To</label>
                <select class="form-select" name="assign_to">
                    <option value="">Select Staff</option>
                    @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}"
                        {{ old('assign_to', $task->assign_to) == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
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
            <option value="{{ $tester->id }}"
                {{ old('tester_id', $task->tester_id ?? '') == $tester->id ? 'selected' : '' }}>
                {{ $tester->name }}
            </option>
        @endforeach

    </select>

    @error('tester_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
            {{-- Priority --}}
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Priority</label>
                <select class="form-select" name="priority">
                    <option value="">Select Priority</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                </select>
                @error('priority')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            {{-- Buttons --}}
            <div class="mt-4">
                <button class="btn btn-primary me-3" type="submit">Update Task</button>
                <a href="{{ route('staff_table') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
    integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous">
</script>
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
    let $moduleTypeDropdown = $('#module_type');
    let $moduleNameDropdown = $('#module_name');
    let projectId = $('input[name="project_id"]').val();

    // Pre-selected values from edit mode
    let selectedModuleType = @json(old('module_type', $task->module_type));
    let selectedModuleId = @json(old('module_id', $task->module_id));

    // Setup CSRF
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Function to load Module Names
    function loadModuleNames(typeId, selectedId = null) {
        $moduleNameDropdown.html('<option>Loading...</option>');

        if (!typeId) {
            $moduleNameDropdown.html('<option value="">-- Select Module Name --</option>');
            return;
        }

        $.ajax({
            url: "{{ route('getModuleName') }}",
            type: 'POST',
            data: {
                module_type: typeId,
                project_id: projectId
            },
            success: function(response) {
                $moduleNameDropdown.empty().append('<option value="">-- Select Module Name --</option>');

                $.each(response, function(index, module) {
                    let isSelected = (selectedId == module.id) ? 'selected' : '';
                    $moduleNameDropdown.append(
                        `<option value="${module.id}" ${isSelected}>${module.module_name}</option>`
                    );
                });
            },
            error: function() {
                $moduleNameDropdown.html('<option value="">-- Select Module Name --</option>');
                alert("Error fetching modules.");
            }
        });
    }

    // === Load Module Names on page load (edit mode) ===
    if (selectedModuleType) {
        loadModuleNames(selectedModuleType, selectedModuleId);
    }

    // === Reload Module Names when Module Type changes ===
    $moduleTypeDropdown.on('change', function() {
        let typeId = $(this).val();
        loadModuleNames(typeId, null); // clear selection on change
    });
});

    document.getElementById('finalsubmit').addEventListener('click', function(e) {
        e.preventDefault();
        let btn = this;
        btn.disabled = true;
        btn.innerText = 'Processing...';
        document.getElementById('login_form').submit();
    });
</script>

@endsection