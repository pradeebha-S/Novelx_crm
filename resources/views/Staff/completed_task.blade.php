@extends('Staff.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    .link {
        text-decoration: underline;
    }
</style>
@section('content')
<div class="row align-items-center justify-content-between mb-3">
    <!-- Heading -->
    <div class="col-auto">
        <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('staff_task') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>Complete Tasks</h5>
    </div>
</div>
<div class="card p-4">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full ">
                    <table id="dept" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th class="text-nowrap">Start Date</th>
                                <th class="text-nowrap">Due Date</th>
                                <th>PROJECT</th>
                                <th>MODULE</th>
                                <th>TASK</th>
                                                                <th>EH</th>

                                <th>STATUS</th>
                                <th>ACTION</th>

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
    $(function() {
        $('#dept').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('completed_task_data') }}",

            order: [
                [1, 'desc']
            ],
            language: {
                search: "",
                searchPlaceholder: "Search project",
                lengthMenu: "_MENU_"
            },

            lengthMenu: [
                [10, 25, 50, 100, -1],
                ["10", "25", "50", "100", "All"]
            ],

            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'start_date',
                    orderable: false
                },
                {
                    data: 'due_date',
                    orderable: false
                },

                {
                    data: 'project',
                    orderable: false
                },
                {
                    data: 'module',
                    orderable: false
                },
                {
                    data: 'task',
                    orderable: false
                },
                 {
                        data: 'estimated_time'
                    },
                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }

            ]
        });
    });
</script>

@endsection