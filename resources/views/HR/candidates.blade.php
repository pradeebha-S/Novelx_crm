@extends('Admin.layout')
<style>
.text-underline {
    text-decoration: underline;
    text-decoration-thickness: 1px;
    text-underline-offset: 2px;
}

.dt-search {
    display: none !important;
}
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')
<div class="row align-items-center mb-3 mt-3 m-2">
    <div class="col d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Candidates</h5>

        <a href="{{ route('candidates_form') }}" class="text-decoration-none">
            <button class="btn btn-primary">Add Candidates</button>
        </a>
    </div>
</div>

<div class="card p-0 m-2">

    <div class="row align-items-center mb-3 p-3 g-3">

        <div class="col-lg-3 col-12">
            <h6 class="mb-0">Filters</h6>
        </div>
        <div class="col-lg-9 col-12 d-flex flex-lg-row flex-column justify-content-lg-end gap-2">
            <input type="text" id="techFilter" placeholder="Search By Technologies..." class="form-control w-auto">
            <input type="text" id="expFilter" placeholder="Search By Category..." class="form-control w-auto">
            <button id="filterBtn" class="btn btn-primary">Search</button>

        </div>

    </div>


    <hr>
    <div class="row mb-3 align-items-center p-3 g-2">
        <div class="col-1">
            <select id="pageLength" class="form-select">
                <option value="10"> 10</option>
                <option value="25"> 25</option>
                <option value="50"> 50</option>
                <option value="100"> 100</option>
            </select>
        </div>

        <div class="col-md-3 ms-auto">
            <input type="text" id="toDate" class="form-control" placeholder="Search">
        </div>
    </div>


    <div class="table-responsive pt-0 pb-2">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 p-0">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Sno</th>
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Category</th>
                                <th class="text-nowrap">Name</th>
                                <th>Count</th>
                                <th class="text-nowrap">Mobile</th>
                                <th class="text-nowrap">Technologies</th>
                                <th class="text-nowrap">Experience</th>
                                <!-- <th class="text-nowrap">Follow Up</th> -->
                                <th>Status</th>
                                <th class="text-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="candidateModal" tabindex="-1" aria-labelledby="candidateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.candidates') }}" id="login_form">
                    <h6>Interview & Remarks</h6>
                    <div class="row g-2">
                        <div class="col-lg-4 mb-3">
                            <label for="interview_date" class="form-label">Interview Date</label>
                            <input type="date" name="interview_date" id="interview_date" class="form-control">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label for="interview_time" class="form-label">Interview Time</label>
                            <input type="time" name="interview_time" id="interview_time" class="form-control">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label for="interview_mode" class="form-label">Interview Mode <span
                                    class="text-danger">*</span></label>
                            <select name="interview_mode" id="interview_mode" class="form-select" required>
                                <option value="">Select</option>
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                            </select>
                        </div>


                        <div class="col-lg-12 mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="3"
                                placeholder="Enter remarks"></textarea>
                        </div>
                    </div>

                    <div class="form-actions d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary me-3" id="submit_btn">Submit</button>
                        <button type="reset" class="btn btn-label-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="callModal" tabindex="-1" aria-labelledby="callModal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('update_call_status') }}" id="callStatusForm">
                    @csrf
                    <input type="hidden" name="candidate_id" id="candidate_id">
                    <h6>Last Call Status</h6>
                    <div class="row g-2">

                        <div class="col-lg-12 mb-3">
                            <label for="interview_mode" class="form-label">Call Status <span
                                    class="text-danger">*</span></label>
                            <select name="call_status" id="call_status" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Shortlisted">Shortlisted</option>
                                <option value="Completed">Completed</option>
                                <option value="Follow up">Follow up</option>
                            </select>
                        </div>


                        <div class="col-lg-12 mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="3"
                                placeholder="Enter remarks" required></textarea>
                        </div>
                    </div>

                    <div class="form-actions d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary me-3 submit_btn">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-label-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {

    $('#callStatusForm').on('submit', function() {

        let btn = $('#submit_btn');

        btn.prop('disabled', true);

        btn.html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Processing...
            `);

    });

});
</script>
<script>
$(document).ready(function() {

    const table = $('#usersTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: "{{ route('candidate_list') }}",

        paging: true,
        searching: true,
        info: true,
        ordering: false,
        lengthChange: false,
        pageLength: 10,

        columnDefs: [{
            targets: '_all',
            className: 'text-nowrap'
        }],

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },

            {
                data: 'created_at',
                name: 'created_at'
            },

            {
                data: 'category',
                name: 'category'
            },

            {
                data: 'candidate_name',
                name: 'candidate_name'
            },

            {
                data: 'count',
                name: 'count',
                searchable: false,
                orderable: false
            },

            {
                data: 'phone_number',
                name: 'phone_number'
            },

            {
                data: 'technology',
                name: 'technology'
            },

            {
                data: 'experience',
                name: 'experience',
                render: function(data) {
                    return data + ' Years';
                }
            },

            {
                data: 'status',
                name: 'status',
                searchable: false,
                orderable: false
            },

            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false
            }
        ]
    });

    $('#filterBtn').on('click', function() {

        table
            .column(6).search($('#techFilter').val())
            .column(7).search($('#expFilter').val())
            .draw();
    });

    $('#toDate').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#pageLength').on('change', function() {
        table.page.len(this.value).draw();
    });

});
</script>

<script>
$(document).on('click', '.openCallModal', function() {

    let candidateId = $(this).data('id');

    $('#candidate_id').val(candidateId);
});


$('#callStatusForm').submit(function(e) {

    e.preventDefault();

    let btn = $(this).find('.submit_btn');

    btn.prop('disabled', true);

    btn.html(`
        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
        Processing...
    `);

    $('.error-text').remove();

    $('.form-control, .form-select').removeClass('is-invalid');

    $.ajax({

        url: "{{ route('update_call_status') }}",

        type: "POST",

        data: $(this).serialize(),

        success: function(response) {

            $('#callModal').modal('hide');

            $('#callStatusForm')[0].reset();

            $('#usersTable').DataTable().ajax.reload(null, false);

            btn.prop('disabled', false);

            btn.html('Submit');

            showToast('success', response.message);
        },

        error: function(xhr) {

            btn.prop('disabled', false);

            btn.html('Submit');

            if (xhr.status == 422) {

                $.each(xhr.responseJSON.errors, function(key, value) {

                    $('#' + key).addClass('is-invalid');

                    $('#' + key).after(
                        '<small class="text-danger error-text">' +
                        value[0] +
                        '</small>'
                    );
                });

            } else {

                showToast('error', xhr.responseJSON.message);
            }
        }
    });
});
</script>
@endsection