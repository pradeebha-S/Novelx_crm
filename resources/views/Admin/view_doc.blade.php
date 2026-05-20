@extends('Admin.layout')

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
                </button>Documents</h5>
        </div>
        <div class="col-auto">
                <a href="{{ route('create_doc', $project->id ?? null) }}">
    <button class="btn btn-primary">Create Document</button>
</a>
    </a>
    <a href="{{ route('add_document', $project->id ?? null) }}">
        <button class="btn btn-primary">Upload Document</button>
    </a>
        </div>
    </div>
    <div class="card p-0 m-2">
        <div class="row mb-3 align-items-center p-3 g-2">

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
                                    <th>Project Name</th>
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>Document</th>
                                    <th>Is Mailed</th>
                                    <th>Send Mail</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
       <!-- DELETE MODAL -->
<div class="modal fade" id="delete" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content p-4 text-center">

            <h5>Are you sure?</h5>

            <form method="POST" action="{{ route('delete_document') }}">
                @csrf

                <input type="hidden" name="id" id="deleteId">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="submit" class="btn btn-danger" id="finalSubmit">
                    Yes Delete
                </button>
            </form>

        </div>
    </div>
</div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {

    const projectId = @json($project_id);

    // ✅ IMPORTANT FIX
    let ajaxUrl = projectId
        ? "{{ route('project.documents', ':id') }}".replace(':id', projectId)
        : "{{ route('project.documents') }}"; // ← THIS WAS YOUR BUG

    $('#usersTable').DataTable({
        ajax: {
            url: ajaxUrl,
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: 'created_at',
                render: data => new Date(data).toLocaleDateString()
            },
            {
                data:null
            },
            { data: 'document_name' },

             {
                data:null
            }, {
                data: 'file',
                render: file => file
                    ? `<a href="/uploads/documents/${file}" target="_blank">View</a>`
                    : '-'
            },
             {
                data:null
            },
            {
    data: null,
    render: function(data, type, row) {
        return `
            <a href="{{ route('sent_mail') }}" class="btn btn-sm btn-primary">
                Send Email
            </a>
        `;
    }
},
            {
                data: null,
                render: row => `
                    <i class="ti tabler-trash text-danger deleteBtn"
                       data-id="${row.id}"
                       data-bs-toggle="modal"
                       data-bs-target="#delete"
                       style="cursor:pointer;font-size:22px"></i>`
            }
        ]
    });

    // Delete button
    $(document).on('click', '.deleteBtn', function () {
        let id = $(this).data('id');
        $('#deleteId').val(id);
    });

});
</script>
@endsection