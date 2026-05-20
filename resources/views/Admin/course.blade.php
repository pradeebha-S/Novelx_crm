@extends('Admin.layout')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('content')

<div class="row align-items-center justify-content-between mb-3 mt-3 m-2">
    <div class="col-auto">
        <h5>Course</h5>
    </div>

</div>
<form action="{{ route('submit_course') }}" method="POST" id="courseForm">
    @csrf

    <div class="card p-3 mb-3">
        <div class="row">
            <div class="col-lg-10 mb-2">
                <label class="form-label">Course Title <span class="text-danger">*</span></label>

                <input type="text"
                    name="course_name"
                    class="form-control"
                    value="{{ old('course_name') }}"
                    placeholder="Enter Course Title">
                @error('course_name') <div class="text-danger">{{ $message }}</div> @enderror

            </div>
            <div class="col-lg-2 mb-2 mt-auto">
                <button type="submit" id="submitBtn" class="btn btn-primary w-100">Submit</button>
            </div>
        </div>
    </div>
</form>

<div class="card p-0">


    <div class="table-responsive pt-0 pb-2">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 p-0">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th>Sno</th>
                                <th>Course</th>
                                <th>Topic</th>
                                <th class="text-nowrap">Total Topic</th>
                                <th>Chapter</th>
                                <th class="text-nowrap">Total Chapter</th>
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
            <form id="deleteForm" method="POST" action="{{ route('delete_course') }}">
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
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>


<script>
    $(document).ready(function() {
        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('course_data') }}",
            order: [
                [1, 'desc']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'course_name',
                    name: 'course_name'
                },
                {
                    data: 'topics',
                    name: 'topics',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'total_topic',
                    name: 'total_topic',

                },
                {
                    data: 'chapters',
                    name: 'chapters',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'total_chapter',
                    name: 'total_chapter',

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
    document.getElementById('courseForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerText = 'Submitting...';
    });

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