@extends('Staff.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <div class="d-flex align-items-center">
        <h5 class="mb-0">
            <!-- <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('admin.reminder') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button> -->
            Personal Request
        </h5>
    </div>
</div>

<div class="card p-4 mt-4">
    <h6>Personal Request</h6>

    <form action="{{ route('add_request') }}" method="post" id="login_form">
        @csrf

        <div class="row">
            <div class="col-lg-6 mb-2">
                <label class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                    placeholder="Personal Request Title" name="title" value="{{ old('title') }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-lg-6 mb-2">
                <label class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" rows="3"
                    name="description">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </form>

    <div class="d-flex form-actions mt-3">
        <button type="button" class="btn btn-primary me-3" id="finalSubmit">
            Send Request
        </button>
        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
    </div>
</div>

<div class="card p-4 mt-4">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table">

                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th class="text-nowrap">DATE</th>
                                <th class="text-nowrap">TITLE</th>
                                <th class="text-nowrap">DESCRIPTION</th>
                                <th>REMARK</th>
                                <th class="text-nowrap">IS REPLIED</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- Modal --}}
<!-- <div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 px-4 py-5 text-center">
            <h5 class="fw-bold mb-2">Are you sure?</h5>
            <p class="text-muted mb-4">Do you confirm to submit this reminder?</p>

            <div class="d-flex justify-content-center gap-3 mt-3">

                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                    Yes, Sure
                </button>

            </div>
        </div>
    </div>
</div> -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script>
    $(function() {
        $('#dept').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('personal_request_data') }}",

            order: [
                [1, 'desc']
            ],
            language: {
                search: "",
                searchPlaceholder: "Search",
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
                    data: 'date',
                    name: 'created_at'
                },
                {
                    data: 'title'
                },
                {
                    data: 'description'
                },
                {
                    data: 'remark'
                },
                {
                    data: 'is_replied',
                    orderable: false,
                    searchable: false
                },


            ]
        });

        document.getElementById('finalSubmit').addEventListener('click', function(e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('login_form').submit();
        });
    });
</script>

@endsection