@extends('Intern.layout')
<style>
    p {
        color: black;
    }

    .b {
        background: linear-gradient(to right, #FFD1DF, #ffb3ba);
        /* border-left: 10px solid #E3CD8B !important; */
    }

    .c {
        background: linear-gradient(to right, #e8f4ea, #b8d8be);
        /* border-left: 10px solid #E3CD8B !important; */
    }

    .d {
        background: linear-gradient(to right, #ece6ff, #e0d6ff);
        /* border-left: 10px solid #E3CD8B !important; */
    }

    .e {
        background: linear-gradient(to right, #d7e3fc, #c1d3fe);
        /* border-left: 10px solid #E3CD8B !important; */
    }
</style>
@section('title', 'Dashboard')

@section('content')
<div class="row align-items-center mb-3">
    <!-- Left: Welcome text -->
    <div class="col-md-5">
        <h5 class="mb-0 fw-bold">
            Welcome Back,
            <span class="text-danger">{{ Auth::guard('intern')->user()->name }}</span>
        </h5>
    </div>

    <!-- Centered buttons -->
    <div class="col-md-6 d-flex justify-content-start">
        <div class="d-flex gap-2">
            <button class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#checkin">
                Check In
            </button>

            <button class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#checkout">Check
                Out</button>

        </div>
    </div>
</div>


<div class="row">


    <div class="col-lg-4 col-sm-6 mb-3">
        <a href="#" class="text-decoration-none text-dark">
            <div class="card b h-100">
                <div class="card-body d-flex flex-column justify-content-center">

                    @php
                    $user = Auth::guard('intern')->user();
                    @endphp

                    @if($user)
                    <p class="mb-1 fw-semibold">ID : {{ $user->user_id }}</p>
                    <p class="mb-1 fw-semibold">{{ $user->name }}</p>
                    <p class="mb-0 fw-semibold">{{ $user->designation }}</p>
                    @else
                    <p class="text-danger">User not logged in</p>
                    @endif

                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-4 col-sm-6 mb-3">
        <a href="{{ route('intern_task') }}">
            <div class="card p-3 d h-100">

                <div class="text-center mb-2">
                    <h5 class="mb-0 fw-semibold text-black">
                        Total Task
                    </h5>
                </div>

                <h6 class="text-center m-0">12</h6>

                <!-- Labels -->
                <div class="d-flex justify-content-between px-2 mt-2">
                    <p class="mb-0 fw-semibold">Completed</p>
                    <p class="mb-0 fw-semibold">Pending</p>
                </div>

                <!-- Values -->
                <div class="d-flex justify-content-between px-2 mt-1">
                    <p class="mb-0">10</p>
                    <p class="mb-0">2</p>
                </div>

            </div>
        </a>
    </div>



    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="card p-3 e h-100">

            <div class="text-center mb-2">
                <h5 class="mb-0 fw-semibold text-black">
                    Late Logins
                </h5>
            </div>

            <h6 class="text-center m-0">12</h6>

            <!-- Labels -->
            <div class="d-flex justify-content-between px-2 mt-2">
                <p class="mb-0 fw-semibold">This Month</p>
                <p class="mb-0 fw-semibold">Last Month</p>
            </div>

            <!-- Values -->
            <div class="d-flex justify-content-between px-2 mt-1">
                <p class="mb-0">10</p>
                <p class="mb-0">2</p>
            </div>

        </div>
    </div>

</div>
<div class="card shadow-sm mt-3">
    <div class="card-body">
        <h6 class="mb-3">Task Status Overview</h6>
        <div id="taskStatusChart"></div>
    </div>
</div>



<div class="modal fade" id="checkin" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 text-center p-4 py-5 shadow-sm">

            <p id="liveTime" class="fw-bold fs-5 mb-3"></p>
            <p class="mb-4 fs-6">Do you want to Check In now?</p>

            <form action="{{ route('intern_check_in') }}" method="POST" id="checkinForm">
                @csrf

                <!-- Late Section -->
                <div id="lateSection" class="d-none text-start">
                    <label class="form-label fw-semibold">
                        Reason <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                        class="form-control mb-3"
                        name="late_reason"
                        placeholder="Enter reason if you are late...">
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button"
                        class="btn btn-outline-primary px-4 py-2 fw-semibold"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        class="btn btn-danger px-4 py-2 fw-semibold" id="checkinSubmit">
                        Yes, Sure
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="checkout" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 text-center p-4 py-5 shadow-sm">

            <form action="{{ route('intern_check_out') }}" method="POST" id="checkoutForm">
                @csrf
                <p id="checkoutLiveTime" class="fw-bold fs-5 mb-3"></p>

                <div id="checkoutLateSection" class="text-start">
                    <label class="form-label fw-semibold">
                        Learnt Today <span class="text-danger">*</span>
                    </label>

                    <input type="text" class="form-control mb-3" name="remark"
                        placeholder="What You Learnt Today...?">
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold" id="finalSubmit">
                        Yes, Sure
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="{{ asset('/assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const liveTime = document.getElementById('liveTime');
        const lateSection = document.getElementById('lateSection');
        const reasonInput = document.querySelector('input[name="late_reason"]');
        const lateBtn = document.getElementById('lateBtn');

        if (!liveTime || !lateSection || !reasonInput) return;

        const now = new Date();

        const lateTime = new Date();
        lateTime.setHours(9, 10, 0, 0);

        liveTime.innerText =
            now.toLocaleDateString('en-GB') + ' | ' +
            now.toLocaleTimeString('en-US');

        if (now <= lateTime) {


            liveTime.classList.add('text-success');
            liveTime.classList.remove('text-danger');

            lateSection.classList.add('d-none');
            reasonInput.removeAttribute('required');

        } else {


            liveTime.classList.add('text-danger');
            liveTime.classList.remove('text-success');

            lateSection.classList.remove('d-none');
            reasonInput.setAttribute('required', true);

            if (lateBtn) {
                lateBtn.classList.remove('btn-warning');
                lateBtn.classList.add('btn-danger');
                lateBtn.innerText = 'Late';
            }
        }
    });
</script>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {

        const checkinLiveTime = document.getElementById('checkinLiveTime');
        const checkoutLiveTime = document.getElementById('checkoutLiveTime');

        const checkinLateSection = document.getElementById('checkinLateSection');
        const checkinReason = document.querySelector('input[name="late_reason"]');

        function updateLiveTime() {
            const now = new Date();

            const timeText =
                now.toLocaleDateString('en-GB') + ' | ' +
                now.toLocaleTimeString('en-US');

            /* ---------- UPDATE BOTH MODALS ---------- */
            if (checkinLiveTime) checkinLiveTime.innerText = timeText;
            if (checkoutLiveTime) checkoutLiveTime.innerText = timeText;

            /* ---------- CHECK-IN LATE LOGIC ---------- */
            if (checkinLateSection && checkinReason) {

                const lateTime = new Date();
                lateTime.setHours(9, 10, 0, 0);

                if (now > lateTime) {
                    checkinLiveTime?.classList.add('text-danger');
                    checkinLateSection.classList.remove('d-none');
                    checkinReason.setAttribute('required', true);
                } else {
                    checkinLiveTime?.classList.add('text-success');
                    checkinLateSection.classList.add('d-none');
                    checkinReason.removeAttribute('required');
                }
            }
        }

        // Initial call
        updateLiveTime();

        // Update every second
        setInterval(updateLiveTime, 1000);

    });
</script> -->



<script>
    document.getElementById('checkinSubmit').addEventListener('click', function() {

        const btn = this;
        const form = document.getElementById('checkinForm');
        btn.disabled = true;
        btn.innerText = 'Processing...';

        form.submit();
    });
    
    document.getElementById('finalSubmit').addEventListener('click', function() {

        const btn = this;
        const form = document.getElementById('checkoutForm');
        btn.disabled = true;
        btn.innerText = 'Processing...';

        form.submit();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const options = {
            chart: {
                type: 'area',
                height: 260,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },

            series: [{
                    name: 'Pending',
                    data: [10, 15, 12, 18, 14, 20, 16]
                },
                {
                    name: 'Completed',
                    data: [5, 10, 18, 25, 30, 35, 40]
                },
                {
                    name: 'On Hold',
                    data: [2, 4, 3, 5, 6, 4, 5]
                }
            ],

            colors: ['#FF9F43', '#34C759', '#FF4D4F'],

            dataLabels: {
                enabled: false
            },

            stroke: {
                curve: 'smooth',
                width: 2
            },

            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0,
                    opacityFrom: 0.17,
                    opacityTo: 0,
                    stops: [0, 100]
                }
            },

            markers: {
                size: 3,
                strokeWidth: 2,
                strokeColors: '#fff',
                hover: {
                    size: 5
                }
            },

            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },

            grid: {
                strokeDashArray: 4,
                borderColor: '#eee'
            },

            legend: {
                position: 'top',
                horizontalAlign: 'right'
            },

            tooltip: {
                shared: true
            }
        };

        new ApexCharts(
            document.querySelector("#taskStatusChart"),
            options
        ).render();

    });
</script>


@endsection