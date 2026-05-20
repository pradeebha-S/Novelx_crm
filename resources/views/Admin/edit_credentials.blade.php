@extends('Admin.layout')



<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">



<style>

.is-invalid {

    border: 2px solid #dc3545 !important;

}

</style>



@section('content')



<div class="container-fluid">



    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">

        <h5 class="mb-0">

            <a href="{{ route('view_credentials','id') }}" class="btn btn-light me-2">←</a>

            Edit Credential

        </h5>

    </div>



    {{-- ALERTS --}}

    @if(session('success'))

        <div class="alert alert-success">{{ session('success') }}</div>

    @endif



    @if(session('error'))

        <div class="alert alert-danger">{{ session('error') }}</div>

    @endif



    {{-- CARD --}}

    <div class="card shadow-sm">

        <div class="card-body">



            <h6 class="mb-3">Edit Credential</h6>



            <form action="{{ route('update_credential', $project->id) }}" method="POST" enctype="multipart/form-data">

                @csrf



                <div class="row g-3">
<input type="hidden" name="project_id" value="{{ $project->project_id }}">

                    {{-- PLATFORM --}}

                    <div class="col-md-6">

                        <label class="form-label">Platform <span class="text-danger">*</span></label>

                        <input type="text" name="platform"

                            class="form-control @error('platform') is-invalid @enderror"

                            value="{{ old('platform', $project->platform) }}"

                            placeholder="Enter Platform">



                        @error('platform')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>



                    {{-- USER ID --}}

                    <div class="col-md-6">

                        <label class="form-label">User ID / Email <span class="text-danger">*</span></label>

                        <input type="text" name="user_id"

                            class="form-control @error('user_id') is-invalid @enderror"

                            value="{{ old('user_id', $project->user_id) }}"

                            placeholder="Enter User ID / Email">



                        @error('user_id')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>



                    {{-- PASSWORD --}}

                    <div class="col-md-6">

                        <label class="form-label">Password <span class="text-danger">*</span></label>

                        <input type="text" name="password"

                            class="form-control @error('password') is-invalid @enderror"

                            value="{{ old('password', $project->password_hint) }}"

                            placeholder="Enter Password">



                        @error('password')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>



                    {{-- DOCUMENT --}}

                    <div class="col-md-6">

                        <label class="form-label">Upload Document</label>

                        <input type="file" name="document"

                            class="form-control @error('document') is-invalid @enderror">



                        @error('document')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror



                        {{-- EXISTING FILE --}}

                        @if($project->document)

                            <div class="mt-2">

                                <a href="{{ asset($project->document) }}" target="_blank" class="text-primary">

                                    📄 View Existing File

                                </a>

                            </div>

                        @endif

                    </div>



                </div>



                {{-- BUTTONS --}}

                <div class="text-center mt-4">

                    <button type="reset" class="btn btn-secondary me-3">Discard</button>



                    <button type="button" id="submitBtn" class="btn btn-primary">

                        Update Credential

                    </button>

                </div>



            </form>



        </div>

    </div>



</div>



{{-- SUBMIT SCRIPT --}}

<script>

document.addEventListener("DOMContentLoaded", function () {



    const form = document.querySelector("form");

    const btn = document.getElementById("submitBtn");



    btn.addEventListener("click", function () {

        btn.disabled = true;

        btn.innerHTML = "Updating...";

        form.submit();

    });



});

</script>



@endsection