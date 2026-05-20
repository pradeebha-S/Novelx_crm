@extends('Admin.layout')
@section('content')
<div class="card p-4 mt-4">
    <div class="card-datatable table-responsive pt-0">
        <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
            <div class="justify-content-between dt-layout-table">
                <div class=" justify-content-between align-items-center dt-layout-full table-responsive">
                    <table id="dept" class="table">
                        <thead>
                            <tr>
                                <th>SNO</th>
                                <th class="text-nowrap">DATE</th>
                                <th class="text-nowrap">Employee ID</th>
                                <th class="text-nowrap">Name</th>
                                <th class="text-nowrap">Check In</th>
                                <th class="text-nowrap">Check Out</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $row->user_id }}
                                </td>
                                <td class="text-nowrap">{{ $row->user->name ?? 'N/A' }}</td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($row->check_in)->format('h:i:s A') }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('h:i:s A') : '-' }}
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new DataTable('#dept', {
            language: {
                search: "",
                searchPlaceholder: "Search",
                lengthMenu: "_MENU_"
            },
            lengthMenu: [
                [10, 25, 50, 100, -1], // actual values
                ["10", "25", "50", "100", "All"] // labels shown to user
            ]
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
@endsection