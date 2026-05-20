@extends('Admin.layout')

@section('content')

<div class="row me-6">



    <div class="col-lg-11">

        <ul class="nav nav-pills flex-column flex-sm-row mb-4 gap-sm-0 gap-2 mt-4">

            <li class="nav-item">

                <a class="nav-link active waves-effect waves-light" href="{{ route('attendance_history') }}"> Attendance</a>

            </li>

            <li class="nav-item">

                <a class="nav-link waves-effect waves-light" href="{{ route('leave_request_table') }}">Leave Request</a>

            </li>

            <li class="nav-item">

                <a class="nav-link waves-effect waves-light" href="{{ route('wfh_table') }}">Work From Home</a>

            </li>

            <li class="nav-item">

                <a class="nav-link waves-effect waves-light" href="{{ route('permission_table') }}">Permission</a>

            </li>

        </ul>

    </div>

</div>

<div class="card p-4 mt-4">

    <div class="card-datatable table-responsive pt-0">

        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">

            <div class="justify-content-between dt-layout-table">

                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">

                    <table id="dept" class="table">

                        <thead>

                            <tr>

                                <th>SNO</th>

                                <th class="text-nowrap">DATE</th>

                                <th class="text-nowrap">Employee ID</th>

                                <th class="text-nowrap">Name</th>

                                <th class="text-nowrap">Check In</th>

                                <th class="text-nowrap">Type</th>

                                <th class="text-nowrap">Check Out</th>

                                <th class="text-nowrap">Late Reason</th>

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

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

<script>

  var jq = jQuery.noConflict();



jq(document).ready(function () {

    jq('#dept').DataTable({

        processing: true,

        serverSide: true,

        ajax: "{{ route('attendance_history_data') }}",

        columns: [

            { data: 'DT_RowIndex', orderable: false, searchable: false },

            { data: 'created_at' },

            { data: 'user_id' },

            { data: 'user_name' },

            { data: 'check_in' },

            { data: 'type', orderable: false, searchable: false },

            { data: 'check_out' },

            { data: 'late_reason', orderable: false, searchable: false }



        ],

        language: {

        order: [[1,'desc']],

            search: "",

            searchPlaceholder: "Search",

            lengthMenu: "_MENU_"

        },

        lengthMenu: [

            [10, 25, 50, 100, -1],

            ["10", "25", "50", "100", "All"]

        ]

    });

});

</script>



@endsection