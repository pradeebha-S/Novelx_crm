@extends('Admin.layout')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-1">

        <!-- Left side -->
        <div class="d-flex align-items-center">
            <h5 class="mb-0">
                <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('staff_table') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>
                Create Role
            </h5>
        </div>
    </div>
    <div class="card p-4 mt-4">
        <h6>Create Role</h6>
        <form action="{{ route('add_role') }}" method="post" id="login_form">
            @csrf
           <div class="row align-items-end">
            <div class="col-lg-10 mb-2">
                <label class="form-label">Role</label>
                <input type="text" class="form-control @error('role') is-invalid @enderror" placeholder="Enter Role"
                    name="role" value="{{ old('role') }}">
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-2 mb-2">
                <button type="button" class="btn btn-primary w-100" id="finalSubmit">
                    Add Role
                </button>
            </div>
        </div>
        </form>
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

                    <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                        Yes, Sure
                    </button>

                </div>
            </div>
        </div>
    </div> -->
      <div class="card mt-4">
        <div class="card-datatable table-responsive pt-0">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table id="staff" class="table">

                            <thead>
                                <tr>
                                    <th>SNO</th>
                                    <th>ROLE</th>
                                    <th>ACTION</th>

                                </tr>
                            </thead>
                            <tbody>
 @foreach($data as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->role }}</td>

                                        <td>
                                            <div class="dropdown">

                                                <a class="action-btn-danger deleteBtn" data-id="{{ $row->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#delete">
                                                    <img src="{{ asset('assets/img/trash.png') }}" alt="">
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
<div class="modal fade" id="delete" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">

            <div class="modal-content rounded-4 text-center p-4 py-5">
                <h5 class="fw-bold mb-2">Are you sure!!</h5>
                <p class="text-muted">Are you confirm to delete?</p>

                <form id="delete_form" method="POST" action="{{ route('delete_role') }}">
                    @csrf
                    <input type="hidden" name="id" id="deleteId">
                    <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                        <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger p-3 ms-2 fw-semibold" id="submit_btn">
                            Yes, Sure
                        </button>
                    </div>
                </form>

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
    </script>
     <script>



        document.addEventListener("DOMContentLoaded", function () {
            new DataTable('#staff', {
                language: {
                    search: "",
                    searchPlaceholder: "Search Staff",
                    lengthMenu: "_MENU_"
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],        // actual values
                    ["10", "25", "50", "100", "All"]  // labels shown to user
                ]
            });
        });
        document.addEventListener("DOMContentLoaded", function () {

            // When clicking delete icon → set the hidden input value
            document.querySelectorAll(".deleteBtn").forEach(btn => {
                btn.addEventListener("click", function () {
                    let id = this.getAttribute("data-id");
                    document.getElementById("deleteId").value = id;
                });
            });

            // Disable button + submit form
            document.getElementById('submit_btn').addEventListener('click', function (e) {
                e.preventDefault();
                this.disabled = true;
                this.innerText = 'Processing...';

                document.getElementById('delete_form').submit();
            });
        });

        document.getElementById("exp").addEventListener("click", function () {
            let table = document.getElementById("staff");
            let rows = Array.from(table.querySelectorAll("tr"));

            let csv = rows.map(row => {
                let cells = Array.from(row.querySelectorAll("th, td"));
                return cells.map(cell => `"${cell.innerText}"`).join(",");
            }).join("\n");

            let blob = new Blob([csv], { type: "text/csv" });
            let url = URL.createObjectURL(blob);

            let a = document.createElement("a");
            a.href = url;
            a.download = "modules.csv"; // File name
            a.click();

            URL.revokeObjectURL(url);
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
@endsection