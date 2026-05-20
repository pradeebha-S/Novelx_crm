@extends('Admin.layout')
@section('content')
    <style>
        .is-invalid {
            border: 1px solid #dc3545 !important;
        }
        .invalid-feedback {
            display: block;
        }
        .page-title {
            font-weight: 700;
            color: var(--bs-body-color);
            letter-spacing: .4px;
        }
        .invoice-card {
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 20px;
            background: var(--bs-body-bg);
            box-shadow: 0 14px 34px rgba(0, 0, 0, .08);
            overflow: hidden;
            transition: .3s ease;
        }
        .invoice-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 38px rgba(0, 0, 0, .12);
        }
        .card-head {
            background: linear-gradient(135deg, #0d6efd, #4f8cff);
            color: #fff;
            padding: 18px 22px;
            font-size: 18px;
            font-weight: 700;
        }
        .card-body-custom {
            padding: 28px;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--bs-body-color);
            margin: 12px 0 18px;
            padding-bottom: 8px;
            border-bottom: 1px dashed rgba(128, 128, 128, .25);
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--bs-body-color);
        }
        .form-control,
        .form-select {
            border-radius: 12px;
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
            border: 1px solid rgba(128, 128, 128, .25);
        }
        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 .18rem rgba(13, 110, 253, .15);
            border-color: #0d6efd;
        }
        .submit-btn {
            border-radius: 12px;
            padding: 11px 34px;
            font-weight: 600;
            min-width: 180px;
            transition: .3s ease;
        }
        .submit-btn:hover {
            transform: scale(1.03);
        }
        .summary-box {
            background: rgba(13, 110, 253, .06);
            border: 1px dashed rgba(13, 110, 253, .18);
            padding: 18px;
            border-radius: 16px;
            margin-top: 10px;
        }
        .summary-box h6 {
            font-weight: 700;
            margin-bottom: 12px;
        }
        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
    </style>
    <div class="container-fluid">
        <h4 class="page-title mb-4"><button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('bill_table') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M15 6l-6 6l6 6"></path>
                    </svg>
                </a>
            </button> Create Invoice</h4>
        <div class="invoice-card">
            <div class="card-head">
                <i class="ti tabler-file-invoice me-2"></i> Create Invoice
            </div>
            <div class="card-body-custom">
                <form action="{{ route('create_invoice') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="section-title">Invoice Details</div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" name="invoice_no" class="form-control" value="{{ $nextNumber }}"
                                readonly>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">Choose Project</label>
                            <select name="project_id" id="project_id"
                                class="form-select @error('project_id') is-invalid @enderror">
                                <option value="">Select</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="section-title">Client Details</div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">Client Name</label>
                            <input type="text" name="client_name" id="client_name"
                                class="form-control @error('client_name') is-invalid @enderror"
                                value="{{ old('client_name') }}">
                            @error('client_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" id="mobile" name="mobile"
                                class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">Email</label>
                            <input type="text" id="address" name="address"
                                class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="section-title">Pricing Details</div>
                        <div>
                          <div id="dynamicRows">
@php
    $oldModules = old('module', ['']);
    $oldDescriptions = old('description', ['']);
    $oldTypes = old('type', ['']);
    $oldRates = old('rate', ['']);
@endphp
@foreach ($oldModules as $index => $module)
<div class="row align-items-end item-row mb-2">
    <div class="col-lg-3 mb-3">
        <label class="form-label">Module</label>
        <input type="text" name="module[]" 
    class="form-control @error('module.' . $index) is-invalid @enderror"
    value="{{ $oldModules[$index] }}">
@error('module.' . $index)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
    </div>
    <div class="col-lg-3 mb-3">
        <label class="form-label">Description</label>
        <textarea name="description[]" 
    class="form-control @error('description.' . $index) is-invalid @enderror"
    rows="2">{{ $oldDescriptions[$index] }}</textarea>
@error('description.' . $index)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
    </div>
    <div class="col-lg-2 mb-3">
        <label class="form-label">Type</label>
       <select name="type[]" 
    class="form-select @error('type.' . $index) is-invalid @enderror">
            <option value="">Select</option>
            <option value="Update" {{ $oldTypes[$index] == 'Update' ? 'selected' : '' }}>Update</option>
            <option value="Corrections" {{ $oldTypes[$index] == 'Corrections' ? 'selected' : '' }}>Corrections</option>
            <option value="New" {{ $oldTypes[$index] == 'New' ? 'selected' : '' }}>New</option>
        </select>
        @error('type.' . $index)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
    </div>
    <div class="col-lg-2 mb-3">
        <label class="form-label">Rate</label>
        <input type="number" name="rate[]" 
    class="form-control @error('rate.' . $index) is-invalid @enderror"
    value="{{ $oldRates[$index] }}">
@error('rate.' . $index)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
    </div>
    <div class="col-lg-2 mb-3">
        <button type="button" class="btn btn-danger w-100 removeRow">✕ Remove</button>
    </div>
</div>
@endforeach
</div>
<!-- ✅ Add More Button (outside rows) -->
<div class="text-end mt-2">
    <button type="button" class="btn btn-primary addRow">+ Add More</button>
</div>
                        </div>
                        <div class="col-lg-2 mb-4">
                            <label class="form-label">Choose Bank</label>
                            <select name="bank_id" class="form-select @error('bank_id') is-invalid @enderror">
                                <option value="">Select</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}"
                                        {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-2 mb-4">
                            <label class="form-label">Tax (%)</label>
                            <input type="number" name="tax" class="form-control @error('tax') is-invalid @enderror"
                                placeholder="Enter Tax" value="{{ old('tax') }}">
                            @error('tax')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-2 mb-4">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" name="discount"
                                class="form-control @error('discount') is-invalid @enderror"
                                placeholder="Enter Discount %" value="{{ old('discount') }}">
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label">Back Logs</label>
                            <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" placeholder="Enter">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 mb-4">
                            <div class="summary-box">
                                <h6><i class="ti tabler-calculator me-1"></i> Invoice Summary</h6>
                                <div class="summary-line">
                                    <span>Subtotal</span>
                                    <span id="subtotal">₹0.00</span>
                                </div>
                                <div class="summary-line">
                                    <span>Tax</span>
                                    <span id="taxAmount">₹0.00</span>
                                </div>
                                <div class="summary-line">
                                    <span>Discount</span>
                                    <span id="discountAmount">₹0.00</span>
                                </div>
                                <div class="summary-line fw-bold">
                                    <span>Total</span>
                                    <span id="totalAmount">₹0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-primary submit-btn">
                                <i class="ti tabler-device-floppy me-1"></i> Create Invoice
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".addRow").addEventListener("click", function () {
        let newRow = `
        <div class="row align-items-end item-row mb-2">
            <div class="col-lg-3 mb-3">
                <input type="text" name="module[]" class="form-control" placeholder="Enter Module">
            </div>
            <div class="col-lg-3 mb-3">
                <textarea name="description[]" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-lg-2 mb-3">
                <select name="type[]" class="form-select">
                    <option value="">Select</option>
                    <option value="Update">Update</option>
                    <option value="Corrections">Corrections</option>
                    <option value="New">New</option>
                </select>
            </div>
            <div class="col-lg-2 mb-3">
                <input type="number" name="rate[]" class="form-control">
            </div>
            <div class="col-lg-2 mb-3">
                <button type="button" class="btn btn-danger w-100 removeRow">✕ Remove</button>
            </div>
        </div>`;
        document.getElementById("dynamicRows")
            .insertAdjacentHTML("beforeend", newRow);
    });
    // REMOVE ROW
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("removeRow")) {
            let totalRows = document.querySelectorAll(".item-row").length;
            // prevent deleting last row
            if (totalRows > 1) {
                e.target.closest(".item-row").remove();
            }
        }
    });
});
</script>
    <script>
        const getProjectUrl = "{{ url('/admin/get-project') }}";
        document.getElementById('project_id').addEventListener('change', function() {
            let projectId = this.value;
            if (projectId) {
                fetch(getProjectUrl + '/' + projectId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error ' + response.status);
                        }
                        return response.text(); // first text ah eduthu
                    })
                    .then(text => {
                        try {
                            let data = JSON.parse(text);
                            document.getElementById('client_name').value = data.client_name ?? '';
                            document.getElementById('mobile').value = data.mobile ?? '';
                            document.getElementById('address').value = data.email ?? '';
                        } catch (e) {
                            console.error('Not JSON:', text); // 🔥 show actual error page
                        }
                    })
                    .catch(error => console.error('Fetch error:', error));
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function calculateSummary() {
                let rates = document.querySelectorAll('input[name="rate[]"]');
                let taxInput = document.querySelector('input[name="tax"]');
                let discountInput = document.querySelector('input[name="discount"]');
                let subtotal = 0;
                rates.forEach(function(input) {
                    let value = parseFloat(input.value) || 0;
                    subtotal += value;
                });
                let taxPercent = parseFloat(taxInput?.value) || 0;
                let discountPercent = parseFloat(discountInput?.value) || 0;
                let taxAmount = (subtotal * taxPercent) / 100;
                let discountAmount = (subtotal * discountPercent) / 100;
                let total = subtotal + taxAmount - discountAmount;
                document.getElementById('subtotal').innerText = '₹' + subtotal.toFixed(2);
                document.getElementById('taxAmount').innerText = '₹' + taxAmount.toFixed(2);
                document.getElementById('discountAmount').innerText = '₹' + discountAmount.toFixed(2);
                document.getElementById('totalAmount').innerText = '₹' + total.toFixed(2);
            }
            document.addEventListener('input', function(e) {
                if (
                    e.target.name === 'rate[]' ||
                    e.target.name === 'tax' ||
                    e.target.name === 'discount'
                ) {
                    calculateSummary();
                }
            });
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("addRow") || e.target.classList.contains("removeRow")) {
                    setTimeout(calculateSummary, 200);
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const submitBtn = document.querySelector(".submit-btn");
            form.addEventListener("submit", function(e) {
                // Button disable
                submitBtn.disabled = true;
                // Text change to Processing
                submitBtn.innerHTML = `
            <i class="ti tabler-loader me-1"></i> Processing...
        `;
            });
        });
    </script>
@endsection
