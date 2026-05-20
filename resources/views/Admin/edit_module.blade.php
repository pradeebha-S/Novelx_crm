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
                Edit Module
            </h5>

        </div>

    </div>
    <h6 class="mt-2">Project Name : {{ $project->project_name }}</h6>
    <h6>Module</h6>
    <div class="card p-4 mb-4">
        <h6>Edit Module</h6>
        <form action="{{ route('update_module') }}" method="post" id="module_form">
            @csrf
            <input type="hidden" name="project_id" id="" value="{{ $project->id }}">
                <input type="hidden" name="id" value="{{ $module->id }}"> {{-- Module ID --}}

             <div class="row g-3">
            <!-- Module Type -->
            <div class="col-lg-6">
                <label class="form-label">Module Type</label>
                <select name="module_type" class="form-select">
                    <option value="">-- Select Module Type --</option>
                    <option value="Admin" {{ $module->module_type == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="User Web" {{ $module->module_type == 'User Web' ? 'selected' : '' }}>User Web</option>
                    <option value="User App" {{ $module->module_type == 'User App' ? 'selected' : '' }}>User App</option>
                </select>
                @error('module_type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Module Name -->
            <div class="col-lg-6">
                <label class="form-label">Module Name</label>
                <input type="text" class="form-control" name="module_name" value="{{ old('module_name', $module->module_name) }}">
                @error('module_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        </form>


            <div class="col-lg-2 mt-3">
                <button type="button" class="btn btn-primary" data-bs-target="#confirmSubmit" data-bs-toggle="modal">Update
                    Module</button>
            </div>

    </div>


    <div class="modal fade" id="confirmSubmit" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-5 text-center">

                <h5 class="fw-bold mb-2">Are you sure?</h5>
                <p class="text-muted mb-4">Do you confirm to submit this form?</p>

                <div class="d-flex justify-content-center gap-3 mt-3">

                    <!-- Cancel -->
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <!-- Final submit -->
                    <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                        Yes, Sure
                    </button>

                </div>
            </div>
        </div>
    </div>



    <script>

        document.getElementById('finalSubmit').addEventListener('click', function (e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('module_form').submit();
        });

    </script>
@endsection