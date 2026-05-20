@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
<style>
    .status-dropdown {
        font-weight: 600;
        padding: 4px 12px;
    }

    .status-success {
        background-color: #d1f7e0;
        color: #198754;
        border: 1px solid #198754;
    }

    .status-failed {
        background-color: #fde2e2;
        color: #dc3545;
        border: 1px solid #dc3545;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #ffc107;
        border: 1px solid #ffc107;
    }

    .dt-search {
        display: none;
    }

    .status-pill {
        border-radius: 2px;
        padding: 2px !important;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid transparent;
        background-position: right 8px center;
        background-repeat: no-repeat;
    }

    .status-success {
        color: #28c76f;
        background-color: rgba(40, 199, 111, 0.12);
        border-color: #28c76f;
    }

    .status-failed {
        color: #ea5455;
        background-color: rgba(234, 84, 85, 0.12);
        border-color: #ea5455;
    }

    .card.a {
        border-left: 6px solid #34C759;
    }

    .card.b {
        border-left: 6px solid #FF8D28;
    }

    .card.c {
        border-left: 6px solid #FF383C;
    }

    .card.d {
        border-left: 6px solid #0088FF;
    }

    .card.e {
        border-left: 6px solid #09A8C3;
    }

    /* Multi-select dropdown styles */
    .multiselect-container {
        position: relative;
    }

    .multiselect-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1050;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        display: none;
    }

    .multiselect-item {
        padding: 0.375rem 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
    }

    .multiselect-item:hover {
        background-color: #f8f9fa;
    }

    .multiselect-item input[type="checkbox"] {
        margin-right: 0.5rem;
        width: 16px;
        height: 16px;
    }

    .multiselect-selected {
        background-color: #e3f2fd;
        font-weight: 500;
    }

    .multiselect-header {
        padding: 0.5rem 0.75rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        display: flex;
        justify-content: flex-end;
    }

    .multiselect-clear {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .multiselect-clear:hover {
        color: #dc3545;
    }

    .details.mb-3 {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-light: rgba(79, 70, 229, 0.12);
    }

    .mail-composer-card {
        background: #fff;
        max-width: 100%;
        transition: all 0.3s ease;
    }

    .mail-icon-wrapper {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg,
                var(--primary-color),
                #6d63ff);
        color: #fff;
        font-size: 1.2rem;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
    }

    .custom-input,
    .custom-textarea {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        padding: 1rem 1rem;
        transition: all 0.3s ease;
        background: #fff;
    }

    .custom-input:focus,
    .custom-textarea:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem var(--primary-light);
    }

    .custom-textarea {
        min-height: 250px;
        resize: vertical;
    }

    /* Styled Scrollbar */
    .custom-textarea::-webkit-scrollbar {
        width: 8px;
    }

    .custom-textarea::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 50px;
    }

    .upload-wrapper {
        border: 2px dashed #d1d5db;
        background: #fafafa;
        padding: 2.5rem 1.5rem;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .upload-wrapper:hover,
    .upload-wrapper.drag-over {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }

    .file-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        width: 70px;
        height: 70px;
        margin: auto;
        border-radius: 50%;
        background: rgba(79, 70, 229, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .upload-supported {
        display: inline-block;
        background: #fff;
        padding: 0.4rem 0.9rem;
        border-radius: 30px;
        border: 1px solid #e5e7eb;
        font-size: 0.8rem;
    }

    .uploaded-file-item {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 14px 16px;
        transition: all 0.3s ease;
    }

    .uploaded-file-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .file-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary-color);
        font-size: 1rem;
    }

    .remove-file-btn {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 50%;
        background: #f3f4f6;
        transition: all 0.3s ease;
    }

    .remove-file-btn:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .send-btn {

        border: none;
        color: #fff !important;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
    }

    .send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(79, 70, 229, 0.3);
    }

    .reset-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .form-floating>label {
        padding-left: 1rem;
    }

    /* Mobile */
    @media (max-width: 768px) {

        .mail-composer-card {
            padding: 1.5rem !important;
        }

        .send-btn,
        .reset-btn {
            width: 100%;
        }

        .upload-wrapper {
            padding: 2rem 1rem;
        }
    }
