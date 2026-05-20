@extends('Admin.layout')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('content')

    <div class="row align-items-center mb-3">
        <div class="col">
            <h5 class="mb-0 d-flex align-items-center">
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
                Chapter
            </h5>
        </div>

        <div class="col-auto d-flex gap-2">
            <a href="{{ route('course') }}"> <button class="btn btn-primary text-nowrap">Create Course</button>
            </a> <a href="{{ route('topic', $course->id) }}"><button class="btn btn-primary text-nowrap">Add New
                    Topic</button></a>
        </div>
    </div>
    <div class="card p-3 mb-3">

        <form action="{{ route('submit_chapter') }}" method="POST" id="chapterForm">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="row">
                <div class="col-lg-6 mb-3 mt-2">
                    <label class="form-label">
                        Select Topic <span class="text-danger">*</span>
                    </label>

                    <select id="topic_id" class="form-select" name="topic_id">
                        <option value="">Select Topic</option>

                        @foreach ($topics as $item)
                            <option value="{{ $item->id }}" {{ old('topic_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->topic_name }}
                            </option>
                        @endforeach

                    </select>

                    @error('topic_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-6 mb-3 mt-2">

                    <label class="form-label">Chapter <span class="text-danger">*</span></label>
                    <input type="text" name="chapter_name" class="form-control" value="{{ old('chapter_name') }}"
                        placeholder="Enter Chapter ">
                    @error('chapter_name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-12 mb-2">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4"
                        placeholder="Chapter Description">{{ old('description') }}</textarea>
                </div>
                <div class="form-actions d-flex justify-content-center mt-2 mb-2">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>


    <div class="card p-0">





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
                                    <th class="text-nowrap">Assign To</th>
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
                <form id="deleteForm" method="POST" action="{{ route('delete_chapter') }}">
                    @csrf
                    <input type="hidden" name="id" id="deleteId">
                    <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                        <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" id='submitDeleteBtn' class="btn btn-danger p-3 ms-2 fw-semibold"
                            id="finalSubmit">
                            Yes, Sure
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="task" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content custom-modal p-3">

                <h6 class="fw-semibold text-center mb-3">Assign Task To</h6>

                <form method="POST" action="{{ route('assign_chapter') }}">
                    @csrf
                    <input type="hidden" name="course_id" id="course_id">
                    <input type="hidden" name="topic_id" id="id">
                    <input type="hidden" name="chapter_id" id="chapter_id">

                    <div class="mb-3">
                        <label class="form-label small text-muted">Assign To</label>

                        <select name="student_id" class="form-select form-select-sm" required>
                            <option value="">Select Student</option>

                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('student_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            Assign
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
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('chapter_data', $course->id) }}",
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
                    name: 'assign_to'
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
        document.getElementById('chapterForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerText = 'Submitting...';
        });

        function setDeleteId(button) {
            var staffId = button.getAttribute('data-id');
            document.getElementById('deleteId').value = staffId;
        }

        document.getElementById('submitDeleteBtn').addEventListener('click', function (e) {
            e.preventDefault();
            this.disabled = true;
            this.innerText = 'Deleting...';
            document.getElementById('deleteForm').submit();
        });
    </script>
    <script>
        var taskModal = document.getElementById('task');
        taskModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var courseId = button.getAttribute('data-course-id');
            var topicId = button.getAttribute('data-topic-id');
            var chapterId = button.getAttribute('data-chapter-id');

            document.getElementById('course_id').value = courseId;
            document.getElementById('id').value = topicId;
            document.getElementById('chapter_id').value = chapterId;
        });
    </script>



@endsection