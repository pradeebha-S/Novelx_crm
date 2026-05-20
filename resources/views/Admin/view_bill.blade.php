@extends('Admin.layout')
@section('content')
<style>
  .is-invalid {
    border: 2px solid #dc3545 !important;
}
.error-text {
    margin-top: 4px;
}
</style>
<div class="container-fluid">
    <h4 class="mb-4">
        <a href="{{ route('bill_table') }}" class="btn btn-light me-2">←</a>
        Edit Invoice
    </h4>
    <div class="card">
        <div class="card-body">
                <form action="{{ route('invoice_update', $invoice->id) }}" method="POST">
                @csrf
                <div class="row">
                    {{-- ================= INVOICE ================= --}}
                    <h6 class="mb-3">Invoice Details</h6>
                    <div class="col-md-4 mb-3">
                        <label>Invoice Date</label>
                        <input type="date" name="invoice_date" class="form-control"
                            value="{{ old('invoice_date',$invoice->invoice_date) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Invoice Number</label>
                        <input type="text" name="invoice_no" class="form-control"
                            value="{{ old('invoice_no',$invoice->invoice_no) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Project</label>
                        <select name="project_id" id="project_id" class="form-control">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ $project->id == old('project_id',$invoice->project_id) ? 'selected':'' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- ================= CLIENT ================= --}}
                    <h6 class="mb-3 mt-3">Client Details</h6>
                    <div class="col-md-4 mb-3">
                        <label>Client Name</label>
                        <input type="text" id="client_name" name="client_name" class="form-control"
                            value="{{ old('client_name',$invoice->client_name) }}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Mobile</label>
                        <input type="text" id="mobile" name="mobile" class="form-control"
                            value="{{ old('mobile',$invoice->mobile) }}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Email</label>
                        <input type="text" id="email" name="email" class="form-control"
                            value="{{ old('address',$invoice->address ?? '') }}" readonly >
                    </div>
                    {{-- ================= ITEMS ================= --}}
                    <h6 class="mb-3 mt-3">Pricing Details</h6>
                    <div id="dynamicRows">
                        @foreach($invoice->items as $index => $item)
                        <div class="row item-row mb-2">
                            <div class="col-md-3">
                                <label>Module</label>
                                <input type="text" name="module[]" class="form-control"
                                    value="{{ old('module.'.$index,$item->module) }}">
                            </div>
                            <div class="col-md-3">
                                <label>Description</label>
                                {{-- <input type="text" name="description[]" class="form-control"
                                    value="{{ old('description.'.$index,$item->description) }}"> --}}
                                    <textarea name="description[]" class="form-control" rows="2">{{ old('description.'.$index, $item->description) }}</textarea>
                            </div>
                            <div class="col-md-2">
                                <label>Type</label>
                                <select name="type[]" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Update" {{ $item->type=='Update'?'selected':'' }}>Update</option>
                                    <option value="Corrections" {{ $item->type=='Corrections'?'selected':'' }}>Corrections</option>
                                    <option value="New" {{ $item->type=='New'?'selected':'' }}>New</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Rate</label>
                                <input type="number" name="rate[]" class="form-control rate"
                                    value="{{ old('rate.'.$index,$item->rate) }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger removeRow w-100">Remove</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- ADD BUTTON --}}
                    <div class="text-end mb-3">
                        <button type="button" class="btn btn-success addRow">+ Add More</button>
                    </div>
                    {{-- ================= BANK & TAX ================= --}}
                    <div class="col-md-3">
                        <label>Bank</label>
                        <select name="bank_id" class="form-control">
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ $bank->id == old('bank_id',$invoice->bank_id)?'selected':'' }}>
                                    {{ $bank->bank_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Tax</label>
                        <input type="number" id="tax" name="tax" class="form-control"
                            value="{{ old('tax_percentage',$invoice->tax_percentage) }}">
                    </div>
                    <div class="col-md-3">
                        <label>Discount</label>
                        <input type="number" id="discount" name="discount" class="form-control"
                            value="{{ old('discount_percentage',$invoice->discount_percentage) }}">
                    </div>
                    <div class="col-md-3">
                        <label>Summary</label>
                        <div class="border p-2">
                            <div>Subtotal: <span id="subtotal">₹0</span></div>
                            <div>Tax: <span id="taxAmount">₹0</span></div>
                            <div>Discount: <span id="discountAmount">₹0</span></div>
                            <div><b>Total: <span id="total">₹0</span></b></div>
                        </div>
                    </div>
                    {{-- REMARKS --}}
                    <div class="col-12 mt-3">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control">{{ old('remarks',$invoice->remarks) }}</textarea>
                    </div>
                    <div class="text-center mt-4">
                        <button id="submitBtn" class="btn btn-success">Update Invoice</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- ================= JS ================= --}}

