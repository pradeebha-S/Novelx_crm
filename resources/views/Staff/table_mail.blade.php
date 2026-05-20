@extends('Staff.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">

@section('content')
    <h5 class="mb-0"> Mail Report </h5>
    <div class="card p-0 mt-3">
        <div class="row mb-3 g-1 align-items-center p-3">

            <!-- Page Length -->
            <div class="col-md-1 mt-auto">
                <select id="pageLength" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="All">All</option>
                </select>
            </div>

            <!-- Right Section -->
            <div class="col d-flex justify-content-end align-items-center gap-3 mt-auto">


                <!-- Search -->
                <div class="position-relative">
                    <input type="text" id="searchBox" class="form-control" placeholder="Search..." style="width:200px;">
                </div>

                <!-- Export -->
                <button class="btn btn-label-secondary">
                    <i class="ti tabler-upload"></i>&nbsp;Export
                </button>

            </div>

        </div>
        <div class="card-datatable table-responsive pt-0">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 p-0">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table id="dept" class="table">
                            <thead>
                                <th class="text-nowrap">S.No</th>
                                <th class="text-nowrap">Date & Time</th>
                                <th class="text-nowrap">Communication Type</th>
                                <th class="text-nowrap">Priority</th>
                                <th class="text-nowrap">Subject</th>
                                <th class="text-nowrap">Need Reply</th>
                                <th class="text-nowrap">Is Replied</th>
                                <th class="text-nowrap">Is Viewed</th>
                                <th class="text-nowrap">Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
   <script>

    $(document).ready(function () {

        const table = $('#dept').DataTable({

            processing: true,

            serverSide: true,

            ajax: "{{ route('mail_report_list') }}",

            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'communication_type',
                    name: 'communication_type'
                },

                {
                    data: 'priority_level',
                    name: 'priority_level'
                },

                {
                    data: 'subject',
                    name: 'subject'
                },

                {
                    data: 'reply_needed',
                    name: 'reply_needed'
                },

                {
                    data: 'is_replied',
                    name: 'is_replied'
                },

                {
                    data: 'is_viewed',
                    name: 'is_viewed'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }

            ],

            pageLength: 10,

            lengthChange: false,

            dom:
                '<"table-responsive"t>' +
                '<"row align-items-center mt-3 px-3"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6 d-flex justify-content-md-end"p>>'

        });

        $('#searchBox').on('keyup', function () {

            table.search(this.value).draw();

        });

        $('#pageLength').on('change', function () {

            table.page.len(this.value).draw();

        });

    });

</script>


@endsection