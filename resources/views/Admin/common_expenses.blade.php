@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<style>
@keyframes typing {
    from {
        width: 0;
    }

    to {
        width: 100%;
    }
}

@keyframes blink {
    50% {
        border-color: transparent;
    }
}
</style>
@section('content')
<div class="row flex-column flex-md-row mb-3">
    <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
        <h5> Expenses</h5>
    </div>
</div>
<ul class="nav nav-pills flex-column flex-sm-row mb-4 gap-sm-0 gap-2 mt-4">
    <li class="nav-item">
        <a class="nav-link active waves-effect waves-light" href="{{ route('admin.common_expenses') }}">Common
            Expenses</a>
    </li>
    <li class="nav-item">
        <a class="nav-link waves-effect waves-light" href="{{ route('admin.project_expenses') }}">Project Expenses</a>
    </li>
</ul>
<div class="card p-3">
    <!-- <h5 class="text-center text-decoration-underline">Work From Home Request</h5> -->

    <form action="{{route('add_common_expenses')}}" method="POST" id="login_form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-4 mb-2">
                <label class="form-label">Month<span class="text-danger">*</span></label>
                <input type="text" id="monthPicker" name="month"
                    class="form-control @error('month') is-invalid @enderror" placeholder="Select Month"
                    value="{{ old('month') }}">

                @error('month')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label">Expense Type<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('expense_type') is-invalid @enderror" name="expense_type"
                    placeholder="Enter Expense Type" value="{{ old('expense_type') }}">

                @error('expense_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label">Amount<span class="text-danger">*</span></label>
                <input type="number" min="1" class="form-control @error('amount') is-invalid @enderror" name="amount"
                    placeholder="Enter Amount" value="{{ old('amount') }}">

                @error('amount')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-2">
                <label class="form-label">Proof<span class="text-danger">*</span></label>
                <input type="file" class="form-control @error('proof') is-invalid @enderror" name="proof">

                @error('proof')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label">Status<span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">

                    <option value="">Select</option>

                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>
                        Paid
                    </option>

                    <option value="not_paid" {{ old('status') == 'not_paid' ? 'selected' : '' }}>
                        Not paid
                    </option>

                </select>

                @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label">Remark<span class="text-danger">*</span></label>
                <textarea class="form-control @error('remarks') is-invalid @enderror" placeholder="Enter remarks"
                    name="remarks" rows="3">{{ old('remarks') }}</textarea>

                @error('remarks')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>


        <div class="d-flex justify-content-center align-items-center mb-3 mt-3">
            <button type="reset" class="btn btn-label-secondary me-3">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="finalSubmit">
                Submit
            </button>
        </div>
    </form>
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
                                <th>Date</th>
                                <th class="text-nowrap">Month</th>
                                <th class="text-nowrap">Expense Type</th>
                                <th class="text-nowrap">Amount</th>
                                <th class="text-nowrap">Proof</th>
                                <th>Status</th>
                                <th>Remark</th>
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

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script>
$(function() {


    $('#dept').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('common_expenses_data') }}",

        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'month',
                name: 'month'
            },
            {
                data: 'expense_type',
                name: 'expense_type'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'proof',
                name: 'proof',
                orderable: false,
                searchable: false
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'remarks',
                name: 'remarks'
            }
        ]
    });
    $('#login_form').on('submit', function() {

        let btn = $('#finalSubmit');

        btn.prop('disabled', true);
        btn.text('Processing...');
    });
    flatpickr("#monthPicker", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "F Y",
                altFormat: "F Y"
            })
        ]
    });

});
</script>
@endsection