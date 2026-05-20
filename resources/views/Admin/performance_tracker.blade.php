@extends('Admin.layout')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h5 class="mb-1"><button type="button" class="btn btn-icon bg-white waves-effect me-2"

                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                    <a href="{{ route('transaction_details') }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                            <path d="M15 6l-6 6l6 6" />

                        </svg>

                    </a>

                </button>Performance History</h5>

            <small class="text-muted">Track all staff credit and debit entries</small>

        </div>

    </div>

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-body p-0">

           

            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">



    <!-- Left Side -->

    <div>

        <select id="customLength" class="form-select" style="width:80px;">

            <option value="10">10</option>

            <option value="25">25</option>

            <option value="50">50</option>

            <option value="100">100</option>

        </select>

    </div>



    <!-- Right Side -->

    <div class="d-flex align-items-center gap-2">



        <input type="text"

            id="customSearch"

            class="form-control"

            placeholder="Search..."

            style="width:200px;">



        <select id="staffFilter"

            class="form-select"

            style="width:200px;">



            <option value="">Select Employee</option>



            @foreach($users as $user)

            <option value="{{$user->id}}">

                {{$user->name}}

            </option>

            @endforeach



        </select>



        <select id="monthFilter"

            class="form-select"

            style="width:160px;">



            <option value="">Select Month</option>

            <option value="01">January</option>

            <option value="02">February</option>

            <option value="03">March</option>

            <option value="04">April</option>

            <option value="05">May</option>

            <option value="06">June</option>

            <option value="07">July</option>

            <option value="08">August</option>

            <option value="09">September</option>

            <option value="10">October</option>

            <option value="11">November</option>

            <option value="12">December</option>



        </select>



        <button id="clearFilters"

            class="btn btn-light border">



            Clear



        </button>



    </div>



</div>

            <div class="table-responsive">

                <table class="table align-middle mb-0" id="adminHistoryTable">

                    <thead class="table-light">

                        <tr>

                            <th>Date&nbsp;&&nbsp;Time</th>

                            <th>Staff&nbsp;Name</th>

                            <th>Type</th>

                            <th>Points</th>

                            <th>Description</th>

                            <th>Added&nbsp;By</th>

                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<script>

$(document).ready(function(){

let table = $('#adminHistoryTable').DataTable({

processing:true,

serverSide:true,

ordering:false,

lengthChange:false,

ajax:{

url:"{{ route('transaction_data') }}",

data:function(d){

d.staff = $('#staffFilter').val();

d.month = $('#monthFilter').val();

}

},

dom:'rt<"d-flex justify-content-between align-items-center px-3 py-3"ip>',

columns:[

{data:'datetime',name:'datetime'},

{data:'staff',name:'staff'},

{data:'type',name:'type'},

{data:'points',name:'points'},

{data:'description',name:'description'},

{data:'added_by',name:'added_by'}

]

});

// Search box

$('#customSearch').keyup(function(){

table.search(this.value).draw();

});

// Staff filter

$('#staffFilter').change(function(){

table.draw();

});

// Month filter

$('#monthFilter').change(function(){

table.draw();

});

// Clear Filter

$('#clearFilters').click(function(){

$('#customSearch').val('');

$('#staffFilter').val('');

$('#monthFilter').val('');

table.search('').draw();

});

});

</script>