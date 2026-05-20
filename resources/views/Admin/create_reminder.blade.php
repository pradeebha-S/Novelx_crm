@extends('Admin.layout')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">
                <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('admin.reminder') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                </button>
                Create Reminder
            </h5>
        </div>
    </div>
    <div class="card p-4 mt-4">
        <h6>Create Reminder</h6>
        <form action="{{ route('add_reminder') }}" method="post" id="login_form">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="col-lg-12 mb-2">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter reminder title" name="title" value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label class="form-label">Remind To</label>
                        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                            <option value="" selected disabled>-- Select Person --</option>
                            @foreach ($all_users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} (@if ($user->role == 'admin')
                                        Admin
                                    @else
                                        Staff
                                    @endif)
                                </option>
                            @endforeach
                        </select>
                        @error('remind_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 mb-2">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" rows="3" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-2">
                    <label class="form-label">Reminder Type</label>
                    <select name="reminder_type" class="form-select @error('reminder_type') is-invalid @enderror">
                        <option value="">Select Type</option>
                        <option value="every_day" {{ old('reminder_type') == 'every_day' ? 'selected' : '' }}>Every Day
                        </option>
                        <option value="every_month" {{ old('reminder_type') == 'every_month' ? 'selected' : '' }}>Every
                            Month
                        </option>
                        <option value="tomorrow" {{ old('reminder_type') == 'tomorrow' ? 'selected' : '' }}>Tomorrow
                        </option>
                        <option value="due_date" {{ old('reminder_type') == 'due_date' ? 'selected' : '' }}>Due Date
                        </option>
                    </select>
                    @error('reminder_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-2">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                        value="{{ old('date') }}">
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </form>
        <div class="d-flex form-actions mt-3">
            <button type="button" class="btn btn-primary me-3" id="finalSubmit">
                Create Reminder
            </button>
            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
        </div>
    </div>
    {{-- Modal --}}
    <!-- <div class="modal fade" id="submit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content rounded-4 px-4 py-5 text-center">
                    <h5 class="fw-bold mb-2">Are you sure?</h5>
                    <p class="text-muted mb-4">Do you confirm to submit this reminder?</p>
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
    <script>
        document.getElementById('finalSubmit').addEventListener('click', function(e) {
            e.preventDefault();
            let btn = this;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            document.getElementById('login_form').submit();
        });
    </script>
@endsection
