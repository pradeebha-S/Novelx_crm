@extends('Staff.layout')

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>

    .link {

        text-decoration: underline;

    }

</style>

@section('content')

<div class="row align-items-center mb-3">

    <!-- LEFT SIDE -->

    <div class="col d-flex align-items-center gap-3">

        <h5 class="mb-0"><a href="" class="btn btn-icon bg-white p-2 shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                    stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                    <path d="M15 6l-6 6l6 6" />

                </svg>

            </a>&nbsp;Daily Report </h5>

    </div>

</div>

<div class="card mb-4">



    <div class="card-body">



        <div class="row g-3 align-items-end">

            <div class="col-md-3">



                <label class="form-label fw-semibold">From Date</label>



                <input type="date" id="from_date" class="form-control">



            </div>

            <div class="col-md-3">



                <label class="form-label fw-semibold">To Date</label>



                <input type="date" id="to_date" class="form-control">



            </div>

            <div class="col-md-3">



                <button type="button" id="filterBtn" class="btn btn-primary me-2">



                    <i class="fa fa-filter me-1"></i> Filter



                </button>

                <button type="button" id="resetBtn" class="btn btn-outline-secondary">



                    Reset



                </button>



            </div>

            <div class="col-md-3 text-md-end">



                <button id="exportBtn" class="btn btn-success">



                    <i class="fa fa-file-csv me-1"></i> Export CSV



                </button>



            </div>

        </div>



    </div>



</div>

<div class="card">

    <div class="card-datatable table-responsive pt-0">

        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">

            <div class="justify-content-between dt-layout-table">

                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">

                    <table id="staff" class="table">

                        <thead>

                            <tr>

                                <th>SNO</th>

                                <th class="text-nowrap">Date</th>

                                <th class="text-nowrap">Staff Name</th>

                                <th class="text-nowrap">Check In</th>

                                <th class="text-nowrap">Check Out</th>

                                <th class="text-nowrap">Status</th>

                                <th class="text-nowrap">Project Count</th>



                                  <th class="text-nowrap">Project Names</th>

                                <th class="text-nowrap">Worked Hours</th>

                                <th class="text-nowrap">Action</th>

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

@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>

    const staffData = @json($loginHistories);

</script>

<script>

    $(document).ready(function() {

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

            let fromDate = $('#from_date').val();

            let toDate = $('#to_date').val();

            let tableDate = data[1];

            if (!fromDate && !toDate) return true;

            let parts = tableDate.split('-');

            let rowDate = new Date(parts[2], parts[1] - 1, parts[0]); // yyyy, mm-1, dd

            let from = fromDate ? new Date(fromDate) : null;

            let to = toDate ? new Date(toDate) : null;

            if (from) from.setHours(0, 0, 0, 0);

            if (to) to.setHours(23, 59, 59, 999);

            return (!from || rowDate >= from) && (!to || rowDate <= to);

        });

        const table = $('#staff').DataTable({

            data: staffData,

            paging: true,

            info: true,

            searching: true,

            ordering: false,

            lengthChange: true,

            // dom: 'Bfrtip',

            lengthMenu: [

                [10, 25, 50, 100, -1],

                ["10", "25", "50", "100", "All"]

            ],

            buttons: [{

                extend: 'csv',

                title: 'weekly_report',

                className: 'd-none'

            }],

            columns: [{

                    data: null,

                    render: (d, t, r, m) => m.row + 1

                },

                {

                    data: 'date'

                },

                {

                    data: 'staff_name'

                },

                {

                    data: 'check_in'

                },

                {

                    data: 'check_out'

                },

                {

                    data: 'status',

                    render: function(status) {

                        let badge = 'bg-label-warning';

                        if (status === 'on_time') badge = 'bg-label-success';

                        else if (status === 'late') badge = 'bg-label-danger';

                        return `<span class="badge ${badge} w-100">${status}</span>`;

                    }

                },

                {

                    data: 'project_count'

                },



                 { data: 'project_names' },

                {

                    data: 'worked_hours'

                },

                {

                    data: 'action'

                },

            ]

        });

        $('#filterBtn').on('click', function() {

            table.draw();

        });

        $('#resetBtn').on('click', function() {

            $('#from_date').val('');

            $('#to_date').val('');

            table.draw();

        });

        $('.buttons-collection').on('click', function() {

            table.button('.buttons-csv').trigger();

        });

    });

</script>