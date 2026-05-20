@extends('Admin.layout')
<style>
     .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
    }



    p {
        color: black;
    }

    .b {
        /* background: linear-gradient(to right, #ffd6ea, #ffb3ba); */
        border-left: 10px solid #E21F24 !important;
    }

    .c {
        /* background: linear-gradient(to right, #e8f4ea, #b8d8be); */
        border-left: 10px solid #E21F24 !important;
    }

    .d {
        /* background: linear-gradient(to right, #ece6ff, #e0d6ff); */
        border-left: 10px solid #E21F24 !important;
    }

    .e {
        /* background: linear-gradient(to right, #d7e3fc, #c1d3fe); */
        border-left: 10px solid #E21F24 !important;
    }

    <style>
    .card:hover {
        box-shadow: 0 8px 18px rgba(0, 0, 0, .12);
    }

    p {
        color: black;
    }

    .b {
        background: #ffb3ba !important;
    }

    .c {
        background: #b8d8be !important;
    }

    .d {
        background: #e0d6ff !important;
    }

    .e {
        background: #c1d3fe !important;
    }

    .grassbar,
    .red {
        width: 520px;
        text-align: center;
    }

    .bar {
        position: relative;
        height: 15px;
        border-radius: 20px;
        background: #1e2b1e;
        overflow: hidden;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, .6);
    }

    .grass {
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(90deg, #5fa43a 0, #5fa43a 6px, #6fbf4a 6px, #6fbf4a 12px);
    }

    .ball {
        position: absolute;
        top: 50%;
        left: 0;
        width: 12px;
        height: 12px;
        background: radial-gradient(circle, #fff, #ccc);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: left .35s linear, top .45s ease-in, opacity .3s;
        box-shadow: 0 0 8px rgba(255, 255, 255, .9);
    }

    .hole {
        position: absolute;
        right: 4px;
        top: 50%;
        width: 12px;
        height: 12px;
        background: radial-gradient(circle, #000, #3b2a1a);
        border-radius: 50%;
        transform: translateY(-50%);
    }

    .bar.completed .hole {
        box-shadow: 0 0 10px #ffeb3b, 0 0 20px #ffd700;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% {
            transform: translateY(-50%) scale(1)
        }

        50% {
            transform: translateY(-50%) scale(1.15)
        }

        100% {
            transform: translateY(-50%) scale(1)
        }
    }

    .fireflies {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .fireflies span {
        position: absolute;
        width: 4px;
        height: 4px;
        background: #d8ff9e;
        border-radius: 50%;
        box-shadow: 0 0 8px #d8ff9e;
        animation: fly 6s linear infinite;
        opacity: 0;
    }

    .fireflies span:nth-child(1) {
        left: 10%;
        animation-delay: 0s
    }

    .fireflies span:nth-child(2) {
        left: 25%;
        animation-delay: 1s
    }

    .fireflies span:nth-child(3) {
        left: 45%;
        animation-delay: 2s
    }

    .fireflies span:nth-child(4) {
        left: 65%;
        animation-delay: 3s
    }

    .fireflies span:nth-child(5) {
        left: 80%;
        animation-delay: 4s
    }

    .fireflies span:nth-child(6) {
        left: 90%;
        animation-delay: 5s
    }

    @keyframes fly {
        0% {
            bottom: -10px;
            opacity: 0
        }

        20%,
        80% {
            opacity: 1
        }

        100% {
            bottom: 110%;
            opacity: 0
        }
    }



    .loading {
        font-size: 15px;
        opacity: .8;
    }

    .progressbar {
        position: relative;
        height: 14px;
        border-radius: 20px;
        background: rgba(255, 0, 0, .15);
        box-shadow: 0 0 12px rgba(255, 0, 0, .4), inset 0 0 8px rgba(255, 0, 0, .6);
        overflow: hidden;
    }

    .filled {
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, #ff1a1a, #ff4d4d);
        transition: width .4s linear;
    }

    .goal {
        position: absolute;
        top: 50%;
        left: 0;
        width: 12px;
        height: 12px;
        background: radial-gradient(circle, #fff, #ff3333, #b30000);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: left .4s linear, top .35s ease-in, opacity .3s;
    }


    .percent,
    .percentage {
        width: 50px;
        text-align: right;
        flex-shrink: 0;
    }
</style>

</style>
@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <h5>Dashboard</h5>
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('staff_table') }}">
                <div class="card b h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <p class="mb-1 me-2">Staff</p>
                            <h4>{{ $staffCount }}</h4>
                        </div>
                        <div class="card-icon">
                            <img src="{{ asset('assets/img') }}/users.png" alt="">
                        </div>
                    </div>
                </div>
            </a>

        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('project_table') }}">
                <div class="card e h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <p class="mb-1 me-2">Projects</p>
                            <h4>{{ $projectCount }}</h4>
                        </div>
                        <div class="card-icon">
                            <img src="{{ asset('assets/img') }}/completed.png" alt="">

                        </div>
                    </div>
                </div>
            </a>

        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('project_table') }}">
                <div class="card c h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <p class="mb-1 me-2">In Progress</p>

                            <h4>1,584</h4>
                        </div>
                        <div class="card-icon">
                            <img src="{{ asset('assets/img') }}/completed.png" alt="">

                        </div>
                    </div>
                </div>
            </a>

        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('admin.reminder') }}">
                <div class="card d h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">
                            <p class="mb-1 me-2">Reminders</p>
                            <h4>{{ $pendingCount }}</h4>
                        </div>
                        <div class="card-icon">
                            <img src="{{ asset('assets/img') }}/pending.png" alt="">

                        </div>
                    </div>
                </div>
            </a>

        </div>
    </div>
@endsection