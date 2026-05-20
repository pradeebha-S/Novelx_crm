@extends('Admin.layout')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('content')
    <h5>Tasks</h5>
    <div class="card p-0 mt-3">
        <div class="table-responsive pt-0 pb-2">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 p-0">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table class="table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Chapter</th>
                                    <th>Description</th>
                                    <th class="text-nowrap">Assigned To</th>
                                    <th class="text-nowrap">Status</th>
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
            <form id="deleteForm" method="POST" action="{{ route('delete_student_task') }}">
                @csrf
                <input type="hidden" name="id" id="deleteId">
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" id='submitDeleteBtn' class="btn btn-danger p-3 ms-2 fw-semibold" id="finalSubmit">
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
          $(document).ready(function() {
        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('student_tasks_data') }}",
            order: [
                [1, 'asc']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'chapter_name',
                    name: 'chapter_name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'assign_to',
                    name: 'assign_to',
                      orderable: false,
                searchable: false
                },
                 {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
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
<script>
     function setDeleteId(button) {
        var staffId = button.getAttribute('data-id');
        document.getElementById('deleteId').value = staffId;
    }

    document.getElementById('submitDeleteBtn').addEventListener('click', function(e) {
        e.preventDefault();
        this.disabled = true;
        this.innerText = 'Deleting...';
        document.getElementById('deleteForm').submit();
    });
</script>
@endsection