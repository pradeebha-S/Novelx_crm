@extends('Admin.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
           Staff Bank Details
        </h5>
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
                                    <th>AADHAR NUMBER</th>
                                    <th>PAN NUMBER</th>
                                    <th>UPI</th>
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
    <script>
        var jq = jQuery.noConflict();
        jq(document).ready(function() {
            // ✅ DATATABLE INIT
            var table = jq('#dept').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staff_bank_details_data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'account_number'
                    },
                    {
                        data: 'account_holder_name'
                    },
                    {
                        data: 'ifsc_code'
                    },
                    {
                        data: 'bank_name'
                    },
                    {
                        data: 'branch_name'
                    },
                    {
                        data: 'aadhar_number'
                    },
                    {
                        data: 'pan_number'
                    },
                     {
                        data: 'upi'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            // =========================
            // ✅ DELETE FUNCTION
            // =========================
            jq(document).on('click', '.deleteBtn', function() {
                let id = jq(this).data('id');
                jq('#deleteId').val(id);
            });
            jq('#deleteForm').on('submit', function() {
                let btn = jq('#finalSubmit');
                btn.prop('disabled', true).text('Processing...');
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
