@extends('Admin.layout')

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

@section('content')
    <div class="row">



        <h5><strong>Student</strong></h5>

       
   

    <!-- <div class="col-lg-3 col-sm-6 mb-3">

                    <div class="card p-3 e h-100">

                        <div class="d-flex justify-content-between align-items-center mb-2 mx-2">

                            <h5 class="mb-0 fw-semibold text-black">Today</h5>

                            <h5 class="mb-0"><a href="{{ route('intern_attendance') }}">View</a></h5>

                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between px-2 mt-2">

                            <a href="{{ route('today_present') }}" class="text-decoration-none">

                                <p class="mb-0 fw-semibold">Present</p>

                            </a>

                            <a href="{{ route('today_in_progress') }}" class="text-decoration-none">

                                <p class="mb-0 fw-semibold">In Progress</p>

                            </a>

                        </div>

                        <div class="d-flex justify-content-between px-2 mt-1">

                            <p class="mb-0">{{ $studentPresentCount }}</p>

                            <p class="mb-0">{{ $inprogressCount }}</p>

                        </div>

                    </div>

                </div> -->

    <div class="col-lg-3 col-sm-6 mb-3">

        <a href="{{ route('intern_attendance') }}">

            <div class="card e h-100 p-3 d-flex flex-column justify-content-center text-center">

                <h6 class="fw-semibold mb-2 text-black">

                    Today Present Count

                </h6>

                <hr class="my-2">

                <h3 class="fw-bold mb-0 text-black">

                    {{ $studentPresentCount }}

                </h3>

            </div>

        </a>

    </div>

    <div class="col-lg-3 col-sm-6 mb-3">

        <a href="{{ route('intern_table') }}">

            <div class="card c h-100 p-3 d-flex flex-column justify-content-center text-center">

                <h6 class="fw-semibold mb-2 text-black">

                    Total Student Count

                </h6>

                <hr class="my-2">

                <h3 class="fw-bold mb-0 text-black">

                    {{ $studentCount }}

                </h3>

            </div>

        </a>

    </div>

    <div class="col-lg-3 col-sm-6 mb-3">

        <a href="{{ route('course') }}">

            <div class="card d h-100 p-3 d-flex flex-column justify-content-center text-center">

                <h6 class="fw-semibold mb-2 text-black">

                    Total Course

                </h6>

                <hr class="my-2">

                <h3 class="fw-bold mb-0 text-black">

                    {{ $studentCount }}

                </h3>

            </div>

        </a>

    </div>

    <!-- <div class="col-lg-3 col-sm-6 mb-3">

                <div class="card p-3 d h-100">

                    <div class="d-flex justify-content-between align-items-center mb-2 mx-2">

                        <h5 class="mb-0 fw-semibold text-black">Total Project</h5>

                        <h5 class="mb-0"><a href="{{ route('project_table') }}">View</a></h5>

                    </div>

                    <hr class="my-2">

                    <div class="d-flex justify-content-between px-2 mt-2">

                        <a href="{{ route('project_table') }}" class="text-decoration-none">

                            <p class="mb-0 fw-semibold">Project</p>

                        </a>

                        <a href="{{ route('pending_task') }}" class="text-decoration-none text-black">

                            <p class="mb-0 fw-semibold">Pending Task</p>

                        </a>

                    </div>

                    <div class="d-flex justify-content-between px-2 mt-1">

                        <p class="mb-0">{{ $projectCount }}</p>

                        <p class="mb-0">{{ $pendingCount }}</p>

                    </div>

                </div>

            </div> -->



    </div>



    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {



            document.querySelectorAll(".bar").forEach(bar => {



                let g = 0;



                const percentEl = bar.closest(".d-flex").querySelector(".percent");

                const target = parseInt(percentEl.dataset.target);



                const ball = bar.querySelector(".ball");

                const hole = bar.querySelector(".hole");



                const bw = bar.offsetWidth;

                const hx = bw - hole.offsetWidth - 4;



                const interval = setInterval(() => {

                    g++;



                    const progressX = (hx * g) / 100;

                    ball.style.left = progressX + "px";

                    percentEl.textContent = g + "%";



                    if (g >= target) {

                        clearInterval(interval);

                        percentEl.textContent = target + "%";

                    }

                }, 30);



            });



            /* ---------- RED (STOP AT 80%) ---------- */

            document.querySelectorAll(".progressbar").forEach((bar) => {



                const filled = bar.querySelector(".filled");

                const goal = bar.querySelector(".goal");

                const percentage = bar.closest(".d-flex").querySelector(".percentage");



                const target = parseInt(percentage.dataset.percent); // from HTML

                const pw = bar.offsetWidth;



                let r = 0;



                const red = setInterval(() => {

                    r++;



                    filled.style.width = r + "%";

                    goal.style.left = (pw * r) / 100 + "px";

                    percentage.textContent = r + "%";



                    if (r >= target) {

                        clearInterval(red);

                        filled.style.width = target + "%";

                        percentage.textContent = target + "%";

                    }

                }, 30);



            });



        });
    </script>
@endsection