</style>
@section('content')
   <div class="row d-flex justify-content-between">
    <div class="col-auto">
         <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2" style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('mail_table') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M15 6l-6 6l6 6"></path>
                    </svg>
                </a>
            </button>
        View Communication
    </h5>
    </div>


   </div>
    <div class="card mb-2 p-4 w-100">
        <h6>View Communication</h6>
        <div class="row g-3">

           {{-- EMPLOYEE --}}

        <div class="col-md-3">

            <label class="form-label">Employee</label>

            <input type="text"
                class="form-control"
                value="{{ $communication->user->name ?? '-' }}"
                readonly>

        </div>
               {{-- COMMUNICATION TYPE --}}

        <div class="col-md-3">

            <label class="form-label">Communication Type</label>

            <input type="text"
                class="form-control"
                value="{{ $communication->communication_type }}"
                readonly>

        </div>

        {{-- PRIORITY --}}

        <div class="col-md-3">

            <label class="form-label">Priority Level</label>

            <input type="text"
                class="form-control"
                value="{{ $communication->priority_level }}"
                readonly>

        </div>

        {{-- REPLY NEEDED --}}

        <div class="col-md-3">

            <label class="form-label">Reply Needed</label>

            <input type="text"
                class="form-control"
                value="{{ $communication->reply_needed }}"
                readonly>

        </div>


        </div>
    </div>
    <div class="row justify-content-center">
            <div class="col-12 col-lg-12">

                <div class="card mail-composer-card border-0 shadow-sm rounded-4 p-4">

                    <!-- Header -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="mail-icon-wrapper">
                            <i class="ti tabler-mail"></i>
                        </div>

                        <div>
                            <h3 class="mb-1 fw-bold">Composed Mail</h3>

                        </div>
                    </div>

                   
                        <!-- Subject -->
                        <div class="form-floating mb-4">
                              <input type="text"
            class="form-control custom-input"
            value="{{ $communication->subject }}"
            readonly>


                            <label for="mailSubject">
                                <i class="ti tabler-heading me-2"></i>
                                Email Subject
                            </label>
                        </div>

                        <!-- Mail Content -->
                        <div class="mb-4">
                            <label for="mailContent" class="form-label fw-semibold mb-2">
                                <i class="ti tabler-pencil-check me-2"></i>
                                Mail Content
                            </label>

                            <textarea class="form-control custom-textarea"
            readonly>{{ $communication->content }}</textarea>

                            </div>

                      {{-- ATTACHMENTS --}}

    <div class="mb-4">

        <label class="form-label fw-semibold">

            Attachments

        </label>

        @if($communication->attachments->count() > 0)

            @foreach($communication->attachments as $file)

                <div class="uploaded-file-item">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <i class="ti tabler-paperclip"></i>

                            {{ $file->file_name }}

                        </div>

                        <a href="{{ asset('storage/' . $file->file_path) }}"
                            target="_blank"
                            class="btn btn-sm btn-primary">

                          View

                        </a>

                    </div>

                </div>

            @endforeach

        @else

            <p class="text-muted">
                No Attachments
            </p>

        @endif

    </div>



                    </form>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {

            /* ---------- MULTI SELECT FILTERS ---------- */

            let selectedTypes = [];
            let selectedProducts = [];

            $('#typeFilter').on('click', function (e) {
                e.stopPropagation();
                $('#typeDropdown').toggle();
            });

            $('#typeDropdown .multiselect-item input').on('change', function () {

                const value = $(this).val();

                if ($(this).is(':checked')) {
                    if (!selectedTypes.includes(value)) selectedTypes.push(value);
                } else {
                    selectedTypes = selectedTypes.filter(item => item !== value);
                }

                updateTypeDisplay();
            });

            $('#typeClearBtn').on('click', function () {
                $('#typeDropdown input').prop('checked', false);
                selectedTypes = [];
                updateTypeDisplay();
            });

            function updateTypeDisplay() {

                if (selectedTypes.length === 0) {
                    $('#typeSelectedText').text('All Type');
                }
                else if (selectedTypes.length === 1) {
                    $('#typeSelectedText').text(selectedTypes[0]);
                }
                else {
                    $('#typeSelectedText').text(selectedTypes.length + ' selected');
                }

            }

            $('#productFilter').on('click', function (e) {
                e.stopPropagation();
                $('#productDropdown').toggle();
            });

            $('#productDropdown .multiselect-item input').on('change', function () {

                const value = $(this).val();

                if ($(this).is(':checked')) {
                    if (!selectedProducts.includes(value)) selectedProducts.push(value);
                } else {
                    selectedProducts = selectedProducts.filter(item => item !== value);
                }

                updateProductDisplay();

            });

            $('#productClearBtn').on('click', function () {
                $('#productDropdown input').prop('checked', false);
                selectedProducts = [];
                updateProductDisplay();
            });

            function updateProductDisplay() {

                if (selectedProducts.length === 0) {
                    $('#productSelectedText').text('All Products');
                }
                else if (selectedProducts.length === 1) {
                    $('#productSelectedText').text(selectedProducts[0]);
                }
                else {
                    $('#productSelectedText').text(selectedProducts.length + ' selected');
                }

            }

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.multiselect-container').length) {
                    $('.multiselect-dropdown').hide();
                }
            });


        });
    </script>

    <script>
        const fileInput = document.getElementById('attachments');
        const uploadedFilesList = document.getElementById('uploadedFilesList');
        const dropZone = document.getElementById('dropZone');
        const sendBtn = document.getElementById('sendMailBtn');

        let selectedFiles = [];

        // Tabler File Type Icons
        function getFileIcon(extension) {

            switch (extension) {

                case 'pdf':
                    return 'ti tabler-file-type-pdf';

                case 'doc':
                case 'docx':
                    return 'ti tabler-file-type-doc';

                case 'xls':
                case 'xlsx':
                    return 'ti tabler-file-type-xls';

                case 'zip':
                    return 'ti tabler-file-zip';

                case 'jpg':
                case 'jpeg':
                case 'png':
                    return 'ti tabler-photo';

                default:
                    return 'ti tabler-file';
            }
        }

        function renderFiles() {

            uploadedFilesList.innerHTML = '';

            selectedFiles.forEach((file, index) => {

                const extension = file.name.split('.').pop().toLowerCase();

                uploadedFilesList.innerHTML += `
                    <div class="uploaded-file-item d-flex align-items-center justify-content-between mb-2">

                        <div class="d-flex align-items-center gap-3">

                            <div class="file-icon">
                                <i class="${getFileIcon(extension)}"></i>
                            </div>

                            <div>
                                <div class="fw-semibold text-truncate">
                                    ${file.name}
                                </div>

                                <small class="text-muted">
                                    ${(file.size / 1024).toFixed(1)} KB
                                </small>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="remove-file-btn"
                            onclick="removeFile(${index})"
                        >
                            <i class="ti tabler-x"></i>
                        </button>
                    </div>
                `;
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderFiles();
        }

        fileInput.addEventListener('change', function (e) {

            selectedFiles = [
                ...selectedFiles,
                ...Array.from(e.target.files)
            ];

            renderFiles();
        });

        // Drag Events
        ['dragenter', 'dragover'].forEach(eventName => {

            dropZone.addEventListener(eventName, e => {

                e.preventDefault();
                dropZone.classList.add('drag-over');

            });
        });

        ['dragleave', 'drop'].forEach(eventName => {

            dropZone.addEventListener(eventName, e => {

                e.preventDefault();
                dropZone.classList.remove('drag-over');

            });
        });

        dropZone.addEventListener('drop', e => {

            const files = Array.from(e.dataTransfer.files);

            selectedFiles = [
                ...selectedFiles,
                ...files
            ];

            renderFiles();

        });

        // Loading State
        document.getElementById('mailComposerForm')
            .addEventListener('submit', function () {

                sendBtn.disabled = true;

                sendBtn.querySelector('.btn-text')
                    .classList.add('d-none');

                sendBtn.querySelector('.btn-loader')
                    .classList.remove('d-none');
            });
    </script>
@endsection