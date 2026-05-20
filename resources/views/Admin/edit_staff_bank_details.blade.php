@extends('Admin.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-1">

    <!-- Left side -->

    <div class="d-flex align-items-center">

        <h5 class="mb-0">

            <button type="button" class="btn btn-icon bg-white waves-effect me-2"

                style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                <a href="{{ route('staff_bank_details') }}">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                        <path d="M15 6l-6 6l6 6" />

                    </svg>

                </a>

            </button>

            Edit Staff Bank Details

        </h5>

    </div>

</div>

<div class="card p-4 mt-4">

    <h6>Edit Staff Bank Details</h6>

   <form action="{{ route('update_bank_details') }}" method="POST" id="login_form">

    @csrf

    <!-- ✅ Hidden ID -->

  <input type="hidden" name="user_id" value="{{ $bank->user_id }}">
    <div class="row">

        <div class="col-lg-6 mb-2">

            <label>Account Number</label>

            <input type="text" name="account_number"

                value="{{ old('account_number', $bank->account_number) }}"

                class="form-control @error('account_number') is-invalid @enderror">

        </div>

        <div class="col-lg-6 mb-2">

            <label>Holder Name</label>

            <input type="text" name="account_holder_name"

                value="{{ old('account_holder_name', $bank->account_holder_name) }}"

                class="form-control @error('account_holder_name') is-invalid @enderror">

        </div>

        <div class="col-lg-6 mb-2">

            <label>IFSC</label>

            <input type="text" name="ifsc_code"

                value="{{ old('ifsc_code', $bank->ifsc_code) }}"

                class="form-control">

        </div>

        <div class="col-lg-6 mb-2">

            <label>Bank Name</label>

            <input type="text" name="bank_name"

                value="{{ old('bank_name', $bank->bank_name) }}"

                class="form-control">

        </div>

        <div class="col-lg-6 mb-2">

            <label>Branch Name</label>

            <input type="text" name="branch_name"

                value="{{ old('branch_name', $bank->branch_name) }}"

                class="form-control">

        </div>

         <div class="col-lg-6 mb-2">

            <label>Aadhar Number</label>

            <input type="text" name="aadhar_number"

                value="{{ old('aadhar_number', $bank->aadhar_number) }}"

                class="form-control">

        </div>

        <div class="col-lg-6 mb-2">

            <label>Pan Number</label>

            <input type="text" name="pan_number"

                value="{{ old('pan_number', $bank->pan_number) }}"

                class="form-control">

        </div>
         <div class="col-lg-6 mb-2">

            <label>UPI ID</label>

            <input type="text" name="upi"

                value="{{ old('upi', $bank->upi) }}"

                class="form-control">

        </div>

        

    </div>

    <div class="mt-3 text-center">

        <button type="submit" class="btn btn-primary">Update Bank</button>

    </div>

</form>

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