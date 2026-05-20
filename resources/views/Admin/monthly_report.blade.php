@extends('Admin.layout')
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>

    :root {
        --primary: #E21F24;
        --primary-dark: #c91b20;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --purple: #8b5cf6;
        --bg-primary: #f8fafc;
        --bg-secondary: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(226, 31, 36, 0.15);
        --shadow-lg: 0 8px 25px rgba(226, 31, 36, 0.2);
        --gradient-primary: linear-gradient(135deg, #E21F24 0%, #c91b20 50%, #a8181d 100%);
        --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --gradient-gold: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    * {
        box-sizing: border-box;
    }
    .container {
        max-width: 1600px;
        margin: 0 auto;
    }
    /* Enhanced Header */
    .main-header {
        background: var(--bg-secondary);
        padding: 35px;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        margin-bottom: 25px;
        border: 1px solid rgba(226, 31, 36, 0.1);
        position: relative;
        overflow: hidden;
    }
    .main-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -30%;
        width: 300px;
        height: 300px;
        background: var(--gradient-primary);
        border-radius: 50%;
        opacity: 0.1;
        animation: pulse 4s ease-in-out infinite;
    }
    @keyframes pulse {
        0%,
        100% {
            transform: scale(1);
            opacity: 0.1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.15;
        }
    }
    .header-title {
        font-size: 2.3rem;
        font-weight: 800;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    /* Compact Filter Panel */
    .filter-panel {
        background: var(--bg-secondary);
        padding: 25px;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        margin-bottom: 25px;
        border: 1px solid rgba(226, 31, 36, 0.1);
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        align-items: end;
    }
    /* Enhanced Table */
    .table-container {
        background: var(--bg-secondary);
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid rgba(226, 31, 36, 0.1);
    }
    .table-header {
        background: linear-gradient(135deg, #fef7f7 0%, #fee2e2 100%);
        padding: 20px 25px;
        border-bottom: 2px solid rgba(226, 31, 36, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .scroll-wrapper {
        overflow: auto;
        max-height: 75vh;
        border-top: 1px solid var(--border);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1400px;
    }
    th {
        padding: 16px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-secondary);
        border-bottom: 2px solid rgba(226, 31, 36, 0.1);
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
    td {
        padding: 18px 20px;
        border-bottom: 1px solid #f9f5f5;
        vertical-align: middle;
        font-size: 0.9rem;
        height: 75px;
        width: 250px;
    }
    /* Status Cells */
    .status-cell {
        border-radius: 14px;
        padding: 14px 18px;
        min-height: 55px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-weight: 600;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        border: 2px solid transparent;
        width: 200px;
    }
    .status-cell:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .status-working {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-left: 5px solid var(--success);
        color: #065f46;
    }
    .status-leave {
        background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
        border-left: 5px solid var(--danger);
        color: #991b1b;
    }
    .status-wfh {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-left: 5px solid var(--warning);
        color: #92400e;
    }
    .status-permission {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 5px solid var(--info);
        color: #1e40af;
    }
    .status-empty {
        color: var(--text-secondary);
        font-style: italic;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-left: 5px solid var(--border);
    }
    .daily-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #08a171;
        color: white;
        width: 37px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        box-shadow: var(--shadow-md);
        z-index: 2;
    }
    .date-header {
        background: var(--gradient-primary) !important;
        color: white !important;
        font-weight: 800 !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        border: none !important;
        box-shadow: none !important;
    }
    .date-header .date-format {
        font-size: 1rem;
        font-weight: 700;
        white-space: nowrap;
        /* Prevents line breaks */
        display: inline-block;
    }
    .project-count {
        position: absolute;
        top: -6px;
        right: -6px;
        font-size: 10px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #fff;
    }
    /* Working */
    .count-working {
        background: #18b16f;
    }
    /* Permission */
    .count-permission {
        background: #3b82f6;
    }
    /* WFH */
    .count-wfh {
        background: #f5c542;
        color: #000;
    }
    .date-header .day-name {
        font-size: 0.8rem;
        opacity: 0.95;
        margin-top: 2px;
        white-space: nowrap;
    }
    /* STATS DASHBOARD */
    .stats-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    .stat-card {
        background: #fff;
        padding: 20px;
        border-radius: 14px;
        box-shadow: var(--shadow-md);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .stat-icon {
        font-size: 26px;
        margin-bottom: 8px;
    }
    .stat-number {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 13px;
        color: var(--text-secondary);
    }
    /* COLORS */
    .stat-primary .stat-icon {
        color: #E21F24;
    }
    .stat-danger .stat-icon {
        color: #ef4444;
    }
    .stat-warning .stat-icon {
        color: #f59e0b;
    }
    /* Responsive */
    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
        body {
            padding: 15px;
        }
        .header-title {
            font-size: 1.9rem;
        }
    }
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .fade-in-up {
        animation: fadeInUp 0.6s ease forwards;
    }

</style>
@section('content')
<div class="container">
    <!-- Compact Filter Panel -->
    <div class="filter-panel fade-in-up" style="animation-delay: 0.1s;">
        <div class="filter-grid">
            <div class="form-group">
                <label><i class="fas fa-calendar-day"></i> From Date</label>
                <input type="date" id="fromDate" class="form-control">
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-day"></i> To Date</label>
                <input type="date" id="toDate" class="form-control">
            </div>
            <div class="form-group">
                <label><i class="fas fa-user"></i> Select Staff</label>
                <select id="staffSearch" class="form-control">
                    <option value="">All Staff</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" onclick="generateReport()">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <button class="btn btn-clear" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>
    <div class="stats-dashboard" id="statsGrid"></div>
    <!-- Main Table -->
    <div class="table-container fade-in-up" style="animation-delay: 0.2s;">
        <div class="table-header">
            <h3 class="table-title"><i class="fas fa-table"></i> Daily Work Schedule & Productivity Tracker</h3>
        </div>
        <div class="scroll-wrapper">
            <table id="workTable">
                <thead>
                    <tr id="headerRow"></tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>
</div>
<script>

    const reportData = @json($report);
    const wfhStaff = @json($wfhStaff);
    const leaveStaff = @json($leaveStaff);
    const permissionStaff = @json($permissionStaff);
    let staffMembers = [];
    let rawData = {};
    /* PREPARE DATA */
    reportData.forEach(item => {
        if (item.staff_name && !staffMembers.includes(item.staff_name)) {
            staffMembers.push(item.staff_name);
        }
        if (!rawData[item.work_date]) {
            rawData[item.work_date] = {};
        }
        if (!rawData[item.work_date][item.staff_name]) {
            rawData[item.work_date][item.staff_name] = [];
        }
        rawData[item.work_date][item.staff_name].push(item.project_name);
    });
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        const dayName = date.toLocaleDateString('en-IN', {

            weekday: 'short'

        });
        return {
            formatted: `${day}-${month}-${year}`,
            dayName
        };
    }
    function getLocalDate(date) {
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    /* STAFF DROPDOWN */
    const staffDropdown = document.getElementById("staffSearch");
    staffMembers.forEach(name => {
        const option = document.createElement("option");
        option.value = name;
        option.textContent = name;
        staffDropdown.appendChild(option);
    });
    function generateReport() {
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
        const searchInput = document.getElementById('staffSearch');
        const searchName = searchInput ? searchInput.value.toLowerCase() : '';
        const filteredStaff = staffMembers.filter(name =>
            name.toLowerCase().includes(searchName)
        );
        const headerRow = document.getElementById('headerRow');
        const tableBody = document.getElementById('tableBody');
        headerRow.innerHTML = `<th style="text-align:center">Date</th>`;
        filteredStaff.forEach(name => {
            headerRow.innerHTML += `<th style="text-align:center">${name}</th>`;
        });
        tableBody.innerHTML = '';
        /* ======================
           STATS DASHBOARD
        ====================== */
        let stats = {
            totalProjects: 0,
            leaveDays: 0,
            wfhDays: 0
        };
        let projectSet = new Set();
        reportData.forEach(item => {
            if (item.work_date >= fromDate && item.work_date <= toDate) {
                projectSet.add(item.project_name);
            }
        });
        stats.totalProjects = projectSet.size;
        /* LEAVE COUNT */
        let leaveDates = new Set();
        leaveStaff.forEach(l => {
            if (!l.date) return;
            if (l.date >= fromDate && l.date <= toDate) {
                leaveDates.add(l.date);
            }
        });
        stats.leaveDays = leaveDates.size;
        /* WFH COUNT */
        wfhStaff.forEach(w => {
            if (w.from <= toDate && w.to >= fromDate) {
                let start = new Date(Math.max(new Date(w.from), new Date(fromDate)));
                let end = new Date(Math.min(new Date(w.to), new Date(toDate)));
                for (let d = start; d <= end; d.setDate(d.getDate() + 1)) {
                    if (d.getDay() != 0) {
                        stats.wfhDays++;
                    }
                }
            }
        });
        const statsGrid = document.getElementById("statsGrid");
        statsGrid.innerHTML = `
<div class="stat-card stat-primary" onclick="viewProjects()" style="cursor:pointer">
<div class="stat-icon"><i class="fas fa-tasks"></i></div>
<div class="stat-number">${stats.totalProjects}</div>
<div class="stat-label">Total Projects</div>
</div>
<div class="stat-card stat-warning">
<div class="stat-icon"><i class="fas fa-home"></i></div>
<div class="stat-number">${stats.wfhDays}</div>
<div class="stat-label"> Total WFH</div>
</div>
<div class="stat-card stat-danger">
<div class="stat-icon"><i class="fas fa-umbrella-beach"></i></div>
<div class="stat-number">${stats.leaveDays}</div>
<div class="stat-label">Total Leave</div>
</div>
`;
        /* ======================
           DAILY TABLE
        ====================== */
        let startDate = new Date(fromDate);
        let endDate = new Date(toDate);
        for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
            if (d.getDay() === 0) continue;
            const dateKey = getLocalDate(d);
            const {

                formatted,

                dayName

            } = formatDate(dateKey);
            let projectSet = new Set();
            let novelxSet = new Set();
            filteredStaff.forEach(name => {
                const arr = rawData[dateKey]?.[name] || [];
                arr.forEach(p => {
                    if (p.toLowerCase() === 'novelx') {
                        novelxSet.add(p);
                    } else {
                        projectSet.add(p);
                    }
                });
            });
            const otherProjects = projectSet.size;
            const novelxCount = novelxSet.size;
            let totalProjects = otherProjects;
            if (novelxCount > 0) {
                totalProjects = `${otherProjects}+${novelxCount}`;
            }
            let rowHtml = `
<tr>
<td>
<div class="status-cell date-header">
<span class="date-format">${formatted}</span><br>
<span class="day-name">${dayName}</span>
<div class="daily-count">${totalProjects}</div>
</div>
</td>
`;
            filteredStaff.forEach(name => {
                let dayDataArray = rawData[dateKey]?.[name] || [];
                dayDataArray = [...new Set(dayDataArray)];
                let projectCount = dayDataArray.length;
                let display = '-';
                let cellClass = "status-empty";
                const staffObj = reportData.find(r => r.staff_name === name);
                const staffId = staffObj ? staffObj.staff_id : null;
                /* LEAVE */
                if (leaveStaff.some(l =>

                        l.user_id == staffId &&

                        dateKey >= l.from_date &&

                        dateKey <= l.to_date

                    )) {

                    cellClass = "status-leave";

                    display = "Leave";

                }
                /* WFH */

                else if (wfhStaff.some(w =>
                        w.user_id == staffId &&
                        dateKey >= w.from &&
                        dateKey <= w.to
                    )) {
                    cellClass = "status-wfh";
                    if (dayDataArray.length > 0) {
                        display = "WFH - " + dayDataArray.join(', ');
                    } else {
                        display = "WFH";
                    }
                }
                /* PERMISSION */

                else if (permissionStaff.some(p => p.user_id == staffId && p.date == dateKey)) {
                    cellClass = "status-permission";
                    if (dayDataArray.length > 0) {
                        display = "Permission - " + dayDataArray.join(', ');
                    } else {
                        display = "Permission";
                    }
                }
                /* WORKING */

                else if (dayDataArray.length > 0) {
                    cellClass = "status-working";
                    display = dayDataArray.join(', ');
                }
                let countClass = "count-working";
                if (cellClass === "status-permission") {
                    countClass = "count-permission";
                } else if (cellClass === "status-wfh") {
                    countClass = "count-wfh";
                }
                rowHtml += `
<td>
<div class="status-cell ${cellClass}">
${display}
${projectCount>0 ? `<span class="project-count ${countClass}">${projectCount}</span>`:''}
</div>
</td>
`;
            });
            rowHtml += '</tr>';
            tableBody.innerHTML += rowHtml;
        }
    }
    /* CLEAR FILTER */
    function clearFilters() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        document.getElementById('fromDate').value = getLocalDate(firstDay);
        document.getElementById('toDate').value = getLocalDate(today);
        document.getElementById('staffSearch').value = "";
        generateReport();
    }
    /* AUTO LOAD */
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        document.getElementById('fromDate').value = getLocalDate(firstDay);
        document.getElementById('toDate').value = getLocalDate(today);
        document.getElementById('staffSearch').addEventListener('input', generateReport);
        generateReport();
    });
    function viewProjects() {
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
        window.location.href = `/admin/monthly_project_report?from=${fromDate}&to=${toDate}`;
    }

</script>
@endsection