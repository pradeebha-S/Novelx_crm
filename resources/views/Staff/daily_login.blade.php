@extends('Staff.layout')
<style>
    .image-upload {
        border: 2px dashed #ddd;
        border-radius: 6px;
        cursor: pointer;
        height: 160px;
        width: 100%;
        padding: 12px;
    }
    .image-upload span {
        font-size: 14px;
    }
    .upload-box {
        border: 2px dashed #999;
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .upload-box img {
        width: 140px;
        height: auto;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .upload-box p {
        color: #444;
        font-weight: 600;
    }
    .upload-box small {
        color: #777;
    }
</style>
@section('content')
<div class="d-flex justify-content-center align-items-center mb-2">
    <div class="d-flex align-items-center gap-3 mb-3">
        <h5 class="mb-0">Check In
            <span id="currentDateTime" class="badge bg-label-info text-dark"></span>
        </h5>
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
        <div class="card shadow-sm rounded-4 p-3">
            <div class="card-body">
                <div class="row g-0">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="border rounded-0 h-100 text-center p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">New Tasks</span>
                                <span class="fw-bold">{{ $newTasks }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="border rounded-0 h-100 text-center p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Completed Tasks</span>
                                <span class="fw-bold">{{ $completedTasks }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="border rounded-0 h-100 text-center p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Pending Tasks</span>
                                <span class="fw-bold">{{ $pendingTasks}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="border rounded-0 h-100 text-center p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Hold Tasks</span>
                                <span class="fw-bold">{{ $holdTasks }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Task Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="taskTable">
                        @foreach ($newTaskList as $index => $task)
                        <tr class="task-row {{ $index >= 5 ? 'd-none' : '' }}">
                            <td>{{ $task->project->project_name ?? '-' }}</td>
                            <td>{{ $task->task_name }}</td>
                            <td>
                                @if(optional($task->project)->created_at?->isToday())
                                <span class="badge bg-success">New</span>
                                @else
                                <span class="badge bg-danger">Not completed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button id="showMoreBtn" class="btn btn-sm btn-outline-primary mt-2">
                    Show More
                </button>
            </div>
            <form action="{{ route('daily_login_form') }}" method="POST" id="login_form">
                @csrf
                <!-- LATE SECTION -->
                <div id="lateSection" class="d-none text-start mb-3">
                    <label class="form-label fw-semibold">
                        Reason <span class="text-danger"></span>
                    </label>
                    <input type="text"
                        class="form-control flex-1"
                        name="late_reason"
                        placeholder="Enter reason if you are late..." required>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-primary px-4" id="finalSubmit">
                        Check In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const uploadBox = document.getElementById('uploadBox');
    const fileInput = document.getElementById('fileInput');
    const previewImage = document.getElementById('previewImage');
    const uploadText = document.getElementById('uploadText');
    uploadBox.addEventListener('click', () => {
        fileInput.click();
    });
    // Handle file selection
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5 MB.');
            fileInput.value = '';
            return;
        }
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
            uploadText.textContent = 'Image uploaded';
        };
        reader.readAsDataURL(file);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const liveTime = document.getElementById('currentDateTime');
        const lateSection = document.getElementById('lateSection');
        const reasonInput = document.querySelector('[name="late_reason"]');
        const submitBtn = document.getElementById('finalSubmit');
        const form = document.getElementById('login_form');
        function getISTTime() {
            const now = new Date();
            return new Date(now.toLocaleString('en-US', {
                timeZone: 'Asia/Kolkata'
            }));
        }
        const lateTime = new Date();
        lateTime.setHours(9, 10, 0, 0);
        function updateDateTime() {
            const now = getISTTime();
            liveTime.innerText =
                now.toLocaleDateString('en-GB') + ' | ' +
                now.toLocaleTimeString('en-US');
            // Late check
            if (now.getTime() >= lateTime.getTime()) {
                lateSection.classList.remove('d-none');
                reasonInput.setAttribute('required', true);
            } else {
                lateSection.classList.add('d-none');
                reasonInput.removeAttribute('required');
            }
        }
        // Start clock
        updateDateTime();
        setInterval(updateDateTime, 1000);
        // Submit handler
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerText = 'Processing...';
            form.submit();
        });
    });
</script>
<script>
    const button = document.getElementById('showMoreBtn');
    const rows = document.querySelectorAll('.task-row');
    let expanded = false;
    button.addEventListener('click', function() {
        if (!expanded) {
            // SHOW ALL
            rows.forEach(row => row.classList.remove('d-none'));
            button.textContent = 'Hide';
            expanded = true;
        } else {
            // SHOW ONLY FIRST 5
            rows.forEach((row, index) => {
                if (index >= 5) {
                    row.classList.add('d-none');
                }
            });
            button.textContent = 'Show More';
            expanded = false;
        }
    });
</script>
<script>
    if (/Mobi|Android|iPhone|iPad/i.test(navigator.userAgent)) {
        alert("Mobile login is not allowed. Please use desktop.");
        window.location.href = "{{ route('staff.dashboard') }}";
    }
</script>
@endsection