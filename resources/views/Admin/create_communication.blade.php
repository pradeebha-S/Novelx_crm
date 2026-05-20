@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
<style>
   #typeFilter.is-invalid{
    border: 1px solid #dc3545 !important;
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.15rem rgba(220,53,69,.25) !important;
}
    .status-dropdown {
        font-weight: 600;
        padding: 4px 12px;
    }
.uploaded-file-item {
    background: #f8f9ff;
}

.remove-file-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
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
.remove-file-btn {
    width: 34px;
    height: 34px;
    border: none;
    border-radius: 50%;
    background: #ffe5e5;
    color: #ff3b3b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.2s ease;
}

.remove-file-btn:hover {
    background: #ff3b3b;
    color: #fff;
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
    .uploaded-file-item {
    background: #fff;
}

.remove-file-btn {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border: none;
    outline: none;
    border-radius: 10px;
    background: #f3f4f6;
    color: #ef4444;

    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;
    transition: all 0.2s ease;
}

.remove-file-btn i {
    font-size: 18px;
    font-weight: 600;
}

.remove-file-btn:hover {
    background: #ef4444;
    color: #fff;
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
.upload-wrapper {
    width: 100%;
    min-height: 220px;
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
.is-invalid{
    border: 1px solid red !important;
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
        <h5>
            Create Communication
        </h5>
    </div>
    <div class="col-auto">
        <a href="{{ route('mail_table') }}"><button class="btn btn-primary">Mail</button></a>
    </div>

</div>
<form
    id="mailComposerForm"
    action="{{ route('store_communication') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    <div class="card mb-2 p-4 w-100">

        <h6>Create Communication</h6>

        <div class="row g-3">

            <!-- Employee -->

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                <label class="form-label">
                    Employee
                </label>

                <div class="multiselect-container">

                   
                     <div   class="form-select"
            id="typeFilter"
            style="cursor:pointer;"
            tabindex="0">


    <span id="typeSelectedText">
        Select Employee
    </span>

</div>
                    <div
                        class="multiselect-dropdown"
                        id="typeDropdown">

                        <div class="multiselect-header">

                            <button
                                type="button"
                                class="multiselect-clear"
                                id="typeClearBtn">

                                Clear All

                            </button>

                            <button
                                type="button"
                                class="multiselect-clear"
                                id="typeSelectBtn">

                                Select All

                            </button>

                        </div>

                        @foreach($employees as $employee)

                        <div
                            class="multiselect-item">

                            <span>
                                {{ $employee->name }}
                            </span>

                            <input
                                type="checkbox"
                                name="employee_ids[]"
                                value="{{ $employee->id }}">

                        </div>

                        @endforeach

                    </div>

                </div>

                @error('employee_ids')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
 <small
        class="text-danger d-none"
        id="employeeError">

        Please select at least one employee

    </small>
            </div>

            <!-- Communication Type -->

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                <label class="form-label">
                    Communication Type
                </label>

                <select
                    class="form-select"
                    name="communication_type"
                    required>

                    <option value="">
                        Select
                    </option>

                    <option value="Warning">
                        Warning
                    </option>

                    <option value="Disciplinary Issue">
                        Disciplinary Issue
                    </option>

                    <option value="Common Notice">
                        Common Notice
                    </option>

                    <option value="General Information">
                        General Information
                    </option>

                </select>

                @error('communication_type')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror

            </div>

            <!-- Priority -->

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                <label class="form-label">
                    Priority Level
                </label>

                <select
                    class="form-select"
                    name="priority_level"
                    required>

                    <option value="">
                        Select
                    </option>

                    <option value="High">
                        High
                    </option>

                    <option value="Medium">
                        Medium
                    </option>

                    <option value="Low">
                        Low
                    </option>

                </select>

                @error('priority_level')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror

            </div>

            <!-- Reply Needed -->

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                <label class="form-label">
                    Is Reply Needed
                </label>

                <select
                    class="form-select"
                    name="reply_needed"
                    required>

                    <option value="">
                        Select
                    </option>

                    <option value="Yes">
                        Yes
                    </option>

                    <option value="No">
                        No
                    </option>

                </select>

                @error('reply_needed')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror

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

                        <h3 class="mb-1 fw-bold">
                            Compose Mail
                        </h3>

                        <p class="text-muted mb-0">
                            Create and send professional email communication
                        </p>

                    </div>

                </div>

                <!-- Subject -->

                <div class="form-floating mb-4">

                    <input
                        type="text"
                        class="form-control custom-input"
                        id="mailSubject"
                        name="subject"
                        placeholder="Enter mail subject"
                        required>

                    <label for="mailSubject">

                        <i class="ti tabler-heading me-2"></i>

                        Email Subject

                    </label>

                </div>

                <!-- Content -->

                <div class="mb-4">

                    <label
                        for="mailContent"
                        class="form-label fw-semibold mb-2">

                        <i class="ti tabler-pencil-check me-2"></i>

                        Mail Content

                    </label>

                    <textarea
                        class="form-control custom-textarea"
                        id="mailContent"
                        name="content"
                        placeholder="Write your email content here..."
                        rows="10"
                        required></textarea>

                </div>

                <!-- Attachment -->

                <div class="mb-4">

                    <label class="form-label fw-semibold mb-3">

                        <i class="ti tabler-paperclip me-2"></i>

                        Attachments

                    </label>

                    <div
                        class="upload-wrapper rounded-4 position-relative"
                        id="dropZone">

                        <input
                            type="file"
                            class="file-input"
                            id="attachments"
                            name="attachments[]"
                            multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.jpeg,.png">

                        <div class="upload-content text-center">

                            <div class="upload-icon mb-3">

                                <i class="ti tabler-upload"></i>

                            </div>

                            <h6 class="fw-semibold mb-2">
                                Drag & Drop files here
                            </h6>

                            <p class="text-muted small mb-2">
                                or click to browse files
                            </p>

                            <span class="upload-supported">
                                PDF, DOC, XLS, ZIP, JPG, PNG
                            </span>

                        </div>

                    </div>

                    <div
                        class="uploaded-files-list mt-3"
                        id="uploadedFilesList"></div>

                </div>

                <!-- Submit -->

                <div class="d-flex justify-content-center">

                    <button
                        type="submit"
                        class="btn btn-primary send-btn gap-2"
                        id="sendMailBtn">

                        <span class="btn-text">

                            <i class="ti tabler-plane"></i>

                            Send Mail

                        </span>

                        <span class="btn-loader d-none">

                            <span class="spinner-border spinner-border-sm"></span>

                            Sending...

                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>

</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>


<script>
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE MULTISELECT DROPDOWN
    |--------------------------------------------------------------------------
    */

    let selectedTypes = [];

    $('#typeFilter').on('click', function (e) {
        e.stopPropagation();
        $('#typeDropdown').toggle();
    });

    $('#typeDropdown .multiselect-item input').on('change', function () {

        const label = $(this)
            .closest('.multiselect-item')
            .find('span')
            .text()
            .trim();

        if ($(this).is(':checked')) {

            if (!selectedTypes.includes(label)) {
                selectedTypes.push(label);
            }

        } else {

            selectedTypes = selectedTypes.filter(
                item => item !== label
            );
        }

        updateTypeDisplay();
    });

    $('#typeClearBtn').on('click', function () {

        $('#typeDropdown input').prop('checked', false);

        selectedTypes = [];

        updateTypeDisplay();
    });

    $('#typeSelectBtn').on('click', function () {

        $('#typeDropdown input').prop('checked', true);

        selectedTypes = [];

        $('#typeDropdown .multiselect-item span').each(function () {
            selectedTypes.push($(this).text().trim());
        });

        updateTypeDisplay();
    });

    function updateTypeDisplay() {

        if (selectedTypes.length === 0) {

            $('#typeSelectedText').text('Select Employee');

        } else if (selectedTypes.length === 1) {

            $('#typeSelectedText').text(selectedTypes[0]);

        } else {

            $('#typeSelectedText').text(selectedTypes.length + ' selected');
        }
    }

    $(document).on('click', function (e) {

        if (!$(e.target).closest('.multiselect-container').length) {
            $('.multiselect-dropdown').hide();
        }
    });

});


/*
|--------------------------------------------------------------------------
| FILE UPLOAD + DRAG DROP
|--------------------------------------------------------------------------
*/
const fileInput = document.getElementById('attachments');
const uploadedFilesList = document.getElementById('uploadedFilesList');
const dropZone = document.getElementById('dropZone');
const sendBtn = document.getElementById('sendMailBtn');

let selectedFiles = [];

/*
|--------------------------------------------------------------------------
| FILE ICON
|--------------------------------------------------------------------------
*/

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
        case 'webp':
            return 'ti tabler-photo';

        default:
            return 'ti tabler-file';
    }
}

