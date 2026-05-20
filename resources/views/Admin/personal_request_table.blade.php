@extends('Admin.layout')
@section('content')
<div class="row align-items-center justify-content-between mb-3">
    <!-- Heading -->
    <div class="col-auto">
        <h5>Personal Request</h5>
    </div>

    <!-- Button -->
    <div class="col-auto">
        <a href="{{ route('personal_request_history_table') }}"> <button class="btn buttons-collection btn-primary" type="button" aria-haspopup="dialog"
                aria-expanded="false">
                <span class="d-flex align-items-center gap-2">
                    <span class="d-sm-inline-block">Reply Histories</span>
                </span>
            </button></a>
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
                                <th>REPLY</th>

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


<div class="modal fade" id="form" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 py-5">

            <p class="text-muted text-center">Reply...?</p>

            <form action="{{ route('remark_personal') }}" method="post" id="replyForm">
                @csrf
                <input type="hidden" id="reply_id" name="id">

                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Reply</label>
                        <textarea class="form-control" rows="3" name="remark"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary p-3 fw-semibold" id="finalSubmit">
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
    var jq = jQuery.noConflict();

    jq(document).ready(function() {
        jq('#dept').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('personal_request_table_data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'title'
                },
                {
                    data: 'description'
                },
                {
                    data: 'reply',
                    orderable: false,
                    searchable: false
                }
            ],
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
            ]
       
        });
    });

    jq(document).on('click', '.open-reply', function() {
        jq('#reply_id').val(jq(this).data('id'));
    });


    document.getElementById('finalSubmit').addEventListener('click', function(e) {
        e.preventDefault();
        let btn = this;
        btn.disabled = true;
        btn.innerText = 'Processing...';
        document.getElementById('replyForm').submit();
    });
</script>

@endsection