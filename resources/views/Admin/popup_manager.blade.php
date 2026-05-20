@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
<style>
    .dt-search {
        display: none !important;
    }
</style>
@section('content')
   <div class="row d-flex justify-content-between">
    <div class="col-auto">
         <h5>

        Popup Report
    </h5>
    </div>
    <div class="col-auto">
        <a href="{{ route('popup_manager_form') }}" class="btn btn-primary mb-2">
            <i class="ti tabler-plus"></i>&nbsp;Add Popup
        </a>
    </div>
   </div>
    <div class="card p-0 mt-3">
        <div class="row mb-3 g-1 align-items-center p-3">

            <!-- Page Length -->
            <div class="col-md-1 mt-auto mb-2">
                <select id="pageLength" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="All">All</option>
                </select>
            </div>

            <div class="col d-flex justify-content-end align-items-center gap-3 mt-auto">

                <div class="position-relative mb-2">
                    <input type="text" id="searchBox" class="form-control" placeholder="Search...">
                </div>

                <button class="btn btn-label-secondary mb-2">
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
                                <th class="text-nowrap">User Name</th>
                                <th class="text-nowrap">Description</th>
                                <th class="text-nowrap">Popup Status</th>
                                <th class="text-nowrap">Noted Count</th>
                                <th class="text-nowrap">Done Status</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Status Change Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header border-0">



                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body text-center">
                    <h5 class="text-center">
                        Change Status
                    </h5>
                    <div class="mb-3">
                        <i class="fa-solid fa-circle-exclamation text-warning" style="font-size:55px;"></i>
                    </div>

                    <h4 class="fw-bold mb-2">
                        Confirm Action
                    </h4>

                    <p class="text-muted mb-0" id="statusMessage">
                        Do you want to inactive?
                    </p>

                </div>

                <div class="text-center mb-5">



                    <button type="button" class="btn btn-danger" id="confirmStatusBtn">

                        Yes, Continue

                    </button>

                </div>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
 <script>
    $(document).ready(function () {

        const data = [
            {
                sno: 1,
                datetime: "16 May 2026 10:30 AM",
                username: "Abi",
                description: "Lead assigned successfully",
                popup_status: "Active",
                noted_count: 5,
                done_status: "Completed",
            },
            {
                sno: 2,
                datetime: "16 May 2026 11:15 AM",
                username: "Arun",
                description: "Follow up reminder created",
                popup_status: "Inactive",
                noted_count: 2,
                done_status: "Pending",
            },
            {
                sno: 3,
                datetime: "16 May 2026 12:45 PM",
                username: "Kumar",
                description: "Quotation shared with customer",
                popup_status: "Active",
                noted_count: 7,
                done_status: "Completed",
            }
        ];

        let table = $('#dept').DataTable({

            data: data,

            pageLength: 10,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,

            columnDefs: [
                {
                    targets: '_all',
                    className: 'text-nowrap text-center align-middle'
                }
            ],

            columns: [

                {
                    data: 'sno'
                },

                {
                    data: 'datetime'
                },

                {
                    data: 'username'
                },

                {
                    data: 'description'
                },

                // Popup Status
                {
                    data: 'popup_status',
                    render: function (data, type, row) {

                        let badgeClass = data === 'Active'
                            ? 'bg-label-success'
                            : 'bg-label-danger';

                        return `
                            <span class="badge ${badgeClass} popup-status-btn"
                                role="button"
                                data-id="${row.sno}"
                                data-status="${data}">
                                ${data}
                            </span>
                        `;
                    }
                },

                // Noted Count
                {
                    data: 'noted_count',
                    render: function (data) {
                        return `
                            <span class="badge bg-label-primary">
                                ${data}
                            </span>
                        `;
                    }
                },

                // Done Status
                {
                    data: 'done_status',
                    render: function (data) {

                        let statusClass = data === 'Completed'
                            ? 'bg-label-success'
                            : 'bg-label-warning';

                        return `
                            <span class="badge ${statusClass}">
                                ${data}
                            </span>
                        `;
                    }
                }

            ]

        });

        // Custom Search
        $('#searchBox').on('keyup', function () {
            table.search($(this).val()).draw();
        });

        // Custom Page Length
        $('#pageLength').on('change', function () {

            let value = $(this).val();

            if (value === 'All') {
                table.page.len(-1).draw();
            } else {
                table.page.len(parseInt(value)).draw();
            }

        });

    });
</script>
    <script>

        let selectedRowId = null;

        $(document).on('click', '.popup-status-btn', function () {

            let status = $(this).data('status');
            selectedRowId = $(this).data('id');

            if (status === 'Active') {

                $('#statusMessage').text('Do you want to inactive?');

            } else {

                $('#statusMessage').text('Do you want to active?');

            }

            let modal = new bootstrap.Modal(
                document.getElementById('statusModal')
            );

            modal.show();

        });

        $('#confirmStatusBtn').on('click', function () {

            console.log('Selected Row ID:', selectedRowId);

            // AJAX CALL HERE

            $('#statusModal').modal('hide');

        });

    </script>
@endsection