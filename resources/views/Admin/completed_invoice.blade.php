@extends('Admin.layout')
<style>
    .dt-search,
    .dt-length {
        display: none !important;
    }

    .table-responsive::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari */
    }

    .upload-box {
        border: 2px dashed #0d6efd;
        border-radius: 10px;
        cursor: pointer;
        background: #f8f9fa;
        transition: 0.3s;
    }

    .upload-box:hover {
        background: #e9f2ff;
    }

    .upload-box.dragover {
        background: #d0e7ff;
        border-color: #084298;
    }
</style>
@section('content')
<div class="row align-items-center justify-content-between mt-3 mb-3 m-2">
    <div class="col-auto">
        <h5 class="page-title"><button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('bill_table') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M15 6l-6 6l6 6"></path>
                    </svg>
                </a>
            </button> Completed Invoice</h5>
    </div>
    <div class="col-auto">
        <a href="{{ route('bill_form') }}"> <button class="btn btn-primary">Create Invoice</button></a>
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
                    <th>Project Details</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Status</th>
                    <th>View Bill</th>
                    <th>View Invoice</th>
                    <th>Update Payment</th>
                    <th>View Payment History</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<!-- Bootstrap Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Header -->
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">Upload Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <!-- Form -->
            <form action="{{ route('payment_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="invoice_id" id="invoice_id">
                <input type="hidden" id="invoice_total">
                <div class="modal-body p-4">
                    <div class="mb-2">
                        <label>Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control">
                    </div>
                    <input type="hidden" id="paid_amount">
                    <div class="mb-2">
                        <label>Balance</label>
                        <input type="text" id="balance" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Receipt</label>
                        <div id="uploadBox" class="upload-box text-center p-4">
                            <p class="mb-1">Drag & Drop / Paste / Click to Upload</p>
                            <small class="text-muted">PNG, JPG only</small>
                            <input type="file" name="receipt" id="fileInput" hidden required>
                            <img id="preview" class="img-fluid mt-2 d-none" style="max-height:150px;">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        let table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('completed_invoice_data') }}",
            },
            pageLength: 10,
            ordering: false,
            searching: true,
            lengthChange: false,
            info: true,
            columnDefs: [{
                targets: '_all',
                className: 'text-nowrap align-middle'
            }],
          columns: [
    {
        data: 'DT_RowIndex',
        name: 'id',
        orderable: false,
        searchable: false
    },
    {
        data: 'invoice_date',
        name: 'invoice_date'
    },
    {
        data: 'invoice_no',
        name: 'invoice_no'
    },
    {
        data: null,
        orderable: false,
        searchable: false,
        render: function(data, type, row) {
            return `
                <div>
                    <div class="mb-1">
                        <strong>Project Name:</strong> ${row.project ?? '-'}
                    </div>
                    <div>
                        <strong>Client Mobile:</strong> ${row.mobile ?? '-'}
                    </div>
                </div>
            `;
        }
    },
    {
        data: 'total',
        name: 'total'
    },
    {
        data: 'paid_amount',
        name: 'paid_amount'
    },
    {
        data: 'status',
        name: 'status'
    },
    {
        data: 'view_bill',
        orderable: false,
        searchable: false
    },
    {
        data: 'view_invoice',
        orderable: false,
        searchable: false
    },
    {
        data: 'update_payment',
        orderable: false,
        searchable: false
    },
    {
        data: 'payment_history',
        orderable: false,
        searchable: false
    }
]
        });
        $('#customSearch').keyup(function() {
            table.search($(this).val()).draw();
        });
        $('#pageLength').change(function() {
            let value = $(this).val() === 'All' ? -1 : $(this).val();
            table.page.len(value).draw();
        });
    });
</script>
{{-- paymentmodel --}}
<script>
    // OPEN MODAL
    function openPaymentModal(id, total, paid) {
        $('#paymentModal').modal('show');
        $('#invoice_id').val(id);
        let balance = total - paid;
        $('#invoice_total').val(total);
        $('#paid_amount').val(paid);
        $('#amount').val('');
        $('#balance').val(balance);
    }
    document.getElementById('amount').addEventListener('input', function() {
        let total = parseFloat(document.getElementById('invoice_total').value) || 0;
        let paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        let amount = parseFloat(this.value) || 0;
        let balance = total - paid - amount;
        if (balance < 0) balance = 0;
        document.getElementById('balance').value = balance;
    });
</script>
<script>
    const uploadBox = document.getElementById('uploadBox');
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('preview');
    // Click → open file
    uploadBox.addEventListener('click', () => fileInput.click());
    // File select
    fileInput.addEventListener('change', function() {
        handleFile(this.files[0]);
    });
    // Drag over
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.classList.add('dragover');
    });
    // Drag leave
    uploadBox.addEventListener('dragleave', () => {
        uploadBox.classList.remove('dragover');
    });
    // Drop
    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files;
        handleFile(file);
    });
    // Paste (Ctrl + V)
    document.addEventListener('paste', function(e) {
        const items = e.clipboardData.items;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                const file = items[i].getAsFile();
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                handleFile(file);
            }
        }
    });
    // Preview
    function handleFile(file) {
        if (!file) return;
        if (!file.type.startsWith('image/')) {
            alert('Only images allowed!');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
</script>
<script>
    function downloadInvoice(id) {
        let url = "/admin/invoice/" + id + "?print=1";
        let win = window.open(url, "_blank");
        win.onload = function() {
            win.print();
        };
    }
</script>
@endsection