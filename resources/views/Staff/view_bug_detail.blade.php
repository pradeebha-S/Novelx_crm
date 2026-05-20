@extends('Staff.layout')
<style>
    .Bug-log-row {
        min-height: 80px;
    }
    .col-date {
        width: 20%;
        font-weight: 500;
    }
    .col-type {
        width: 20%;
        text-align: center;
        font-weight: 500;
    }
    .col-time {
        width: 15%;
        font-weight: 500;
    }
    .col-remark {
        width: 60%;
        font-size: 14px;
        line-height: 1.5;
        padding-right: 6px;
        word-break: break-word;
    }
    .col-remark::-webkit-scrollbar {
        width: 4px;
    }
    .col-remark::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }
    .upload-box {
        border: 2px dashed #dce1e7;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
    }
    .upload-box:hover {
        background: #f8f9fa;
    }
    .upload-box.dragover {
        border-color: #7367f0;
        background: #f4f4ff;
    }
    .preview-thumb {
        max-height: 120px;
        margin-top: 10px;
        border-radius: 8px;
    }
</style>
@section('content')
    <div class="col-auto">
        <h5 class="d-flex align-items-center">
            <button type="button" class="btn btn-icon bg-white me-2" style="box-shadow:0px 9px 12px -2px #66328E1F;"
                onclick="history.back()">
                <i class="ti tabler-chevron-left text-dark"></i>
            </button>
            <i class="ti tabler-bug text-danger me-2"></i>
            Bug Details
        </h5>
    </div>
    <div class="row align-items-stretch mb-4 g-2">
        <div class="col-lg-4 col-md-6 d-flex">
            <div class="card shadow-sm border-2 rounded-3 p-3 h-100 w-100">
                <div class="row p-5 align-items-center">
                    <div class="col-5 d-flex align-items-center gap-2">
                        <i class="ti tabler-refresh text-warning"></i>
                        <h6 class="mb-0">Reopen</h6>
                    </div>
                    <div class="col-7 d-flex justify-content-between align-items-center">
                        <p class="mb-0">{{ $bug->reopen_count }}</p>
                        <button type="button" onclick="openReopenModal({{ $bug->id }})" class="btn btn-warning btn-sm">
                            Reopen
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 d-flex">
            <div class="card shadow-sm border-2 rounded-3 p-3 h-100 w-100">
                <div class="row p-5 align-items-center">
                    <div class="col-6 d-flex align-items-center gap-2">
                        <i class="ti tabler-code text-success"></i>
                        <h6 class="mb-0">Status</h6>
                    </div>
                    @php
                        $latestLog = $bug->logs->last();
                    @endphp
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <a class="text-warning fw-bold" data-bs-toggle="modal" data-bs-target="#TestStatusModal"
                            style="cursor:pointer;">
                            {{ $latestLog->status ?? 'Not Provided' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 d-flex">
            <div class="card shadow-sm border-2 rounded-3 p-3 h-100 w-100">
                <div class="row p-5 align-items-center">
                    <div class="col-6 d-flex align-items-center gap-2">
                        <i class="ti tabler-photo text-success"></i>
                        <h6 class="mb-0">Attachments</h6>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <a class="text-warning fw-bold text-decoration-underline" data-bs-toggle="modal"
                            data-bs-target="#imageModal" style="cursor:pointer;">View</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-2">
        <div class="col-lg-6">
            <div class="col-lg-12 mb-3">
                <div class="card shadow-sm border-2 rounded-3 p-3">
                    <h6 class="d-flex align-items-center gap-2">
                        <i class="ti tabler-stethoscope text-danger"></i>
                        Testing Scenario
                    </h6>
                    <div class="text-muted">
{!! str_replace('.', '.<br>', $bug->testing_scenario ?? 'Not Provided') !!}                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card shadow-sm border-2 rounded-3 p-3">
                    <h6 class="d-flex align-items-center gap-2">
                        <i class="ti tabler-alert-triangle text-danger"></i>
                        Actual Output
                    </h6>
                    <div class="text-muted">
                        <!-- {{ $bug->current_output ?? 'Not Provided' }} -->
                        {!! str_replace('.', '.<br>', $bug->current_output ?? 'Not Provided') !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-3">
                <div class="card shadow-sm border-2 rounded-3 p-3">
                    <h6 class="d-flex align-items-center gap-2">
                        <i class="ti tabler-check text-success"></i>
                        Expected Output
                    </h6>
                    <div class="text-muted">
                        <!-- {{ $bug->expected_output ?? 'Not Provided' }} -->
                        {!! str_replace('.', '.<br>', $bug->expected_output ?? 'Not Provided') !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-3">
                <div class="card shadow-sm border-2 rounded-3 p-3">
                    <h6 class="d-flex align-items-center gap-2">
                        <i class="ti tabler-menu text-success"></i>
                        Suggestions
                    </h6>
                    <div class="text-muted">
                      {!! str_replace('.', '.<br>', $bug->suggestion ?? 'Not Provided') !!} </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-2 rounded-3 p-2 h-100 overflow-auto" style="max-height:403px;">
                <div class="card-body">
                    <p class="text-black mb-2 d-flex align-items-center gap-2">
                        <i class="ti tabler-list-details text-primary"></i>
                        Bug Log
                    </p>
                    <div class="border rounded-1 p-2 w-100 d-flex align-items-start mt-2">
                        <div class="col-date fw-bold">Date</div>
                        <div class="col-type fw-bold">Type</div>
                        <div class="col-remark fw-bold">Remark</div>
                    </div>
                    @foreach($bug->logs as $log)
                        <div class="Bug-log-row border rounded-1 p-3 d-flex align-items-start mt-2">
                            <div class="col-date">
                                {{ $log->created_at->format('d/m/Y h:i A') }}
                            </div>
                            <div class="col-type">
                                {{ $bug->moduleData->module_name ?? 'N/A' }}
                            </div>
                            <div class="col-remark">
                                {{ $log->comment ?? '-' }}
                            </div>
                        </div>
                    @endforeach
                    @if($bug->logs->isEmpty())
                        <div class="text-center text-muted mt-3">
                            No logs available for this bug.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="ti tabler-photo text-primary me-1"></i>
                        Uploaded Images
                    </h5>
                    <button type="button" class="btn btn-sm btn-icon btn-label-danger" data-bs-dismiss="modal">
                        <i class="ti tabler-x"></i>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Image Name</th>
                                <th>Uploaded Date</th>
                            </tr>
                        </thead>
                          <tbody>

@php
$attachments = $bug->attachment ? json_decode($bug->attachment, true) : [];
@endphp

@forelse ($attachments as $filePath)

@php
$fileName = basename($filePath);
$fileUrl = asset('storage/' . $filePath);
$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

$fullStoragePath = storage_path('app/public/' . $filePath);

$uploadedDate = file_exists($fullStoragePath)
? \Carbon\Carbon::createFromTimestamp(filemtime($fullStoragePath))->format('d M Y')
: now()->format('d M Y');
@endphp

<tr>

<td>

@if(in_array($ext,['mp4','mov','avi','webm']))

<a href="javascript:void(0);" 
class="text-primary fw-semibold"
data-bs-toggle="modal"
data-bs-target="#imagePreviewModal"
onclick="showVideo('{{ $fileUrl }}')">

🎥 {{ $fileName }}

</a>

@else

<a href="javascript:void(0);" 
class="text-primary fw-semibold"
data-bs-toggle="modal"
data-bs-target="#imagePreviewModal"
onclick="showImage('{{ $fileUrl }}')">

🖼 {{ $fileName }}

</a>

@endif

</td>

<td>
<i class="ti tabler-calendar text-muted me-1"></i>
{{ $uploadedDate }}
</td>

</tr>

@empty

<tr>
<td colspan="2" class="text-center text-muted">
No attachments uploaded
</td>
</tr>

@endforelse

</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- <div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 p-3">
            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-sm btn-label-danger" data-bs-dismiss="modal">
                    <i class="ti tabler-x"></i>
                </button>
            </div>
            <div class="text-center">
                <img id="previewImage" src="" class="img-fluid rounded" alt="Preview">
            </div>
        </div>
    </div>
</div> --}}

                    </table>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content p-3 text-center">

<img id="previewImage" class="img-fluid d-none">

<video id="previewVideo" class="w-100 d-none" controls></video>

</div>

</div>
</div>
    <div class="modal fade" id="TestStatusModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-4">
                <h5 class="fw-bold mb-3 text-center">
                    <i class="ti tabler-refresh text-warning me-1"></i>
                    Update Bug Status
                </h5>
                <input type="hidden" id="bug_id" value="{{ $latestLog->bug_id ?? $bug->id ?? '' }}">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="ti tabler-checklist text-primary me-1"></i>
                        Status
                    </label>
                    <select class="form-select" id="statusSelect">
                        <option value="">Select</option>
                        <option value="Developer Completed">Developer Completed</option>
                        <option value="Testing Completed">Testing Completed</option>
                        <option value="Need Discussion">Need Discussion</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        <i class="ti tabler-message text-info me-1"></i>
                        Remark
                    </label>
                    <textarea class="form-control" rows="3" id="remarkInput"></textarea>
                </div>
                <button type="button" class="btn btn-primary w-100" id="testStatusSubmit">
                    <i class="ti tabler-check me-1"></i> Update
                </button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reopenStatusModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-4">
                <h5 class="fw-bold mb-3 text-center">
                    <i class="ti tabler-refresh text-warning me-1"></i>
                    Reopen Bug
                </h5>
                <form id="reopenForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ti tabler-checklist text-primary me-1"></i>
                            Status
                        </label>
                        <select class="form-select" id="reopen_status" name="status">
                            <option value="">Select</option>
                            <option value="reopened">Reopened</option>
                            <option value="in_progress">In Progress</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ti tabler-message text-info me-1"></i>
                            Remark
                        </label>
                        <textarea class="form-control" id="reopen_remark" name="remark" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ti tabler-photo text-success me-1"></i>
                            Screenshot / Attachment
                        </label>
                        <div class="upload-box text-center p-3" id="uploadBox">
                            <div id="uploadContent">
                                <i class="ti tabler-cloud-upload fs-1 text-primary"></i>
                                <p class="mb-1 mt-2 fw-semibold">Drag & Drop image(s)</p>
                                <small class="text-muted">or click to browse</small>
                            </div>
                            <input type="file" id="fileInput" name="attachments[]" class="d-none" accept="image/*" multiple>
                        </div>
                    </div>
                    <input type="hidden" name="bug_id" id="reopen_bug_id" value="{{ $bug->id ?? '' }}">
                    <button type="submit" class="btn btn-primary w-100 mt-2" id="reopenSubmit">
                        <i class="ti tabler-check me-1"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.openReopenModal = function (bugId) {
                $('#reopen_bug_id').val(bugId);
                $('#reopen_status').val('');
                $('#reopen_remark').val('');
                $('#fileInput').val('');
                $('#uploadContent').show();
                $('#uploadBox img').remove();
                $('#reopenSubmit').prop('disabled', false).html('<i class="ti tabler-check me-1"></i> Update');
                $('#reopenStatusModal').modal('show');
            };
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
                previewImages(fileInput.files);
            });
            fileInput.addEventListener('change', function () {
                previewImages(this.files);
            });
            function previewImages(files) {
                document.querySelectorAll('#uploadBox img').forEach(img => img.remove());
                if (!files || files.length === 0) {
                    uploadContent.style.display = 'block';
                    return;
                }
                uploadContent.style.display = 'none';
                Array.from(files).forEach(file => {
                    let img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.classList.add('img-fluid', 'rounded', 'mt-2', 'preview-thumb');
                    uploadBox.appendChild(img);
                });
            }
            $('#reopenForm').on('submit', function (e) {
                e.preventDefault();
                let status = $('#reopen_status').val();
                if (!status) {
                    toastr.error('Please select a status!');
                    return;
                }
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route("reopen.bug") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $('#reopenSubmit').prop('disabled', true).html('Updating...');
                    },
                    success: function (res) {
                        if (res.success) {
                            $('#reopenStatusModal').modal('hide');
                            toastr.success(res.message || 'Bug status updated successfully!');
                            setTimeout(function () {
                                location.reload();
                            }, 800);
                        } else {
                            if (res.errors) {
                                let errorMsg = Object.values(res.errors).flat().join("\n");
                                toastr.error(errorMsg);
                            } else {
                                toastr.error(res.message || 'Something went wrong.');
                            }
                            $('#reopenSubmit').prop('disabled', false).html('<i class="ti tabler-check me-1"></i> Update');
                        }
                    },
                    error: function (xhr) {
                        let message = 'Server Error. Please try again.';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join("\n");
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message);
                        $('#reopenSubmit').prop('disabled', false).html('<i class="ti tabler-check me-1"></i> Update');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#testStatusSubmit').on('click', function (e) {
                e.preventDefault();
                let bug_id = $('#bug_id').val();
                let status = $('#statusSelect').val();
                let remark = $('#remarkInput').val();
                if (!status) {
                    toastr.error('Please select a status!');
                    return;
                }
                if (!remark.trim()) {
                    toastr.error('Remark is required!');
                    return;
                }
                $('#testStatusSubmit').prop('disabled', true).html('Updating...');
                $.ajax({
                    url: "{{ route('update_bug_status') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bug_id: bug_id,
                        status: status,
                        remark: remark
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message || 'Bug status updated successfully!');
                            let modalEl = document.getElementById('TestStatusModal');
                            let modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 800);
                        } else {
                            if (response.errors) {
                                let errorMsg = Object.values(response.errors).flat().join("\n");
                                toastr.error(errorMsg);
                            } else {
                                toastr.error(response.message || 'Update failed');
                            }
                            $('#testStatusSubmit').prop('disabled', false).html('<i class="ti tabler-check me-1"></i> Update');
                        }
                    },
                    error: function (xhr) {
                        let message = "Something went wrong";
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join("\n");
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message);
                        $('#testStatusSubmit').prop('disabled', false).html('<i class="ti tabler-check me-1"></i> Update');
                    }
                });
            });
        });
    </script>
    {{-- <script>
        function showImage(url) {
            document.getElementById('previewImage').src = url;
        }
    </script> --}}
    <script>
function showImage(url){
document.getElementById('previewVideo').classList.add('d-none');
document.getElementById('previewImage').classList.remove('d-none');
document.getElementById('previewImage').src = url;
}
function showVideo(url){
document.getElementById('previewImage').classList.add('d-none');
let video = document.getElementById('previewVideo');
video.classList.remove('d-none');
video.src = url;
}
</script>
@endsection