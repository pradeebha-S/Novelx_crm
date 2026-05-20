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
                <a href="{{ route('admin.bug_report', $project_id) }}" class="btn btn-icon bg-white me-2"
                    style="box-shadow:0px 9px 12px -2px #66328E1F;">
                    <i class="ti tabler-chevron-left text-black"></i>
                </a>
                <i class="ti tabler-bug text-danger me-2"></i>
                Add Bug
            </h5>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm border-1 border-danger p-3">
                <div class="card-body p-4">
                    <form action="{{ route('admin_create_bug') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="attachment_preview" id="attachmentPreview"
                            value="{{ old('attachment_preview') }}">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="project_id" value="{{ $project_id }}">
                                        <label class="form-label fw-semibold">
                                            <i class="ti tabler-user me-1 text-primary"></i>
                                            Identified By <span class="text-danger">*</span>
                                        </label>
                                        <input type="hidden" name="identified_by"
                                            value="{{ auth()->guard('admin')->user()->id }}">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ auth()->guard('admin')->user()->name }}" readonly>
                                        @error('identified_by')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label fw-semibold">
                                            <i class="ti tabler-layout-dashboard me-1 text-primary"></i>
                                            Panel <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('panel') is-invalid @enderror" name="panel">
                                            <option value="">Select</option>
                                            <option value="Admin" {{ old('panel') == 'Admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="User Web" {{ old('panel') == 'User Web' ? 'selected' : '' }}>User
                                                Web
                                            </option>
                                            <option value="Website" {{ old('panel') == 'Website' ? 'selected' : '' }}>Website
                                            </option>
                                            <option value="App" {{ old('panel') == 'App' ? 'selected' : '' }}>App</option>
                                        </select>
                                        @error('panel')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-photo-video me-1 text-primary"></i>
                                    Screenshot / Video
                                </label>
                                <div class="upload-box text-center p-4" id="uploadBox">
                                    <div id="uploadContent">
                                        <i class="ti tabler-cloud-upload fs-1 text-primary"></i>
                                        <p class="mb-1 mt-2 fw-semibold">
                                            Drag & Drop your file here
                                        </p>
                                        <small class="text-muted">
                                            Image, Video, PDF, DOC
                                        </small>
                                    </div>
                                    <!-- Image preview -->
                                    <img id="previewImage" class="img-fluid rounded mt-2 d-none" style="max-height:80px">
                                    <!-- Video preview -->
                                    <video id="previewVideo" class="rounded mt-2 d-none" style="max-height:80px" controls>
                                    </video>
                                    <input type="file" id="fileInput" name="attachment" class="d-none"
                                        accept="image/*,video/*,.pdf,.zip,.doc,.docx">
                                </div>
                            </div>
                            <!-- <div class="col-lg-4 mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="ti tabler-photo me-1 text-primary"></i>
                                        Screenshot / Attachment
                                    </label>
                                    <div class="upload-box text-center" id="uploadBox">
                                        @if(old('attachment_preview'))
                    <img src="{{ old('attachment_preview') }}"
                        class="img-fluid rounded"
                        style="max-height:200px">
                    @else
                                        <div id="uploadContent">
                                            <i class="ti tabler-cloud-upload fs-1 text-primary"></i>
                                            <p class="mb-1 mt-2 fw-semibold">
                                                Drag & Drop your screenshot here
                                            </p>
                                            <small class="text-muted">
                                                or click to browse files
                                            </small>
                                        </div>
                                        <img id="previewImage" class="img-fluid rounded mt-2 d-none" style="max-height:120px">
                                        @endif
                                        <input type="file" id="fileInput" name="attachment" class="d-none"
                                            accept="image/*,.pdf,.zip,.doc,.docx">
                                    </div>
                                </div> -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-category me-1 text-primary"></i>
                                    Bug Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('bug_type') is-invalid @enderror" name="bug_type">
                                    <option value="">Select</option>
                                    <option value="Design" {{ old('bug_type') == 'Design' ? 'selected' : '' }}>Design
                                    </option>
                                    <option value="Functionality" {{ old('bug_type') == 'Functionality' ? 'selected' : '' }}>
                                        Functionality</option>
                                    <option value="UI Issue" {{ old('bug_type') == 'UI Issue' ? 'selected' : '' }}>UI Issue
                                    </option>
                                      <option value="suggestion" {{ old('bug_type') == 'suggestion' ? 'selected' : '' }}>suggestion
                                    </option>
                                </select>
                                @error('bug_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-box me-1 text-primary"></i>
                                    Module <span class="text-danger">*</span>
                                </label>
                                  <select id="moduleSelect" class="form-select @error('module') is-invalid @enderror" name="module">
                                    <option value="">Select Modules to view bugs</option>
                                    @foreach ($modules as $module)
                                        <option value="{{ $module->id }}" {{ old('module') == $module->id ? 'selected' : '' }}>
                                            {{ $module->module_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-pencil me-1 text-primary"></i>
                                    Bug Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('bug_title') is-invalid @enderror"
                                    name="bug_title" placeholder="Enter Bug Title" value="{{ old('bug_title') }}">
                                @error('bug_title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-code me-1 text-primary"></i>
                                    Debug By <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('debug_by') is-invalid @enderror" name="debug_by">
                                    <option value="">Select</option>
                                    @foreach ($staffs as $staff)
                                        <option value="{{ $staff->id }}" {{ old('debug_by') == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label fw-semibold d-block">
                                    <i class="ti tabler-flag me-1 text-danger"></i>
                                    Priority
                                </label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        {{-- <input class="form-check-input" type="radio" name="priority" value="Low"> --}}
                                        <input class="form-check-input" type="radio" name="priority" value="Low" {{ old('priority') == 'Low' ? 'checked' : '' }}>
                                        <label class="form-check-label">Low</label>
                                    </div>
                                    <div class="form-check">
                                        {{-- <input class="form-check-input" type="radio" name="priority" value="Medium">
                                        --}}
                                        <input class="form-check-input" type="radio" name="priority" value="Medium" {{ old('priority') == 'Medium' ? 'checked' : '' }}>
                                        <label class="form-check-label">Medium</label>
                                    </div>
                                    <div class="form-check">
                                        {{-- <input class="form-check-input" type="radio" name="priority" value="High"> --}}
                                        <input class="form-check-input" type="radio" name="priority" value="High" {{ old('priority') == 'High' ? 'checked' : '' }} checked>
                                        <label class="form-check-label text-danger fw-semibold">High</label>
                                    </div>
                                </div>
                                @error('priority')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-stethoscope me-1 text-danger"></i>
                                    Testing Scenerio <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" rows="3" name="testing_scenario"
                                    placeholder="Explain the Scenerio..?">{{ old('testing_scenario') }}</textarea>
                                @error('testing_scenario')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-alert-triangle me-1 text-danger"></i>
                                    Current Output <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('current_output') is-invalid @enderror" rows="3"
                                    name="current_output"
                                    placeholder="What is the Current output..?">{{ old('current_output') }}</textarea>
                                @error('current_output')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-check me-1 text-success"></i>
                                    Expected Output <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('expected_output') is-invalid @enderror" rows="3"
                                    name="expected_output"
                                    placeholder="What is the expected output..?">{{ old('expected_output') }}</textarea>
                                @error('expected_output')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-list me-1 text-primary"></i>
                                    Suggestions <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('suggestions') is-invalid @enderror" rows="3"
                                    name="suggestion"
                                    placeholder="Provide your suggestions..?"></textarea>
                                @error('suggestions')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-3">
                            <button type="reset" class="btn btn-label-secondary">
                                <i class="ti tabler-x me-1"></i> Discard
                            </button>
                            <button type="submit" id="submitBugBtn" class="btn btn-primary">
                                <i class="ti tabler-send me-1"></i> Submit Bug
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-4 mb-3">
            <div class="card shadow-sm border-1 border-danger p-3" style="max-height: 675px; overflow: auto;">
                <h5 class="text-center m-0 fw-bold">Module Name</h5>
                <div class="card-body">
                    <p class="text-black mb-2 d-flex align-items-center gap-2">
                        <i class="ti tabler-list-details text-primary"></i>
                        Bug Description
                    </p>
                    <div class="border rounded-1 p-2 w-100 align-items-start mt-2">
                        <div class="row">
                            <div class="col-12 fw-bold">
                                <span class="text-danger">Title : Design</span><br>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis laboriosam minima, aut eum
                                doloremque totam vero numquam amet excepturi dolorum tempore est aliquam officiis nihil
                                nulla
                                nostrum! Ea, animi officiis!
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end mt-2">
                                <a href="{{ route('admin.view_bug_details', 1) }}" class="text-danger fw-bold">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded-1 p-2 w-100 align-items-start mt-2">
                        <div class="row">
                            <div class="col-12 fw-bold">
                                <span class="text-danger">Title : Design</span><br>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis laboriosam minima, aut eum
                                doloremque totam vero numquam amet excepturi dolorum tempore est aliquam officiis nihil
                                nulla
                                nostrum! Ea, animi officiis!
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end mt-2">
                                <a href="{{ route('admin.view_bug_details', 1) }}" class="text-danger fw-bold">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded-1 p-2 w-100 align-items-start mt-2">
                        <div class="row">
                            <div class="col-12 fw-bold">
                                <span class="text-danger">Title : Design</span><br>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis laboriosam minima, aut eum
                                doloremque totam vero numquam amet excepturi dolorum tempore est aliquam officiis nihil
                                nulla
                                nostrum! Ea, animi officiis!
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end mt-2">
                                <a href="{{ route('admin.view_bug_details', 1) }}" class="text-danger fw-bold">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-4 mb-3">
    <div class="card shadow-sm border-1 border-danger p-3" style="max-height: 675px; overflow: auto;">
        <h5 class="text-center m-0 fw-bold">Module Name</h5>
        <div class="card-body" id="moduleBugList">
            <p class="text-black mb-2 d-flex align-items-center gap-2">
                <i class="ti tabler-list-details text-primary"></i>
                Bug Description
            </p>
            <p class="text-center text-muted">
                Select module to view bugs
            </p>
        </div>
    </div>
</div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");
            const submitBtn = document.getElementById("submitBugBtn");
            form.addEventListener("submit", function () {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
            });
        });
    </script>
    <script>
        const uploadBox = document.getElementById("uploadBox");
        const fileInput = document.getElementById("fileInput");
        uploadBox.addEventListener("click", function () {
            fileInput.click();
        });
        fileInput.addEventListener("change", function (e) {
            let file = e.target.files[0];
            if (!file) return;
            const content = document.getElementById("uploadContent");
            const imgPreview = document.getElementById("previewImage");
            const videoPreview = document.getElementById("previewVideo");
            content.style.display = "none";
            imgPreview.classList.add("d-none");
            videoPreview.classList.add("d-none");
            let fileURL = URL.createObjectURL(file);
            /* Image Preview */
            if (file.type.startsWith("image/")) {
                imgPreview.src = fileURL;
                imgPreview.classList.remove("d-none");
            }
            /* Video Preview */
            else if (file.type.startsWith("video/")) {
                videoPreview.src = fileURL;
                videoPreview.classList.remove("d-none");
            }
        });
    </script>
<script>
document.getElementById('moduleSelect').addEventListener('change', function () {
    let moduleId = this.value;
    let bugList = document.getElementById('moduleBugList');
    if(moduleId == ""){
        bugList.innerHTML = "<p class='text-center'>Select module to view bugs</p>";
        return;
    }
    fetch("{{ route('admin.get_module_bugs','') }}/" + moduleId)
    .then(response => response.json())
    .then(data => {
        let html = "";
        if(data.length == 0){
            html = "<p class='text-danger text-center'>No Bugs Found</p>";
        }else{
            data.forEach(function(bug){
                html += `
                <div class="border rounded-1 p-2 w-100 align-items-start mt-2">
                    <div class="row">
                        <div class="col-12 fw-bold">
                            <span class="text-danger">
                                Title : ${bug.bug_title}
                            </span>
                            <br>
                            ${bug.expected_output ?? ''}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-end mt-2">
                            <a href="/admin/view_bug_details/${bug.id}" class="text-danger fw-bold">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                `;
            });
        }
        bugList.innerHTML = html;
    })
    .catch(error => {
        console.log("Error:", error);
    });
});
</script>
@endsection