@extends('Admin.layout')
<style>
    .dt-search {
        display: none !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('content')
    <div class="row align-items-center justify-content-between mb-3 mt-3 m-2">
        <div class="col-auto">
            <h5><button type="button" class="btn btn-icon bg-white me-2" style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('project_table') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>Credentials</h5>
        </div>
        <div class="col-auto">
         <!-- <a href="{{ route('upload_credentials', $project->id ?? null) }}">
    <button class="btn btn-primary">Upload Credentials</button>
</a> -->
<a href="{{ $project ? route('upload_credentials', $project->id) : route('upload_credentials') }}">
    <button class="btn btn-primary">Upload Credentials</button>
</a>
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
                                    <th>Sno</th>
                                    <th>Date</th>
                                    <th>Platform</th>
                                    <th>User ID / Email</th>
                                    <th>Password</th>
                                    <th>Document</th>
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
   <div class="modal fade" id="delete" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 text-center p-4 py-5">
            <h5 class="fw-bold mb-2">Are you sure!!</h5>
            <p class="text-muted">Are you confirm to delete?</p>
            <form id="deleteForm" method="POST" action="{{ route('delete_credentials') }}">
                @csrf
                <input type="hidden" name="id" id="deleteId">
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger p-3 ms-2 fw-semibold" id="finalSubmit">
    Yes, Sure
</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <script>
$(document).ready(function () {
    let table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('credentials.data') }}",
        pageLength: 10,
        lengthChange: false,
        ordering: false,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'date', name: 'date' },
            { data: 'platform', name: 'platform' },
            { data: 'user_id', name: 'user_id' },
            {
                data: 'password',
                orderable: false,
                searchable: false
            },
            {
                data: 'document',
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
    // 🔍 Search
    $('#toDate').on('keyup', function () {
        table.search(this.value).draw();
    });
    // 📄 Page Length
    $('#pageLength').on('change', function () {
        table.page.len(this.value).draw();
    });
    // 👁️ PASSWORD TOGGLE
    $('#usersTable').on('click', '.eye-toggle', function () {
        let wrapper = $(this).closest('.password-wrap');
        let text = wrapper.find('.password-text');
        let icon = $(this).find('i');
        if (text.attr('data-visible') == '0') {
            text.text(text.data('password'));
            text.attr('data-visible', '1');
            icon.removeClass('tabler-eye')
                .addClass('tabler-eye-off');
        } else {
            text.text('••••••••');
            text.attr('data-visible', '0');
            icon.removeClass('tabler-eye-off')
                .addClass('tabler-eye');
        }
    });
     // =========================
    // ✅ DELETE FUNCTION
    // =========================
    $(document).on('click', '.deleteBtn', function () {
    let id = $(this).data('id');
    $('#deleteId').val(id);

    console.log('Delete ID:', id); // debug
});
   
});
</script>
@endsection