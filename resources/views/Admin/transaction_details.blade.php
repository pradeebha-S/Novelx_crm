@extends('Admin.layout')
@section('content')
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-3 gap-2">
    <h5 class="mb-0">
        Transaction
    </h5>
    <a href="{{ route('admin.performance_tracker') }}" class="mt-2 mt-md-0">
        <button class="btn btn-primary">
            <i class="ti tabler-coins me-2"></i>
            Transaction History
        </button>
    </a>
</div>
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header border-0 pt-4 px-4">
            <h5 class="fw-semibold mb-0 d-flex align-items-center">
                <i class="ti tabler-coins text-success me-2 fs-5"></i>
                Transaction Details
            </h5>
            <hr class="mt-3">
        </div>
        <div class="card-body px-4 pb-4">
            <form action="{{ route('create_pps_transaction') }}" method="POST">
                @csrf
                <div class="row g-4">
                 <div class="col-lg-6">
    <label class="form-label fw-semibold">
        <i class="ti tabler-user me-1 text-muted"></i>
        Select Employee
    </label>
    <select name="user_id"
        class="form-select @error('user_id') is-invalid @enderror">
        <option value="" disabled selected>
            Select Employee
        </option>
        @foreach ($all_users as $user)
            <option value="{{ $user->id }}"
                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
    @error('user_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
                    {{-- <div class="col-lg-6">
                        <label class="form-label fw-semibold d-block">
                            <i class="ti tabler-arrows-exchange me-1 text-muted"></i>
                            Transaction Type
                        </label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transaction_type" id="credit"
                                    value="credit">
                                <label class="form-check-label fw-semibold text-success" for="credit">
                                    <i class="ti tabler-arrow-up-right me-1"></i>
                                    Credit
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transaction_type" id="debit"
                                    value="debit">
                                <label class="form-check-label fw-semibold text-danger" for="debit">
                                    <i class="ti tabler-arrow-down-left me-1"></i>
                                    Debit
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-coins me-1 text-muted"></i>
                            Enter Points </label>
                        <input type="number" class="form-control" placeholder="Enter Points">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-align-left me-1 text-muted"></i>
                            Description
                        </label>
                        <textarea name="description" rows="3" class="form-control"
                            placeholder="Enter transaction description"></textarea>
                    </div> --}}
                    <div class="col-lg-6">
    <label class="form-label fw-semibold d-block">
        <i class="ti tabler-arrows-exchange me-1 text-muted"></i>
        Transaction Type
    </label>
    <div class="d-flex gap-4 mt-2 p-2 border rounded @error('transaction_type') border-danger bg-light-danger @enderror">
        <div class="form-check">
            <input class="form-check-input @error('transaction_type') is-invalid @enderror" 
                   type="radio" name="transaction_type" id="credit" value="credit" 
                   {{ old('transaction_type') == 'credit' ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold text-success" for="credit">
                <i class="ti tabler-arrow-up-right me-1"></i> Credit
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input @error('transaction_type') is-invalid @enderror" 
                   type="radio" name="transaction_type" id="debit" value="debit"
                   {{ old('transaction_type') == 'debit' ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold text-danger" for="debit">
                <i class="ti tabler-arrow-down-left me-1"></i> Debit
            </label>
        </div>
    </div>
    @error('transaction_type')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-6">
    <label class="form-label fw-semibold">
        <i class="ti tabler-coins me-1 text-muted"></i>
        Enter Points 
    </label>
    <input type="number" name="points" value="{{ old('points') }}"
           class="form-control @error('points') is-invalid @enderror" 
           placeholder="Enter Points">
    
    @error('points')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-6">
    <label class="form-label fw-semibold">
        <i class="ti tabler-align-left me-1 text-muted"></i>
        Description
    </label>
    <textarea name="reason" rows="3" 
              class="form-control @error('description') is-invalid @enderror" 
              placeholder="Enter transaction description">{{ old('description') }}</textarea>
    
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
                </div>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button type="reset" class="btn btn-label-secondary border px-4" onclick="window.location.href=window.location.href">
                        <i class="ti tabler-x me-1"></i>
                        Discard
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti tabler-check me-1"></i>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
document.querySelector("form")
.addEventListener("submit",function(){
let btn =
document.querySelector(
'button[type="submit"]');
btn.disabled=true;
btn.innerHTML="Processing...";
});
</script>
@endsection