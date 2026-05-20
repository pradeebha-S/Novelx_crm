@extends('Admin.layout')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>

.is-invalid {

    border: 2px solid #dc3545 !important;

}

</style>

@section('content')

    <div class="row align-items-center justify-content-between mb-3 mt-3">

        <div class="col-auto">

            <h5>

                <button type="button" class="btn btn-icon bg-white me-2" style="box-shadow: 0px 9px 12px -2px #66328E1F;">

<a href="{{ route('view_credentials', $project_id ?? null) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                            class="icon icon-tabler">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                            <path d="M15 6l-6 6l6 6" />

                        </svg>

                    </a>

                </button>

                Upload credentials

            </h5>

        </div>

    </div>

    <div class="card p-3">

        <h6>Upload credentials</h6>

     <form action="{{ route('store_credential') }}" method="POST" enctype="multipart/form-data">

    @csrf



    <div class="row g-2">
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

        {{-- PLATFORM --}}

        <div class="col-lg-6 mb-2">

            <label class="form-label">Platform<span class="text-danger">*</span></label>

            <input type="text" name="platform"

                class="form-control @error('platform') is-invalid @enderror"

                value="{{ old('platform') }}" placeholder="Enter Platform">



            @error('platform')

                <div class="invalid-feedback">{{ $message }}</div>

            @enderror

        </div>



        {{-- USER ID --}}

        <div class="col-lg-6 mb-2">

            <label class="form-label">User ID / Email<span class="text-danger">*</span></label>

            <input type="text" name="user_id"

                class="form-control @error('user_id') is-invalid @enderror"

                value="{{ old('user_id') }}" placeholder="Enter User ID / Email">



            @error('user_id')

                <div class="invalid-feedback">{{ $message }}</div>

            @enderror

        </div>



        {{-- PASSWORD --}}

        <div class="col-lg-6 mb-2">

            <label class="form-label">Password<span class="text-danger">*</span></label>

            <input type="text" name="password"

                class="form-control @error('password') is-invalid @enderror"

                value="{{ old('password') }}" placeholder="Enter Password">



            @error('password')

                <div class="invalid-feedback">{{ $message }}</div>

            @enderror

        </div>



        {{-- DOCUMENT --}}

        <div class="col-lg-6 mb-2">

            <label class="form-label">Upload Document (Nullable)</label>

            <input type="file" name="document"

                class="form-control @error('document') is-invalid @enderror">



            @error('document')

                <div class="invalid-feedback">{{ $message }}</div>

            @enderror

        </div>



    </div>



    <div class="form-action d-flex justify-content-center mt-3">

        <button class="btn btn-label-secondary me-4" type="reset">Discard</button>

        <button type="button" id="submitBtn" class="btn btn-primary">

    Submit

</button>

    </div>

</form>

    </div>

    <script>

document.addEventListener("DOMContentLoaded", function () {



    const form = document.querySelector("form");

    const btn = document.getElementById("submitBtn");



    btn.addEventListener("click", function () {



        // disable button

        btn.disabled = true;



        // loading text

        btn.innerHTML = "Processing...";



        // submit form manually

        form.submit();

    });



});

</script>

@endsection