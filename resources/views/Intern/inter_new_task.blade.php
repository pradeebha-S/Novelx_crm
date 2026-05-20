@extends('Intern.layout')

@section('content')

<div class="row align-items-center justify-content-between mb-3 mt-3 m-2">
    <div class="col-auto">
        <h5>
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('intern_task') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
            New Tasks
        </h5>
    </div>

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
                                <th>Status</th>
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
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 px-4 py-5 text-center">
            <h5 class="fw-bold mb-3">Update Task Status</h5>

            <form method="POST" action="{{ route('update_student_task_status') }}">
                @csrf

                <input type="hidden" name="task_id" id="statusTaskId">
                <input type="hidden" name="student_id" id="statusStudentId">


                <div class="mb-3 text-start">
                    <label>Status</label>
                    <select name="status" class="form-select" required>
                        <option value="">Select</option>
                        <option value="complete">Complete</option>
                        <option value="hold">Hold</option>
                    </select>
                </div>

                <div class="mb-3 text-start">
                    <label>Spending Hours</label>
                    <input type="text" name="spend_hour" class="form-control" required>
                </div>

                <div class="mb-3 text-start">
                    <label>Remark</label>
                    <textarea name="remark" class="form-control" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Update
                </button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script>
    var jq = jQuery.noConflict();

    jq(document).ready(function() {

        var table = jq('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('intern_new_task_data') }}"
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'chapter',
                    name: 'chapter'
                },
                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, 'desc']
            ],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ["10", "25", "50", "100", "All"]
            ],
            language: {
                search: "",
                searchPlaceholder: "Search"
            }
        });

    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('updateStatusModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const taskId = button.getAttribute('data-task-id');
        const studentId = button.getAttribute('data-student-id');

        document.getElementById('statusTaskId').value = taskId;
        document.getElementById('statusStudentId').value = studentId;
    });

});
</script>

@endsection