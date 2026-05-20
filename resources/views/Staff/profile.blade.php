@extends('Staff.layout')

@section('content')

@php
$user = Auth::guard('staff')->user();
@endphp

<div class="row me-3">
    <div class="card m-3">
        <h4 class="mt-2">Profile</h4>

        <form id="login_form" method="POST" action="{{ route('update_profile_staff') }}" enctype="multipart/form-data">
            @csrf

            <div class="d-flex align-items-start align-items-sm-center gap-6 mt-2">

                <img src="{{ $staff->profile_image && file_exists(public_path('storage/' . $staff->profile_image))
        ? asset('storage/' . $staff->profile_image)
        : asset('assets/img/dp.jpg') }}" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">


                <div class="button-wrapper">
                    <label for="upload" class="btn btn-primary me-3 mb-4">
                        Upload
                    </label>

                    <input type="file" id="upload" name="profile_image" hidden accept="image/png, image/jpeg">

                    <div>Allowed JPG or PNG. Max size 800K</div>
                </div>
            </div>

            <!-- FORM FIELDS -->
            <div class="card-body pt-4">
                <div class="row gy-4 gx-6 mb-6">

                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input class="form-control" type="text" name="name" value="{{ $staff->name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Employee ID</label>
                        <input class="form-control" type="text" name="employee_id" value="{{ $staff->user_id }}" readonly >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Office Email</label>
                        <input class="form-control" type="email" name="email" value="{{ $staff->email }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Personal Email</label>
                        <input class="form-control" type="email" name="personal_email"
                            value="{{ $staff->personal_email }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input class="form-control" type="text" name="mobile" value="{{ $staff->mobile }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" value="{{ $staff->dob }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Designation</label>
                        <input type="text" class="form-control" name="designation" value="{{ $staff->designation }}">
                    </div>


                    <div class="col-md-4">
                        <label class="form-label">Joining Date</label>
                        <input type="date" class="form-control" name="doj" value="{{ $staff->doj }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Address</label>
                        <input class="form-control" type="text" name="address" value="{{ $staff->address }}">
                    </div>
                </div>
                <div class="text-center">

                    <button type="button" class="btn btn-primary" id="finalSubmit">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('finalSubmit').addEventListener('click', function() {
        this.disabled = true;
        this.innerText = 'Processing...';
        document.getElementById('login_form').submit();
    });
</script>

<!-- IMAGE PREVIEW SCRIPT -->
<script>
    document.getElementById('upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('uploadedAvatar');

        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only JPG and PNG images are allowed');
            event.target.value = '';
            return;
        }

        const maxSize = 800 * 1024;
        if (file.size > maxSize) {
            alert('Image must be less than 800KB');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(file);
    });
</script>

@endsection