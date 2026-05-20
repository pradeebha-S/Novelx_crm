@extends('Admin.layout')
<style>
    .dt-search
    {
        display: none !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')
    <div class="row align-items-center mb-3 mt-3 m-2">
        <div class="col d-flex justify-content-between align-items-center">
            <h5 class="mb-0"> <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('admin.candidates') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>Candidates Follow Up</h5>

        </div>
    </div>

    <div class="card p-0 m-2">

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
                                    <th class="text-nowrap">Interview Date</th>
                                    <th class="text-nowrap">Interview Mode</th>
                                    <th class="text-nowrap">Interview Time</th>
                                    <th class="text-nowrap">Remarks</th>
                                    <th class="text-nowrap">Status</th>
                                    <!-- <th class="text-nowrap">Action</th> -->
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function () {

            // Sample Data
            const users = [
                {
                    interview_date: '12/10/2025',
                    interview_mode: 'Online',
                    interview_time: '10:00 AM',
                    remarks: 'Good communication',
                    status: 'Pending'
                },
                {
                    interview_date: '15/10/2025',
                    interview_mode: 'Offline',
                    interview_time: '2:00 PM',
                    remarks: 'Strong technical skills',
                    status: 'Completed'
                },
                {
                    interview_date: '18/10/2025',
                    interview_mode: 'Online',
                    interview_time: '11:30 AM',
                    remarks: 'Needs improvement',
                    status: 'Pending'
                }
            ];

            // Initialize DataTable
            const table = $('#usersTable').DataTable({
                data: users,
                paging: true,
                searching: true,
                info: true,
                ordering: false,
                lengthChange: false,
                pageLength: 10,
                columns: [
                    {
                        data: null,
                        render: (data, type, row, meta) =>
                            meta.row + meta.settings._iDisplayStart + 1
                    },
                    { data: 'interview_date' },
                    { data: 'interview_mode' },
                    { data: 'interview_time' },
                    { data: 'remarks' },
                    { data: 'status' },
                    // {
                    //     data: null,
                    //     orderable: false,
                    //     render: () =>
                    //         `<a href="#" class="text-underline"><b>View</b></a>`
                    // }
                ]
            });

            $('#toDate').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Page Length Selector
            $('#pageLength').on('change', function () {
                table.page.len(this.value).draw();
            });

        });
    </script>
@endsection