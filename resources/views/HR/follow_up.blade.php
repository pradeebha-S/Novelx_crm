@extends('HR.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')
    <div class="row align-items-center mb-3 mt-3 m-2">
        <div class="col d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('follow_up_table') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>Follow Up Details</h5>

        </div>
    </div>

    <div class="card p-0 m-2">

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
                                    <th class="text-nowrap">Interview Date</th>
                                    <th class="text-nowrap">Interview Mode</th>
                                    <th class="text-nowrap">Interview Time</th>
                                    <th class="text-nowrap">Remarks</th>
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
    <div class="modal fade" id="candidateModal" tabindex="-1" aria-labelledby="candidateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('follow_up') }}" id="login_form">
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
                },
                {
                    interview_date: '15/10/2025',
                    interview_mode: 'Offline',
                    interview_time: '2:00 PM',
                    remarks: 'Strong technical skills',
                },
                {
                    interview_date: '18/10/2025',
                    interview_mode: 'Online',
                    interview_time: '11:30 AM',
                    remarks: 'Needs improvement',
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
                    {
                        data: null,
                        orderable: false,
                        render: () =>
                            `      <div class="d-flex justify-content-between"></div>
                                                            <a href="#" class="btn btn-label-success ms-2" data-bs-toggle="modal" data-bs-target="#candidateModal"><b>Add</b></a>
                                                        </div>
                    `
                    },
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