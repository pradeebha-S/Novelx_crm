@extends('Admin.layout')
@section('content')

@php
$admin = Auth::guard('admin')->user();
@endphp

<div class="row me-3">
    <div class="card m-3">
        <h4 class="mt-2">Profile</h4>

        <form id="login_form"
            method="POST"
            action="{{ route('update_profile') }}"
            enctype="multipart/form-data">
            @csrf

            <!-- PROFILE IMAGE -->
            <div class="d-flex align-items-start align-items-sm-center gap-6 mt-2">

                <img src="{{ $admin->profile_image && file_exists(public_path('storage/' . $admin->profile_image))
    ? asset('storage/' . $admin->profile_image)
    : asset('assets/img/dp.jpg') }}"
                    class="d-block w-px-100 h-px-100 rounded"
                    id="uploadedAvatar">


                <div class="button-wrapper">
                    <label for="upload" class="btn btn-primary me-3 mb-4">
                        Upload
                    </label>

                    <!-- ✅ ONLY ONE FILE INPUT -->
                    <input type="file"
                        id="upload"
                        name="profile_image"
                        hidden
                        accept="image/png, image/jpeg">

                    <div>Allowed JPG or PNG. Max size 800K</div>
                </div>
            </div>

            <!-- FORM FIELDS -->
            <div class="card-body pt-4">
                <div class="row gy-4 gx-6 mb-6">

                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input class="form-control"
                            type="text"
                            name="name"
                            value="{{ $admin->name }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input class="form-control"
                            type="email"
                            name="email"
                            value="{{ $admin->email }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input class="form-control"
                            type="text"
                            name="mobile"
                            value="{{ $admin->mobile }}">
                    </div>

                </div>

                <button type="button"
                    class="btn btn-primary"
                    id="finalSubmit">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SUBMIT SCRIPT -->
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