@extends('Admin.layout')

<style>

    .dt-search {

        display: none !important;

    }

</style>

@section('content')

    <div class="row align-items-center justify-content-between">

        <div class="col-auto">

            <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2"

                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">

                    <a href="{{ route('admin.dashboard') }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                            <path d="M15 6l-6 6l6 6" />

                        </svg>

                    </a>

                </button>Absent Employees</h5>

        </div>



    </div>

    <div class="card p-0 mt-2">



        <div class="row mb-3 align-items-center p-3 g-2">

            <div class="col-1">

                <select id="pageLength" class="form-select">

                    <option value="10"> 10</option>

                    <option value="25"> 25</option>

                    <option value="50"> 50</option>

                    <option value="100"> 100</option>

                </select>

            </div>



            <div class="col-md-3 ms-auto">

                <input type="text" id="toDate" class="form-control" placeholder="Search">

            </div>

        </div>





        <div class="table-responsive pt-0 pb-2">

            <div class="row card-header flex-column flex-md-row border-bottom mx-0 p-0">

                <div class="justify-content-between dt-layout-table">

                    <div class=" justify-content-between align-items-center dt-layout-full table-responsive">

                        <table class="table" id="staff">

                            <thead>

                                <tr>

                                          <th>SNO</th>



                                    <th>NAME</th>



                                    <th>TASKS</th>



                                    <th class="text-nowrap">Weekly Report</th>



                                    <th>COUNT</th>



                                    <th>MOBILE</th>



                                    <th>OFFICE EMAIL</th>

                                    <th>PERSONAL EMAIL</th>



                                    <th>ROLE</th>



                                    <th>STATUS</th>



                                    <th>Actions</th>

                                </tr>

                            </thead>

                            <tbody></tbody>

                        </table>



                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

      <script>

        var jq = jQuery.noConflict();



        jq(document).ready(function() {



            jq('#staff').DataTable({



                processing: true,



                serverSide: true,



                ajax: "{{ route('today_absent_employee_data') }}",



                columns: [{



                        data: 'DT_RowIndex',



                        name: 'DT_RowIndex',



                        orderable: false,



                        searchable: false



                    },



                    {



                        data: 'name',



                        name: 'name'



                    },



                    {



                        data: 'task',



                        name: 'task',



                        orderable: false,



                        searchable: false



                    },



                    {



                        data: 'report',



                        name: 'report',



                        orderable: false,



                        searchable: false



                    },







                    {



                        data: 'tasks_count',



                        orderable: false,



                        searchable: false



                    },







                    {



                        data: 'mobile',



                        name: 'mobile'



                    },



                    {



                        data: 'email',



                        name: 'email'



                    },

                    {



                        data: 'personal_email',



                        name: 'personal_email'



                    },



                    {



                        data: 'designation',



                        name: 'designation'



                    },



                    {



                        data: 'is_active',



                        name: 'is_active'



                    },



                    {



                        data: 'actions',



                        name: 'actions',



                        orderable: false,



                        searchable: false



                    },







                ],



                order: [



                    [1, 'asc']



                ],



                lengthMenu: [



                    [10, 25, 50, 100, -1],



                    ["10", "25", "50", "100", "All"]



                ],



                language: {



                    search: "",



                    searchPlaceholder: "Search",



                    lengthMenu: "_MENU_"



                }



            });



        });







        function setDeleteId(button) {



            var staffId = button.getAttribute('data-id');



            document.getElementById('deleteId').value = staffId;



        }







        document.getElementById('submitDeleteBtn').addEventListener('click', function(e) {



            e.preventDefault();



            this.disabled = true;



            this.innerText = 'Deleting...';



            document.getElementById('deleteForm').submit();



        });







        //toggle_status



        function setToggleId(button) {



            const staffId = button.getAttribute('data-id');



            const isActive = button.getAttribute('data-active') === '1';







            document.getElementById('toggle_staff_id').value = staffId;







            const statusText = document.getElementById('toggle_status_text');



            const submitBtn = document.getElementById('submit_toggle');







            if (isActive) {



                statusText.textContent = "Are you sure you want to block this staff?";







            } else {



                statusText.textContent = "Are you sure you want to unblock this staff?";







            }



            const icon = document.querySelector(`#toggle_icon_${staffId}`);



            if (icon) {



                icon.setAttribute('fill', isActive ? '#FF0000' : '#28a745');



                icon.setAttribute('fill-opacity', '1');



            }



        }







        document.getElementById('ToggleForm').addEventListener('submit', function() {



            const btn = document.getElementById('submit_toggle');



            btn.disabled = true;



            btn.innerText = 'Processing...';



        });







        document.getElementById("exp").addEventListener("click", function() {



            let table = document.getElementById("staff");



            let rows = Array.from(table.querySelectorAll("tr"));



            let csv = rows.map(row => {



                let cells = Array.from(row.querySelectorAll("th, td"));



                return cells.map(cell => `"${cell.innerText}"`).join(",");



            }).join("\n");



            let blob = new Blob([csv], {



                type: "text/csv"



            });



            let url = URL.createObjectURL(blob);



            let a = document.createElement("a");



            a.href = url;



            a.download = "modules.csv"; // File name



            a.click();



            URL.revokeObjectURL(url);



        });

    </script>



    <script>

        document.addEventListener("DOMContentLoaded", function() {







            const weekSelect = document.getElementById('weekSelect');



            const totalWeeksBadge = document.getElementById('totalWeeksBadge');







            const year = new Date().getFullYear();







            // Get ISO week number



            function getISOWeek(date) {



                const tempDate = new Date(date);



                tempDate.setHours(0, 0, 0, 0);



                tempDate.setDate(tempDate.getDate() + 3 - (tempDate.getDay() + 6) % 7);



                const week1 = new Date(tempDate.getFullYear(), 0, 4);



                return 1 + Math.round(



                    ((tempDate - week1) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7



                );



            }







            // Get Monday of a given ISO week



            function getDateOfISOWeek(week, year) {



                const simple = new Date(year, 0, 1 + (week - 1) * 7);



                const dow = simple.getDay();



                const ISOweekStart = simple;



                if (dow <= 4)



                    ISOweekStart.setDate(simple.getDate() - simple.getDay() + 1);



                else



                    ISOweekStart.setDate(simple.getDate() + 8 - simple.getDay());



                return ISOweekStart;



            }







            // Get total ISO weeks in a year



            function getTotalWeeks(year) {



                const d = new Date(year, 11, 31);



                return getISOWeek(d);



            }







            const totalWeeks = getTotalWeeks(year);



            totalWeeksBadge.innerHTML = `Total Weeks: <strong>${totalWeeks}</strong>`;







            const currentWeek = getISOWeek(new Date());







            for (let week = 1; week <= totalWeeks; week++) {



                const startDate = getDateOfISOWeek(week, year);



                const endDate = new Date(startDate);



                endDate.setDate(startDate.getDate() + 6);







                const format = (date) =>



                    date.toLocaleDateString('en-GB', {



                        day: '2-digit',



                        month: 'short'



                    });







                const option = document.createElement('option');



                option.value = `${year}-W${week.toString().padStart(2, '0')}`;



                option.textContent =

                    `W${week.toString().padStart(2, '0')} (${format(startDate)} – ${format(endDate)})`;







                if (week === currentWeek) {



                    option.selected = true;



                }







                weekSelect.appendChild(option);



            }







        });

    </script>



    <script>

        const progressData = [{



                name: 'UI',



                spent: 12,



                total: 20



            },



            {



                name: 'Frontend',



                spent: 28,



                total: 40



            },



            {



                name: 'Backend',



                spent: 35,



                total: 30



            },



            {



                name: 'App Development',



                spent: 18,



                total: 25



            },



            {



                name: 'Testing',



                spent: 6,



                total: 15



            }



        ];







        function getProgressColor(percent) {



            if (percent <= 70) return 'bg-success';



            if (percent <= 100) return 'bg-warning';



            return 'bg-danger';



        }







        function renderProgressGrid() {



            const grid = document.getElementById('progressGrid');



            grid.innerHTML = '';







            progressData.forEach(item => {



                const percent = Math.round((item.spent / item.total) * 100);



                const width = Math.min(percent, 100);



                const color = getProgressColor(percent);







                const col = `



                <div class="col-md-6 mb-3">



                    <div class="p-2 border rounded-3 h-100">



                        <div class="d-flex justify-content-between mb-1">



                            <small class="fw-semibold">${item.name}</small>



                            <small class="text-muted">${item.spent}h / ${item.total}h (${percent}%)</small>



                        </div>







                        <div class="progress" style="height: 8px;">



                            <div class="progress-bar ${color}"



                                 role="progressbar"



                                 style="width: ${width}%">



                            </div>



                        </div>



                    </div>



                </div>



            `;







                grid.insertAdjacentHTML('beforeend', col);



            });



        }







        renderProgressGrid();

    </script>



@endsection