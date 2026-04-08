@extends('layouts.app')

@section('title','Fee Concession')

@section('page-title','Fee Concession')


@push('styles')
<style>
        /* Specific Inner Page styles */
        .manager-card {
            max-width: 1400px;
            width: 100%;
            background: white;
            border-radius: 36px;
            box-shadow: var(--shadow);
            padding: 28px 30px;
            transition: var(--transition);
        }
        .manager-card:hover {
            box-shadow: var(--shadow-hover);
        }
        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .header-title h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }
        .header-title i {
            font-size: 2rem;
            color: var(--primary-orange);
        }
        .header-sub {
            color: var(--text-muted);
            margin-bottom: 24px;
            margin-left: 10px;
        }
        /* Premium Unified Filter Bar */
        .premium-filter-bar { 
            background: #f8fcff; 
            padding: 10px; 
            border-radius: 60px; 
            margin-bottom: 20px; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            border: 1.5px solid #e0eafc;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            flex-wrap: wrap;
        }
        .filter-item-wrap {
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #e0e7f0;
            border-radius: 40px;
            padding: 4px 4px 4px 18px;
            transition: var(--transition);
            flex: 1;
            min-width: 300px;
        }
        .filter-item-wrap:focus-within { border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(61,132,245,0.1); }
        .filter-item-wrap i { color: var(--text-muted); font-size: 0.9rem; margin-right: 12px; }
        .filter-item-wrap select, .filter-item-wrap input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.85rem;
            padding: 10px 0;
            color: var(--text-dark);
            width: 100%;
            font-weight: 500;
        }
        .select-divider {
            min-width: 130px;
            border-right: 1px solid #eee;
            padding-right: 12px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .search-btn-premium {
            background: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 40px;
            padding: 12px 30px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            white-space: nowrap;
        }
        .search-btn-premium:hover { background: var(--primary-orange); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(255,145,59,0.2); }

        .action-bar-top {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn {
            background: white;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
            white-space: nowrap;
        }
        .btn i {
            font-size: 1rem;
        }
        .btn:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }
        .btn-orange {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
        }
        .btn-orange:hover {
            background: white;
            color: var(--primary-orange);
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--text-muted);
            color: var(--text-muted);
        }
        .btn-outline:hover {
            background: var(--text-muted);
            color: white;
        }
        /* table */
        .table-wrapper {
            overflow-x: auto;
            margin-bottom: 24px;
            border-radius: 24px;
            background: white;
            box-shadow: var(--shadow);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1300px;
        }
        th {
            background: var(--primary-blue);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 14px 8px;
            text-align: left;
            border: 1px solid #3a6fa8;
        }
        td {
            padding: 14px 8px;
            border: 1px solid #d9e2ec;
            color: var(--text-dark);
            font-size: 0.9rem;
            vertical-align: middle;
        }
        tr:hover td {
            background: #f8fcff;
        }
        .action-icons {
            display: flex;
            gap: 12px;
            color: var(--text-muted);
        }
        .action-icons i {
            cursor: pointer;
            font-size: 1.1rem;
            transition: 0.2s;
        }
        .action-icons i:hover {
            transform: scale(1.2);
        }
        .edit-icon { color: #f39c12; }
        .delete-icon { color: #e74c3c; }
        .total-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 20px;
            font-weight: 600;
            color: var(--primary-blue);
        }
        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .page-btn {
            width: 38px;
            height: 38px;
            border-radius: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #e2eaf5;
            color: var(--primary-blue);
            font-weight: 600;
            transition: 0.2s;
            cursor: pointer;
        }
        .page-btn.active-page {
            background: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }
        .page-btn:hover:not(.active-page) {
            background: var(--blue-light);
            border-color: var(--primary-blue);
        }
        /* MODAL STYLES (compact) */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(3px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 16px;
        }
        .modal-overlay.show {
            display: flex;
        }
        .modal-container {
            background: white;
            border-radius: 24px;
            max-width: 520px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.25);
            padding: 12px 16px; /* reduced padding */
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px; /* reduced */
        }
        .modal-header h3 {
            font-size: 1.3rem; /* slightly smaller */
            color: var(--primary-blue);
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 1.6rem;
            cursor: pointer;
            color: var(--text-muted);
            line-height: 1;
        }
        .student-badge {
            background: var(--blue-light);
            padding: 6px 10px; /* reduced */
            border-radius: 30px;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary-blue);
            text-align: center;
            font-size: 0.85rem;
        }
        .readonly-info {
            background: #f0f7ff;
            border-radius: 14px;
            padding: 10px; /* reduced */
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .readonly-info .info-item strong {
            color: var(--primary-blue);
            display: block;
            font-size: 0.6rem; /* smaller */
            text-transform: uppercase;
        }
        .readonly-info .info-item span {
            font-size: 0.8rem; /* smaller */
            color: var(--text-dark);
        }
        .form-group {
            margin-bottom: 8px; /* reduced */
        }
        .form-group label {
            font-size: 0.65rem; /* smaller */
            text-transform: uppercase;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 2px;
            display: block;
        }
        .form-group label.required::after {
            content: " *";
            color: #e53e3e;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            background: var(--bg-light);
            border: 1px solid #e0e7f0;
            border-radius: 12px;
            padding: 6px 10px; /* reduced */
            font-size: 0.8rem;
            outline: none;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
        }
        .radio-group {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 4px;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            color: var(--text-dark);
            text-transform: none;
        }
        .radio-group input {
            width: auto;
            accent-color: var(--primary-orange);
        }
        /* Month checkbox grid */
        .month-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }
        .month-header label {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .month-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columns to reduce rows */
            gap: 6px;
            background: var(--bg-light);
            padding: 8px;
            border-radius: 12px;
            margin-bottom: 8px;
        }
        .month-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem; /* smaller */
        }
        .month-item input {
            accent-color: var(--primary-orange);
            width: 14px;
            height: 14px;
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 12px;
        }
        .modal-actions .btn {
            padding: 6px 16px; /* smaller */
            font-size: 0.8rem;
        }
        /* mobile adjustments */
        @media (max-width: 700px) {
            .manager-card {
                padding: 20px 16px;
            }
            .filter-tabs {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                padding: 18px;
            }
            .filter-group {
                min-width: 0;
            }
            .search-row {
                flex-wrap: nowrap;
            }
            .search-field {
                flex: 1 1 auto;
            }
            .search-row .btn {
                flex-shrink: 0;
            }
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .action-buttons {
                justify-content: center;
            }
            .action-buttons .btn {
                flex: 1;
                justify-content: center;
            }
            .total-info {
                flex-direction: column;
                align-items: flex-start;
            }
            .readonly-info {
                grid-template-columns: 1fr;
            }
            .month-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 480px) {
            .action-buttons {
                flex-direction: column;
            }
            .action-buttons .btn {
                width: 100%;
            }
            .search-row {
                flex-wrap: wrap;
            }
            .search-row .btn {
                width: 100%;
            }
        }
    
    </style>
