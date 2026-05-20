@extends('Admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">

    <!-- Left side -->
    <div class="d-flex align-items-center">
        <h5 class="mb-0">
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('intern_table') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
            Edit Student
        </h5>
    </div>
</div>
<div class="card p-4">
    <h5>Edit Student</h5>
    <form action="{{ route('update_intern') }}" method="post" id="intern_form">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">

        <div class="row">
            <div class="col-lg-6 mb-2">
                <label class="form-label">Student Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Intern Name"
                     value="{{ old('name', $user->name) }}">
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror

            </div>

            <div class="col-lg-6 mb-2">
                <label class="form-label">Mobile</label>
                <input type="text" class="form-control" name="mobile" placeholder="Enter Mobile Number"
                    value="{{ old('mobile', $user->mobile) }}">
                @error('mobile') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Enter Email"
                    value="{{ old('email', $user->email) }}">
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror

            </div>

            <div class="col-lg-6 mb-2">
                <label for="designation" class="form-label">Course</label>
                <select name="designation" id="designation" class="form-select" required>

                <option value="">Select Course</option>

                    @foreach($roles as $role)
                    <option value="{{ $role->role }}"
                        {{ old('designation', $user->designation) == $role->role ? 'selected' : '' }}>
                        {{ $role->role }}
                    </option>
                    @endforeach
                </select>

                @error('designation')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="col-lg-4 mb-2">
                <label class="form-label">Internship period</label>
                <input type="text" class="form-control" name="intern_period" placeholder="Enter Internship period"
                    value="{{ old('intern_period', $user->intern_period) }}">
                @error('intern_period') <div class="text-danger">{{ $message }}</div> @enderror

            </div>

            <div class="col-lg-4 mb-2">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Enter Address"
                    value="{{ old('address', $user->address) }}">
                @error('address') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label">DOB</label>
                <input type="date" class="form-control" name="dob" value="{{ old('dob', $user->dob) }}">
                @error('dob') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
            <div class="d-flex justify-content-center form-actions mt-3">
                <button type="reset" class="btn btn-outline-secondary me-3">Cancel</button>
                <button type="button" class="btn btn-primary me-3" id="finalSubmit">Update</button>
            </div>
    </form>

</div>
</div>
<script>
    document.getElementById('finalSubmit').addEventListener('click', function(e) {
        e.preventDefault();
        let btn           = this;
            btn.disabled  = true;
            btn.innerText = 'Processing...';
        document.getElementById('intern_form').submit();
    });
</script>
@endsection