@extends('Admin.layout')


@section('content')
    <div class="row align-items-center mb-3">

        <!-- LEFT SIDE -->
        <div class="col d-flex align-items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-icon bg-white waves-effect"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 6l-6 6l6 6" />
                </svg>
            </a>

            <h5 class="mb-0">Histories</h5>
        </div>



    </div>


    <div class="card p-4">


        <!-- <h5 class="card-title mb-2">Filters</h5> -->

        <!-- Filters -->
        <div class="row g-3 align-items-end">

            <!-- From Date -->
            <div class="col-12 col-md-4 col-lg-5">
                <label class="form-label mb-1">From Date</label>
                <input type="date" class="form-control">
            </div>

            <!-- To Date -->
            <div class="col-12 col-md-4 col-lg-5">
                <label class="form-label mb-1">To Date</label>
                <input type="date" class="form-control">
            </div>

            <!-- Search Button -->
            <div class="col-12 col-md-4 col-lg-2 d-grid">
                <button class="btn btn-primary">
                    Search
                </button>
            </div>

        </div>


        <div class="card-datatable table-responsive pt-0">
            <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
                <div class="justify-content-between dt-layout-table">
                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                        <table id="dept" class="table">

                          <thead>
                                <tr>
                                    <th>SNO</th>
                                    <th class="text-nowrap">Staff Name</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">Task</th>
                                    <th class="text-nowrap">Task Description</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
     @php
        $staffName = \App\Models\User::where('id', $row->assign_to)->value('name') ?? 'N/A';
        $projectName= \App\Models\Project::where('id', $row->project_id)->value('project_name') ?? 'N/A';
    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td class="text-nowrap">
                                            {{ $staffName }}
                                        </td>

                                        <!-- Project Name -->
                                        <td class="text-nowrap">
                                            {{$projectName}}
                                        </td>

                                        <!-- Task -->
                                        <td>
                                            {{ $row->task_name }}
                                        </td>
                                        <td>
                                            {{ $row->task_description }}
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
    <div class="modal fade" id="reply" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 py-5">

                <p class="text-muted text-center">Reply...?</p>

                <form action="{{ route('leave_reply') }}" method="post" id="replyForm">
                    @csrf
                    <input type="hidden" id="reply_id" name="id">

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Reply</label>
                            <textarea class="form-control" rows="2" name="reply"></textarea>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Remark</label>
                            <textarea class="form-control" rows="2" name="remark"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                        <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary p-3 fw-semibold" id="finalSubmit">
                            Yes, Sure
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>



        document.addEventListener("DOMContentLoaded", function () {
            new DataTable('#dept', {
                language: {
                    search: "",
                    searchPlaceholder: "Search",
                    lengthMenu: "_MENU_"
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],        // actual values
                    ["10", "25", "50", "100", "All"]  // labels shown to user
                ]
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".open-reply").forEach(btn => {
                btn.addEventListener("click", function () {
                    let id = this.getAttribute("data-id");
                    document.getElementById("reply_id").value = id;
                });
            });
        });


        document.getElementById('finalSubmit').addEventListener('click', function (e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('replyForm').submit();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
@endsection