@endpush
@section('content')

<div class="manager-card">

<div class="header-title">
<i class="fas fa-hand-holding-usd"></i>
<h1>Fee Concession Manager</h1>
</div>

<div class="header-sub">
Manage student fee concessions efficiently.
</div>

<!-- Unified Premium Filter Bar -->
<form method="GET" action="{{ route('admin.fee-concession.index') }}" class="premium-filter-bar">
    <div class="filter-item-wrap">
        <i class="fas fa-search"></i>
        
        <div class="select-divider" style="min-width: 120px;">
            <select name="class">
                <option value="">Class</option>
                @foreach($globalClasses as $cls)
                <option value="{{ $cls->id }}" {{ request('class') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="select-divider" style="min-width: 100px;">
            <select name="section">
                <option value="">Sec</option>
                @foreach($sections as $sec)
                <option value="{{ $sec->id }}" {{ request('section') == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="select-divider" style="min-width: 110px;">
            <select name="month">
                <option value="">Month</option>
                @foreach(['April','May','June','July','August','September','October','November','December','January','February','March'] as $m)
                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>

        <div class="select-divider" style="min-width: 130px;">
            <select name="fee_name">
                <option value="">Fee Type</option>
                @foreach($feeTypes as $ft)
                <option value="{{ $ft->id }}" {{ request('fee_name') == $ft->id ? 'selected' : '' }}>{{ $ft->name }}</option>
                @endforeach
            </select>
        </div>

        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search name, reg no...">
    </div>
    
    <button type="submit" class="search-btn-premium">
        <i class="fas fa-search"></i> Search
    </button>
</form>

<div class="action-bar-top">
    <div class="action-buttons">
        <button class="btn btn-orange" id="addSingleBtn">
            <i class="fas fa-plus-circle"></i> Add New Concession
        </button>
        <button class="btn btn-outline" id="addBulkBtn">
            <i class="fas fa-layer-group"></i> Bulk Concession
        </button>
    </div>
</div>

<!-- Table -->
<div class="table-wrapper">

<table>

<thead>

<tr>
<th>#</th>
<th>RegNo</th>
<th>Student Name</th>
<th>Father Name</th>
<th>Class</th>
<th>Section</th>
<th>Fee Name</th>
<th>Month</th>
<th>Amount</th>
<th>Remarks</th>
<th>Action</th>
</tr>

</thead>

<tbody>

@foreach($concessions as $key => $row)

<tr>

<td>{{ $key+1 }}</td>
<td>{{ $row->student->admission_no }}</td>
<td>{{ $row->student->student_name }}</td>
<td>{{ $row->student->father_name ?? '-' }}</td>
<td>{{ $row->student->class->name ?? '-' }}</td>
<td>{{ $row->student->section->name ?? '-' }}</td>
<td>{{ $row->feeType->name }}</td>
<td>{{ $row->month }}</td>
<td>{{ $row->amount }}</td>
<td>{{ $row->remarks }}</td>

<td class="action-icons">

<a href="{{ route('fee.concession.edit',$row->id) }}">
<i class="fas fa-edit edit-icon"></i>
</a>

<form action="{{ route('fee.concession.delete',$row->id) }}"
method="POST"
style="display:inline">

@csrf
@method('DELETE')

<button type="submit" style="border:none;background:none">
<i class="fas fa-trash delete-icon"></i>
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<div class="total-info">

<span>
<strong>Total Concessions:</strong>
{{ $concessions->count() }}
</span>

</div>

{{ $concessions->links() }}

</div>

@endsection

@push('scripts')
<script>
    (function() {
        // ---------- Data ----------
        let concessions = [
            { id: 1, regNo: 'ITest001', studentName: 'Test001', father: 'Mr. Sharma', cssr: 'I - A - 2025-26 - 2', feeName: 'Tuition Fee', month: 'October', amount: 101, remarks: 'Approval:40' },
            { id: 2, regNo: 'ITest001', studentName: 'Test001', father: 'Mr. Sharma', cssr: 'I - A - 2025-26 - 2', feeName: 'Tuition Fee', month: 'November', amount: 101, remarks: 'Approval:39' },
            { id: 3, regNo: 'ITest001', studentName: 'Test001', father: 'Mr. Sharma', cssr: 'I - A - 2025-26 - 2', feeName: 'Tuition Fee', month: 'April', amount: 101, remarks: 'Approval:38' },
            { id: 4, regNo: 'ITest001', studentName: 'Test001', father: 'Mr. Sharma', cssr: 'I - A - 2025-26 - 2', feeName: 'Tuition Fee', month: 'May', amount: 101, remarks: 'Approval:37' },
            { id: 5, regNo: 'ITest001', studentName: 'Test001', father: 'Mr. Sharma', cssr: 'I - A - 2025-26 - 2', feeName: 'Tuition Fee', month: 'August', amount: 101, remarks: 'Approval:36' }
        ];
        let nextId = 6;

        // Month list
        const monthNames = ["April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March"];

        // Render table
        const tbody = document.getElementById('tableBody');
        function renderTable() {
            let html = '';
            concessions.forEach(c => {
                html += `<tr data-id="${c.id}">
                    <td>${c.id}</td>
                    <td>${c.regNo}</td>
                    <td>${c.studentName}</td>
                    <td>${c.father}</td>
                    <td>${c.cssr}</td>
                    <td>${c.feeName}</td>
                    <td>${c.month}</td>
                    <td>${c.amount}</td>
                    <td>${c.remarks}</td>
                    <td class="action-icons">
                        <i class="fas fa-edit edit-icon" title="Edit" data-id="${c.id}"></i>
                        <i class="fas fa-trash delete-icon" title="Delete" data-id="${c.id}"></i>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;

            // Attach edit events
            document.querySelectorAll('.fa-edit').forEach(icon => {
                icon.addEventListener('click', (e) => {
                    const id = parseInt(e.target.dataset.id);
                    openEditModal(id);
                });
            });

            // Attach delete events
            document.querySelectorAll('.fa-trash').forEach(icon => {
                icon.addEventListener('click', (e) => {
                    const id = parseInt(e.target.dataset.id);
                    if (confirm('Delete this concession?')) {
                        concessions = concessions.filter(c => c.id !== id);
                        renderTable();
                    }
                });
            });
        }

        // Populate month grids
        function populateMonthGrid(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            let html = '';
            monthNames.forEach(month => {
                html += `<label class="month-item"><input type="checkbox" value="${month}"> ${month}</label>`;
            });
            container.innerHTML = html;
        }

        // Select All functionality for single modal
        function setupSelectAll(selectAllId, gridId) {
            const selectAll = document.getElementById(selectAllId);
            if (!selectAll) return;
            selectAll.addEventListener('change', function(e) {
                const checkboxes = document.querySelectorAll(`#${gridId} input[type="checkbox"]`);
                checkboxes.forEach(cb => cb.checked = e.target.checked);
            });
        }

        // ---------- Modal logic ----------
        const singleModal = document.getElementById('singleModal');
        const singleModalTitle = document.getElementById('singleModalTitle');
        const addSingle = document.getElementById('addSingleBtn');
        const closeSingle = document.getElementById('closeSingleModal');
        const cancelSingle = document.getElementById('cancelSingle');
        const saveSingle = document.getElementById('saveSingle');
        const singleAmount = document.getElementById('singleAmount');
        const concessionRadios = document.querySelectorAll('input[name="concessionType"]');
        const studentSearch = document.getElementById('studentSearch');
        const studentBadge = document.getElementById('studentBadge');
        const studentReadonly = document.getElementById('studentReadonly');
        const singleFeeName = document.getElementById('singleFeeName');
        const singleRemarks = document.getElementById('singleRemarks');
        const singleMonthGrid = document.getElementById('singleMonthGrid');

        // Handle radio change: if "Concession All Fee" is checked, disable amount input
        concessionRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                if (e.target.value === 'full') {
                    singleAmount.disabled = true;
                    singleAmount.value = 0;
                } else {
                    singleAmount.disabled = false;
                }
            });
        });

        let editingId = null; // null for new, otherwise id

        function openEditModal(id) {
            const concession = concessions.find(c => c.id === id);
            if (!concession) return;
            editingId = id;
            singleModalTitle.innerText = 'Edit Fee Concession';
            // Fill fields with concession data (demo)
            studentSearch.value = `${concession.studentName} [${concession.cssr.split(' - ')[0]}] - ${concession.regNo}`;
            studentBadge.innerText = `${concession.studentName} [${concession.cssr.split(' - ')[0]}] - ${concession.regNo}`;
            // Update readonly info
            studentReadonly.innerHTML = `
                <div class="info-item"><strong>Student's Name</strong><span>${concession.studentName}</span></div>
                <div class="info-item"><strong>Father's Name</strong><span>${concession.father}</span></div>
                <div class="info-item"><strong>Class</strong><span>${concession.cssr.split(' - ')[0]}</span></div>
                <div class="info-item"><strong>Section</strong><span>${concession.cssr.split(' - ')[1]}</span></div>
            `;
            singleFeeName.value = concession.feeName;
            // Pre-check the month (single month)
            document.querySelectorAll('#singleMonthGrid input').forEach(cb => {
                cb.checked = (cb.value === concession.month);
            });
            singleAmount.value = concession.amount;
            singleAmount.disabled = false;
            // Set radio to partial
            document.querySelector('input[name="concessionType"][value="partial"]').checked = true;
            singleRemarks.value = concession.remarks;
            singleModal.classList.add('show');
        }

        function resetSingleModal() {
            editingId = null;
            singleModalTitle.innerText = 'Create New Fee Concession';
            studentSearch.value = 'Aman [II-A] - PAT10';
            studentBadge.innerText = 'Aman [II-A] - PAT10';
            studentReadonly.innerHTML = `
                <div class="info-item"><strong>Student's Name</strong><span>Aman</span></div>
                <div class="info-item"><strong>Father's Name</strong><span>—</span></div>
                <div class="info-item"><strong>Class</strong><span>II</span></div>
                <div class="info-item"><strong>Section</strong><span>A</span></div>
            `;
            singleFeeName.value = '---Select Fee---';
            document.querySelectorAll('#singleMonthGrid input').forEach(cb => cb.checked = false);
            document.getElementById('singleSelectAllMonths').checked = false;
            singleAmount.value = 0;
            singleAmount.disabled = false;
            document.querySelector('input[name="concessionType"][value="partial"]').checked = true;
            singleRemarks.value = '';
        }

        addSingle.addEventListener('click', () => {
            resetSingleModal();
            singleModal.classList.add('show');
        });

        closeSingle.addEventListener('click', () => singleModal.classList.remove('show'));
        cancelSingle.addEventListener('click', () => singleModal.classList.remove('show'));

        saveSingle.addEventListener('click', () => {
            const selectedMonths = [];
            document.querySelectorAll('#singleMonthGrid input:checked').forEach(cb => {
                selectedMonths.push(cb.value);
            });
            if (selectedMonths.length === 0) {
                alert('Please select at least one month.');
                return;
            }
            const isFull = document.querySelector('input[name="concessionType"]:checked').value === 'full';
            const amount = isFull ? 0 : parseFloat(singleAmount.value) || 0;
            // In a real app, you'd collect all data and save
            if (editingId) {
                alert('Concession updated (demo)');
            } else {
                alert('New concession created for months: ' + selectedMonths.join(', ') + (isFull ? ' (Full concession)' : ''));
            }
            singleModal.classList.remove('show');
        });

        // Bulk modal
        const bulkModal = document.getElementById('bulkModal');
        const addBulk = document.getElementById('addBulkBtn');
        const closeBulk = document.getElementById('closeBulkModal');
        const cancelBulk = document.getElementById('cancelBulk');
        const applyBulk = document.getElementById('applyBulk');

        addBulk.addEventListener('click', () => {
            populateMonthGrid('bulkMonthGrid');
            document.getElementById('bulkSelectAllMonths').checked = false;
            bulkModal.classList.add('show');
        });

        closeBulk.addEventListener('click', () => bulkModal.classList.remove('show'));
        cancelBulk.addEventListener('click', () => bulkModal.classList.remove('show'));
        applyBulk.addEventListener('click', () => {
            const selectedMonths = [];
            document.querySelectorAll('#bulkMonthGrid input:checked').forEach(cb => {
                selectedMonths.push(cb.value);
            });
            if (selectedMonths.length === 0) {
                alert('Please select at least one month.');
                return;
            }
            alert('Bulk concession applied for months: ' + selectedMonths.join(', ') + ' (demo)');
            bulkModal.classList.remove('show');
        });

        // Close modals on outside click
        window.addEventListener('click', (e) => {
            if (e.target === singleModal) singleModal.classList.remove('show');
            if (e.target === bulkModal) bulkModal.classList.remove('show');
        });

        // Demo search button
        document.querySelector('.search-row .btn').addEventListener('click', (e) => {
            e.preventDefault();
            alert('Search clicked (demo)');
        });

        // Demo pagination
        document.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.classList.contains('active-page')) return;
                document.querySelectorAll('.page-btn').forEach(p => p.classList.remove('active-page'));
                this.classList.add('active-page');
                alert('Page changed (demo)');
            });
        });

        // Initial render and month grid population
        renderTable();
        populateMonthGrid('singleMonthGrid');
        populateMonthGrid('bulkMonthGrid');
        setupSelectAll('singleSelectAllMonths', 'singleMonthGrid');
        setupSelectAll('bulkSelectAllMonths', 'bulkMonthGrid');
    })();
</script>
       
@endpush