/*
|--------------------------------------------------------------------------
| UPDATE INPUT FILES
|--------------------------------------------------------------------------
*/

function updateInputFiles() {

    const dt = new DataTransfer();

    selectedFiles.forEach(file => {
        dt.items.add(file);
    });

    fileInput.files = dt.files;
}

/*
|--------------------------------------------------------------------------
| RENDER FILES
|--------------------------------------------------------------------------
*/

function renderFiles() {

    uploadedFilesList.innerHTML = '';

    selectedFiles.forEach((file, index) => {

        const extension = file.name
            .split('.')
            .pop()
            .toLowerCase();

        uploadedFilesList.innerHTML += `
            <div class="uploaded-file-item d-flex align-items-center justify-content-between mb-2 p-2 border rounded">

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

    updateInputFiles();
}

/*
|--------------------------------------------------------------------------
| REMOVE FILE
|--------------------------------------------------------------------------
*/

function removeFile(index) {

    selectedFiles.splice(index, 1);

    renderFiles();
}

/*
|--------------------------------------------------------------------------
| INPUT CHANGE
|--------------------------------------------------------------------------
*/

fileInput.addEventListener('change', function () {

    selectedFiles = [
        ...selectedFiles,
        ...Array.from(this.files)
    ];

    renderFiles();
});

/*
|--------------------------------------------------------------------------
| DRAG ENTER / OVER
|--------------------------------------------------------------------------
*/

['dragenter', 'dragover'].forEach(eventName => {

    dropZone.addEventListener(eventName, function (e) {

        e.preventDefault();
        e.stopPropagation();

        dropZone.classList.add('drag-over');

    });
});

/*
|--------------------------------------------------------------------------
| DRAG LEAVE
|--------------------------------------------------------------------------
*/

['dragleave', 'drop'].forEach(eventName => {

    dropZone.addEventListener(eventName, function (e) {

        e.preventDefault();
        e.stopPropagation();

        dropZone.classList.remove('drag-over');

    });
});

/*
|--------------------------------------------------------------------------
| DROP FILES
|--------------------------------------------------------------------------
*/

dropZone.addEventListener('drop', function (e) {

    const files = Array.from(e.dataTransfer.files);

    selectedFiles = [
        ...selectedFiles,
        ...files
    ];

    renderFiles();
});

/*
|--------------------------------------------------------------------------
| CLICK DROPZONE
|--------------------------------------------------------------------------
*/

dropZone.addEventListener('click', function () {
    fileInput.click();
});

/*
|--------------------------------------------------------------------------
| SUBMIT LOADER
|--------------------------------------------------------------------------
*/

document.getElementById('mailComposerForm')
.addEventListener('submit', function () {

    sendBtn.disabled = true;

    sendBtn.querySelector('.btn-text')
        .classList.add('d-none');

    sendBtn.querySelector('.btn-loader')
        .classList.remove('d-none');
});
</script>
<script>

document.querySelector("#mailComposerForm")
.addEventListener("submit", function(e){

    let checkedEmployees =
        document.querySelectorAll(
            'input[name="employee_ids[]"]:checked'
        );

    let typeFilter =
        document.getElementById("typeFilter");

    let employeeError =
        document.getElementById("employeeError");

    if(checkedEmployees.length === 0){

        e.preventDefault();

        typeFilter.classList.add("is-invalid");

        employeeError.classList.remove("d-none");

        typeFilter.focus();

    } else {

        typeFilter.classList.remove("is-invalid");

        employeeError.classList.add("d-none");
    }

});

</script>
@endsection