@extends('HR.layout')

@section('content')
 
    <h4>Reset Password</h4>

    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card">
                 <div class="card-body">
                     <form id="resetPasswordForm" method="POST" action="#">
                    @csrf
                    <div class="mb-6 form-password-toggle fv-plugins-icon-container">
                        <div class="input-group input-group-merge has-validation">
                            <span class="input-group-text">
                                <span class="lock-icon menu-icon icon-base ti tabler-lock me-2"></span>
                            </span>

                            <input type="password" id="password" class="form-control p-3" name="password"
                                placeholder="Current Password" aria-describedby="password" required>
                            <span class="input-group-text cursor-pointer">
                                <i class="icon-base ti tabler-eye-off"></i>
                            </span>

                        </div>

                    </div>

                    <div class="mb-6 form-password-toggle fv-plugins-icon-container">
                        <div class="input-group input-group-merge has-validation">
                            <span class="input-group-text">
                                <span class="lock-icon menu-icon icon-base ti tabler-lock me-2"></span>
                            </span>

                            <input type="password" id="password" class="form-control p-3" name="password"
                                placeholder="New Password" aria-describedby="password" required>
                            <span class="input-group-text cursor-pointer">
                                <i class="icon-base ti tabler-eye-off"></i>
                            </span>

                        </div>

                    </div>

                    <div class="mb-6 form-password-toggle fv-plugins-icon-container">
                        <div class="input-group input-group-merge has-validation">
                            <span class="input-group-text">
                                <span class="lock-icon menu-icon icon-base ti tabler-lock me-2"></span>
                            </span>

                            <input type="password" id="password" class="form-control p-3" name="password"
                                placeholder="Confirm Password" aria-describedby="password" required>
                            <span class="input-group-text cursor-pointer">
                                <i class="icon-base ti tabler-eye-off"></i>
                            </span>

                        </div>

                    </div>



                    <button type="button" id="resetBtn"
                        class="btn btn-primary d-grid w-100 waves-effect waves-light mt-6">Reset Password</button>
                </form>
                 </div>

            </div>


        </div>
    </div>

@endsection