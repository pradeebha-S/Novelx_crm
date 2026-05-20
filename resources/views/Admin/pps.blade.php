@extends('Admin.layout')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('admin.dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M15 6l-6 6l6 6"></path>
                        </svg>
                    </a>
                </button>Performance Tracker |<span class="text-danger">&nbsp;{{ $user->name }} </span></h4>
            <small class="text-muted">Monitor yearly performance and point distribution</small>
        </div>
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
            <i class="ti tabler-calendar me-1"></i> 2025
        </span>
    </div>
    <div class="row g-3 mb-3 align-items-stretch">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <p class="text-muted small mb-1">Total Points</p>
                        <h2 class="fw-bold text-primary mb-1">50</h2>
                        <small class="text-success">
                            <i class="ti tabler-trending-up me-1"></i>+10% from last year
                        </small>
                    </div>
                    <div class="icon-wrapper bg-primary-subtle text-primary p-3 rounded-3">
                        <i class="ti tabler-star fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                    <h6 class="fw-semibold mb-4">
                        <i class="ti tabler-filter me-2 text-primary"></i>Filter Performance
                    </h6>
                    <div class="row g-2 align-items-end">
                        <div class="col-6">
                            <label class="form-label small text-muted">Select Year</label>
                            <select id="yearDropdown" class="form-select">
                                @for ($y = now()->year; $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted">Select Month</label>
                          
                            <select id="monthDropdown" class="form-select">
                                @foreach ([
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ] as $num => $name)
                                    <option value="{{ $num }}" {{ $num == $month ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <p class="text-muted small mb-1">Added Points</p>
                        <h2 class="fw-bold text-success mb-1">{{ $addedPoints }}</h2>
                        <small class="text-success">
                            <i class="ti tabler-check text-success me-1"></i>Successfully credited
                        </small>
                    </div>
                    <div class="icon-wrapper bg-success-subtle text-success p-3 rounded-3">
                        <i class="ti tabler-award fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <p class="text-muted small mb-1">Reduced Points</p>
                        <h2 class="fw-bold text-danger mb-1">{{ $remainingPoints }}</h2>
                        <small class="text-danger">
                            <i class="ti tabler-info-circle text-danger me-1"></i>Pending allocation
                        </small>
                    </div>
                    <div class="icon-wrapper bg-danger-subtle text-danger p-3 rounded-3">
                        <i class="ti tabler-clock fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-2">
        <div class="col-12 mt-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-semibold mb-0">
                            <i class="ti tabler-chart-line me-2 text-primary"></i>
                            Performance Overview
                        </h6>
                        <small class="text-muted">Monthly Comparison</small>
                    </div>
                    <div style="height: 350px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4 rounded-4">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <h6 class="fw-semibold mb-0">
                            <i class="ti tabler-history me-2 text-primary"></i>
                            Points History
                        </h6>
                        <small class="text-muted">Track earned and reduced points with reason</small>
                    </div>
                    <div class="d-flex gap-2 align-items-center mb-3">
                        {{-- Excel Export --}}
                        <button id="exportExcel" class="btn btn-light border d-flex align-items-center gap-1">
                            <i class="ti tabler-upload"></i>
                            Excel
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="pointsHistoryTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Points</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    $(document).ready(function () {
    let table = $('#pointsHistoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('pps_data', $user->id) }}",
            data: function(d) {
                d.month = $('#monthDropdown').val();
                d.year = $('#yearDropdown').val();
            },
            dataSrc: 'transactions'
        },
        columns: [
            { data: 'id' },
            { data: 'created_at', render: function(d) { return new Date(d).toLocaleDateString(); } },
            { data: 'transaction_type', render: function(d){
                if(d === 'debit') {
                    return `<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill"><i class="ti tabler-arrow-down me-1"></i> Reduced</span>`;
                }
                return `<span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="ti tabler-arrow-up me-1"></i> Added</span>`;
            }},
            { data: 'points', render: function(d, type, row){
                if(row.transaction_type === 'debit'){
                    return `<span class="fw-bold text-danger">- ${d}</span>`;
                }
                return `<span class="fw-bold text-success">+ ${d}</span>`;
            }},
            { data: 'reason' }
        ],
        dom: 'Bfrtip', // default search box will show
        lengthChange: true // keep length selector if you want
    });
    // Reload table when month/year changes
    $('#monthDropdown, #yearDropdown').on('change', function(){
        table.ajax.reload();
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        let table = $('#pointsHistoryTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('pps_data', $user->id) }}",
                data: function(d) {
                    d.month = $('#monthDropdown').val();
                    d.year = $('#yearDropdown').val();
                },
                dataSrc: 'transactions' // <-- important!
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'created_at',
                    render: function(d) {
                        return new Date(d).toLocaleDateString();
                    }
                },
                {
                    data: 'transaction_type',
                    render: function(d) {
                        if (d === 'debit') {
                            return `<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill"><i class="ti tabler-arrow-down me-1"></i> Reduced</span>`;
                        }
                        return `<span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="ti tabler-arrow-up me-1"></i> Added</span>`;
                    }
                },
                {
                    data: 'points',
                    render: function(d, type, row) {
                        if (row.transaction_type === 'debit') {
                            return `<span class="fw-bold text-danger">- ${d}</span>`;
                        }
                        return `<span class="fw-bold text-success">+ ${d}</span>`;
                    }
                },
                {
                    data: 'reason'
                }
            ]
        });
        // Reload on filter change
        $('#monthDropdown, #yearDropdown').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script>
document.addEventListener("DOMContentLoaded", function() {
    const months = @json($months); // now Blade passes it
    const creditPoints = @json($credit_points);
    const debitPoints = @json($debit_points);
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const gradientBlue = ctx.createLinearGradient(0, 0, 0, 350);
    gradientBlue.addColorStop(0, 'rgba(13,110,253,0.25)');
    gradientBlue.addColorStop(1, 'rgba(13,110,253,0.02)');
    const gradientRed = ctx.createLinearGradient(0, 0, 0, 350);
    gradientRed.addColorStop(0, 'rgba(220,53,69,0.25)');
    gradientRed.addColorStop(1, 'rgba(220,53,69,0.02)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Credit Points',
                    data: creditPoints,
                    borderColor: '#0d6efd',
                    backgroundColor: gradientBlue,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
                {
                    label: 'Debit Points',
                    data: debitPoints,
                    borderColor: '#dc3545',
                    backgroundColor: gradientRed,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
                tooltip: { backgroundColor: '#111', padding: 10, cornerRadius: 8 }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script> --}}
<script>

document.addEventListener("DOMContentLoaded", function () {

const months = @json($months);
const creditPoints = @json($credit_points);
const debitPoints = @json($debit_points);

const ctx = document.getElementById('performanceChart').getContext('2d');

new Chart(ctx,{

type:'line',

data:{

labels:months,

datasets:[

{
label:'Credit Points',
data:creditPoints,
borderColor:'#0d6efd',
tension:0.4,
fill:true,
pointRadius:4,
borderWidth:2
},

{
label:'Debit Points',
data:debitPoints,
borderColor:'#dc3545',
tension:0.4,
fill:true,
pointRadius:4,
borderWidth:2
}

]

},

options:{

responsive:true,
maintainAspectRatio:false,
interaction:{
mode:'nearest',   // ⭐ IMPORTANT
intersect:true    // ⭐ IMPORTANT
},

plugins:{

tooltip:{

callbacks:{

// ⭐ SHOW ONLY CURRENT LINE VALUE
label:function(context){

if(context.dataset.label === 'Credit Points'){

return 'Credit Points : '+context.raw;

}

if(context.dataset.label === 'Debit Points'){

return 'Debit Points : '+context.raw;

}

}

}

}

},

scales:{
y:{
beginAtZero:true
}
}

}

});

});

</script>
<style>
    .icon-wrapper {
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>

