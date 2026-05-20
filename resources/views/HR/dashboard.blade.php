@extends('HR.layout')
<style>
.custom-card {
    position: relative;
    border-left: 6px solid transparent;
    border-radius: 10px;
    padding: 1rem;
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}

.custom-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 6px;
    border-radius: 10px 0 0 10px;
    background: linear-gradient(180deg, #ff2c54, #ff8a5c54);
}

.custom-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
}

.card-body .icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 15%;
    background: rgba(250, 67, 102, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fa4366;
}

.card-body h6 {
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.card-body h3 {
    font-size: 1.7rem;
}
</style>

@section('content')
<div class="row g-3">
<h5>Welcome Back, <span class="text-danger"><b>Name</b></span></h5>
    <div class="col-lg-3 col-sm-6">
        <div class="card h-100 custom-card">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div>
                    <h6 class="text-uppercase text-muted mb-1">Total Candidates</h6>
                    <h3 class="fw-bold mb-0">1000</h3>
                </div>

                <div class="icon-circle">
                    <i class="ti tabler-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card h-100 custom-card">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div>
                    <h6 class="text-uppercase text-muted mb-1">Total Follow Up</h6>
                    <h3 class="fw-bold mb-0">1000</h3>
                </div>

                <div class="icon-circle">
                    <i class="ti tabler-rotate-rectangle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card h-100 custom-card">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div>
                    <h6 class="text-uppercase text-muted mb-1">Pending Follow Up</h6>
                    <h3 class="fw-bold mb-0">1000</h3>
                </div>

                <div class="icon-circle">
                    <i class="ti tabler-clock-pause"></i>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
