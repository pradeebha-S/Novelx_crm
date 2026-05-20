@extends('Admin.layout')
<style>
    .dt-search {
        display: none !important;
    }
    .dt-length {
        display: none !important;
    }
</style>
@section('content')
    <div class="row align-items-center justify-content-between mb-2 m-1">
        <div class="col-auto">
            <h5>Seen Feedbacks</h5>
        </div>
    </div>
    <div class="card p-0 m-2">
        <h6 class="d-flex align-items-center mb-0 m-3">
            <i class="ti tabler-filter text-primary me-2"></i>
            Filter
        </h6>
        <div class="row g-3 m-3 align-items-end">
            <!-- Page Length -->
            <div class="col-auto">
                <select id="pageLength" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <!-- Push filters to right -->
            <div class="col"></div>
            <!-- Filter By Month -->
            <div class="col-lg-3">
                <label class="form-label d-flex align-items-center">
                    <i class="ti tabler-calendar text-primary me-1"></i>
                    Filter By Month
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti tabler-search"></i>
                    </span>
                    <select id="filterStatus" class="form-select">
                        <option value="">Select</option>
                        <option>January</option>
                        <option>February</option>
                        <option>March</option>
                        <option>April</option>
                        <option>May</option>
                        <option>June</option>
                        <option>July</option>
                        <option>August</option>
                        <option>September</option>
                        <option>October</option>
                        <option>November</option>
                        <option>December</option>
                    </select>
                </div>
            </div>
            <!-- Filter By Employee -->
            <div class="col-lg-3">
                <label class="form-label d-flex align-items-center">
                    <i class="ti tabler-user text-danger me-1"></i>
                    Filter By Employee
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti tabler-search"></i>
                    </span>
                <select id="filterType" class="form-select">
    <option value="">Select</option>
    @foreach($staffs as $staff)
        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
    @endforeach
</select>
                </div>
            </div>
            <!-- Buttons -->
            <div class="col-lg-2 d-flex gap-2">
                <button id="filterBtn" class="btn btn-primary w-100">Filter</button>
                <button id="resetBtn" type="reset" class="btn btn-label-secondary w-100">Reset</button>
            </div>
        </div>
        <div class="table-responsive pt-0 pb-2">
            <table class="table table-hover" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th>Sno</th>
                        <th>Employee Name</th>
                        <th>Month</th>
                        <th>View</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal fade" id="seenModal" tabindex="-1"> 
            <div class="modal-dialog"> <form method="POST" action="{{ route('mark_feedback_seen') }}">
                 @csrf <input type="hidden" name="feedback_id" id="feedback_id">
                  <div class="modal-content"> <div class="modal-header"> 
                    <h5 class="modal-title"> Mark Feedback as Seen </h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                     </div> <div class="modal-body"> Are you sure you want to mark this feedback as seen? </div>
                      <div class="modal-footer"> <button type="button"
    class="btn btn-success"
    id="submitSeenBtn"
    onclick="submitSeenForm(this)">

    Yes

</button></div> </div>
                     </form> </div> </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function () {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('seen_feed_back_data') }}",
            data: function (d) {
                d.month = $('#filterStatus').val();
                d.employee_id = $('#filterType').val();
            }
        },
     columns: [ { data: 'DT_RowIndex', orderable: false, searchable: false }, { data: 'emp', name: 'user.name' },
      { data: 'month', name: 'month' }, 
      { data: 'description', orderable: false, searchable: false } ]
    });

    // Filter button
    $('#filterBtn').on('click', function () {
        table.ajax.reload();
    });

    // Reset button
    $('#resetBtn').on('click', function () {
        $('#filterStatus').val('');
        $('#filterType').val('');
        table.ajax.reload();
    });
    $(document).on('click', '.seenBtn', function () { let id = $(this).data('id'); $('#feedback_id').val(id); });
});
    </script>
    <script>

function submitSeenForm(button)
{
    button.innerHTML = 'Processing...';

    button.disabled = true;

    button.closest('form').submit();
}

</script>
@endsection