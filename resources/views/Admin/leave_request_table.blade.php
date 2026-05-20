@extends('Admin.layout')

@section('content')

<div class="row me-6">

    <div class="col-lg-11">

        <ul class="nav nav-pills flex-column flex-sm-row mb-4 gap-sm-0 gap-2 mt-4">

            <li class="nav-item">

                <a class="nav-link waves-effect waves-light" href="{{ route('attendance_history') }}"> Attendance</a>

            </li>

            <li class="nav-item">

                <a class="nav-link active waves-effect waves-light" href="{{ route('leave_request_table') }}">Leave Request</a>

            </li>

            <li class="nav-item">

                <a class="nav-link waves-effect waves-light" href="{{ route('wfh_table') }}">Work From Home</a>

            </li>

            <li class="nav-item">

                <a class="nav-link waves-effect waves-light" href="{{ route('permission_table') }}">Permission</a>

            </li>

        </ul>

    </div>

    <div class="col-lg-1">

        <a href="{{ route('leave_request_history') }}"> <button class="btn btn-primary">Histories</button></a>

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

                                  <th>Date</th>

                                <th class="text-nowrap">Staff Name</th>

                                <th class="text-nowrap">From Date</th>

                                <th class="text-nowrap">To Date</th>

                                    <th class="text-nowrap">Informed To</th>
                                     <th class="text-nowrap">Is Mailed</th>

                                <th>Reason</th>

                                <th>Status</th>

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

<div class="modal fade" id="reply" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-md modal-dialog-centered">

        <div class="modal-content rounded-4 p-4 py-5">

            <p class="text-muted text-center">Reply...?</p>

            <form action="{{ route('leave_reply') }}" method="post" id="replyForm">

                @csrf

                <input type="hidden" id="reply_id" name="id">

                <div class="row">

                    <div class="col-lg-6 mb-3">

                        <label class="form-label">Reply</label>

                        <select class="form-select" name="reply">

                            <option value="">-- Select Status --</option>

                            <option value="approved">Approved</option>

                            <option value="not_approved">Not Approved</option>

                        </select>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <label class="form-label">Remark</label>

                        <textarea class="form-control" rows="2" name="remark"></textarea>

                    </div>

                </div>

                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">

                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit" class="btn btn-primary p-3 fw-semibold" id="finalSubmit">

                        Yes, Sure

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

<script>

    $(function() {

        $('#dept').DataTable({

            processing: true,

            serverSide: true,

            ajax: "{{ route('leave_request_table_data') }}",

            columns: [{

                    data: 'DT_RowIndex',

                    orderable: false,

                    searchable: false

                },

  {

                    data: 'date',

                    name: 'date'

                },

                {

                    data: 'staff_name',

                    name: 'user.name'

                },

                {

                    data: 'from',

                    name: 'from'

                },

                {

                    data: 'to',

                    name: 'to'

                },

                   { data: 'informed_to'},
  { data: 'mailed', name: 'mailed' },
                {

                    data: 'reason',

                    name: 'reason'

                },

                {

                    data: 'action',

                    orderable: false,

                    searchable: false

                }

            ],

            language: {

                search: "",

                searchPlaceholder: "Search Leave",

                lengthMenu: "_MENU_"

            },

            lengthMenu: [

                [10, 25, 50, 100, -1],

                ["10", "25", "50", "100", "All"]

            ]

        });

    });

   $(document).on('click', '.open-reply', function () {

    let id = $(this).data('id');

    $('#reply_id').val(id);

});

    document.getElementById('finalSubmit').addEventListener('click', function(e) {

        e.preventDefault();

        let btn = this;

        btn.disabled = true;

        btn.innerText = 'Processing...';

        document.getElementById('replyForm').submit();

    });

</script>

@endsection