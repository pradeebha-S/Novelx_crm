@extends('Staff.layout')

<style>
     .dt-search, .dt-length {
        display: none !important;
    }
</style>
@section('content')
  <div class="row align-items-center justify-content-between mb-3 mt-3 m-2">
        <div class="col-auto">
            <h5>
                <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{route('staff.dashboard')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a></button>Break Report
            </h5>
        </div>

    </div>
    <div class="card p-0 m-2">
        <div class="row mb-3 align-items-center p-3 g-2">

            <!-- Left -->
            <div class="col-auto">
                <select id="pageLength" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="All">All</option>
                </select>
            </div>

            <!-- Right side -->
            <div class="col d-flex justify-content-end gap-2">

                <div style="width:180px;">
                    <input type="text" id="fromDate" class="form-control" placeholder="From Date" onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'">
                </div>

                <div style="width:180px;">
                    <input type="text" id="toDate" class="form-control" placeholder="To Date" onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'">
                </div>
                <div style="width:180px;">

                    <input type="text" id="searchBox" class="form-control" placeholder="Search...">
                </div>
                <button class="btn btn-primary" id="searchButton">
                    Search
                </button>

            </div>

        </div>
        <div class="card-datatable table-responsive pt-0">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 p-0">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table id="dept" class="table">
                            <thead>
                                <th>SNO</th>
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Start Time</th>
                                <th class="text-nowrap">End Time</th>
                                <th class="text-nowrap">Hours</th>
                                <th class="text-nowrap">Status</th>


                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
   <script>
$(document).ready(function () {

    let table = $('#dept').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('break_report_data') }}",
            type: "GET",

            data: function (d) {

                d.from_date = $('#fromDate').val();
                d.to_date = $('#toDate').val();
                d.search_value = $('#searchBox').val();
            }
        },

        searching: false,
        lengthChange: false,
        pageLength: 10,
        ordering: false,

        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },

            { data: 'date', name: 'date' },

            { data: 'start_time', name: 'start_time' },

            { data: 'end_time', name: 'end_time' },

            { data: 'hours', name: 'hours' },

            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },
        ]
    });

    // Search button click
    $('#searchButton').on('click', function () {
        table.draw();
    });

    // Search textbox enter
    $('#searchBox').on('keyup', function () {
        table.draw();
    });

    // Page length
    $('#pageLength').on('change', function () {

        let length = $(this).val() === 'All'
            ? -1
            : $(this).val();

        table.page.len(length).draw();
    });

});
</script>
@endsection