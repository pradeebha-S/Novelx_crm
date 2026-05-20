@extends('Admin.layout')
<style>
    .password-toggle {
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s ease;
}

.password-toggle:hover {
    color: #111827;
}
</style>
@section('content')

<div class="d-flex justify-content-between align-items-center mb-1">

    <!-- Left side -->

    <div class="d-flex align-items-center">

        <h5 class="mb-0">

            <button type="button" class="btn btn-icon bg-white waves-effect me-2"

                style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                <a href="{{ route('staff_table') }}">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"

                        viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"

                        stroke-linecap="round" stroke-linejoin="round"

                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                        <path d="M15 6l-6 6l6 6" />

                    </svg>

                </a>

            </button>

            Edit Employee

        </h5>

    </div>

</div>

<div class="card p-4">

    <h5>Edit Employee</h5>

    <form action="{{ route('update_staff', $user->id) }}" method="POST" id="login_form">

        @csrf

        <input type="text" name="id" value="{{ $user->id }}" hidden>

        <div class="row">

            <!-- Name -->

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="name">Employee Name</label>

                <input type="text" name="name" id="name"

                    class="form-control @error('name') is-invalid @enderror"

                    value="{{ old('name', $user->name) }}">

                @error('name')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="user_id">Employee ID</label>

                <input type="text" name="user_id" id="user_id"

                    class="form-control @error('user_id') is-invalid @enderror"

                    value="{{ old('user_id', $user->user_id) }}">

                @error('user_id')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <!-- Mobile -->

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="mobile">Mobile</label>

                <input type="text" name="mobile" id="mobile"

                    class="form-control @error('mobile') is-invalid @enderror"

                    value="{{ old('mobile', $user->mobile) }}" minlength="10" maxlength="10">

                @error('mobile')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <!-- Email -->

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="email"> Office Email</label>

                <input type="email" name="email" id="email"

                    class="form-control @error('email') is-invalid @enderror"

                    value="{{ old('email', $user->email) }}">

                @error('email')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="email"> Personal Email</label>

                <input type="email" name="personal_email" id="email"

                    class="form-control @error('personal_email') is-invalid @enderror"

                    value="{{ old('personal_email', $user->personal_email) }}">

                @error('personal_email')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <!-- Password -->

            <!-- <div class="col-lg-6 mb-2">

                <label class="form-label" for="password">Password</label>

                <input type="password" name="password" id="password"

                       class="form-control @error('password') is-invalid @enderror"

                       placeholder="Leave blank to keep current password">

                @error('password')

                    <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div> -->

            <!-- Designation -->

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="designation">Designation</label>

                <select name="designation"

                    id="designation"

                    class="form-select @error('designation') is-invalid @enderror"

                    required>

                    <option value="">Select Designation</option>

                    @foreach($roles as $role)

                    <option value="{{ $role->role }}"

                        {{ old('designation', $user->designation) == $role->role ? 'selected' : '' }}>

                        {{ $role->role }}

                    </option>

                    @endforeach

                </select>

                @error('designation')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <!-- Address -->

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="address">Address</label>

                <input type="text" name="address" id="address"

                    class="form-control @error('address') is-invalid @enderror"

                    value="{{ old('address', $user->address) }}">

                @error('address')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

            <!-- DOB -->

            <div class="col-lg-6 mb-2">

                <label class="form-label" for="dob">DOB</label>

                <input type="date" name="dob" id="dob"

                    class="form-control @error('dob') is-invalid @enderror"

                    value="{{ old('dob', \Carbon\Carbon::parse($user->dob)->format('Y-m-d')) }}">

                @error('dob')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

             <div class="col-lg-4 mb-2">

                <label class="form-label" for="doj">DOJ</label>

                <input type="date" name="doj" id="doj"

                    class="form-control @error('doj') is-invalid @enderror"

                    value="{{ old('doj', \Carbon\Carbon::parse($user->doj)->format('Y-m-d')) }}">

                @error('doj')

                <div class="invalid-feedback">{{ $message }}</div>

                @enderror

            </div>

              <div class="col-lg-4 mb-2">

            <label class="form-label">Type</label>

            <select name="type" class="form-select">

                <option value="">Select Type</option>

                <option value="staff" {{ ($user->type ?? old('type')) == 'staff' ? 'selected' : '' }}>

                    Staff

                </option>

                <option value="student" {{ ($user->type ?? old('type')) == 'student' ? 'selected' : '' }}>

                    Student

                </option>

                <option value="intern" {{ ($user->type ?? old('type')) == 'intern' ? 'selected' : '' }}>

                    Intern

                </option>
                 <option value="freelancer" {{ ($user->type ?? old('type')) == 'freelancer' ? 'selected' : '' }}>

                    freelancer

                </option>

            </select>

        </div>
          <div class="col-lg-4 mb-2">

    <label class="form-label">Password</label>

    <div class="position-relative">

        <input
            type="password"
            name="password"
            id="password"
            class="form-control pe-5"
            value="{{ old('password_hint', $user->password_hint) }}"
        >

        <span class="password-toggle" id="togglePassword">
            <i class="ti tabler-eye"></i>
        </span>

    </div>

</div>

        </div>



        <div class="d-flex form-actions mt-3">

            <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#submit">Update Employee</button>

            <button type="reset" class="btn btn-outline-secondary">Cancel</button>

        </div>

    </form>

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
<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {

        const type = passwordInput.getAttribute('type') === 'password'
            ? 'text'
            : 'password';

        passwordInput.setAttribute('type', type);

        this.innerHTML = type === 'password'
            ? '<i class="ti tabler-eye"></i>'
            : '<i class="ti tabler-eye-off"></i>';
    });
</script>
@endsection