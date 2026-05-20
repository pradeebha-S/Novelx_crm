@extends('Admin.layout')

<style>

    .dt-search,

    .dt-length {

        display: none !important;

    }



    .table-responsive {

        overflow-x: auto;

        scrollbar-width: none;

        /* Firefox */

    }



    .table-responsive::-webkit-scrollbar {

        display: none;

        /* Chrome, Safari */

    }

</style>



@section('content')

    <div class="row align-items-center justify-content-between mt-3 mb-3 m-2">

        <div class="col-auto">

            <h5 class="page-title"><button type="button" class="btn btn-icon bg-white waves-effect me-2" style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                    <a href="{{ route('bill_table') }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>

                            <path d="M15 6l-6 6l6 6"></path>

                        </svg>

                    </a>

                </button> Payment Report</h5>

        </div>

    </div>

    <div class="card m-2">

        <div class="filter-section p-3">

            <div class="row g-3 align-items-center justify-content-between">



                <!-- Left (DT Length) -->

                <div class="col-12 col-lg-auto">

                    <select id="pageLength" class="form-select w-100">

                        <option value="10">10</option>

                        <option value="25">25</option>

                        <option value="50">50</option>

                        <option value="100">100</option>

                        <option value="All">All</option>

                    </select>

                </div>



                <!-- Right (Search + Export) -->

                <div class="col-12 col-lg-4 ms-lg-auto">

                    <div class="d-flex flex-column flex-lg-row justify-content-lg-end gap-2">



                        <input type="text" id="customSearch" class="form-control" placeholder="Search table...">



                        <button class="btn btn-label-secondary">

                            <i class="ti tabler-download me-1"></i> Export

                        </button>



                    </div>

                </div>



            </div>

        </div>

        <div class="table-responsive">

            <table class="table" id="usersTable">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Date</th>

                        <th>Invoice Number</th>

                        <th>Amount</th>

                        <th>Receipt</th>

                        <th>Status</th>



                    </tr>

                </thead>

                <tbody></tbody>

            </table>

        </div>

    </div>

<!-- View Receipt Modal -->

<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content border-0 shadow-lg rounded-4">



            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="ti tabler-receipt me-2 text-primary"></i>View Receipt

                </h5>



                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

            </div>



            <div class="modal-body text-center p-4">



                <img id="receiptPreview"

                     src=""

                     class="img-fluid rounded-3 border"

                     style="max-height:500px; object-fit:contain; width:100%;">



            </div>



            <div class="modal-footer">

               
<a href="" id="downloadReceipt" download class="btn btn-primary">
    <i class="ti tabler-download me-1"></i> Download
</a>

                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">

                    Close

                </button>

            </div>



        </div>

    </div>

</div>





    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>



<script>
$(document).ready(function () {

    let id = "{{ $id }}";

    let table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/payment-report-data/" + id,
        pageLength: 10,
        ordering: false,
        searching: true,
        lengthChange: false,

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'date' },
            { data: 'invoice_no' },
            { data: 'amount' },
            { data: 'receipt', orderable: false, searchable: false },
            { data: 'status' }
        ]
    });

    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

});
</script>

<script>

$(document).on('click', '.viewReceiptBtn', function () {

    let img = $(this).data('img');
    let fileName = img.split('/').pop();

    $('#receiptPreview').attr('src', img);

    $('#downloadReceipt')
        .attr('href', img)
        .attr('download', fileName);

    let modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    modal.show();
});

$('#receiptModal').on('hidden.bs.modal', function () {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('padding-right', '');
});

</script>

@endsection