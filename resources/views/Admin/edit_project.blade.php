@extends('Admin.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-1">

    <!-- Left side -->

    <div class="d-flex align-items-center">

        <h5 class="mb-0">

            <button type="button" class="btn btn-icon bg-white waves-effect me-2"

                style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                <a href="{{ route('project_table') }}">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                        <path d="M15 6l-6 6l6 6" />

                    </svg>

                </a>

            </button>

            Edit Project

        </h5>

    </div>

</div>

<div class="card p-4 mt-4">

    <h6>Edit Project</h6>

    <form action="{{ route('update_project') }}" method="post" id="login_form">

        @csrf

        <input type="hidden" name="id" value="{{ $project->id }}">

        <div class="row">

            <div class="col-lg-6 mb-2">

                <label class="form-label">Project Name</label>

                <input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name"

                    value="{{ old('project_name', $project->project_name) }}" placeholder="Enter Project Name">

                @error('project_name')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label">Project Type</label>

                <select class="form-control @error('type') is-invalid @enderror" name="type">

                    <option value="">Select Project Type</option>

                    <option value="Client Project"

                        {{ old('type', $project->type ?? '') == 'Client Project' ? 'selected' : '' }}>

                        Client Project

                    </option>

                    <option value="Product"

                        {{ old('type', $project->type ?? '') == 'Product' ? 'selected' : '' }}>

                        Product

                    </option>

                </select>

                @error('type')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label">Figma link</label>

                <input type="text" class="form-control @error('figma_link') is-invalid @enderror" name="figma_link"

                    value="{{ old('figma_link', $project->figma_link) }}" placeholder="Enter Module Name">

                @error('figma_link')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label">sheet link</label>

                <input type="text" class="form-control @error('sheet_link') is-invalid @enderror" name="sheet_link"

                    value="{{ old('sheet_link', $project->sheet_link) }}" placeholder="Enter Module Name">

                @error('sheet_link')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label">Document link</label>

                <input type="text" class="form-control @error('document_link') is-invalid @enderror" name="document_link"

                    value="{{ old('document_link', $project->document_link) }}">

                @error('document_link')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label">Mobile</label>

                <input type="text" class="form-control @error('client_mobile') is-invalid @enderror"

                    name="client_mobile" value="{{ old('client_mobile', $project->client_mobile) }}" minlength="10"

                    placeholder="Enter Mobile Number">

                @error('client_mobile')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label">Email</label>

                <input type="email" class="form-control @error('client_email') is-invalid @enderror" name="client_email"

                    value="{{ old('client_email', $project->client_email) }}" placeholder="Enter Email">

                @error('client_email')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label">Client Name</label>

                <input type="text" class="form-control @error('client_name') is-invalid @enderror" name="client_name"

                    value="{{ old('client_name', $project->client_name) }}" placeholder="Enter Client Name">

                @error('client_name')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

               <div class="col-lg-6 mb-2">

                <label class="form-label">Client Address</label>

                <input type="text" class="form-control @error('address') is-invalid @enderror" name="address"

                    value="{{ old('address', $project->address) }}" placeholder="Enter address ">

                @error('address')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

        </div>

    </form>

    <div class="d-flex form-actions mt-3">

        <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#submit">Update

            Project</button>

        <button type="reset" class="btn btn-outline-secondary">Cancel</button>

    </div>

</div>

</div>

<div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content rounded-4 px-4 py-5 text-center">

            <h5 class="fw-bold mb-2">Are you sure?</h5>

            <p class="text-muted mb-4">Do you confirm to update?</p>

            <div class="d-flex justify-content-center gap-3 mt-3">

                <!-- Cancel -->

                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">

                    Cancel

                </button>

                <!-- Final submit -->

                <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">

                    Yes, Sure

                </button>

            </div>

        </div>

    </div>

</div>

<script>

    document.getElementById('finalSubmit').addEventListener('click', function(e) {

        e.preventDefault();

        let btn = this;

        btn.disabled = true;

        btn.innerText = 'Processing...';

        document.getElementById('login_form').submit();

    });

</script>

@endsection