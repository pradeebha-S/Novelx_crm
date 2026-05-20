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
            Create Project
        </h5>
    </div>
</div>
<div class="card p-4 mt-4">
    <h6>Create Project</h6>
    <form action="{{ route('add_project') }}" method="post" id="login_form">
        @csrf
        <div class="row">
            <div class="col-lg-6 mb-2">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-control @error('project_name') is-invalid @enderror"
                    placeholder="Enter Project Name" name="project_name" value="{{ old('project_name') }}">
                @error('project_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Project Type</label>
                <select class="form-control @error('type') is-invalid @enderror" name="type">
                    <option value="">Select Project Type</option>
                    <option value="Client Project" {{ old('type') == 'Client Project' ? 'selected' : '' }}>
                        Client Project
                    </option>
                    <option value="Product" {{ old('type') == 'Product' ? 'selected' : '' }}>
                        Product
                    </option>
                </select>
                @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!-- <div class="col-lg-6 mb-2">
                    <label class="form-label">Module</label>
                    <input type="text" class="form-control @error('module_name') is-invalid @enderror"
                        placeholder="Enter Module Name" name="module_name" value="{{ old('module_name') }}">
                    @error('module_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-2">
                    <label class="form-label">Overall</label>
                    <input type="text" class="form-control @error('overall') is-invalid @enderror" placeholder="Overall"
                        name="overall" value="{{ old('overall') }}">
                    @error('overall')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> -->
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Sheet Link</label>
                <input type="text" class="form-control" name="sheet_link" value="{{ old('sheet_link') }}">
                @error('sheet_link')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Figma Link</label>
                <input type="text" class="form-control" name="figma_link" value="{{ old('figma_link') }}">
                @error('figma_link')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-lg-6 mb-3 mt-2">
                <label class="form-label">Document Link</label>
                <input type="text" class="form-control" name="document_link" value="{{ old('document_link') }}">
                @error('document_link')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Mobile</label>
                <input type="text" class="form-control @error('client_mobile') is-invalid @enderror"
                    placeholder="Enter Mobile Number" name="client_mobile" value="{{ old('client_mobile') }}"
                    minlength="10" maxlength="10">
                @error('client_mobile')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Email</label>
                <input type="email" class="form-control @error('client_email') is-invalid @enderror"
                    placeholder="Enter Email" name="client_email" value="{{ old('client_email') }}">
                @error('client_email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Client Name</label>
                <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                    placeholder="Enter Client Name" name="client_name" value="{{ old('client_name') }}">
                @error('client_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
              <div class="col-lg-6 mb-2">
                <label class="form-label">Client address</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror"
                    placeholder="Enter Client address" name="address" value="{{ old('address') }}">
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
    </form>
    <div class="d-flex form-actions mt-3">
        <button type="button" class="btn btn-primary me-3" id="finalSubmit">Create
            Project</button>
        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
    </div>
</div>
</div>
<!-- <div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 px-4 py-5 text-center">
            <h5 class="fw-bold mb-2">Are you sure?</h5>
            <p class="text-muted mb-4">Do you confirm to submit this form?</p>
            <div class="d-flex justify-content-center gap-3 mt-3">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                    Yes, Sure
                </button>
            </div>
        </div>
    </div>
</div> -->
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