@extends('Admin.layout')



@section('content')



    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center">

            <h5 class="fw-bold mb-1"><button type="button" class="btn btn-icon bg-white waves-effect me-2"

                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                    <a href="{{ route('admin.dashboard') }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>

                            <path d="M15 6l-6 6l6 6"></path>

                        </svg>

                    </a>

                </button>Hold Tasks</h5>

            <div>

            </div>

        </div>

    </div>



    <div class="card border-0 shadow-sm rounded-4">





        <div class="card-header bg-white border-bottom rounded-top-4 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">



                <div class="d-flex align-items-center gap-2">

                    <span class="text-muted small">Show</span>

                    <select id="customLength" class="form-select" style="width:80px;">

                        <option value="10">10</option>

                        <option value="25">25</option>

                        <option value="50">50</option>

                        <option value="100">100</option>

                    </select>

                    <span class="text-muted small">entries</span>

                </div>



                <div>

                    <input type="text" id="customSearch" class="form-control" placeholder="Search tasks..."

                        style="width:220px;">

                </div>



            </div>

        </div>



        <div class="table-responsive" style="max-height:500px; overflow-y:auto;">

            <table class="table table-hover align-middle mb-0" id="pointsHistoryTable">

                <thead class="table-light sticky-top">

                    <tr>

                        <th width="60">SNO</th>

                        <th>Project</th>

                        <th>Module</th>

                        <th>Task</th>

                        <th>Assigned Employee</th>

                        <th>Status</th>

                        <th width="120">Action</th>

                    </tr>

                </thead>

                <tbody></tbody>

            </table>

        </div>







    </div>



@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>



<script>

$(document).ready(function () {

const taskUrlTemplate ="{{ route('task_description','') }}/";

const table = $('#pointsHistoryTable').DataTable({

processing:true,

ajax:{

url:"{{ route('admin_hold_tasks_data') }}",

dataSrc:''

},

pageLength:10,

lengthChange:false,

searching:true,

ordering:false,

dom:'t<"d-flex justify-content-between align-items-center px-3 py-3"ip>',

columns:[

{
data:null,
render:function(data,type,row,meta){

return meta.row +
meta.settings._iDisplayStart +1;

}
},

{data:'project'},

{data:'module'},

{data:'task'},

{
data:'employee',
render:function(data){

return `<span class="fw-semibold">${data}</span>`;

}
},

{

data:'status',

render:function(data){

return `

<span class="badge rounded-pill px-3 py-2"
style="background:#fff3cd;color:#856404;">

<i class="ti tabler-clock me-1"></i>

${data}

</span>`;

}

},

{

data:null,

render:function(data,type,row){

return`

<a class="btn btn-sm btn-outline-primary rounded-pill px-3"

href="${taskUrlTemplate + row.id}">

<i class="ti tabler-eye me-1"></i> View

</a>`;

}

}

]

});

$('#customLength').on('change',function(){

table.page.len($(this).val()).draw();

});

$('#customSearch').on('keyup',function(){

table.search(this.value).draw();

});

});

</script>