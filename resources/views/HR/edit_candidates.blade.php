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
        Edit Candidates Form
    </h5>
</div>

<div class="card p-3 mt-3">
    <form action="{{ route('update_candidate') }}" id="login_form" method="POST" enctype="multipart/form-data">

        @csrf

        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
        <h6>Basic Details</h6>
        <p><span class="text-danger">*</span> indicates required fields</p>

        <div class="row g-2">
            <!-- <div class="col-lg-4 mb-3">
                        <label for="candidate_date" class="form-label">Date<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="candidate_date" value="2026-02-05">
                    </div> -->
            <div class="col-lg-4 mb-3">
                <label for="cat" class="form-label">Category<span class="text-danger">*</span></label>
                <select name="category" id="candidate_category"
                    class="form-select @error('category') is-invalid @enderror">

                    <option value="">Select Category</option>

                    <option value="frontend"
                        {{ old('category', $candidate->category) == 'frontend' ? 'selected' : '' }}>
                        Front-end
                    </option>

                    <option value="backend" {{ old('category', $candidate->category) == 'backend' ? 'selected' : '' }}>
                        Back-end
                    </option>

                    <option value="Fullstack"
                        {{ old('category', $candidate->category) == 'Fullstack' ? 'selected' : '' }}>
                        Fullstack
                    </option>

                    <option value="DevOps" {{ old('category', $candidate->category) == 'DevOps' ? 'selected' : '' }}>
                        DevOps
                    </option>

                    <option value="HR" {{ old('category', $candidate->category) == 'HR' ? 'selected' : '' }}>
                        HR
                    </option>

                    <option value="UI/UX" {{ old('category', $candidate->category) == 'UI/UX' ? 'selected' : '' }}>
                        UI/UX
                    </option>

                    <option value="Data Analyst"
                        {{ old('category', $candidate->category) == 'Data Analyst' ? 'selected' : '' }}>
                        Data Analyst
                    </option>

                    <option value="Business Analyst"
                        {{ old('category', $candidate->category) == 'Business Analyst' ? 'selected' : '' }}>
                        Business Analyst
                    </option>

                    <option value="Manual Testing"
                        {{ old('category', $candidate->category) == 'Manual Testing' ? 'selected' : '' }}>
                        Manual Testing
                    </option>

                    <option value="Automatic Testing"
                        {{ old('category', $candidate->category) == 'Automatic Testing' ? 'selected' : '' }}>
                        Automatic Testing
                    </option>

                    <option value="app" {{ old('category', $candidate->category) == 'app' ? 'selected' : '' }}>
                        App Developer
                    </option>

                </select>

                @error('category')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-lg-4 mb-3">
                <label for="candidate_name" class="form-label">Candidate Name<span class="text-danger">*</span></label>
                <input type="text" name="candidate_name" id="candidate_name" class="form-control"
                    value="{{ old('candidate_name', $candidate->candidate_name) }}">
            </div>

            <div class="col-lg-4 mb-3">
                <label for="candidate_technology" class="form-label">Technology<span
                        class="text-danger">*</span></label>
                <input type="text" name="technology" id="candidate_technology" class="form-control"
                    value="{{ old('technology', $candidate->technology) }}">
            </div>

            <div class="col-lg-6 mb-3">
                <label for="candidate_work_status" class="form-label">Work Status<span
                        class="text-danger">*</span></label>
                <select name="work_status" id="candidate_work_status" class="form-select">

                    <option value="Experienced"
                        {{ old('work_status', $candidate->work_status) == 'Experienced' ? 'selected' : '' }}>
                        Experienced
                    </option>

                    <option value="Freshers"
                        {{ old('work_status', $candidate->work_status) == 'Freshers' ? 'selected' : '' }}>
                        Freshers
                    </option>

                </select>
            </div>

            <div class="col-lg-6 mb-3">
                <label for="candidate_experience" class="form-label">Experience<span
                        class="text-danger">*</span></label>
                <input type="number" name="experience" id="candidate_experience" class="form-control"
                    value="{{ old('experience', $candidate->experience) }}">
            </div>
        </div>

        <hr>

        <h6>Employment Info & Resume</h6>
        <div class="row g-2">
            <div class="col-lg-3 mb-3">

                <label for="resume" class="form-label">Resume</label>

                <input type="file" name="resume" id="resume" class="form-control">

                @if($candidate->resume)

                <div class="mt-2">

                    <a href="{{ asset('storage/' . $candidate->resume) }}" target="_blank">
                        View Resume
                    </a>


                </div>

                @endif

            </div>

            <div class="col-lg-3 mb-3">
                <label for="notice_period" class="form-label">Notice Period</label>
                <input type="number" name="notice_period" id="notice_period" class="form-control"
                    value="{{ old('notice_period', $candidate->notice_period) }}">
            </div>

            <div class="col-lg-3 mb-3">
                <label for="Current Salary" class="form-label">Current Salary</label>
                <input type="number" name="current_salary" id="current_salary" class="form-control"
                    value="{{ old('current_salary', $candidate->current_salary) }}">
            </div>

            <div class="col-lg-3 mb-3">
                <label for="expected_salary" class="form-label">Expected Salary</label>
                <input type="number" name="expected_salary" id="expected_salary" class="form-control"
                    value="{{ old('expected_salary', $candidate->expected_salary) }}">
            </div>
        </div>

        <hr>

        <h6>Contact Info</h6>
        <div class="row g-2">
            <div class="col-lg-4 mb-3">
                <label for="Phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" name="phone_number" id="phone_number" class="form-control"
                    value="{{ old('phone_number', $candidate->phone_number) }}">
            </div>

            <div class="col-lg-4 mb-3">
                <label for="Alternate_Phone_Number" class="form-label">Alternate Phone Number</label>
                <input type="tel" name="alternate_phone_number" id="alternate_phone_number" class="form-control"
                    value="{{ old('alternate_phone_number', $candidate->alternate_phone_number) }}">
            </div>

            <div class="col-lg-4 mb-3">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email', $candidate->email) }}">
            </div>
        </div>
        <hr>
        <h6>Location Info</h6>
        <div class="row g-2">
            <div class="col-lg-6 mb-3">
                <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                <input type="text" name="state" id="state" class="form-control"
                    value="{{ old('state', $candidate->state) }}">
            </div>

            <div class="col-lg-6 mb-3">
                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                <input type="text" name="city" id="city" class="form-control"
                    value="{{ old('city', $candidate->city) }}">
            </div>
        </div>
        <hr>
        <!-- <h6>Interview & Remarks</h6>
            <div class="row g-2">
                <div class="col-lg-3 mb-3">
                    <label for="interview_date" class="form-label">Interview Date</label>
                    <input type="date" name="interview_date" id="interview_date" class="form-control" value="2026-02-10">
                </div>

                <div class="col-lg-3 mb-3">
                    <label for="interview_time" class="form-label">Interview Time</label>
                    <input type="time" name="interview_time" id="interview_time" class="form-control" value="11:30">
                </div>
           <div class="col-lg-3 mb-3">
                    <label for="interview_mode" class="form-label">Interview Mode <span class="text-danger">*</span></label>
                    <select name="interview_mode" id="interview_mode" class="form-select" required>
                        <option value="offline">Offline</option>
                        <option value="online" selected>Online</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Pending">Pending</option>
                        <option value="Interview Scheduled" selected>Interview Scheduled</option>
                        <option value="Interview Completed">Interview Completed</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <div class="col-lg-12 mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" rows="3"
                        placeholder="Enter any additional remarks about the candidate">Strong React + Node.js knowledge, good communication skills. Available for immediate technical round.</textarea>
                </div>
            </div> -->

        <div class="form-actions d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-3" id="submit_btn">Update</button>
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