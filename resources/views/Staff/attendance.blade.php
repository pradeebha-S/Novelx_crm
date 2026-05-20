@extends('Staff.layout')





@section('content')

    <div class="row flex-column flex-md-row mb-3">

        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">

            <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2"

                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                    <a href="attendance_dashboard"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"

                            viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round"

                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                            <path d="M15 6l-6 6l6 6" />

                        </svg>

                    </a></button>Attendance</h5>

        </div>

    </div>







    <ul class="nav nav-pills flex-column flex-sm-row mb-4 gap-sm-0 gap-2 mt-4">

        <!-- <li class="nav-item">

            <a class="nav-link active waves-effect waves-light" href="{{ route('attendance') }}"> Attendance</a>

        </li> -->

        <li class="nav-item">

            <a class="nav-link waves-effect waves-light" href="{{ route('wfh') }}">Work From Home</a>

        </li>

        <li class="nav-item">

            <a class="nav-link waves-effect waves-light" href="{{ route('leave_request') }}">Leave Request</a>

        </li>

    </ul>



    <div class="card p-3">

        <form action="" id="login_form">

            <div class="row">

                <div class="col-lg-6 mb-2">

                    <label class="form-label">Check In</label>

                    <input type="time" class="form-control" id="checkIn" placeholder="Check In">

                </div>

                <div class="col-lg-6 mb-2">

                    <label class="form-label">Check Out</label>

                    <input type="time" class="form-control" placeholder="Check Out">

                </div>

                <div class="col-lg-6 mb-2">

                    <label class="form-label">Task</label>

                    <select class="form-select">

                        <option selected>Select</option>

                    </select>

                </div>

                <div class="col-lg-6 mb-2">

                    <label class="form-label">Status</label>

                    <select class="form-select">

                        <option selected>Select Status</option>

                        <option value="1">On Time</option>

                        <option value="2">Late</option>

                    </select>

                </div>

                <div class="col-lg-12 mb-2">

                    <label class="form-label">Remark</label>

                    <input type="text" class="form-control" placeholder="Enter Message">

                </div>

                <div class="col-lg-12 mb-2" id="delayReasonBox" style="display:none;">

                    <label class="form-label">Reason for Delay</label>

                    <input type="text" class="form-control" placeholder="Enter reason">

                </div>

                <div class="row d-flex align-items-center mb-3">

                    <div class="col-12 text-start mt-4 d-flex gap-3">

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submit">

                            Submit

                        </button>

                        <button type="reset" class="btn btn-secondary">

                            Cancel

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

    </div>

    <div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog modal-sm modal-dialog-centered">

            <div class="modal-content rounded-4 px-4 py-5 text-center">



                <h5 class="fw-bold mb-2">Are you sure?</h5>

                <p class="text-muted mb-4">Do you confirm to submit this form?</p>



                <div class="d-flex justify-content-center gap-3 mt-3">



                    <!-- Cancel -->

                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">

                        Cancel

                    </button>



                    <!-- Final submit -->

                    <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">

                        Yes, Sure

                    </button>



                </div>

            </div>

        </div>

    </div>



    <script>



        document.getElementById('finalSubmit').addEventListener('click', function (e) {

            e.preventDefault();

            let btn = this;

            btn.disabled = true;

            btn.innerText = 'Processing...';

            document.getElementById('login_form').submit();

        });

        document.getElementById("checkIn").addEventListener("change", function () {

            let time = this.value;



            // Standard comparison: 09:15

            if (time > "09:15") {

                document.getElementById("delayReasonBox").style.display = "block";

            } else {

                document.getElementById("delayReasonBox").style.display = "none";

            }

        });

    </script>

@endsection