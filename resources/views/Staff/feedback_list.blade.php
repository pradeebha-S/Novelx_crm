@extends('Staff.layout')

<style>

    .dt-search {

        display: none !important;

    }

</style>

@section('content')

    <div class="row align-items-center justify-content-between mb-2 m-1">

        <!-- Heading -->

        <div class="col-auto">

            <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2"

                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                    <a href="{{ route('feed_back_submit') }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                            <path d="M15 6l-6 6l6 6" />

                        </svg>

                    </a>

                </button>Previous Feedbacks</h5>

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

                        <table class="table table-hover align-middle mb-0" id="usersTable">



                                <thead class="table-light">
    <tr>
        <th>Sno</th>
         <th>Date</th>
        <th>Positive Feedback</th>
        <th>Negative Feedback</th>
        <th>Action</th>
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

     $(document).ready(function () {

    var table = $('#usersTable').DataTable({

        processing: true,
        serverSide: true,
        ajax: "{{ route('feedback_data') }}",

        searching: true,
        paging: true,
        info: true,
        lengthChange: false,
        pageLength: 10,
        ordering: false,

    columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
{ data: 'date', name: 'created_at' },
    { data: 'positive_feedback', name: 'positive_feedback' },

    { data: 'negative_feedback', name: 'negative_feedback' },


    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
    }
]

    });

    // custom search
    $('#toDate').on('keyup change', function () {
        table.search(this.value).draw();
    });

});

    </script>



@endsection