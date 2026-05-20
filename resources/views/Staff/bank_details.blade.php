@extends('Staff.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
       <div class="d-flex align-items-center">
        <h5 class="mb-0">
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('staff.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
           Add KYC Details
        </h5>
    </div>
</div>
<div class="card p-4 mt-4">
    <h6>Create kyc details</h6>
    <form action="{{ route('add_kyc_details') }}" method="POST" id="bankForm">
    @csrf
    <div class="row">
        {{-- Account Number --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">Account Number</label>
            <input type="text"
                class="form-control @error('account_number') is-invalid @enderror"
                name="account_number"
                value="{{ old('account_number') }}"
                placeholder="Enter Account Number">
            @error('account_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        {{-- Holder Name --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">Account Holder Name</label>
            <input type="text"
                class="form-control @error('holder_name') is-invalid @enderror"
                name="holder_name"
                value="{{ old('holder_name') }}"
                placeholder="Enter Holder Name">
            @error('holder_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        {{-- IFSC Code --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">IFSC Code</label>
            <input type="text"
                class="form-control @error('ifsc_code') is-invalid @enderror"
                name="ifsc_code"
                value="{{ old('ifsc_code') }}"
                placeholder="Enter IFSC Code">
            @error('ifsc_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        {{-- Bank Name --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">Bank Name</label>
            <input type="text"
                class="form-control @error('bank_name') is-invalid @enderror"
                name="bank_name"
                value="{{ old('bank_name') }}"
                placeholder="Enter Bank Name">
            @error('bank_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        {{-- Branch Name --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">Branch Name</label>
            <input type="text"
                class="form-control @error('branch_name') is-invalid @enderror"
                name="branch_name"
                value="{{ old('branch_name') }}"
                placeholder="Enter Branch Name">
            @error('branch_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
         {{-- Branch Name --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">Aadhar Number</label>
            <input type="text"
                class="form-control @error('aadhar_number') is-invalid @enderror"
                name="aadhar_number"
                value="{{ old('aadhar_number') }}"
                placeholder="Enter aadhar number">
            @error('aadhar_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
         {{-- Branch Name --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">Pan Number</label>
            <input type="text"
                class="form-control @error('pan_number') is-invalid @enderror"
                name="pan_number"
                value="{{ old('pan_number') }}"
                placeholder="Enter pan number">
            @error('pan_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
          <div class="col-lg-6 mb-2">
            <label class="form-label">Upi Id</label>
            <input type="text"
                class="form-control @error('upi') is-invalid @enderror"
                name="upi"
                value="{{ old('upi') }}"
                placeholder="Enter upi id">
            @error('upi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="text-center mt-3">
        @if(!$details)
    <button type="submit" class="btn btn-success">
        Save Bank Details
    </button>
@else
    <div class="text-success fw-bold">
        ✔ Bank details already submitted
    </div>
@endif
    </div>
</form>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script>
var jq = jQuery.noConflict();
jq(document).ready(function() {
    // ✅ BANK FORM SUBMIT LOADING
    jq('#bankForm').on('submit', function() {
        let btn = jq(this).find('button[type="submit"]');
        btn.prop('disabled', true);
        btn.text('Processing...');
    });
});
</script>
@endsection