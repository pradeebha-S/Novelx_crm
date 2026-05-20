@extends('Admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <!-- Left side -->
    <div class="d-flex align-items-center">
        <h5 class="mb-0">
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                <a href="{{ route('project_table') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
            </button>
            View Task
        </h5>
    </div>
</div>
<h6 class="mt-2">Project Name : {{ $project->project_name }}</h6>
<h6>Tasks</h6>
<ul class="nav nav-pills flex-column flex-sm-row mb-4 gap-sm-0 gap-2 mt-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('task') ? 'active' : '' }}"
            href="{{ route('task', $project->id) }}">
            Not Started
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('in_progress') ? 'active' : '' }}"
            href="{{ route('in_progress', $project->id) }}">
            In Progress
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('completed') ? 'active' : '' }}"
            href="{{ route('completed', $project->id) }}">
            Completed
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('hold') ? 'active' : '' }}"
            href="{{ route('hold', $project->id) }}">
            Hold
        </a>
    </li>
</ul>
<div class="card p-2 mt-5">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th>PROJECT</th>
                                <th>MODULE</th>
                                <th>TASK</th>
                                <th> ASSIGNED STAFF</th>
                                <th>REOPEN</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                {{-- PROJECT (Name + Dates) --}}
                                <td>
                                    {{ $task->project->project_name?? 'N/A' }}<br><br>
                                    <small class="text-muted">
                                        Start:
                                        {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d M Y') : '-' }}
                                        <br>
                                        End:
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}
                                    </small>
                                </td>
                                {{-- MODULE (Type + Name) --}}
                                <td>
                                    <strong>{{ $task->module_type ?? '-' }}</strong><br>
                                    <small class="text-muted">
                                        {{ $task->module->module_name ?? '-' }}
                                    </small>
                                </td>
                                {{-- TASK (Name + Description) --}}
                                <td>
                                    <strong>Task Title:</strong>{{ $task->task_name }}<br><br>
                                    <small>
                                        {!! $task->task_description !!}
                                    </small>
                                </td>
                                <td>
                                    {{ $task->assignedStaff->name ?? 'Not Assigned' }}
                                </td>
                                {{-- REMARK (Count) --}}
                                <td class="text-center">
                                    {{ $task->remark_count ?? 0 }}
                                </td>
                                {{-- STATUS --}}
                                <td>
                                    @if ($task->task_status === 'inprogress')
                                    <span class="badge bg-warning">In Progress</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="update" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 px-4 py-5 text-center">
                <h5 class="fw-bold mb-2">Are you sure?</h5>
                <form action="">
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Module</label>
                            <input type="text" class="form-control" value="Staff">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Task</label>
                            <input type="text" class="form-control" value="Create Staff">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Due Date</label>
                            <input type="text" class="form-control" value="12 Nov 2025">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label d-block text-start">Assign To</label>
                            <input type="text" class="form-control" value="John">
                        </div>
                    </div>
                </form>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <!-- Cancel -->
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <!-- Final submit -->
                    <button type="button" class="btn btn-primary px-4 fw-semibold" id="finalSubmit">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new DataTable('#dept', {
            language: {
                search: "",
                searchPlaceholder: "Search",
                lengthMenu: "_MENU_"
            }
        });
    });
</script>
@endsection