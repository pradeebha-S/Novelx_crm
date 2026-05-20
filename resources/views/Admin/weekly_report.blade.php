@extends('Admin.layout')

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div class="d-flex align-items-center gap-3">

            <div>
                <h4 class="fw-bold mb-0"><a href="{{ route('staff_table') }}" class="btn btn-icon bg-white p-2 shadow-sm">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                            <path d="M15 6l-6 6l6 6" />

                        </svg>

                    </a>&nbsp; Weekly Report | <span class="text-muted">{{ $staff->name }}</span> |
                </h4>
            </div>
        </div>

        <button id="exportBtn" class="btn btn-label-secondary shadow-sm">
            <i class="ti tabler-upload me-1"></i> Export CSV
        </button>

    </div>


    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">

            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" id="from_date" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" id="to_date" class="form-control">
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button id="filterBtn" class="btn btn-primary w-100">
                        <i class="ti tabler-filter me-1"></i> Filter
                    </button>
                    <button id="resetBtn" class="btn btn-outline-secondary w-100">
                        Reset
                    </button>
                </div>

            </div>

        </div>
    </div>


    <!-- ================= TABLE CARD ================= -->
    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-header border-0 pt-4 pb-2 px-4">
            <h5 class="fw-semibold mb-0">Attendance & Work Summary</h5>
            <small class="text-muted">Weekly activity overview</small>
            <hr>
        </div>

        <div class="card-body pt-0">

            <div class="table-responsive">
                <table id="staff" class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>SNO</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Project Count</th>
                            <th>Project Names</th>
                            <th>Worked Hours</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>
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

    $(document).ready(function () {



        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {



            let fromDate = $('#from_date').val();

            let toDate = $('#to_date').val();

            let tableDate = data[1];



            if (!fromDate && !toDate) return true;



            let parts = tableDate.split('-');

            let rowDate = new Date(parts[2], parts[1] - 1, parts[0]);



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

                data: 'check_in'

            },

            {

                data: 'check_out'

            },

            {

                data: 'status',

                render: function (status) {

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





        $('#filterBtn').on('click', function () {

            table.draw();

        });



        $('#resetBtn').on('click', function () {

            $('#from_date').val('');

            $('#to_date').val('');

            table.draw();

        });





        $('.buttons-collection').on('click', function () {

            table.button('.buttons-csv').trigger();

        });



    });

</script>