@extends('Admin.layout')
<style>
.text-underline {
    text-decoration: underline;
    text-decoration-thickness: 1px;
    text-underline-offset: 2px;
}

.dt-search {
    display: none !important;
}
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')
<div class="row align-items-center mb-3 mt-3 m-2">
    <div class="col d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('admin.candidates') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>Candidate Status</h5>
        <!--
            <a href="{{ route('candidates_form') }}" class="text-decoration-none">
                <button class="btn btn-primary">Add Candidates</button>
            </a> -->
    </div>
</div>

<div class="card p-0 m-2">

    <div class="row align-items-center mb-3 p-3 g-3">

        <div class="col-lg-3 col-12">
            <h6 class="mb-0">Filters</h6>
        </div>
        <div class="col-lg-9 col-12 d-flex flex-lg-row flex-column justify-content-lg-end gap-2">
            <input type="text" id="techFilter" placeholder="Search By Technologies..." class="form-control w-auto">
            <input type="text" id="expFilter" placeholder="Search By Experience..." class="form-control w-auto">
            <button id="filterBtn" class="btn btn-primary">Search</button>

        </div>

    </div>


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
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Category</th>
                                <th class="text-nowrap">Name</th>
                                <th class="text-nowrap">Mobile</th>

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

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {

    $('#usersTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: "{{ route('view_status_data', $id) }}",

        paging: true,
        searching: true,
        info: true,
        ordering: false,
        lengthChange: false,
        pageLength: 10,

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },

            {
                data: 'created_at',
                name: 'created_at'
            },

            {
                data: 'category',
                name: 'category'
            },

            {
                data: 'candidate_name',
                name: 'candidate_name'
            },

            {
                data: 'phone_number',
                name: 'phone_number'
            }
        ]
    });

});
</script>
@endsection