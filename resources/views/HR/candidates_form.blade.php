@extends('Admin.layout')

@section('content')
<div class="d-flex align-items-center">
    <h5 class="mb-0">
        <button type="button" class="btn btn-icon bg-white waves-effect me-2"
            style="box-shadow: 0px 9px 12px -2px #66328E1F;">
            <a href="{{ route('admin.candidates') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 6l-6 6l6 6" />
                </svg>
            </a>
        </button>
        Candidates Form
    </h5>
</div>

<div class="card p-3 mt-3">
    <form action="{{ route('add_candidate') }}" id="login_form" method="POST" enctype="multipart/form-data">

        @csrf
        <h6>Basic Details</h6>
        <p><span class="text-danger">*</span> indicates required fields</p>
        <div class="row g-2">

            <div class="col-lg-4 mb-3">
                <label for="cat" class="form-label">Category<span class="text-danger">*</span></label>
                <select name="category" id="candidate_category"
                    class="form-select @error('category') is-invalid @enderror">
                    <option value="">Select Category</option>
                    <option value="frontend" {{ old('category') == 'frontend' ? 'selected' : '' }}>Front-end</option>
                    <option value="backend" {{ old('category') == 'backend' ? 'selected' : '' }}>Back-end</option>
                    <option value="Fullstack" {{ old('category') == 'Fullstack' ? 'selected' : '' }}>Fullstack</option>
                    <option value="DevOps" {{ old('category') == 'DevOps' ? 'selected' : '' }}>DevOps</option>
                    <option value="HR" {{ old('category') == 'HR' ? 'selected' : '' }}>HR</option>
                    <option value="UI/UX" {{ old('category') == 'UI/UX' ? 'selected' : '' }}>UI/UX</option>
                    <option value="Data Analyst" {{ old('category') == 'Data Analyst' ? 'selected' : '' }}>Data Analyst
                    </option>
                    <option value="Business Analyst" {{ old('category') == 'Business Analyst' ? 'selected' : '' }}>
                        Business Analyst</option>
                    <option value="Manual Testing" {{ old('category') == 'Manual Testing' ? 'selected' : '' }}>Manual
                        Testing</option>
                    <option value="Automatic Testing" {{ old('category') == 'Automatic Testing' ? 'selected' : '' }}>
                        Automatic Testing</option>
                    <option value="app" {{ old('category') == 'app' ? 'selected' : '' }}>App Developer</option>
                </select>
                @error('category')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-4 mb-3">
                <label for="candidate_name" class="form-label">Candidate Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('candidate_name') is-invalid @enderror"
                    id="candidate_name" name="candidate_name" value="{{ old('candidate_name') }}"
                    placeholder="Enter candidate name">

                @error('candidate_name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-4 mb-3">
                <label for="candidate_technology" class="form-label">Technology<span
                        class="text-danger">*</span></label>

                <input type="text" name="technology" id="candidate_technology"
                    class="form-control @error('technology') is-invalid @enderror" value="{{ old('technology') }}"
                    placeholder="Enter or Select Technology" list="technologyList" >

                @error('technology')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-6 mb-3">
                <label for="candidate_work_status" class="form-label">Work Status<span
                        class="text-danger">*</span></label>

                <select name="work_status" id="candidate_work_status"
                    class="form-select @error('work_status') is-invalid @enderror">

                    <option value="">Select Work Status</option>
                    <option value="Experienced" {{ old('work_status') == 'Experienced' ? 'selected' : '' }}>Experienced
                    </option>
                    <option value="Freshers" {{ old('work_status') == 'Freshers' ? 'selected' : '' }}>Freshers</option>
                </select>

                @error('work_status')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-6 mb-3">
                <label for="candidate_experience" class="form-label">Experience<span
                        class="text-danger">*</span></label>

                <input type="number" name="experience" id="candidate_experience" value="{{ old('experience') }}"
                    class="form-control @error('experience') is-invalid @enderror">

                @error('experience')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <hr>

        <h6>Employment Info & Resume</h6>

        <div class="row g-2">

            <div class="col-lg-3 mb-3">
                <label for="resume" class="form-label">Resume</label>

                <input type="file" name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror">

                @error('resume')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 mb-3">
                <label for="notice_period" class="form-label">Notice Period</label>

                <input type="number" name="notice_period" id="notice_period" value="{{ old('notice_period') }}"
                    class="form-control @error('notice_period') is-invalid @enderror"
                    placeholder="Enter Notice Period in days">

                @error('notice_period')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 mb-3">
                <label for="Current Salary" class="form-label">Current Salary</label>

                <input type="number" name="current_salary" id="current_salary" value="{{ old('current_salary') }}"
                    class="form-control @error('current_salary') is-invalid @enderror"
                    placeholder="Enter Current Salary">

                @error('current_salary')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 mb-3">
                <label for="expected_salary" class="form-label">Expected Salary</label>

                <input type="number" name="expected_salary" id="expected_salary" value="{{ old('expected_salary') }}"
                    class="form-control @error('expected_salary') is-invalid @enderror"
                    placeholder="Enter Expected Salary">

                @error('expected_salary')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <hr>

        <h6>Contact Info</h6>

        <div class="row g-2">

            <div class="col-lg-4 mb-3">
                <label for="Phone Number" class="form-label">Phone Number
                    <span class="text-danger">*</span>
                </label>

                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                    class="form-control @error('phone_number') is-invalid @enderror" minlength="10" maxlength="10"
                    placeholder="Enter Phone Number">

                @error('phone_number')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-4 mb-3">
                <label for="Alternate Phone Number" class="form-label">Alternate Phone Number (nullable)</label>

                <input type="tel" name="alternate_phone_number" id="alternate_phone_number"
                    value="{{ old('alternate_phone_number') }}"
                    class="form-control @error('alternate_phone_number') is-invalid @enderror" minlength="10"
                    maxlength="10" placeholder="Enter Alternate Phone Number">

                @error('alternate_phone_number')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-4 mb-3">
                <label for="email" class="form-label">Email Address
                    <span class="text-danger">*</span>
                </label>

                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email Address">

                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <hr>

        <h6>Location Info</h6>

        <div class="row g-2">

            <div class="col-lg-6 mb-3">
                <label for="Phone Number" class="form-label">State
                    <span class="text-danger">*</span>
                </label>

                <input type="text" name="state" id="state" value="{{ old('state') }}"
                    class="form-control @error('state') is-invalid @enderror" placeholder="Enter or Select state"
                    list="stateList" >

                <datalist id="stateList">
                    <option value="Tamil Nadu">
                    <option value="Karnataka">
                    <option value="Telangana">
                </datalist>

                @error('state')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-6 mb-3">
                <label class="form-label">City <span class="text-danger">*</span></label>

                <input type="text" name="city" id="city" value="{{ old('city') }}"
                    class="form-control @error('city') is-invalid @enderror" placeholder="Enter or Select City"
                    list="cityList" >

                <datalist id="cityList">
                    <option value="Chennai">
                    <option value="Bangalore">
                    <option value="Hyderabad">
                </datalist>

                @error('city')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-12 mb-3">
                <label class="form-label">Ready To Reallocate <span class="text-danger">*</span></label>

                <select name="ready_to_reallocate" id="ready_to_reallocate"
                    class="form-select @error('ready_to_reallocate') is-invalid @enderror">

                    <option value="">Select</option>
                    <option value="Yes" {{ old('ready_to_reallocate') == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ old('ready_to_reallocate') == 'No' ? 'selected' : '' }}>No</option>
                </select>

                @error('ready_to_reallocate')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-6 mb-3">
                <label class="form-label">Team Management <span class="text-danger">*</span></label>

                <select name="team_management" id="team_management"
                    class="form-select @error('team_management') is-invalid @enderror" >

                    <option value="">Select</option>
                    <option value="Yes" {{ old('team_management') == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ old('team_management') == 'No' ? 'selected' : '' }}>No</option>
                </select>

                @error('team_management')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-6 mb-3">
                <label class="form-label">Client Management <span class="text-danger">*</span></label>

                <select name="client_management" id="client_management"
                    class="form-select @error('client_management') is-invalid @enderror" >

                    <option value="">Select</option>
                    <option value="Yes" {{ old('client_management') == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ old('client_management') == 'No' ? 'selected' : '' }}>No</option>
                </select>

                @error('client_management')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <hr>

        <div class="form-actions d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-3" id="submit_btn">Submit</button>
            <button type="reset" class="btn btn-label-secondary">Reset</button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

        $('#login_form').on('submit', function () {

            let btn = $('#submit_btn');

            btn.prop('disabled', true);

            btn.html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Processing...
            `);

        });

    });
</script>
@endsection