@extends('Admin.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
           Add Bank
        </h5>
    </div>
</div>
<div class="card p-4 mt-4">
    <h6>Create Project</h6>
    <form action="{{ route('store_bank') }}" method="POST" id="bankForm">
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
          {{-- GST --}}
        <div class="col-lg-6 mb-2">
            <label class="form-label">GST</label>
            <select class="form-control @error('gst') is-invalid @enderror" name="gst">
                <option value="">Select GST</option>
                <option value="Yes" {{ old('gst') == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ old('gst') == 'No' ? 'selected' : '' }}>No</option>
            </select>
            @error('gst')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
         <div class="col-lg-12 mb-2">
            <label class="form-label">UPI ID</label>
            <input type="text"
                class="form-control @error('upi') is-invalid @enderror"
                name="upi"
                value="{{ old('upi') }}"
                placeholder="Enter UPI ID">
            @error('upi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

    </div>
    <div class="text-center mt-3">
        <button type="submit" class="btn btn-success">
            Save Bank Details
        </button>
    </div>
</form>
</div>
</div>
<div class="card p-4">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table">
                     <thead>
    <tr>
        <th>SNO</th>
        <th>ACCOUNT NO</th>
        <th>HOLDER NAME</th>
        <th>IFSC</th>
        <th>BANK</th>
        <th>BRANCH</th>
        <th>GST</th>
         <th>UPI</th>
          <th>STATUS</th>
        <th>ACTION</th>
    </tr>
</thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 text-center p-4 py-5">
            <h5 class="fw-bold mb-2">Are you sure!!</h5>
            <p class="text-muted">Are you confirm to delete?</p>
            <form id="deleteForm" method="POST" action="{{ route('delete_bank') }}">
                @csrf
                <input type="hidden" name="id" id="deleteId">
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger p-3 ms-2 fw-semibold" id="finalSubmit">
    Yes, Sure
</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <h5 class="fw-bold">Change Status?</h5>
            <p class="text-muted">Are you sure you want to update status?</p>
            <form id="statusForm">
                @csrf
                <input type="hidden" id="statusId">
                <input type="hidden" id="statusValue">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success" id="statusBtn">Yes</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
{{-- <script>
var jq = jQuery.noConflict();
jq(document).ready(function() {
    // ✅ DATATABLE
    jq('#dept').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('bank_data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false },
            { data: 'account_number' },
            { data: 'holder_name' },
            { data: 'ifsc_code' },
            { data: 'bank_name' },
            { data: 'branch_name' },
            { data: 'gst' },
             { data: 'upi' },
             { data: 'status', orderable: false },
            { data: 'action', orderable: false }
        ]
    });
    // ✅ SET DELETE ID
    jq(document).on('click', '.deleteBtn', function () {
        let id = jq(this).data('id');
        jq('#deleteId').val(id);
    });
});
// ✅ BUTTON LOADING
document.getElementById('deleteForm').addEventListener('submit', function() {
    let btn = document.getElementById('finalSubmit');
    btn.disabled = true;
    btn.innerText = 'Processing...';
});
let selectedCheckbox = null;
// OPEN MODAL
jq(document).on('change', '.toggleStatus', function () {
    selectedCheckbox = this;
    let id = jq(this).data('id');
    let status = jq(this).is(':checked') ? 1 : 0;
    jq('#statusId').val(id);
    jq('#statusValue').val(status);
    jq('#statusModal').modal('show');
});
// SUBMIT STATUS CHANGE
jq('#statusForm').on('submit', function(e) {
    e.preventDefault();
    let id = jq('#statusId').val();
    let status = jq('#statusValue').val();
    jq('#statusBtn').prop('disabled', true).text('Processing...');
    jq.ajax({
        url: "{{ route('update_bank_status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            status: status
        },
        success: function(res) {
            jq('#statusModal').modal('hide');
            jq('#dept').DataTable().ajax.reload(null, false);
        },
        error: function() {
            alert('Something went wrong');
        }
    });
});
// CANCEL → revert toggle
jq('#statusModal').on('hidden.bs.modal', function () {
    if (selectedCheckbox) {
        selectedCheckbox.checked = !selectedCheckbox.checked;
    }
});
</script> --}}
{{-- <script>
var jq = jQuery.noConflict();
jq(document).ready(function() {
    // ✅ DATATABLE INIT
    var table = jq('#dept').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('bank_data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false },
            { data: 'account_number' },
            { data: 'holder_name' },
            { data: 'ifsc_code' },
            { data: 'bank_name' },
            { data: 'branch_name' },
            { data: 'gst' },
              { data: 'upi' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    // =========================
    // ✅ DELETE FUNCTION
    // =========================
    jq(document).on('click', '.deleteBtn', function () {
        let id = jq(this).data('id');
        jq('#deleteId').val(id);
    });
    jq('#deleteForm').on('submit', function() {
        let btn = jq('#finalSubmit');
        btn.prop('disabled', true).text('Processing...');
    });
    // =========================
    // ✅ STATUS CHANGE FUNCTION
    // =========================
    let selectedBtn = null;
    // CLICK STATUS BUTTON
    jq(document).on('click', '.changeStatus', function () {
        selectedBtn = this;
        let id = jq(this).data('id');
        let status = jq(this).data('status');
        jq('#statusId').val(id);
        jq('#statusValue').val(status);
        jq('#statusModal').modal('show');
    });
    // SUBMIT STATUS CHANGE
    jq('#statusForm').on('submit', function(e) {
        e.preventDefault();
        let btn = jq('#statusBtn');
        btn.prop('disabled', true).text('Processing...');
        let id = jq('#statusId').val();
        let status = jq('#statusValue').val();
        jq.ajax({
            url: "{{ route('update_bank_status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                status: status
            },
            success: function(res) {
                // CLOSE MODAL
                jq('#statusModal').modal('hide');
                // RESET BUTTON
                btn.prop('disabled', false).text('Yes');
                // RELOAD TABLE (NO PAGE RESET)
                table.ajax.reload(null, false);
            },
            error: function() {
                alert('Something went wrong');
                btn.prop('disabled', false).text('Yes');
            }
        });
    });
});
</script> --}}
<script>
var jq = jQuery.noConflict();
jq(document).ready(function() {
    // ✅ DATATABLE INIT
    var table = jq('#dept').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('bank_data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false },
            { data: 'account_number' },
            { data: 'holder_name' },
            { data: 'ifsc_code' },
            { data: 'bank_name' },
            { data: 'branch_name' },
            { data: 'gst' },
              { data: 'upi' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    // =========================
    // ✅ DELETE FUNCTION
    // =========================
    jq(document).on('click', '.deleteBtn', function () {
        let id = jq(this).data('id');
        jq('#deleteId').val(id);
    });
    jq('#deleteForm').on('submit', function() {
        let btn = jq('#finalSubmit');
        btn.prop('disabled', true).text('Processing...');
    });
    // =========================
    // ✅ STATUS CHANGE FUNCTION
    // =========================
    let selectedBtn = null;
    // CLICK STATUS BUTTON
    jq(document).on('click', '.changeStatus', function () {
        selectedBtn = this;
        let id = jq(this).data('id');
        let status = jq(this).data('status');
        jq('#statusId').val(id);
        jq('#statusValue').val(status);
        jq('#statusModal').modal('show');
    });
    // SUBMIT STATUS CHANGE
    jq('#statusForm').on('submit', function(e) {
        e.preventDefault();
        let btn = jq('#statusBtn');
        btn.prop('disabled', true).text('Processing...');
        let id = jq('#statusId').val();
        let status = jq('#statusValue').val();
        jq.ajax({
            url: "{{ route('update_bank_status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                status: status
            },
            success: function(res) {
                // CLOSE MODAL
                jq('#statusModal').modal('hide');
                // RESET BUTTON
                btn.prop('disabled', false).text('Yes');
                // RELOAD TABLE (NO PAGE RESET)
                table.ajax.reload(null, false);
            },
            error: function() {
                alert('Something went wrong');
                btn.prop('disabled', false).text('Yes');
            }
        });
    });
});
</script>
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