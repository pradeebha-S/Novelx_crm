@extends('Intern.layout')

<style>
    .dt-search {
        display: none;
    }
</style>
@section('content')

    <div class="row">
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="text-info"><b>New Tasks</b></p>
                        <h4>{{$newTasks}}</h4>
                    </div>
                    <div class="card-icon text-center">
                        <img src="{{ asset('assets/img') }}/list.png" class="mb-3" alt="">
                        <div>
                            <a href="{{ route('inter_new_task') }}" class="d-block mt-1 line"><b>View</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="text-success"><b>Completed Tasks</b></p>
                        <h4>{{$completedTasks}}</h4>
                    </div>
                    <div class="card-icon text-center">
                        <img src="{{ asset('assets/img') }}/list.png" class="mb-3" alt="">
                        <div>
                            <a href="{{ route('completed_task_intern') }}" class="d-block mt-1 line"><b>View</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="text-danger"><b>Hold Tasks</b></p>
                        <h4>{{ $holdTasks }}</h4>
                    </div>
                    <div class="card-icon text-center">
                        <img src="{{ asset('assets/img') }}/list.png" class="mb-3" alt="">
                        <div>
                            <a href="{{ route('hold_tasks_intern') }}" class="d-block mt-1 line"><b>View</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection