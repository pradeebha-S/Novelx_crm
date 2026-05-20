@extends('Admin.layout')

@section('content')

<style>
    .upload-box {
        border: 2px dashed #d3d6db;
        border-radius: 10px;
        cursor: pointer;
    }

    .upload-area {
        padding: 30px;
        text-align: center;
    }

    .upload-area:hover {
        background: #f8f9fa;
    }

    .upload-area.dragging {
        border: 2px dashed #f59e0b;
        background: #fff7ed;
    }

    #previewImage {
        max-height: 200px;
        margin-top: 10px;
    }

    #fileName {
        margin-top: 10px;
        font-weight: bold;
        color: #444;
    }
</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h5>Upload Document</h5>
        <a href="{{ route('view_doc', $project->id ?? null) }}" class="btn btn-outline-secondary">← Back</a>
    </div>

    <div class="card p-4 shadow-sm">

        <form method="POST" action="{{ route('store_document') }}" enctype="multipart/form-data">
            @csrf

            <!-- <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}"> -->
            <input type="hidden" name="temp_image" value="{{ session('temp_image') }}">
            <div class="col-lg-4 mb-4">
                <label class="form-label">Choose Project</label>

                <select name="project_id" id="project_id"
                    class="form-select @error('project_id') is-invalid @enderror">

                    <option value="">Select</option>

                    @foreach ($projects as $proj)
                    <option value="{{ $proj->id }}"
                        {{ old('project_id', $project->id ?? '') == $proj->id ? 'selected' : '' }}>
                        {{ $proj->project_name }}
                    </option>
                    @endforeach

                </select>

                @error('project_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            {{-- Document Name --}}
            <div class="mb-3">
                <label class="form-label">Document Name</label>
                <input type="text"
                    name="document_name"
                    class="form-control @error('document_name') is-invalid @enderror"
                    value="{{ old('document_name') }}"
                    placeholder="Enter Document Name">

                @error('document_name')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Upload --}}
            <label class="form-label">Upload Document</label>

            <label class="upload-box w-100">

                <input type="file"
                    name="image"
                    id="fileInput"
                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                    hidden>

                <div class="upload-area">

                    <div id="uploadIcon">⬆ Upload</div>

                    <img id="previewImage" class="d-none">

                    <!-- FILE NAME -->
                    <div id="fileName" class="d-none"></div>

                    <div id="uploadtexts">
                        <h6>Drag & Drop or Click</h6>
                        <small>JPG, PNG, PDF, DOC (Max 10MB)</small>
                    </div>

                </div>
            </label>

            @error('image')
            <div class="text-danger mt-2">{{ $message }}</div>
            @enderror

            <div class="text-center mt-4">
                <button type="submit" id="submitBtn" class="btn btn-warning w-100">
                    Submit
                </button>
            </div>

        </form>

    </div>
</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        let uploadArea = $('.upload-area');
        let fileInput = $('#fileInput');
        let isSubmitting = false;

        // 🔥 DRAG OVER
        uploadArea.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragging');
        });

        // 🔥 DRAG LEAVE
        uploadArea.on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('dragging');
        });

        // 🔥 DROP
        uploadArea.on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragging');

            let files = e.originalEvent.dataTransfer.files;

            if (files.length > 0) {
                fileInput[0].files = files;
                previewFile(files[0]);
            }
        });

        // 🔥 INPUT CHANGE
        fileInput.on('change', function() {
            let file = this.files[0];
            if (file) {
                previewFile(file);
            }
        });

        // 🔥 PREVIEW FUNCTION
        function previewFile(file) {

            let fileType = file.type;
            let fileName = file.name;

            $('#previewImage').addClass('d-none');
            $('#fileName').addClass('d-none');

            // IMAGE
            if (fileType.startsWith('image/')) {

                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#previewImage')
                        .attr('src', e.target.result)
                        .removeClass('d-none');
                };

                reader.readAsDataURL(file);

            } else {
                // PDF / DOC
                $('#fileName')
                    .removeClass('d-none')
                    .html('📄 ' + fileName);
            }

            $('#uploadIcon').hide();
            $('#uploadtexts').hide();
        }

        // 🔥 SUBMIT PROTECTION
        $('form').on('submit', function() {

            if (isSubmitting) return false;

            isSubmitting = true;

            let btn = $('#submitBtn');
            btn.prop('disabled', true);
            btn.html('Processing...');
        });

    });
</script>

@endsection