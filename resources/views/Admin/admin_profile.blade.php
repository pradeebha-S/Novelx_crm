@extends('Staff.layout')
@section('content')

    <div class="row fv-plugins-icon-container me-3">
        <!-- Account -->
        <div class="card m-3">
            <h4 class="mt-2">
                Profile
            </h4>
            <div class="d-flex align-items-start align-items-sm-center gap-6  mt-2">
                <img src="{{ asset('assets/img') }}/dp.jpg" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded"
                    id="uploadedAvatar">
                <div class="button-wrapper">
                    <label for="upload" class="btn btn-primary me-3 mb-4 waves-effect waves-light" tabindex="0">
                        <i class="icon-base ti tabler-upload"></i>
                        &nbsp;&nbsp;
                        <span class=" d-sm-block">Upload</span>
                        <input type="file" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                    </label>

                    <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                </div>
            </div>

            <div class="card-body pt-4">
                <form id="login_form" method="GET" onsubmit="return false"
                    class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    <div class="row gy-4 gx-6 mb-6">
                        <div class="col-md-6 form-control-validation fv-plugins-icon-container">
                            <label for="firstName" class="form-label">First Name</label>
                            <input class="form-control" type="text" id="firstName" name="firstName" value="John"
                                autofocus="">
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>
                        <div class="col-md-6 form-control-validation fv-plugins-icon-container">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input class="form-control" type="text" name="lastName" id="lastName" value="Doe">
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email" name="email" value="john.doe@example.com"
                                value="john.doe@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phoneNumber">Phone Number</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">India (+91)</span>
                                <input type="text" id="phoneNumber" name="phoneNumber" class="form-control"
                                    value="9764321058">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="form-actions mt-4">
                            <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal"
                                data-bs-target="#submit">Save
                                Changes</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                    <input type="hidden">
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
 <div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-5 text-center">

                <h5 class="fw-bold mb-2">Are you sure?</h5>
                <p class="text-muted mb-4">Do you confirm to submit this form?</p>

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

        document.getElementById('finalSubmit').addEventListener('click', function (e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('login_form').submit();
        });
    </script>
@endsection