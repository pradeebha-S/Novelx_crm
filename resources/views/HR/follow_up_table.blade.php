@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')
    <div class="row align-items-center mb-3 mt-3 m-2">
        <div class="col d-flex justify-content-between align-items-center">
            <h5 class="mb-0"> Candidates Follow Up</h5>

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
                                    <th class="text-nowrap">Date</th>
                                    <th class="text-nowrap">Total Follow Up</th>
                                    <th class="text-nowrap">Pending Follow Up</th>
                                    <!-- <th class="text-nowrap">Status</th> -->
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
                    interview_mode: '100',
                    interview_time: '58',
                },
                {
                    interview_date: '15/10/2025',
                    interview_mode: '100',
                    interview_time: '0',
                    status: 'Completed'
                },
                {
                    interview_date: '18/10/2025',
                    interview_mode: '100',
                    interview_time: '58',
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
{
                        data: null,
                        orderable: false,
                        render: () =>
                            `      <div class="d-flex justify-content-between"></div>
                                                        <a href="{{ route('follow_up') }}" class="btn btn-label-info"><b>View</b></a>
                                                    </div>
                `
                    },                    // {
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