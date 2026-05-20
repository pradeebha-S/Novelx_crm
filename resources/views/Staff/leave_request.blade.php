@extends('Staff.layout')
<style>
    .typing-text {
    display: inline-block;
    overflow: hidden;
    white-space: nowrap;
    border-right: 2px solid #dc3545;
    width: 0;
    animation: typing 6s steps(90, end) forwards, blink 0.7s infinite;
}
@keyframes typing {
    from { width: 0; }
    to { width: 100%; }
}
@keyframes blink {
    50% { border-color: transparent; }
}
</style>
@section('content')
    <div class="row flex-column flex-md-row mb-3">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
            <h5> Request</h5>
        </div>
    </div>
    <ul class="nav nav-pills flex-column flex-sm-row mb-4 gap-sm-0 gap-2 mt-4">
        <!-- <li class="nav-item">
                        <a class="nav-link waves-effect waves-light" href="{{ route('attendance') }}"> Attendance</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link active waves-effect waves-light" href="{{ route('leave_request') }}">Leave Request</a>
                    </li>
        <li class="nav-item">
            <a class="nav-link waves-effect waves-light" href="{{ route('wfh') }}">Work From Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link waves-effect waves-light" href="{{ route('permission') }}">Permission</a>
        </li>
    </ul>
    <div class="card p-3">
        <h5 class="text-center text-decoration-underline">Leave Request</h5>

        <form action="{{ route('request_leave') }}" method="POST" id="login_form">
            @csrf
            <div class="row">
                <div class="col-lg-6 mb-2">
                    <label class="form-label">From Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="from">
                </div>
                <div class="col-lg-6 mb-2">
                    <label class="form-label">To Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="to">
                </div>

                  <div class="col-lg-6 mb-2">
    <div class="row">
        <!-- Informed To -->
        <div class="col-lg-6 mb-2">
            <label class="form-label">
                Informed To <span class="text-danger">*</span>
            </label>
            <select class="form-select" name="informed_to" required>
                <option value="" selected disabled>-- Select --</option>
                <option value="Project Incharge">Project Incharge</option>
                <option value="HR">HR</option>
                <option value="Project Coordinator">Project Coordinator</option>
            </select>
        </div>
        <!-- Mailed To -->
        <div class="col-lg-6 mb-2">
            <label class="form-label">
              Is mailed <span class="text-danger">*</span>
            </label>
          <select class="form-select" name="mailed" required>
    <option value="" selected disabled>-- Select --</option>
    <option value="yes">Yes</option>
    <option value="no">No</option>
</select>
        </div>
        <!-- Note -->
        <div class="col-12">
            <span class="text-danger fw-semibold small typing-text">
    Kindly make sure you have already sent an email to hr@novelx.in & novelxsoftware@gmail.com
</span>
        </div>
    </div>
</div>
                <div class="col-lg-6 mb-2">
                    <label class="form-label">Reason<span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" placeholder="Enter reason" name="reason" rows="3"></textarea>
                </div>
                <span class="text-danger fw-bold small">Note: Please ensure you have informed the Project Incharge, HR, or Project
                    Coordinator and obtained approval via phone call before submitting your request..</span>
                <div class="d-flex justify-content-center align-items-center mb-3 mt-3">
                    <button type="reset" class="btn btn-label-secondary me-3">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="finalSubmit">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="card p-4 mt-3">
        <div class="card-datatable table-responsive pt-0">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table id="dept" class="table">
                            <thead>
                                <tr>
                                    <th>SNO</th>
                                    <th>Date</th>
                                    <th class="text-nowrap">From Date</th>
                                    <th class="text-nowrap">To Date</th>
                                    <th class="text-nowrap">Informed To</th>
                                       <th class="text-nowrap">Is Mailed</th>
                                    <th>Reason</th>
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
    <!-- <div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content rounded-4 px-4 py-5 text-center">
                        <h5 class="fw-bold mb-2">Are you sure?</h5>
                        <p class="text-muted mb-4">Do you confirm to submit this form?</p>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                                Yes, Sure
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script>
        $(function () {
            $('#dept').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('leave_request_data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'date', name: 'date' },
                    { data: 'from', name: 'from' },
                    { data: 'to', name: 'to' },
                     { data: 'informed_to', name: 'informed_to' },
                      { data: 'mailed', name: 'mailed' },
                    { data: 'reason', name: 'reason' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'remark', name: 'remark' }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search leave",
                    lengthMenu: "_MENU_"
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    ["10", "25", "50", "100", "All"]
                ]
            });
            document.getElementById('finalSubmit').addEventListener('click', function (e) {
                e.preventDefault();
                let btn = this;
                btn.disabled = true;
                btn.innerText = 'Processing...';
                document.getElementById('login_form').submit();
            });
        });
    </script>
@endsection