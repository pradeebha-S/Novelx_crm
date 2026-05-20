@extends('Admin.layout')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('content')

<div class="row align-items-center justify-content-between mb-3 mt-3 m-2">
    <div class="col-auto">
        <h5>
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('course') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
            Topics
        </h5>
    </div>

</div>
<form action="{{ route('submit_topic')  }}" method="POST" id="topicForm">
    @csrf
    <input type="hidden" name="course_id" value="{{$course->id}}">

    <div class="card p-3 mb-3">
        <div class="row">
            <div class="col-lg-10 mb-2">
                <label class="form-label">Topic <span class="text-danger">*</span></label>
                <input type="text"
                    name="topic_name"
                    class="form-control"
                    value="{{ old('topic_name') }}"
                    placeholder="Enter Topic">
                @error('topic_name') <div class="text-danger">{{ $message }}</div> @enderror
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
                                <th>Topics</th>
                                <!-- <th>Chapter</th> -->

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
            <form id="deleteForm" method="POST" action="{{ route('delete_topic') }}">
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
        ajax: "{{ route('topic_data', $course->id) }}",
        order: [[1, 'desc']],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
            { data: 'topic_name', name: 'topic_name' },
            // { data: 'chapters', name: 'chapters', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
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
    document.getElementById('topicForm').addEventListener('submit', function() {
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