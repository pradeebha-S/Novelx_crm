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
            Hold Tasks
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
<div class="modal fade" id="resumeTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resume Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to resume this task?
            </div>

            <div class="modal-footer">
                <form method="POST" action="{{ route('intern_resume_task') }}">
                    @csrf

                    <input type="hidden" name="task_id" id="resume_task_id">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Yes, Resume
                    </button>
                </form>
            </div>
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
                url: "{{ route('hold_tasks_intern_data') }}"
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
    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('resumeTaskModal');

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const taskId = button.getAttribute('data-id'); // ✅ fixed

            document.getElementById('resume_task_id').value = taskId;

            console.log('Task ID set:', taskId); // debug
        });

    });
</script>



@endsection