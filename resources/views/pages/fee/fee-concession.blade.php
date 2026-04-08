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
    .filter-tabs {
        background: #f2f8ff;
        border-radius: 28px;
        padding: 20px 24px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 16px 20px;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 140px;
        flex: 1 1 150px;
    }
    .filter-group label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 4px;
    }
    .filter-group select,
    .filter-group input {
        background: white;
        border: 1px solid #e0e7f0;
        border-radius: 16px;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: var(--text-dark);
        outline: none;
        width: 100%;
    }
    .filter-group select:focus,
    .filter-group input:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
    }
    .search-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }
    .search-field {
        flex: 2 1 250px;
        display: flex;
        flex-direction: column;
    }
    .search-field label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 4px;
    }
    .search-field input {
        background: white;
        border: 1px solid #e0e7f0;
        border-radius: 16px;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: var(--text-dark);
        outline: none;
        width: 100%;
    }
    .search-field input:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
    }
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
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

<!-- Filter tabs matching reference -->
<form method="GET" action="{{ route('admin.fee-concession.index') }}">
<div class="filter-tabs">
    <div class="filter-group">
        <label>Class</label>
        <select name="class">
            <option value="">All Classes</option>
            @foreach($globalClasses as $cls)
            <option value="{{ $cls->id }}" {{ request('class') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label>Section</label>
        <select name="section">
            <option value="">All Sections</option>
            @foreach($sections as $sec)
            <option value="{{ $sec->id }}" {{ request('section') == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label>Fee Month</label>
        <select name="month">
            <option value="">All Months</option>
            @foreach(['April','May','June','July','August','September','October','November','December','January','February','March'] as $m)
            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label>Fee Name</label>
        <select name="fee_name">
            <option value="">All Fees</option>
            @foreach($feeTypes as $ft)
            <option value="{{ $ft->id }}" {{ request('fee_name') == $ft->id ? 'selected' : '' }}>{{ $ft->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Search bar and action buttons -->
<div class="action-bar">
    <div class="search-row">
        <div class="search-field">
            <label>Search Text</label>
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search by name, reg no...">
        </div>
        <button type="submit" class="btn"><i class="fas fa-search"></i> Search</button>
    </div>
    <div class="action-buttons">
        <button type="button" class="btn btn-orange" id="addSingleBtn"><i class="fas fa-plus-circle"></i> Add New Concession</button>
        <button type="button" class="btn btn-outline" id="addBulkBtn"><i class="fas fa-layer-group"></i> Add Concession (Bulk)</button>
    </div>
</div>
</form>

<!-- Table -->
<div class="table-wrap">

<table>
<thead>
<tr>
<th>#</th>
<th>RegNo</th>
<th>Student Name</th>
<th>Father's Name</th>
<th>C-S-S-R</th>
<th>FeeName</th>
<th>MonthName</th>
<th>ConcessionAmt</th>
<th>Remarks</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

@foreach($concessions as $key => $row)
<tr>
<td>{{ $key+1 }}</td>
<td>{{ $row->student->admission_no }}</td>
<td>{{ $row->student->student_name }}</td>
<td>{{ $row->student->father_name ?? '-' }}</td>
<td>{{ ($row->student->classInfo->name ?? '-') }} - {{ ($row->student->sectionInfo->name ?? '-') }}</td>
<td>{{ $row->feeType->name }}</td>
<td>{{ $row->month }}</td>
<td>{{ $row->amount }}</td>
<td>{{ $row->remarks }}</td>
<td class="action-icons">
    <a href="{{ route('fee.concession.edit',$row->id) }}" title="Edit"><i class="fas fa-edit edit-icon"></i></a>
    <form action="{{ route('fee.concession.delete',$row->id) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" style="border:none;background:none" title="Delete"><i class="fas fa-trash delete-icon"></i></button>
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

</div>

{{ $concessions->links() }}

    <!-- MODAL: Single Concession -->
    <div class="modal-overlay" id="singleModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="singleModalTitle">Create New Fee Concession</h3>
                <button type="button" class="close-modal" id="closeSingleModal">&times;</button>
            </div>
            <form id="singleConcessionForm" method="POST" action="{{ route('admin.fee-concession.store') }}">
                @csrf
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="required">Search Student (by Name, Reg No, Mobile)</label>
                    <input type="text" class="form-control" name="student_search" id="studentSearch" placeholder="Type to search..." autocomplete="off">
                    <small style="color:var(--text-muted); font-size:0.7rem;">Select a student from the dropdown</small>
                </div>
                
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="required">Fee Name</label>
                    <select class="form-control" id="singleFeeName" name="fee_name_id" required>
                        <option value="">---Select Fee---</option>
                        @foreach($feeTypes as $ft)
                        <option value="{{ $ft->id }}">{{ $ft->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Month checkboxes with Select All -->
                <div class="form-group" style="margin-bottom:12px;">
                    <div class="month-header">
                        <label class="required">Select Months</label>
                        <label style="margin-left:auto; display:flex; align-items:center; gap:4px;"><input type="checkbox" id="singleSelectAllMonths"> Select All</label>
                    </div>
                    <div class="month-grid" id="singleMonthGrid"></div>
                </div>
                <!-- Concession Amount with radio -->
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="required">Concession Amount</label>
                    <input type="number" step="0.01" value="0.00" class="form-control" name="amount" id="singleAmount" style="margin-bottom:4px;" required>
                    <div class="radio-group">
                        <label><input type="radio" name="concessionType" value="partial" checked> Partial</label>
                        <label><input type="radio" name="concessionType" value="full"> Concession All Fee</label>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:18px;">
                    <label>Remarks</label>
                    <input type="text" class="form-control" name="remarks" placeholder="Enter remarks (optional)" id="singleRemarks">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" id="cancelSingle">Cancel</button>
                    <button type="submit" class="btn btn-orange" id="saveSingle">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Bulk Concession -->
    <div class="modal-overlay" id="bulkModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Bulk Fee Concession</h3>
                <button type="button" class="close-modal" id="closeBulkModal">&times;</button>
            </div>
            <p style="margin-bottom: 10px; color: var(--text-muted); font-size:0.8rem;">Apply concession to multiple students based on criteria.</p>
            <form id="bulkConcessionForm" method="POST" action="{{ route('admin.fee-concession.bulk-store') }}">
                @csrf
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="required">Class</label>
                    <select class="form-control" name="class_id" id="bulkClass" required>
                        <option value="">Select Class</option>
                        @foreach($globalClasses as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="required">Section</label>
                    <select class="form-control" name="section_id" id="bulkSection" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="required">Fee Name</label>
                    <select class="form-control" name="fee_name_id" id="bulkFeeName" required>
                        <option value="">---Select Fee---</option>
                        @foreach($feeTypes as $ft)
                        <option value="{{ $ft->id }}">{{ $ft->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Month checkboxes with Select All -->
                <div class="form-group" style="margin-bottom:12px;">
                    <div class="month-header">
                        <label class="required">Select Months</label>
                        <label style="margin-left:auto; display:flex; align-items:center; gap:4px;"><input type="checkbox" id="bulkSelectAllMonths"> Select All</label>
                    </div>
                    <div class="month-grid" id="bulkMonthGrid"></div>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="required">Concession Amount</label>
                    <input type="number" step="0.01" value="0.00" class="form-control" name="amount" id="bulkAmount" required>
                </div>
                <div class="form-group" style="margin-bottom:18px;">
                    <label>Remarks (optional)</label>
                    <input type="text" class="form-control" name="remarks" placeholder="Common remarks" id="bulkRemarks">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" id="cancelBulk">Cancel</button>
                    <button type="submit" class="btn btn-orange" id="applyBulk">Apply to All</button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    (function() {
        // Month list
        const monthNames = ["April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March"];

        // Populate month grids
        function populateMonthGrid(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            let html = '';
            monthNames.forEach(month => {
                html += `<label class="month-item"><input type="checkbox" name="months[]" value="${month}"> ${month}</label>`;
            });
            container.innerHTML = html;
        }

        // Select All functionality
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
        const singleAmount = document.getElementById('singleAmount');
        const concessionRadios = document.querySelectorAll('input[name="concessionType"]');

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

        function resetSingleModal() {
            document.getElementById('singleConcessionForm').reset();
            singleModalTitle.innerText = 'Create New Fee Concession';
            document.querySelectorAll('#singleMonthGrid input[type="checkbox"]').forEach(cb => cb.checked = false);
            document.getElementById('singleSelectAllMonths').checked = false;
            singleAmount.disabled = false;
        }

        if(addSingle) {
            addSingle.addEventListener('click', () => {
                resetSingleModal();
                singleModal.classList.add('open');
            });
        }

        if(closeSingle) closeSingle.addEventListener('click', () => singleModal.classList.remove('open'));
        if(cancelSingle) cancelSingle.addEventListener('click', () => singleModal.classList.remove('open'));

        // Delete confirmation
        document.querySelectorAll('.delete-icon').forEach(icon => {
            icon.addEventListener('click', (e) => {
                if (confirm('Delete this concession?')) {
                    e.target.closest('form').submit();
                }
            });
        });

        // Bulk modal
        const bulkModal = document.getElementById('bulkModal');
        const addBulk = document.getElementById('addBulkBtn');
        const closeBulk = document.getElementById('closeBulkModal');
        const cancelBulk = document.getElementById('cancelBulk');

        if(addBulk) {
            addBulk.addEventListener('click', () => {
                document.getElementById('bulkConcessionForm').reset();
                document.querySelectorAll('#bulkMonthGrid input[type="checkbox"]').forEach(cb => cb.checked = false);
                document.getElementById('bulkSelectAllMonths').checked = false;
                bulkModal.classList.add('open');
            });
        }

        if(closeBulk) closeBulk.addEventListener('click', () => bulkModal.classList.remove('open'));
        if(cancelBulk) cancelBulk.addEventListener('click', () => bulkModal.classList.remove('open'));

        // Close modals on outside click
        window.addEventListener('click', (e) => {
            if (e.target === singleModal) singleModal.classList.remove('open');
            if (e.target === bulkModal) bulkModal.classList.remove('open');
        });

        // Initial render and month grid population
        populateMonthGrid('singleMonthGrid');
        populateMonthGrid('bulkMonthGrid');
        setupSelectAll('singleSelectAllMonths', 'singleMonthGrid');
        setupSelectAll('bulkSelectAllMonths', 'bulkMonthGrid');
    })();
</script>
       
@endpush