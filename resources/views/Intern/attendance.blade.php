@extends('Intern.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .link {
        text-decoration: underline;
    }

    .a {
        height: 180px !important;
    }

    .status-btn {
        width: 120px;
        text-align: center;
    }


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
@section('content')
<div class="row">

    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card p-3 b h-100">
            <div class="text-center mb-2">
                <h5 class="mb-0 fw-semibold text-black">
                    Late Logins
                </h5>
            </div>
            <hr class="my-2">
            <!-- Labels -->
            <div class="d-flex justify-content-between px-2 mt-2">
                <p class="mb-0 fw-semibold">This Month</p>
                <p class="mb-0 fw-semibold">Last Month</p>
            </div>
            <!-- Values -->
            <div class="d-flex justify-content-between px-2 mt-1">
                <p class="mb-0">{{ $data['thisMonthLateLogin'] }}</p>
                <p class="mb-0">{{ $data['lastMonthLateLogin'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card p-3 e h-100">
            <div class="text-center mb-2">
                <h5 class="mb-0 fw-semibold text-black">
                    This Month
                </h5>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between px-2 mt-2">
                <p class="mb-0 fw-semibold">Leave</p>
                <!-- <p class="mb-0 fw-semibold">WFH</p> -->
                <p class="mb-0 fw-semibold">Permission</p>
            </div>
            <div class="d-flex justify-content-between px-2 mt-1">
                <p class="mb-0">{{ $data['thisMonthLeave'] }}</p>
                <p class="mb-0"></p>
                <p class="mb-0">{{ $data['thisMonthPermission'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card p-3 c h-100">
            <div class="text-center mb-2">
                <h5 class="mb-0 fw-semibold text-black">
                    Last Month
                </h5>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between px-2 mt-2">
                <p class="mb-0 fw-semibold">Leave</p>
                <!-- <p class="mb-0 fw-semibold">WFH</p> -->
                <p class="mb-0 fw-semibold">Permission</p>
            </div>
            <div class="d-flex justify-content-between px-2 mt-1">
                <p class="mb-0">{{ $data['lastMonthLeave'] }}</p>
                <p class="mb-0"></p>
                <p class="mb-0">{{ $data['lastMonthPermission'] }}</p>
            </div>
        </div>
    </div>
</div>
<div class="row d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Login</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#checkin">Check In</button>

            <a href="" class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#checkout">Check Out</a>
        </div>
    </div>
</div>
<div class="card p-4">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Check In</th>
                                <th class="text-nowrap">Check Out</th>
                                <th class="text-nowrap">Type</th>
                                <th>Status</th>
                                <th class="text-nowrap">Late Reason</th>
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
                        placeholder="Enter reason if you are late..." >
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

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script>
    var jq = jQuery.noConflict();

    jq(document).ready(function() {
        jq('#dept').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('attendance_data') }}",

            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'check_in',
                    name: 'check_in'
                },
                {
                    data: 'check_out',
                    name: 'check_out'
                },
                {
                    data: 'type',
                    orderable: false,
                    searchable: false
                },



                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'late_reason',
                    orderable: false,
                    searchable: false
                }
            ],


            order: [
                [1, 'desc']
            ],
            language: {
                search: "",
                searchPlaceholder: "Search",
                lengthMenu: "_MENU_"
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ["10", "25", "50", "100", "All"]
            ]
        });
    });
</script>
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


@endsection