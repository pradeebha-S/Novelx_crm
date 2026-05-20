@extends('Staff.layout')
@section('content')
<div class="row align-items-center mb-3">
    <div class="col">
        <h5 class="mb-0">Feedback</h5>
    </div>
    <div class="col-auto text-end">
        <a href="{{ route('feedback_list') }}">
            <button class="btn btn-primary">
                <i class="ti tabler-history me-1"></i> Feedback History
            </button>
        </a>
    </div>
</div>
<form method="POST" action="{{ route('add_feedback') }}" id="feedbackForm">
    @csrf

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <!-- Header -->
                    <div class="text-center mb-3">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-star me-1 text-warning"></i>
                            Share your thoughts, suggestions, or concerns
                        </label>
                        <br>
                        <small class="text-danger fw-semibold">We’re listening</small>
                    </div>

                    <!-- POSITIVE FEEDBACK -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            What did you like or find useful since the last update?
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti tabler-thumb-up"></i>
                            </span>

                            <textarea rows="3"
                                name="positive_feedback"
                                class="form-control @error('positive_feedback') is-invalid @enderror"
                                placeholder="Enter positive feedback (min 200 characters)">{{ old('positive_feedback') }}</textarea>
                        </div>

                        @error('positive_feedback')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NEGATIVE FEEDBACK -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            What issues or problems did you face?
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti tabler-alert-triangle"></i>
                            </span>

                            <textarea rows="3"
                                name="negative_feedback"
                                class="form-control @error('negative_feedback') is-invalid @enderror"
                                placeholder="Enter negative feedback (min 200 characters)">{{ old('negative_feedback') }}</textarea>
                        </div>

                        @error('negative_feedback')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SUGGESTIONS -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            What features or changes would you like to see?
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti tabler-lightbulb"></i>
                            </span>

                            <textarea rows="3"
                                name="suggestions"
                                class="form-control @error('suggestions') is-invalid @enderror"
                                placeholder="Enter suggestion  (min 100 characters)">{{ old('suggestions') }}</textarea>
                        </div>

                        @error('suggestions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ADDITIONAL -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Anything else you'd like to share?
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti tabler-message"></i>
                            </span>

                            <textarea rows="3"
                                name="additional_feedback"
                                class="form-control @error('additional_feedback') is-invalid @enderror"
                                placeholder="Additional feedback">{{ old('additional_feedback') }}</textarea>
                        </div>

                        @error('additional_feedback')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SUBMIT -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                            <i class="ti tabler-send me-1"></i> Submit Feedback
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</form>

<script>
    document.getElementById("feedbackForm").addEventListener("submit", function() {

        let btn = document.getElementById("submitBtn");

        btn.innerHTML = "Processing...";
        btn.disabled = true;

    });
</script>
@endsection