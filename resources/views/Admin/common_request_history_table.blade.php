@extends('Admin.layout')
@section('content')
<div class="row align-items-center justify-content-between mb-3">
    <!-- Heading -->
    <div class="col-auto">
        <div class="col d-flex align-items-center gap-2">
            <a href="{{ route('common_request_table') }}" class="btn btn-icon bg-white waves-effect"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 6l-6 6l6 6" />
                </svg>
            </a>

            <h5 class="mb-0">Histories</h5>
        </div>
    </div>

    <!-- Button -->
    <!-- <div class="col-auto">
                <a href="{{ route('personal_request_table') }}"> <button class="btn buttons-collection btn-primary" type="button" aria-haspopup="dialog"
                        aria-expanded="false">
                        <span class="d-flex align-items-center gap-2">
                            <span class="d-sm-inline-block">Reply Histories</span>
                        </span>
                    </button></a>
            </div> -->
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
                                <th class="text-nowrap">TITLE</th>
                                <th class="text-nowrap">DESCRIPTION</th>
                                <th>REMARK</th>

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
    jq(document).ready(function() {
        jq('#dept').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('common_request_history_data') }}",
            order: [
                [1, 'desc']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'title'
                },
                {
                    data: 'description'
                },
                 {
                    data: 'remark',
                    orderable: false,
                    searchable: false
                }

            ],
            language: {
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