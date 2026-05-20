@extends('Admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <!-- Left side -->
    <div class="d-flex align-items-center">
        <h5 class="mb-0">
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('staff_table') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
            Create Employee
        </h5>
    </div>
</div>
<div class="card p-4">
    <h5>Create Employee</h5>
    <form action="{{ route('add_staff') }}" method="post" id="login_form">
        @csrf
        <div class="row">
            <div class="col-lg-6 mb-2">
                <label class="form-label">Employee Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Employee Name"
                    value="{{ old('name') }}">
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Employee ID</label>
                {{-- <input type="text" class="form-control" name="user_id" placeholder="Enter Employee ID"
                    value="{{ old('user_id') }}"> --}}
                    <input type="text" class="form-control" name="user_id"
    value="{{ old('user_id', $nextUserId) }}"
    placeholder="Enter Employee ID">
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Mobile</label>
                <input type="text" class="form-control" name="mobile" placeholder="Enter Mobile Number"
                    value="{{ old('mobile') }}">
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label"> Office Email</label>
                <input type="text" class="form-control" name="email" placeholder="Enter Email"
                    value="{{ old('email') }}">
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label"> Personal Email</label>
                <input type="text" class="form-control" name="personal_email" placeholder="Enter Email"
                    value="{{ old('personal_email') }}">
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter Password">
            </div>
            <div class="col-lg-6 mb-2">
                <label for="designation" class="form-label">Designation</label>
                <select name="designation" id="designation" class="form-select" required>
                    <option value="">Select Designation</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->role }}">{{ $role->role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 mb-2">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Enter Address"
                    value="{{ old('address') }}">
            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label">DOB</label>
                <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
            </div>
              <div class="col-lg-4 mb-2">
                <label class="form-label">DOJ</label>
                <input type="date" class="form-control" name="doj" value="{{ old('doj') }}">
            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">Select Type</option>
                    <option value="staff" {{ old('type') == 'staff' ? 'selected' : '' }}>
                        Staff
                    </option>
                      <option value="freelancer" {{ old('type') == 'staff' ? 'selected' : '' }}>
                        freelancer
                    </option>
                    <option value="student" {{ old('type') == 'student' ? 'selected' : '' }}>
                        Student
                    </option>
                    <option value="intern" {{ old('type') == 'intern' ? 'selected' : '' }}>
                        Intern
                    </option>
                </select>
            </div>
    </form>
    <div class="d-flex form-actions mt-3">
        <button type="button" class="btn btn-primary me-3" id="finalSubmit">Submit</button>
        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
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