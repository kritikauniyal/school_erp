@extends('layouts.app')

@section('title', 'Registration Manager')
@section('page_icon', 'fas fa-clipboard-list')

@push('styles')
<style>
    
        .enquiry-container {
            width: 100%;
            padding: 0;
        }
        .section-heading {
            margin-bottom: 20px;
        }
        .section-heading h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-heading h2 i {
            color: var(--primary-orange);
        }
        .section-heading p {
            color: var(--text-muted);
            margin-left: 40px;
        }

        /* filter panel */
        .filter-panel {
            background: white;
            border-radius: 28px;
            padding: 20px 24px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 16px 20px;
            transition: var(--transition);
        }
        .filter-panel:hover {
            box-shadow: var(--shadow-hover);
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 160px;
            flex: 1 1 170px;
        }
        .filter-group label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }
        .filter-group input,
        .filter-group select {
            background: var(--bg-light);
            border: 1px solid #e0e7f0;
            border-radius: 16px;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: var(--text-dark);
            outline: none;
            width: 100%;
        }
        .filter-group input:focus,
        .filter-group select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
        }
        .filter-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn {
            background: white;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.02);
            text-decoration: none;
        }
        .btn i {
            font-size: 1rem;
        }
        .btn:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
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

        /* tabs */
        .status-tabs {
            display: flex;
            gap: 2px;
            background: white;
            padding: 6px;
            border-radius: 50px;
            box-shadow: var(--shadow);
            width: fit-content;
            margin-bottom: 24px;
        }
        .status-tab {
            padding: 8px 28px;
            border-radius: 40px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: 0.2s;
        }
        .status-tab.active {
            background: var(--primary-blue);
            color: white;
        }
        .status-tab:not(.active):hover {
            background: var(--blue-light);
            color: var(--primary-blue);
        }

        /* main table */
        .table-card {
            background: white;
            border-radius: 28px;
            padding: 20px;
            box-shadow: var(--shadow);
            overflow-x: auto;
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }
        th, td {
            border: 1px solid #d9e2ec;
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f8fcff;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--primary-blue);
            text-transform: uppercase;
        }
        td {
            color: var(--text-dark);
            font-size: 0.9rem;
        }
        tr:hover td {
            background-color: #f2f8ff;
        }
        .action-icons {
            display: flex;
            gap: 12px;
            white-space: nowrap;
        }
        .action-icons i {
            cursor: pointer;
            font-size: 1.1rem;
            transition: transform 0.2s;
        }
        .action-icons i:nth-child(1) { color: #2a86da; } /* view */
        .action-icons i:nth-child(2) { color: #f39c12; } /* edit */
        .action-icons i:nth-child(3) { color: #e74c3c; } /* delete */
        .action-icons i:nth-child(4) { color: #27ae60; } /* print */
        .action-icons i:hover {
            transform: scale(1.2);
        }
        .status-badge {
            background: var(--orange-light);
            color: var(--primary-orange);
            padding: 4px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-block;
            cursor: pointer;
        }
        .status-badge.confirmed {
            background: #d4edda;
            color: #155724;
        }

        /* pagination */
        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            background: white;
            border-radius: 60px;
            padding: 12px 24px;
            box-shadow: var(--shadow);
        }
        .pagination-info {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .pagination-controls {
            display: flex;
            gap: 8px;
        }
        .page-btn {
            width: 40px;
            height: 40px;
            border-radius: 40px;
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
        .page-size {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
        }
        .page-size select {
            background: var(--bg-light);
            border: 1px solid #dae2ec;
            border-radius: 30px;
            padding: 8px 16px;
        }

        /* ===== MODAL STYLES (reused) ===== */
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
        .modal-overlay.open, .modal-overlay.open, .modal-overlay.show {
            display: flex;
        }
        .modal-container {
            background: white;
            border-radius: 28px;
            max-width: 720px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.25);
            animation: modalPop 0.25s ease;
            display: flex;
            flex-direction: column;
        }
        @keyframes modalPop {
            0% { opacity: 0; transform: scale(0.96); }
            100% { opacity: 1; transform: scale(1); }
        }
        .modal-header {
            padding: 22px 28px 10px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4a90e2; /* Matching the screenshot blue */
        }
        .modal-body {
            padding: 0 28px 22px 28px;
            flex: 1;
            overflow-y: auto;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #64748b;
            line-height: 1;
        }
        .close-modal:hover {
            color: var(--primary-orange);
        }
        .modal-tabs {
            display: flex;
            gap: 2px;
            background: #eef3fa;
            padding: 5px;
            border-radius: 50px;
            margin: 14px 22px 16px 22px;
        }
        .modal-tab {
            flex: 1;
            text-align: center;
            padding: 8px 0;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: 0.2s;
        }
        .modal-tab.active {
            background: white;
            color: var(--primary-blue);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .tab-content {
            display: none;
            padding: 0 22px 18px 22px;
        }
        .tab-content.active {
            display: block;
        }
        .form-section {
            margin-bottom: 18px;
        }
        .form-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 12px;
            border-left: 4px solid var(--primary-orange);
            padding-left: 10px;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 10px;
        }
        .form-group {
            flex: 1 1 180px;
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 2px;
        }
        .form-group label.required::after {
            content: " *";
            color: #e53e3e;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            background: var(--bg-light);
            border: 1px solid #e0e7f0;
            border-radius: 14px;
            padding: 8px 12px;
            font-size: 0.85rem;
            color: var(--text-dark);
            outline: none;
            width: 100%;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 60px;
        }
        .double-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 16px;
        }
        .btn-primary {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
        }
        .btn-primary:hover {
            background: white;
            color: var(--primary-orange);
        }

        /* student table inside modal (compact) */
        .student-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .student-header h4 {
            font-size: 1.1rem;
            color: var(--primary-blue);
        }
        .add-student-btn {
            background: var(--blue-light);
            border: 1px dashed var(--primary-blue);
            color: var(--primary-blue);
            padding: 6px 14px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            background: #f8fcff;
            border-radius: 16px;
            overflow: hidden;
            font-size: 0.8rem;
            margin-bottom: 16px;
            table-layout: fixed;
        }
        .student-table th {
            background: var(--primary-blue);
            color: white;
            font-weight: 600;
            font-size: 0.7rem;
            padding: 8px 4px;
            text-align: left;
        }
        .student-table td {
            padding: 8px 4px;
            border-bottom: 1px solid #d9e2ec;
            word-wrap: break-word;
        }
        .student-table .action-icons i {
            margin: 0 3px;
            font-size: 0.9rem;
            color: var(--text-muted);
            cursor: pointer;
        }
        .empty-row td {
            text-align: center;
            color: var(--text-muted);
            padding: 20px;
        }

        /* submodal (add student) */
        .submodal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 11000;
            padding: 16px;
        }
        .submodal-overlay.open, .submodal-overlay.show {
            display: flex;
        }
        .submodal-container {
            background: white;
            border-radius: 28px;
            max-width: 650px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px -12px black;
        }
        .submodal-header {
            padding: 16px 20px 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .submodal-header h3 {
            font-size: 1.4rem;
            color: var(--primary-blue);
        }
        .close-submodal {
            background: none;
            border: none;
            font-size: 1.8rem;
            cursor: pointer;
            color: var(--text-muted);
        }
        .sub-tabs {
            display: flex;
            gap: 4px;
            margin: 10px 20px 0 20px;
            border-bottom: 2px solid #eef3fa;
        }
        .sub-tab {
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }
        .sub-tab.active {
            color: var(--primary-orange);
            border-bottom-color: var(--primary-orange);
        }
        .sub-tab-content {
            display: none;
            padding: 16px 20px;
        }
        .sub-tab-content.active {
            display: block;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .full-width {
            grid-column: span 2;
        }
        .submodal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 12px 20px 18px 20px;
            border-top: 1px solid #ecf3fa;
        }

        /* ===== UPDATED VIEW MODAL (Exact match to Screenshot) ===== */
        .view-modal-content {
            padding: 10px 28px 22px 28px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px 30px;
            background: #f8fbff;
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 20px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-item .label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 800;
            color: #4a90e2;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        .detail-item .value {
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
            line-height: 1.4;
        }
        .greeting-message {
            background: #fff8f0;
            border-left: 5px solid #ff913b;
            padding: 16px 20px;
            border-radius: 12px;
            margin: 0 28px 20px 28px;
            font-size: 0.9rem;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: space-between;
            line-height: 1.5;
            box-shadow: 0 4px 12px rgba(255,145,59,0.08);
        }
        .greeting-message i {
            font-size: 1.5rem;
            color: #25D366;
            cursor: pointer;
            transition: 0.2s;
            margin-left: 15px;
        }
        .greeting-message i:hover {
            transform: scale(1.15) rotate(5deg);
        }
        .view-modal-actions {
            padding: 0 28px 28px 28px;
            display: flex;
            justify-content: flex-start;
        }
        .btn-close-outline {
            background: white;
            border: 2px solid #4a90e2;
            color: #4a90e2;
            padding: 10px 30px;
            border-radius: 100px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-close-outline:hover {
            background: #f0f7ff;
            transform: translateY(-2px);
        }

        /* STUDENT TAB - No Scrollbar */
        #studentsTab {
            max-height: none !important;
            overflow: visible !important;
        }
        .student-table-container {
            max-height: none !important;
            overflow: visible !important;
        }

        </style>
@endpush

@section('content')

<div class="page-title" style="margin-bottom: 20px; font-size: 1.1rem; font-weight: 700; color: var(--primary-blue);"><i class="fas fa-clipboard-list" style="color: var(--primary-orange);"></i> Registration Manager</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<div class="enquiry-container">
    <!-- heading -->
    <div class="section-heading">
        <h2><i class="fas fa-clipboard-list"></i> Registration Manager</h2>
        <p>Manage student registrations and admissions</p>
    </div>

    <!-- filter panel (restored dynamic form) -->
    <form method="GET" action="{{ route('registration.index') }}" class="w-100 m-0 p-0" id="filterForm">
        <div class="filter-panel" style="margin-bottom: 20px;">
            <div class="filter-group">
                <label>SEARCH TEXT</label>
                <input type="text" name="stu_search" value="{{ request('stu_search') }}" placeholder="Search...">
            </div>
            <div class="filter-group">
                <label>STATUS</label>
                <select id="statusFilter" name="status" onchange="document.getElementById('filterForm').submit()">
                    <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                    <option value="Register" {{ request('status') == 'Register' ? 'selected' : '' }}>Register</option>
                    <option value="Admission Confirm" {{ request('status') == 'Admission Confirm' ? 'selected' : '' }}>Admission Confirm</option>
                </select>
            </div>
            <div class="filter-group">
                <label>CLASS</label>
                <select id="classFilter" name="class" onchange="document.getElementById('filterForm').submit()">
                    <option value="">All Classes</option>
                    @foreach($globalClasses as $class)
                        <option value="{{ $class->name }}" {{ request('class') == $class->name ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>REGISTRATION DATE</label>
                <input type="date" name="reg_date" id="regDateFilter" value="{{ request('reg_date') }}" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn" style="display: none;">Filter</button>
                <a href="#" class="btn"><i class="fas fa-download"></i> Export CSV</a>
                <a href="#" class="btn"><i class="fas fa-print"></i> Print Form</a>
                <button type="button" class="btn btn-orange" id="openMainModal"><i class="fas fa-plus-circle"></i> New Registration</button>
            </div>
        </div>
    </form>

    <!-- status tabs (updated labels) -->
    <div class="status-tabs">
        <span class="status-tab active" data-filter="All">All</span>
        <span class="status-tab" data-filter="Register">Register</span>
        <span class="status-tab" data-filter="Admission Confirm">Admission Confirm</span>
    </div>

    <!-- table with new columns -->
    <div class="table-card">
        <table id="registrationTable">
            <thead>
                <tr>
                    <th>Reg No.</th>
                    <th>Student's Name</th>
                    <th>Father's Name</th>
                    <th>Class</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($registrations as $reg)
                
                    <tr>
                        <td>{{ $reg->reg_no ?: ('REG' . str_pad($reg->id, 3, '0', STR_PAD_LEFT)) }}</td>
                        <td>
                            @foreach($reg->students as $student)
                               {{ $student->name }}<br>
                            @endforeach
                        </td>
                        <td>{{ $reg->father_name }}</td>
                        <td>    @foreach($reg->students as $student)
                                    {{ $student->class ?? 'N/A' }}
                                @endforeach
                        </td>
                        
                        <td>{{ $reg->email ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reg->reg_date)->format('d-M-Y') }}</td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-eye view-icon" title="View" onclick="viewRegistration({{ $reg->id }})"></i>
                                <i class="fas fa-edit edit-icon" title="Edit" onclick="editRegistration({{ $reg->id }})"></i>
                                <form action="{{ route('registration.delete', $reg->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this registration?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;">
                                        <i class="fas fa-trash delete-icon" title="Delete"></i>
                                    </button>
                                </form>
                                @if($reg->status == 'Register')
                                    <span class="status-badge" onclick="confirmAdmission({{ $reg->id }})">{{ $reg->status }}</span>
                                @else
                                    <span class="status-badge confirmed">Admission Confirm</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">No registrations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- pagination -->
    <div class="pagination-bar" style="margin-top: 15px;">
        {{ $registrations->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- MAIN MODAL (Create/Edit Registration) – unchanged -->
<div class="modal-overlay" id="mainModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitle">Create New Registration</h2>
            <button class="close-modal" id="closeMainModal">&times;</button>
        </div>
        <form id="registrationForm" onsubmit="return false;">
            @csrf
            <input type="hidden" id="regId" name="regId">
        <div class="modal-tabs">
            <div class="modal-tab active" data-tab="family">FAMILY INFO</div>
            <div class="modal-tab" data-tab="students">STUDENTS</div>
        </div>
        <!-- Family Info tab -->
        <div class="tab-content active" id="familyTab">
            <div class="form-section">
                <h3>FAMILY INFO</h3>
                <div class="double-row">
                    <div class="form-group">
                        <label>Registration Date</label>
                        <input type="date" id="regDate" name="reg_date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="regStatus" name="status">
                            <option>Register</option>
                            <option>Admission Confirm</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-section">
                <h3>Father's Information</h3>
                <div class="double-row">
                    <div class="form-group">
                        <label class="required">Father's Name</label>
                        <input type="text" id="fatherName" name="father_name" placeholder="Enter father's name" required>
                    </div>
                    <div class="form-group">
                        <label class="required">Father's Mobile</label>
                        <input type="text" id="fatherMobile" name="father_mobile" placeholder="Mobile number" required>
                    </div>
                </div>
            </div>
            <div class="form-section">
                <h3>Mother's Information</h3>
                <div class="double-row">
                    <div class="form-group">
                        <label>Mother's Name</label>
                        <input type="text" id="motherName" name="mother_name" placeholder="Enter mother's name">
                    </div>
                    <div class="form-group">
                        <label>Mother's Mobile</label>
                        <input type="text" id="motherMobile" name="mother_mobile" placeholder="Mobile number">
                    </div>
                </div>
            </div>
            <div class="form-section">
                <h3>Contact & Address</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" placeholder="Email address">
                    </div>
                    <div class="form-group">
                        <label>Address Line 1</label>
                        <input type="text" id="addr1" name="address1" placeholder="Address">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Address Line 2</label>
                        <input type="text" id="addr2" name="address2" placeholder="Address (optional)">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" id="city" name="city" placeholder="City">
                    </div>
                </div>
                <div class="form-group">
                    <label>Remarks</label>
                    <textarea id="remarks" name="remarks" placeholder="Any remarks..."></textarea>
                </div>
            </div>
            <div class="action-buttons">
                <button type="button" class="btn" id="cancelFamily">Cancel</button>
                <button class="btn btn-primary" id="saveFamilyContinue">Next</button>
            </div>
        </div>
        <!-- Students tab -->
        <div class="tab-content" id="studentsTab">
            <div class="student-header">
                <h4>Students <span id="studentCount">0</span></h4>
                <button type="button" class="add-student-btn" id="showAddStudent"><i class="fas fa-plus"></i> Add Student</button>
            </div>
            <table class="student-table" id="studentTable">
                <thead>
                    <tr><th>#</th><th>Student Name</th><th>Class</th><th>Gender</th><th>DOB</th><th>Admission Form No.</th><th>Actions</th></tr>
                </thead>
                <tbody id="studentTableBody">
                    <tr class="empty-row"><td colspan="7">No students added yet. Click "Add Student" to add one.</td></tr>
                </tbody>
            </table>
            <div class="action-buttons">
                <button type="button" class="btn" id="prevTabBtn">Previous</button>
                <button class="btn btn-primary" id="saveRegistrationBtn">Save Registration</button>
            </div>
        </div>
    </div>
</div>

</form>
<!-- SUB MODAL (Add Student) – unchanged -->
<div class="submodal-overlay" id="addStudentModal">
    <div class="submodal-container">
        <div class="submodal-header">
            <h3>Add Student</h3>
            <button class="close-submodal" id="closeSubmodal">&times;</button>
        </div>
        <div class="sub-tabs">
            <div class="sub-tab active" data-subtab="basic">Basic Info</div>
            <div class="sub-tab" data-subtab="previous">Previous School</div>
            <div class="sub-tab" data-subtab="test">Test & Form Info</div>
        </div>
        <!-- Basic Info tab -->
        <div class="sub-tab-content active" id="basicTab">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Student name *</label>
                    <input type="text" id="studentName" placeholder="Full name">
                </div>
                <div class="form-group">
                    <label>Class *</label>
                    <select id="studentClass">
                        <option value="">Select Class</option>
                        @foreach($globalClasses as $class)
                            <option value="{{ $class->name }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Gender *</label>
                    <select id="studentGender"><option>Male</option><option>Female</option></select>
                </div>
                <div class="form-group full-width">
                    <label>Date of Birth *</label>
                    <input type="date" id="studentDob" value="2010-01-01">
                </div>
            </div>
        </div>
        <!-- Previous School tab -->
        <div class="sub-tab-content" id="previousTab">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Previous School name</label>
                    <input type="text" id="studentPrevSchool" placeholder="School name">
                </div>
                <div class="form-group">
                    <label>Board</label>
                    <select id="studentBoard"><option>CBSE</option><option>ICSE</option><option>State</option></select>
                </div>
                <div class="form-group">
                    <label>Last Exam Percentage</label>
                    <input type="text" id="studentLastExam" placeholder="%">
                </div>
            </div>
        </div>
        <!-- Test & Form Info tab -->
        <div class="sub-tab-content" id="testTab">
            <div class="form-grid" style="gap: 10px;">
                <div class="form-group"><label>Test Date</label><input type="date" id="testDate" placeholder="dd-mm-yyyy"></div>
                <div class="form-group"><label>Test Time</label><input type="time" id="testTime" placeholder="HH:MM"></div>
                <div class="form-group full-width"><label>Test Venue</label><input type="text" id="testVenue"></div>
                <div class="form-group"><label>Full Marks</label><input type="text" id="fullMarks"></div>
                <div class="form-group"><label>Pass Marks</label><input type="text" id="passMarks"></div>
                <div class="form-group"><label>Percentage</label><input type="text" id="percentage"></div>
                <div class="form-group"><label>Obtained Marks</label><input type="text" id="obtainedMarks"></div>
                <div class="form-group"><label>Obtained Percentage</label><input type="text" id="obtainedPercentage"></div>
                <div class="form-group full-width"><label>Prospectus no</label><input type="text" id="prospectusNo"></div>
                <div class="form-group"><label>Admission Form No</label><input type="text" id="admissionFormNo"></div>
                <div class="form-group"><label>Voucher Receipt No</label><input type="text" id="voucherReceiptNo"></div>
                <div class="form-group"><label>Prospectus Fee</label><input type="text" id="prospectusFee"></div>
                <!-- Need original registrationCharges for JS backward compatibility -->
                <input type="hidden" id="registrationCharges" value="0"> 
            </div>
        </div>
        <div class="submodal-actions">
            <button type="button" class="btn" id="cancelSubmodal">Cancel</button>
            <button class="btn btn-primary" id="saveStudent">Add</button>
        </div>
    </div>
</div>

<!-- VIEW MODAL (Exact Screenshot Match) -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-container" style="max-width: 800px;">
        <div class="modal-header">
            <h2>Registration Details</h2>
            <button class="close-modal" id="closeViewModal">&times;</button>
        </div>
        <div class="view-modal-content">
            <div id="viewDetails" class="detail-grid">
                <!-- Dynamic Content Loaded Here -->
            </div>
        </div>
        <div class="greeting-message" id="greetingBox">
            <span id="greetingText"></span>
            <i class="fab fa-whatsapp" id="whatsappIcon" title="Send WhatsApp"></i>
        </div>
        <div class="view-modal-actions">
            <button type="button" class="btn-close-outline" id="closeViewBtn">Close</button>
        </div>
    </div>
</div>

<!-- ADMISSION CONFIRMATION MODAL (unchanged) -->
<div class="modal-overlay" id="admissionModal">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Confirm Admission</h2>
            <button class="close-modal" id="closeAdmissionModal">&times;</button>
        </div>
        <p style="margin: 20px 0;">Are you sure you want to mark this registration as admitted?</p>
        <form action="" id="admissionForm" method="POST">
            @csrf
            <div class="action-buttons">
                <button type="button" class="btn" id="cancelAdmission">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Students array for adding/editing a registration
    let tempStudents = [];
    let editingId = null;
    let currentEditingStudentIndex = null;
    let currentViewMobile = '';
    let currentViewMessage = '';

    const mainModal = document.getElementById('mainModal');
    const familyTabContent = document.getElementById('familyTab');
    const studentsTabContent = document.getElementById('studentsTab');
    
    function openAddModal() {
        resetRegistration();
        mainModal.classList.add('open');
    }

    document.getElementById('openMainModal').addEventListener('click', openAddModal);
    document.getElementById('closeMainModal').addEventListener('click', () => mainModal.classList.remove('open'));
    document.getElementById('cancelFamily').addEventListener('click', () => mainModal.classList.remove('open'));
    document.getElementById('saveFamilyContinue').addEventListener('click', (e) => {
        e.preventDefault();
        showStudentsTab();
    });
    document.getElementById('prevTabBtn').addEventListener('click', (e) => {
        e.preventDefault();
        showFamilyTab();
    });

    // Inline Tab Logic for Main Modal
    document.querySelectorAll('.modal-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            if (tab.dataset.tab === 'family') {
                familyTabContent.style.display = 'block';
                studentsTabContent.style.display = 'none';
            } else {
                familyTabContent.style.display = 'none';
                studentsTabContent.style.display = 'block';
            }
        });
    });

    window.showStudentsTab = function() {
        const studentTabBtn = document.querySelector('.modal-tab[data-tab="students"]');
        if (studentTabBtn) studentTabBtn.click();
    };
    window.showFamilyTab = function() {
        const familyTabBtn = document.querySelector('.modal-tab[data-tab="family"]');
        if (familyTabBtn) familyTabBtn.click();
    };

    // Student Sub-Tabs (Add Student Form)
    document.querySelectorAll('.sub-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.sub-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const target = tab.dataset.subtab;
            document.querySelectorAll('.sub-tab-content').forEach(c => c.style.display = 'none');
            if (target === 'basic') document.getElementById('basicTab').style.display = 'block';
            else if (target === 'previous') document.getElementById('previousTab').style.display = 'block';
            else if (target === 'test') document.getElementById('testTab').style.display = 'block';
        });
    });

    function resetRegistration() {
        editingId = null;
        document.getElementById('registrationForm').reset();
        document.getElementById('regDate').value = new Date().toISOString().slice(0,10);
        document.getElementById('regId').value = '';
        tempStudents = [];
        updateStudentTable();
        showFamilyTab();
        document.getElementById('modalTitle').innerText = 'Registration Manager — Create New';
    }

    window.resetStudentForm = function() {
        currentEditingStudentIndex = null;
        ['studentName', 'studentClass', 'studentGender', 'studentDob', 'studentPrevSchool', 'studentBoard', 'studentLastExam', 
         'testDate', 'testTime', 'testVenue', 'fullMarks', 'passMarks', 'percentage', 'registrationCharges',
         'obtainedMarks', 'obtainedPercentage', 'prospectusNo', 'admissionFormNo', 'voucherReceiptNo', 'prospectusFee'
        ].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.value = '';
        });
        const addStudentTitle = document.querySelector('.submodal-header h3');
        if (addStudentTitle) addStudentTitle.innerText = 'Add Student';
        
        const saveStudentBtn = document.getElementById('saveStudent');
        if (saveStudentBtn) saveStudentBtn.innerText = 'Add';
        
        const basicTab = document.querySelector('.sub-tab[data-subtab="basic"]');
        if (basicTab) basicTab.click();
    };

    // Sub Modal Toggle Listeners
    const addStudentModal = document.getElementById('addStudentModal');
    
    document.getElementById('showAddStudent').addEventListener('click', () => {
        resetStudentForm();
        addStudentModal.classList.add('open');
    });

    document.getElementById('closeSubmodal').addEventListener('click', () => addStudentModal.classList.remove('open'));
    document.getElementById('cancelSubmodal').addEventListener('click', () => addStudentModal.classList.remove('open'));


    document.getElementById('saveStudent').addEventListener('click', () => {
        const name = document.getElementById('studentName').value.trim();
        const cls = document.getElementById('studentClass').value;
        if(!name || !cls) { 
            Swal.fire({ icon: 'warning', title: 'Missing Info', text: 'Student Name and Class are required!' });
            return; 
        }
        
        const student = { 
            name: name, class: cls,
            gender: document.getElementById('studentGender').value,
            dob: document.getElementById('studentDob').value,
            previous_school: document.getElementById('studentPrevSchool').value,
            board: document.getElementById('studentBoard').value,
            last_exam_percentage: document.getElementById('studentLastExam').value,
            test_date: document.getElementById('testDate').value,
            test_time: document.getElementById('testTime').value,
            test_venue: document.getElementById('testVenue').value,
            full_marks: document.getElementById('fullMarks').value,
            pass_marks: document.getElementById('passMarks').value,
            percentage: document.getElementById('percentage').value,
            registration_charges: document.getElementById('registrationCharges').value,
            obtained_marks: document.getElementById('obtainedMarks').value,
            obtained_percentage: document.getElementById('obtainedPercentage').value,
            prospectus_no: document.getElementById('prospectusNo').value,
            admission_form_no: document.getElementById('admissionFormNo').value,
            voucher_receipt_no: document.getElementById('voucherReceiptNo').value,
            prospectus_fee: document.getElementById('prospectusFee').value,
        };

        if (currentEditingStudentIndex !== null) {
            tempStudents[currentEditingStudentIndex] = student;
            currentEditingStudentIndex = null;
        } else {
            tempStudents.push(student);
        }

        updateStudentTable();
        addStudentModal.classList.remove('open');
    });

    window.updateStudentTable = function() {
        const tbody = document.getElementById('studentTableBody');
        const countSpan = document.getElementById('studentCount');
        if (tempStudents.length === 0) {
            tbody.innerHTML = `<tr class="empty-row"><td colspan="6">No students added yet. Use the form below to add.</td></tr>`;
            countSpan.innerText = '0';
            return;
        }
        let html = '';
        tempStudents.forEach((s, index) => {
            html += `<tr>
                <td>${index+1}</td>
                <td>${s.name}</td>
                <td>${s.class}</td>
                <td>${s.gender || '-'}</td>
                <td>${s.dob || '-'}</td>
                <td class="action-icons">
                    <i class="fas fa-edit" style="color:var(--primary-blue); cursor:pointer; margin-right:8px;" onclick="editTempStudent(${index})"></i>
                    <i class="fas fa-trash" style="color:red; cursor:pointer;" onclick="removeTempStudent(${index})"></i>
                </td>
            </tr>`;
        });
        tbody.innerHTML = html;
        countSpan.innerText = tempStudents.length;
    }

    window.editTempStudent = function(index) {
        currentEditingStudentIndex = index;
        const s = tempStudents[index];
        
        document.getElementById('studentName').value = s.name || '';
        document.getElementById('studentClass').value = s.class || '';
        document.getElementById('studentGender').value = s.gender || '';
        document.getElementById('studentDob').value = s.dob || '';
        document.getElementById('studentPrevSchool').value = s.previous_school || '';
        document.getElementById('studentBoard').value = s.board || '';
        document.getElementById('studentLastExam').value = s.last_exam_percentage || '';
        document.getElementById('testDate').value = s.test_date || '';
        document.getElementById('testTime').value = s.test_time || '';
        document.getElementById('testVenue').value = s.test_venue || '';
        document.getElementById('fullMarks').value = s.full_marks || '';
        document.getElementById('passMarks').value = s.pass_marks || '';
        document.getElementById('percentage').value = s.percentage || '';
        document.getElementById('registrationCharges').value = s.registration_charges || '';
        document.getElementById('obtainedMarks').value = s.obtained_marks || '';
        document.getElementById('obtainedPercentage').value = s.obtained_percentage || '';
        document.getElementById('prospectusNo').value = s.prospectus_no || '';
        document.getElementById('admissionFormNo').value = s.admission_form_no || '';
        document.getElementById('voucherReceiptNo').value = s.voucher_receipt_no || '';
        document.getElementById('prospectusFee').value = s.prospectus_fee || '';
        
        // Update Modal UI
        const addStudentTitle = document.querySelector('.submodal-header h3');
        if (addStudentTitle) addStudentTitle.innerText = 'Edit Student Details';
        document.getElementById('saveStudent').innerText = 'Update';
        const basicTab = document.querySelector('.sub-tab[data-subtab="basic"]');
        if (basicTab) basicTab.click();
        
        // Show Sub Modal
        addStudentModal.classList.add('open');
    }

    window.removeTempStudent = function(index) {
        tempStudents.splice(index, 1);
        updateStudentTable();
    };

    // Save final Registration via AJAX
    document.getElementById('saveRegistrationBtn').addEventListener('click', () => {
        if (tempStudents.length === 0) {
            alert('Please add at least one student.');
            return;
        }
        
        const payload = {
            father_name: document.getElementById('fatherName').value,
            father_mobile: document.getElementById('fatherMobile').value,
            reg_date: document.getElementById('regDate').value,
            status: document.getElementById('regStatus').value,
            mother_name: document.getElementById('motherName').value,
            mother_mobile: document.getElementById('motherMobile').value,
            email: document.getElementById('email').value,
            address1: document.getElementById('addr1').value,
            address2: document.getElementById('addr2').value,
            city: document.getElementById('city').value,
            remarks: document.getElementById('remarks').value,
            students: tempStudents
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const url = editingId ? `{{ url('admin/registration-manager') }}/${editingId}` : `{{ route('registration.store') }}`;
        const method = editingId ? 'PUT' : 'POST';

        Swal.fire({
            title: editingId ? 'Updating Registration...' : 'Saving Registration...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success || data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: data.message || 'Registration saved successfully.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred.'
                });
            }
        })
        .catch(err => {
            console.error('Save fetch error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: 'Something went wrong on the server.'
            });
        });
    });

    // View Registration
    window.viewRegistration = function(id) {
        fetch(`{{ url('admin/registration-manager') }}/${id}/show`)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(res => {
            if(res.success) {
                const reg = res.data;
                const detailsHtml = `
                    <div class="detail-item">
                        <div class="label">REG NO.</div>
                        <div class="value">${reg.reg_no}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">STUDENT NAME</div>
                        <div class="value">${reg.students && reg.students.length > 0 ? reg.students[0].name : '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">FATHER'S NAME</div>
                        <div class="value">${reg.father_name}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">CLASS</div>
                        <div class="value">${reg.students && reg.students.length > 0 ? reg.students[0].class : '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">SECTION</div>
                        <div class="value">${reg.section || 'A'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">EMAIL</div>
                        <div class="value">${reg.email || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">REGISTRATION DATE</div>
                        <div class="value">${reg.reg_date}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">MOBILE</div>
                        <div class="value">${reg.father_mobile}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">ADDRESS</div>
                        <div class="value">${reg.address1 || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">REMARKS</div>
                        <div class="value">${reg.remarks || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">STATUS</div>
                        <div class="value">${reg.status}</div>
                    </div>
                `;
                document.getElementById('viewDetails').innerHTML = detailsHtml;
                
                let studentNames = reg.students.map(s => s.name).join(', ');
                currentViewMessage = `Dear ${reg.father_name}, welcome to our school family! Your child ${studentNames}'s registration has been ${reg.status.toLowerCase() == 'register' ? 'confirmed' : 'admitted'}. We look forward to teaching your child. For any queries, feel free to contact us. Regards, School Management.`;
                currentViewMobile = reg.father_mobile;
                
                document.getElementById('greetingText').innerText = currentViewMessage;
                document.getElementById('viewModal').classList.add('open');
            }
        });
    };
    
    document.getElementById('whatsappIcon').addEventListener('click', () => {
        const url = `https://wa.me/91${currentViewMobile}?text=${encodeURIComponent(currentViewMessage)}`;
        window.open(url, '_blank');
    });

    document.getElementById('closeViewModal').addEventListener('click', () => document.getElementById('viewModal').classList.remove('open'));
    document.getElementById('closeViewBtn').addEventListener('click', () => document.getElementById('viewModal').classList.remove('open'));

    // Edit Registration
    window.editRegistration = function(id) {
        fetch(`{{ url('admin/registration-manager') }}/${id}/edit`)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(res => {
            if(res.success) {
                const reg = res.data;
                editingId = id;
                document.getElementById('modalTitle').innerText = 'Edit Registration: ' + reg.reg_no;
                document.getElementById('regId').value = reg.id;
                document.getElementById('regDate').value = reg.reg_date;
                document.getElementById('regStatus').value = reg.status;
                document.getElementById('fatherName').value = reg.father_name;
                document.getElementById('fatherMobile').value = reg.father_mobile;
                document.getElementById('motherName').value = reg.mother_name || '';
                document.getElementById('motherMobile').value = reg.mother_mobile || '';
                document.getElementById('email').value = reg.email || '';
                document.getElementById('addr1').value = reg.address1 || '';
                document.getElementById('addr2').value = reg.address2 || '';
                document.getElementById('city').value = reg.city || '';
                document.getElementById('remarks').value = reg.remarks || '';
                
                // Map students
                tempStudents = reg.students.map(s => ({
                    name: s.name, class: s.class, gender: s.gender, dob: s.dob,
                    previous_school: s.previous_school, board: s.board, last_exam_percentage: s.last_exam_percentage,
                    test_date: s.test_date, test_time: s.test_time, test_venue: s.test_venue,
                    full_marks: s.full_marks, pass_marks: s.pass_marks, percentage: s.percentage,
                    registration_charges: s.registration_charges,
                    obtained_marks: s.obtained_marks, obtained_percentage: s.obtained_percentage,
                    prospectus_no: s.prospectus_no, admission_form_no: s.admission_form_no,
                    voucher_receipt_no: s.voucher_receipt_no, prospectus_fee: s.prospectus_fee
                }));
                updateStudentTable();
                
                showFamilyTab();
                mainModal.classList.add('open');
            }
        })
        .catch(err => {
            console.error('Edit fetch error:', err);
            alert('Error loading registration data.');
        });
    };

    // Admission Form modal
    window.confirmAdmission = function(id) {
        const form = document.getElementById('admissionForm');
        form.action = `{{ url('admin/registration-manager') }}/${id}/confirm-admission`;
        document.getElementById('admissionModal').classList.add('open');
    };
    
    document.getElementById('closeAdmissionModal').addEventListener('click', () => document.getElementById('admissionModal').classList.remove('open'));
    document.getElementById('cancelAdmission').addEventListener('click', () => document.getElementById('admissionModal').classList.remove('open'));

</script>
@endpush
