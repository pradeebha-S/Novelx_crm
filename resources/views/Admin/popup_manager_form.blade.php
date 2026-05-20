@extends('Admin.layout')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
<style>
.status-dropdown {
    font-weight: 600;
    padding: 4px 12px;
}

.status-success {
    background-color: #d1f7e0;
    color: #198754;
    border: 1px solid #198754;
}

.status-failed {
    background-color: #fde2e2;
    color: #dc3545;
    border: 1px solid #dc3545;
}

.status-pending {
    background-color: #fff3cd;
    color: #ffc107;
    border: 1px solid #ffc107;
}

.dt-search {
    display: none;
}

.status-pill {
    border-radius: 2px;
    padding: 2px !important;
    font-weight: 600;
    font-size: 13px;
    border: 1px solid transparent;
    background-position: right 8px center;
    background-repeat: no-repeat;
}

.status-success {
    color: #28c76f;
    background-color: rgba(40, 199, 111, 0.12);
    border-color: #28c76f;
}

.status-failed {
    color: #ea5455;
    background-color: rgba(234, 84, 85, 0.12);
    border-color: #ea5455;
}

.card.a {
    border-left: 6px solid #34C759;
}

.card.b {
    border-left: 6px solid #FF8D28;
}

.card.c {
    border-left: 6px solid #FF383C;
}

.card.d {
    border-left: 6px solid #0088FF;
}

.card.e {
    border-left: 6px solid #09A8C3;
}

/* Multi-select dropdown styles */
.multiselect-container {
    position: relative;
}

.multiselect-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    display: none;
}

.multiselect-item {
    padding: 0.375rem 0.75rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    width: 100%;
    justify-content: space-between;
}

.multiselect-item:hover {
    background-color: #f8f9fa;
}

.multiselect-item input[type="checkbox"] {
    margin-right: 0.5rem;
    width: 16px;
    height: 16px;
}

.multiselect-selected {
    background-color: #e3f2fd;
    font-weight: 500;
}

.multiselect-header {
    padding: 0.5rem 0.75rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
    display: flex;
    justify-content: flex-end;
}

.multiselect-clear {
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    font-size: 0.875rem;
}

.multiselect-clear:hover {
    color: #dc3545;
}

.details.mb-3 {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>
@section('content')
<div class="d-flex justify-content-between">
    <div>
        <h5>
            <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                <a href="{{ route('popup_manager') }}">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                        <path stroke="none" d="M0 0h24v24H0z"></path>

                        <path d="M15 6l-6 6l6 6"></path>

                    </svg>

                </a>

            </button>

            Popup Management
        </h5>
    </div>

</div>
<div class="row d-flex justify-content-center">
    <div class="col-lg-6">
        <div class="card mb-2 p-4 w-100">
            <h6>Popup Management</h6>
            <form action="{{ route('add_popup_manager') }}" method="POST">
                @csrf
                <div class="row g-3">

                    <!-- Product Type Multi-select -->
                    <div class="col-12">
                        <label class="form-label">Employee</label>
                        <div class="multiselect-container">
                            <div class="form-select" id="typeFilter"
                                style="cursor: pointer; background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iOCIgdmlld0JveD0iMCAwIDEyIDgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xIDFMNiA2TDExIDEiIGZpbGw9IiM2QzY3NzgiIHN0cm9rZT0iIzZDNTc3OCIgc3Ryb2tlLXdpZHRoPSIxIiIHc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo='); background-position: right 12px center; background-repeat: no-repeat; background-size: 12px 8px; padding-right: 36px;">
                                <span id="typeSelectedText">All</span>
                            </div>
                            <div class="multiselect-dropdown" id="typeDropdown">
                                <div class="multiselect-header">
                                    <button type="button" class="multiselect-clear" id="typeClearBtn">Clear All</button>
                                    <button type="button" class="multiselect-clear" id="typeSelectBtn">Select
                                        All</button>
                                </div>
                                @foreach($employees as $employee)
                                <label class="multiselect-item" data-value="{{ $employee->name }}">

                                    <span>{{ $employee->name }}</span>

                                    <input type="checkbox" name="employees[]" value="{{ $employee->id }}">

                                </label>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Message </label>
                        <textarea class="form-control" name="message" placeholder="Enter Description"
                            rows="3"></textarea>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit"> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {


    /* ---------- MULTI SELECT FILTERS ---------- */

    let selectedTypes = [];

    $('#typeFilter').on('click', function(e) {
        e.stopPropagation();
        $('#typeDropdown').toggle();
    });

    $('#typeDropdown .multiselect-item input').on('change', function() {

        const value = $(this).val();

        if ($(this).is(':checked')) {

            if (!selectedTypes.includes(value)) {
                selectedTypes.push(value);
            }

            $(this).closest('.multiselect-item').addClass('multiselect-selected');

        } else {

            selectedTypes = selectedTypes.filter(item => item !== value);
            $(this).closest('.multiselect-item').removeClass('multiselect-selected');

        }

        updateTypeDisplay();
    });

    /* CLEAR ALL */
    $('#typeClearBtn').on('click', function() {

        $('#typeDropdown input').prop('checked', false);
        $('#typeDropdown .multiselect-item').removeClass('multiselect-selected');

        selectedTypes = [];

        updateTypeDisplay();
    });

    /* SELECT ALL */
    $('#typeSelectBtn').on('click', function() {

        selectedTypes = [];

        $('#typeDropdown .multiselect-item input').each(function() {

            $(this).prop('checked', true);

            const value = $(this).val();

            if (!selectedTypes.includes(value)) {
                selectedTypes.push(value);
            }

            $(this).closest('.multiselect-item').addClass('multiselect-selected');

        });

        updateTypeDisplay();
    });

    function updateTypeDisplay() {

        let selectedNames = [];

        $('#typeDropdown input:checked').each(function() {

            selectedNames.push(
                $(this).closest('.multiselect-item').find('span').text()
            );

        });

        if (selectedNames.length === 0) {

            $('#typeSelectedText').text('All');

        } else {

            $('#typeSelectedText').text(selectedNames.join(', '));

        }
    }


    $(document).on('click', function(e) {

        if (!$(e.target).closest('.multiselect-container').length) {
            $('.multiselect-dropdown').hide();
        }

    });

});
</script>
@endsection