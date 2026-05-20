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
        <h5>Reminder</h5>
    </div>
    <!-- Button -->
    <div class="col-auto">
        <a href="{{ route('staff_create_reminder') }}"> <button class="btn buttons-collection btn-primary" type="button"
                aria-haspopup="dialog" aria-expanded="false">
                <span class="d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-plus icon-xs me-sm-1"></i>
                    <span class="d-sm-inline-block">Create Reminder</span>
                </span>
            </button></a>
    </div>
</div>
<div class="card p-4">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th>TITLE</th>
                                <th>REMIND TO</th>
                                <th>DESCRIPTION</th>
                                <th>TYPE</th>
                                <th>DATE</th>
                                <th>ADDED BY</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
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
            <form id="deleteForm" method="POST" action="{{ route('staff_delete_reminder') }}">
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
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <h5 class="fw-bold mb-2">Mark as Completed?</h5>
            <form method="POST" action="{{ route('staff_complete_reminder') }}">
                @csrf
                <input type="hidden" name="id" id="completeId">
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        Yes Complete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).on('click', '.deleteBtn', function () {
    let id = $(this).data('id');
    console.log("Delete ID:", id);
    $('#deleteId').val(id);
});
$(document).on('click', '.completeBtn', function () {
    let id = $(this).data('id');
    $('#completeId').val(id);
});

    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#dept')) {
            $('#dept').DataTable().clear().destroy();
        }
        $('#dept').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('staff_reminder_data') }}",
            language: {
                search: "",
                searchPlaceholder: "Search Reminder",
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
                    data: 'title'
                },
                {
                    data: 'remind_to'
                },
                {
                    data: 'description'
                },
                {
                    data: 'reminder_type'
                },
                {
                    data: 'date'
                },
                 {
                    data: 'added_by'
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