<script>
document.addEventListener("DOMContentLoaded", function(){

const getProjectUrl = "{{ url('/admin/get-project') }}";

// PROJECT AUTO FILL
document.getElementById('project_id').addEventListener('change', function() {
    let id = this.value;
    if(!id) return;
    fetch(getProjectUrl + '/' + id)
    .then(res => res.json())
    .then(data => {
        document.getElementById('client_name').value = data.client_name || '';
        document.getElementById('mobile').value = data.mobile || '';
        document.getElementById('email').value = data.email || '';
    });
});

// ADD / REMOVE ROW
document.addEventListener("click", function(e){
    if(e.target.classList.contains("addRow")){
        let html = `
        <div class="row item-row mb-2">
            <div class="col-md-3">
                <label>Module</label>
                <input type="text" name="module[]" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Description</label>
                
                <textarea name="description[]" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-2">
                <label>Type</label>
                <select name="type[]" class="form-control">
                    <option value="">Select</option>
                    <option value="Update">Update</option>
                    <option value="Corrections">Corrections</option>
                    <option value="New">New</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Rate</label>
                <input type="number" name="rate[]" class="form-control rate">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger removeRow w-100">Remove</button>
            </div>
        </div>`;
        document.getElementById('dynamicRows').insertAdjacentHTML('beforeend', html);
        setTimeout(calc, 100); // 🔥 recalc after adding
    }

    if(e.target.classList.contains("removeRow")){
        e.target.closest('.item-row').remove();
        calc();
    }
});

// CALCULATION
function calc(){
    let subtotal = 0;

    document.querySelectorAll('.rate').forEach(i=>{
        subtotal += parseFloat(i.value) || 0;
    });

    let taxPercent = parseFloat(document.getElementById('tax').value) || 0;
    let discountPercent = parseFloat(document.getElementById('discount').value) || 0;

    let taxAmt = (subtotal * taxPercent) / 100;
    let disAmt = (subtotal * discountPercent) / 100; // ✅ FIXED

    let total = subtotal + taxAmt - disAmt;

    document.getElementById('subtotal').innerText = "₹"+subtotal.toFixed(2);
    document.getElementById('taxAmount').innerText = "₹"+taxAmt.toFixed(2);
    document.getElementById('discountAmount').innerText = "₹"+disAmt.toFixed(2);
    document.getElementById('total').innerText = "₹"+total.toFixed(2);
}

// 🔥 IMPORTANT — run after page fully loads
setTimeout(calc, 200);

// RECALCULATE ON INPUT
document.addEventListener("input", function(e){
    if(e.target.classList.contains("rate") || e.target.id=="tax" || e.target.id=="discount"){
        calc();
    }
});

// ===== VALIDATION (unchanged) =====
document.querySelector("form").addEventListener("submit", function(e){
    let isValid = true;

    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.error-text').forEach(el => el.remove());

    document.querySelectorAll('.item-row').forEach((row, index) => {
        let module = row.querySelector('input[name="module[]"]');
        let desc   = row.querySelector('input[name="description[]"]');
        let type   = row.querySelector('select[name="type[]"]');
        let rate   = row.querySelector('input[name="rate[]"]');

        let m = module.value.trim();
        let d = desc.value.trim();
        let t = type.value.trim();
        let r = rate.value.trim();

        if(m === "" && d === "" && t === "" && r === "") return;

        if(m === ""){ showFieldError(module, `The module.${index} field is required.`); isValid = false; }
        if(d === ""){ showFieldError(desc, `The description.${index} field is required.`); isValid = false; }
        if(t === ""){ showFieldError(type, `The type.${index} field is required.`); isValid = false; }
        if(r === ""){ showFieldError(rate, `The rate.${index} field is required.`); isValid = false; }
    });

    if(!isValid){
        e.preventDefault();
        showToast("Please fix the errors before submitting");
        return;
    }

    let btn = document.getElementById("submitBtn");
    btn.innerHTML = "Processing...";
    btn.disabled = true;
});

// ERROR UI
function showFieldError(input, message){
    input.classList.add('is-invalid');
    let error = document.createElement("div");
    error.className = "text-danger error-text";
    error.style.fontSize = "13px";
    error.innerText = message;
    input.parentNode.appendChild(error);
}

// TOAST
function showToast(msg){
    let t = document.createElement("div");
    t.innerText = msg;
    t.style.position = "fixed";
    t.style.top = "20px";
    t.style.right = "20px";
    t.style.background = "#dc3545";
    t.style.color = "#fff";
    t.style.padding = "12px 20px";
    t.style.borderRadius = "6px";
    t.style.zIndex = "999999";
    t.style.opacity = "0";
    t.style.transition = "0.3s";

    document.body.appendChild(t);

    setTimeout(()=> t.style.opacity = "1", 50);
    setTimeout(()=>{
        t.style.opacity = "0";
        setTimeout(()=> t.remove(), 300);
    },3000);
}

});
</script>
@endsection