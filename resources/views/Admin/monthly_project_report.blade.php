@extends('Admin.layout')

@section('content')
<style>

.report-card{
background:#fff;
border-radius:12px;
box-shadow:0 4px 12px rgba(0,0,0,0.08);
padding:25px;
}

.report-title{
font-size:20px;
font-weight:600;
margin-bottom:20px;
}

.filter-box{
background:#f8fafc;
padding:15px;
border-radius:10px;
margin-bottom:20px;
border:1px solid #e5e7eb;
}

.table thead{
background:#f1f5f9;
}

.table th{
font-weight:600;
text-transform:uppercase;
font-size:13px;
}

.table td{
vertical-align:middle;
}

.project-badge{
background:#10b981;
color:#fff;
padding:4px 10px;
border-radius:6px;
font-size:12px;
margin-right:5px;
}

.staff-list{
color:#374151;
font-weight:500;
}

.btn-view{
background:red;
border:none;
color:white;
padding:5px 12px;
border-radius:6px;
}

.btn-view:hover{
background:#d97706;
}

</style>

<div class="report-card">

<div class="report-title">
<i class="fas fa-project-diagram"></i> Project Report
</div>

<div class="filter-box">

<form method="GET" action="{{ route('monthly_project_report') }}">

<div class="row">

<div class="col-md-3">
<label>From Date</label>
<input type="date" name="from" class="form-control" value="{{ request('from') }}">
</div>

<div class="col-md-3">
<label>To Date</label>
<input type="date" name="to" class="form-control" value="{{ request('to') }}">
</div>

<div class="col-md-3 d-flex align-items-end">
<button type="submit" class="btn btn-primary me-2">
<i class="fas fa-filter"></i> Filter
</button>

<a href="{{ route('monthly_project_report') }}" class="btn btn-secondary">
Reset
</a>
</div>

</div>

</form>

</div>


<div class="table-responsive">

<table class="table table-hover">

<thead>
<tr>
<th>SNO</th>
<th>Project Name</th>
<th>Staff Worked</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@foreach($projects as $key => $project)

<tr>

<td>{{ $key+1 }}</td>

<td>

{{ $project['project_name'] }}

</td>

<td class="staff-list">
{{ implode(', ', $project['staff']) }}
</td>

<td>
<a href="#" class="btn-view">
<i class="fas fa-eye"></i> View
</a>
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection