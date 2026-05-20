@extends('Admin.layout')
<style>
    .upload-box {
        border: 2px dashed #d9dee3;
        border-radius: 8px;
        background: #f8f9fa;
        cursor: pointer;
        /* transition: all .2s ease; */
    }
    .upload-box:hover {
        border-color: #7367f0;
        background: #f4f3ff;
    }
    .upload-box.dragover {
        border-color: #28c76f;
        background: #e9f8f1;
    }
</style>
@section('content')
    <div class="row align-items-center justify-content-between mb-1">
        <div class="col-auto">
            <h5 class="d-flex align-items-center">
                <button type="button" class="btn btn-icon bg-white me-2" style="box-shadow:0px 9px 12px -2px #66328E1F;"
                    onclick="history.back()">
                    <i class="ti tabler-chevron-left text-black"></i>
                </button>
                <i class="ti tabler-bug text-danger me-2"></i>
                Edit Bug
            </h5>
        </div>
    </div>
    <div class="card shadow-sm border-1 border-danger p-3">
        <div class="card-body p-4">
            <form action="{{ route('update_bug', $bug->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row g-4">
                          <div class="col-lg-6">
                            <label class="form-label fw-semibold">
                                <i class="ti tabler-user me-1 text-primary"></i>
                                Identified By <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" name="identified_by" value="{{ auth()->guard('admin')->user()->id }}">
                            <input type="text" class="form-control bg-light"
                                value="{{ auth()->guard('admin')->user()->name }}" readonly>
                            @error('identified_by')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    Panel
                                </label>
                                <select class="form-select" name="panel">
                                    <option value="Admin" {{ $bug->panel == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="User Web" {{ $bug->panel == 'User Web' ? 'selected' : '' }}>User Web</option>
                                    <option value="Website" {{ $bug->panel == 'Website' ? 'selected' : '' }}>Website</option>
                                    <option value="App" {{ $bug->panel == 'App' ? 'selected' : '' }}>App</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    Bug Type
                                </label>
                                <select class="form-select" name="bug_type">
                                    <option value="Design" {{ $bug->bug_type == 'Design' ? 'selected' : '' }}>Design</option>
                                    <option value="Functionality" {{ $bug->bug_type == 'Functionality' ? 'selected' : '' }}>
                                        Functionality</option>
                                    <option value="Deploy" {{ $bug->bug_type == 'Deploy' ? 'selected' : '' }}>Deploy</option>
                                    <option value="App" {{ $bug->bug_type == 'App' ? 'selected' : '' }}>App</option>
                                    <option value="UI Issue" {{ $bug->bug_type == 'UI Issue' ? 'selected' : '' }}>UI Issue
                                           <option value="suggestion" {{ $bug->bug_type == 'suggestion' ? 'selected' : '' }}>suggestion
                                    </option>
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    Bug Title
                                </label>
                                <input type="text" name="bug_title" class="form-control" value="{{ $bug->bug_title }}">
                            </div>
                        </div>
                    </div>
                  
<div class="col-lg-4">
    <label class="form-label fw-semibold">
        Screenshot / Attachment
    </label>
    <div class="text-center border p-3">
        @php
            $files = json_decode($bug->attachment ?? '[]', true);
        @endphp
        @if(isset($files[0]) && $files[0] != '')
            @php
                $file = $files[0];
                $ext = pathinfo($file, PATHINFO_EXTENSION);
            @endphp
            @if(in_array(strtolower($ext), ['mp4','mov','avi','webm']))
                <video width="200" controls class="mb-2">
                    <source src="{{ asset('storage/'.$file) }}">
                    Your browser does not support video
                </video>
            @else
                <img src="{{ asset('storage/'.$file) }}" width="150" class="mb-2">
            @endif
        @else
            <p class="text-muted mb-2">No Attachment</p>
        @endif
        <input type="file" name="attachment" class="form-control"
               accept="image/*,video/*">
    </div>
</div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Module
                        </label>
                        <select class="form-select" name="module">
                            @foreach ($modules as $module)
                                <option value="{{ $module->id }}" {{ $bug->module == $module->id ? 'selected' : '' }}>
                                    {{ $module->module_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Debug By
                        </label>
                        <select class="form-select" name="debug_by">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $bug->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold d-block">
                            Priority
                        </label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" value="Low"
                                    {{ $bug->priority == 'Low' ? 'checked' : '' }}>
                                <label class="form-check-label">Low</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" value="Medium"
                                    {{ $bug->priority == 'Medium' ? 'checked' : '' }}>
                                <label class="form-check-label">Medium</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" value="High"
                                    {{ $bug->priority == 'High' ? 'checked' : '' }}>
                                <label class="form-check-label text-danger">High</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Testing Scenario
                        </label>
                        <textarea name="testing_scenario" class="form-control" rows="3">{{ $bug->testing_scenario }}</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Current Output
                        </label>
                        <textarea name="current_output" class="form-control" rows="3">{{ $bug->current_output }}</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Expected Output
                        </label>
                        <textarea name="expected_output" class="form-control" rows="3">{{ $bug->expected_output }}</textarea>
                    </div>
                      <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                           Suggestion
                        </label>
                        <textarea name="suggestion" class="form-control" rows="3">{{ $bug->suggestion }}</textarea>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="reset" class="btn btn-secondary">
                        Discard
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update Bug
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const uploadBox = document.getElementById('uploadBox');
        const fileInput = document.getElementById('fileInput');
        const uploadContent = document.getElementById('uploadContent');
        uploadBox.addEventListener('click', () => fileInput.click());
        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.classList.add('dragover');
        });
        uploadBox.addEventListener('dragleave', () => {
            uploadBox.classList.remove('dragover');
        });
        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        });
        fileInput.addEventListener('change', function() {
            showPreview(this.files[0]);
        });
        function showPreview(file) {
            uploadContent.style.display = "none"; // hide text
            if (file.type.startsWith('image')) {
                let img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxHeight = '90px';
                img.classList.add('img-fluid', 'rounded');
                uploadBox.appendChild(img);
            } else {
                let fileName = document.createElement('p');
                fileName.classList.add('text-success', 'mt-2', 'fw-semibold');
                fileName.innerText = file.name;
                uploadBox.appendChild(fileName);
            }
        }
    </script>
@endsection
