@extends('layouts.app')

@section('title', 'Student Admission Manager')
@section('page_icon', 'fas fa-user-graduate')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
<style>
    /* Admission Receipt Modal Styles */
    #receiptModal .modal-container { max-width: 900px; background: #b8c5d4; padding: 20px; }
    #receiptModal .modal-header { background: #fff; margin-bottom: 0; border-radius: 12px 12px 0 0; }
    #receiptModal .modal-body { background: #b8c5d4; padding: 0; overflow-y: auto; flex: 1; }
    
    /* Scoped Receipt Styles from reference */
    .rc-outer { display: flex; flex-direction: column; align-items: center; gap: 20px; padding: 20px 0; }
    
    /* Enhanced Modal UI */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10001; display: none; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.open { display: flex; }
    .modal-container { 
        background: #fff; border-radius: 28px; width: 100%; max-width: 1100px; 
        height: 90vh; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        position: relative; display: flex; flex-direction: column; overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .modal-header { 
        padding: 24px 35px; border-bottom: 1px solid #f1f5f9; display: flex; 
        justify-content: space-between; align-items: center; background: #fff; flex-shrink: 0; 
    }
    .modal-header h2 { font-size: 1.4rem; color: var(--primary-blue); font-weight: 800; display: flex; align-items: center; gap: 12px; }
    .modal-header h2 i { color: var(--primary-orange); }
    .close-modal { background: #f1f5f9; border: none; width: 38px; height: 38px; border-radius: 12px; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; color: #64748b; transition: 0.3s; }
    .close-modal:hover { background: #fee2e2; color: #ef4444; transform: rotate(90deg); }

    /* Tabs Styling */
    .details-tabs { 
        display: flex; gap: 8px; padding: 12px 30px; background: #f8fbff; 
        border-bottom: 1px solid #eef2ff; flex-shrink: 0;
    }
    .details-tab { 
        padding: 12px 24px; border-radius: 14px; font-weight: 700; font-size: 0.82rem; 
        color: #64748b; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 10px;
        border: 1px solid transparent;
    }
    .details-tab i { font-size: 1rem; }
    .details-tab:hover { background: rgba(72, 143, 228, 0.05); color: var(--primary-blue); }
    .details-tab.active { 
        background: #fff; color: var(--primary-blue); border-color: #eef2ff;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); 
    }

    .tab-content-area { flex: 1; overflow-y: auto !important; padding: 40px; background: #fff; min-height: 0; }
    #studentAdmissionForm { flex: 1; display: flex; flex-direction: column; min-height: 0; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes slideUp { from{opacity:0; transform: translateY(20px);} to{opacity:1; transform: translateY(0);} }

    /* Form Section Improvements */
    .form-section { margin-bottom: 45px; position: relative; }
    .form-section:last-child { margin-bottom: 0; }
    .form-section-title { 
        font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; 
        color: var(--primary-blue); font-weight: 800; border-left: 4px solid var(--primary-orange); 
        padding: 2px 15px; margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between;
        background: linear-gradient(90deg, #f8fbff, transparent);
    }
    .form-section-title span { display: flex; align-items: center; gap: 10px; }

    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 24px; }
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group label { font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-group label.required::after { content: " *"; color: #ef4444; }

    #studentAdmissionForm input, #studentAdmissionForm select, #studentAdmissionForm textarea { 
        background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; 
        padding: 12px 16px; font-size: 0.95rem; color: #1e293b; font-weight: 500; transition: 0.3s; 
    }
    #studentAdmissionForm input:focus, #studentAdmissionForm select:focus { 
        border-color: var(--primary-blue); background: #fff; box-shadow: 0 0 0 5px rgba(72,143,228,0.1); outline: none;
    }
    #studentAdmissionForm input:disabled, #studentAdmissionForm select:disabled {
        background: #f1f5f9; color: #64748b; border-color: #e2e8f0; cursor: not-allowed;
    }

    /* Photo Upload UI */
    .photo-upload-container {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 40px; border: 2px dashed #e2e8f0; border-radius: 24px; background: #f8fafc;
        transition: 0.3s; cursor: pointer;
    }
    .photo-upload-container:hover { border-color: var(--primary-blue); background: #f0f7ff; }
    .photo-preview-circle {
        width: 180px; height: 180px; border-radius: 50%; border: 6px solid #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; background: #fff;
        display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
    }

    .action-buttons { 
        padding: 24px 40px; border-top: 1px solid #f1f5f9; background: #fff; 
        display: flex; justify-content: flex-end; gap: 15px; flex-shrink: 0;
    }

    /* View Mode Overrides */
    .view-only-label { color: var(--primary-blue) !important; font-weight: 800 !important; }
    .view-only-value { padding: 12px 16px; background: #f8fbff; border-radius: 12px; font-size: 0.95rem; color: #1e293b; font-weight: 600; min-height: 48px; border: 1px solid #eef2ff; }

    /* Premium Scrollbar Styling */
    .tab-content-area::-webkit-scrollbar, 
    .modal-body::-webkit-scrollbar,
    #feeModalBody::-webkit-scrollbar { width: 10px; }
    
    .tab-content-area::-webkit-scrollbar-track,
    .modal-body::-webkit-scrollbar-track,
    #feeModalBody::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; border-left: 1px solid #e2e8f0; }
    
    .tab-content-area::-webkit-scrollbar-thumb,
    .modal-body::-webkit-scrollbar-thumb,
    #feeModalBody::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; border: 2px solid #f8fafc; }
    
    .tab-content-area::-webkit-scrollbar-thumb:hover,
    .modal-body::-webkit-scrollbar-thumb:hover,
    #feeModalBody::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Firefox Support */
    .tab-content-area, .modal-body, #feeModalBody { 
        scrollbar-width: thin; 
        scrollbar-color: #cbd5e1 #f1f5f9; 
    }

    /* Action Icons Spacing */

    .acts { display: flex; gap: 10px; justify-content: center; align-items: center; }
    .act-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 0.85rem; transition: 0.2s; }
    .act-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }


    .receipt { width: 100%; max-width: 800px; background: #fff; border-radius: 6px; overflow: hidden; position: relative; display: flex; flex-direction: column; }
    .receipt.office { border: 2px solid #1e3a5f; }
    .receipt.parents { border: 2px solid #0891b2; }
    .rc-bar { padding: 4px 13px; display: flex; align-items: center; justify-content: space-between; }
    .office .rc-bar { background: linear-gradient(90deg, #1e3a5f, #2563eb); }
    .parents .rc-bar { background: linear-gradient(90deg, #064e63, #0891b2); }
    .rc-bar-label { font-size: 7.5pt; font-weight: 800; text-transform: uppercase; color: #fff; letter-spacing: 1px; }
    .rc-bar-badge { font-size: 6pt; font-weight: 800; padding: 2px 8px; border-radius: 8px; color: #fff; border: 1.5px solid rgba(255,255,255,.5); }
    .rc-bar-rcno { font-size: 7pt; color: #fff; }
    .rc-bar-rcno b { color: #ffe08a; }
    
    .rc-hdr { padding: 10px 15px; display: flex; align-items: center; gap: 15px; border-bottom: 1.5px solid #eee; }
    .rc-logo { width: 50px; height: 50px; border-radius: 50%; border: 2px solid #2563eb; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .parents .rc-logo { border-color: #0891b2; }
    .rc-logo img { width: 100%; height: 100%; object-fit: cover; }
    .rc-sname { font-size: 14pt; font-weight: 800; color: #1e3a5f; }
    .rc-saddr { font-size: 7pt; color: #64748b; line-height: 1.4; }
    
    .rc-adm-banner { padding: 4px; text-align: center; background: #f0f5ff; border-bottom: 1.5px solid #bdd0f4; }
    .parents .rc-adm-banner { background: #f0faff; border-color: #a5d8e6; }
    .rc-adm-title { font-size: 9pt; font-weight: 900; text-transform: uppercase; color: #1e3a5f; text-decoration: underline; }
    
    .rc-info { display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1.5px solid #eee; }
    .rc-ic { padding: 4px 10px; border-bottom: 1px solid #f1f5f9; border-right: 1px solid #f1f5f9; display: flex; align-items: baseline; gap: 5px; }
    .rc-lbl { font-size: 7pt; font-weight: 800; color: #2563eb; white-space: nowrap; }
    .parents .rc-lbl { color: #0891b2; }
    .rc-val { font-size: 7pt; font-weight: 700; color: #1e3a5f; }

    .rc-tbl { width: 100%; border-collapse: collapse; }
    .rc-tbl thead tr { background: #1e3a5f; color: #fff; }
    .parents .rc-tbl thead tr { background: #064e63; }
    .rc-tbl th { padding: 6px 10px; font-size: 7pt; text-align: left; text-transform: uppercase; }
    .rc-tbl td { padding: 5px 10px; font-size: 7.5pt; border-bottom: 1px solid #f1f5f9; color: #1e3a5f; font-weight: 600; }
    .rc-tbl tfoot td { padding: 5px 10px; font-weight: 700; font-size: 8pt; border-top: 1.5px solid #1e3a5f; }
    .parents .rc-tbl tfoot td { border-top-color: #0891b2; }
    
    .rc-ftr { padding: 10px 15px; display: flex; justify-content: space-between; align-items: flex-end; }
    .rc-fnote { font-size: 6pt; color: #64748b; line-height: 1.5; }
    .rc-sig-line { width: 100px; border-bottom: 1px solid #1e3a5f; margin-bottom: 4px; }
    .rc-sig-lbl { font-size: 6pt; font-weight: 800; text-transform: uppercase; text-align: center; }

    .rc-cut { width: 100%; display: flex; align-items: center; gap: 10px; margin: 10px 0; color: #64748b; font-size: 8pt; font-weight: 700; }
    .rc-cut-line { flex: 1; border-top: 2px dashed #cbd5e1; }

    @media print {
        body * { visibility: hidden; }
        #receiptModal, #receiptModal * { visibility: visible; }
        #receiptModal { position: absolute; left: 0; top: 0; width: 100%; padding: 0; margin: 0; background: #fff; }
        .modal-overlay { background: #fff; display: block; position: static; }
        .modal-container { box-shadow: none; max-width: 100%; width: 210mm; background: #fff !important; padding: 0; margin: 0; border-radius: 0; }
        .modal-header, .action-buttons { display: none !important; }
        .rc-outer { padding: 0; }
        .receipt { border-radius: 0; box-shadow: none; page-break-inside: avoid; }
    }
</style>
@endpush

@section('content')
<input type="file" id="photoUploadInput" accept="image/*" style="display: none;">

<div class="card">
    <div class="card-head">
        <div>
            <h2><i class="fas fa-user-graduate"></i> Student Admission</h2>
            <p class="card-sub">Manage student admissions, profiles, and fee assignments.</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-orange" id="addStudentBtn"><i class="fas fa-user-plus"></i> New Admission</button>
            <button class="btn btn-blue"><i class="fas fa-file-import"></i> Import</button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form action="{{ route('admin.student-admission.index') }}" method="GET" style="display: contents;">
            <div class="fg">
                <label>Search Text</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Name/Reg No...">
            </div>
            <div class="fg">
                <label>Class</label>
                <select name="class_id" id="filterClass">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label>Section</label>
                <select name="section_id" id="filterSection">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-blue"><i class="fas fa-search"></i> Search</button>
                <a href="{{ route('admin.student-admission.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Reg No</th>
                <th>Adm No.</th>
                <th>Adm. Date</th>
                <th>Student Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Session</th>
                <th>Mobile</th>
                <th>Status</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
            <tr>
                <td>{{ $students->firstItem() + $index }}</td>
                <td>{{ $student->registration_no ?? 'N/A' }}</td>
                <td>{{ $student->admission_no ?? 'N/A' }}</td>
                <td>{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') : 'N/A' }}</td>
                <td><strong>{{ $student->student_name }}</strong></td>
                <td>{{ $student->classInfo ? $student->classInfo->name : ($student->registrationStudent ? $student->registrationStudent->class : 'N/A') }}</td>
                <td>{{ $student->sectionInfo ? $student->sectionInfo->name : 'N/A' }}</td>
                <td>{{ $student->session ?? 'N/A' }}</td>
                <td>{{ $student->mobile ?? 'N/A' }}</td>
                <td>
                    @if($student->is_active)
                        <span class="badge badge-green">Active</span>
                    @else
                        <span class="badge badge-red">Inactive</span>
                    @endif
                </td>
                <td>
                    @if($student->photo_path)
                        <img src="{{ Storage::url($student->photo_path) }}" alt="Photo" width="40" height="40" class="rounded-circle photo-icon" title="View/Update Photo">
                    @else
                        <i class="fas fa-camera photo-icon" title="Upload photo" style="cursor:pointer; color:var(--txt3)"></i>
                    @endif
                </td>
                <td>
                    <div class="acts">
                        <button class="act-icon blue" title="View" onclick="viewStudent({{ $student->id }})"><i class="fas fa-eye"></i></button>
                        <button class="act-icon orange" title="Edit" onclick="editStudent({{ $student->id }})"><i class="fas fa-edit"></i></button>
                        <button class="act-icon green fee-icon" title="Fee" data-id="{{ $student->id }}" data-student="{{ $student->student_name }}"><i class="fas fa-rupee-sign"></i></button>
                        <button class="act-icon blue receipt-icon" title="Print Receipt" onclick="showAdmissionReceipt({{ $student->id }})"><i class="fas fa-print"></i></button>
                        <button class="act-icon red" title="Delete" onclick="confirmDeleteStudent({{ $student->id }})"><i class="fas fa-trash"></i></button>
                        <a href="https://wa.me/91{{ $student->mobile }}" target="_blank" class="act-icon green"><i class="fab fa-whatsapp" title="WhatsApp"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center py-4 text-muted">No students found. Add a new admission to get started.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

    <!-- Pagination -->
    @if($students->hasPages())
    <div style="margin-top: 20px;">
        {{ $students->links() }}
    </div>
    @endif
</div>

<!-- MODAL: Student Admission Form -->
<div class="modal-overlay" id="studentModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Student Admission</h2>
            <button class="close-modal" id="closeStudentModal">&times;</button>
        </div>
        <form id="studentAdmissionForm">
            <div class="details-tabs">
                <div class="details-tab active" data-tab="basic"><i class="fas fa-info-circle"></i> Basic Info</div>
                <div class="details-tab" data-tab="parent"><i class="fas fa-users"></i> Parent Info</div>
                <div class="details-tab" data-tab="previous"><i class="fas fa-school"></i> Previous School</div>
                <div class="details-tab" data-tab="photo"><i class="fas fa-camera"></i> Upload Photo</div>
            </div>

            <div class="tab-content-area">
                <!-- TAB 1: BASIC INFO -->
                                <div class="tab-pane active" id="basicTab">
                    <div class="form-section">
                        <div class="form-section-title"><span><i class="fas fa-info-circle"></i> Basic Registration Details</span></div>
                        <div class="form-row">
                            <div class="form-group"><label class="required">Admission No.</label><input type="text" name="admission_no" id="adm_no" value="{{ $nextAdmissionNo }}"></div>
                            <div class="form-group"><label class="required">Admission Date</label><input type="date" name="admission_date" id="adm_date" value="{{ date('Y-m-d') }}"></div>
                            <div class="form-group">
                                <label class="required">Registration No.</label>
                                <input type="hidden" name="registration_student_id" id="registration_student_id">
                                <input type="text" name="registration_no" id="registrationDropdown" placeholder="Search Registration...">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label class="required">Class</label>
                                <select name="class_id" id="admissionClass">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group"><label>Section</label>
                                <select name="section_id" id="admissionSection">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group"><label>Session</label><input type="text" name="session" value="2025-2026"></div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title"><span><i class="fas fa-user"></i> Student Personal Details</span></div>
                        <div class="form-row">
                            <div class="form-group"><label class="required">Student Name</label><input type="text" name="student_name" id="adm_student_name" required></div>
                            <div class="form-group"><label>Gender</label>
                                <select name="gender" id="adm_gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Date of Birth</label><input type="date" name="dob" id="adm_dob"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>Mobile No.</label><input type="text" name="mobile" id="adm_mobile"></div>
                            <div class="form-group"><label>Email</label><input type="email" name="email" id="adm_email"></div>
                            <div class="form-group"><label>Aadhar No.</label><input type="text" name="aadhar_no"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group" style="grid-column: span 3;"><label>Full Permanent Address</label><textarea name="address" id="adm_address" rows="2" placeholder="Street, Village, Post..."></textarea></div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PARENT INFO -->
                <div class="tab-pane" id="parentTab">
                    <div class="form-section">
                        <div class="form-section-title"><span><i class="fas fa-male"></i> Father's Information</span></div>
                        <div class="form-row">
                            <div class="form-group"><label class="required">Father's Name</label><input type="text" name="parent[father_name]" id="adm_father_name" required></div>
                            <div class="form-group"><label>Mobile</label><input type="text" name="parent[father_phone]" id="adm_father_mobile"></div>
                            <div class="form-group"><label>Occupation</label>
                                <select name="parent[father_occupation]">
                                    @foreach($occupations as $occ) <option value="{{ $occ }}">{{ $occ }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="form-section-title"><span><i class="fas fa-female"></i> Mother's Information</span></div>
                        <div class="form-row">
                            <div class="form-group"><label>Mother's Name</label><input type="text" name="parent[mother_name]" id="adm_mother_name"></div>
                            <div class="form-group"><label>Mobile</label><input type="text" name="parent[mother_phone]" id="adm_mother_mobile"></div>
                            <div class="form-group"><label>Occupation</label>
                                <select name="parent[mother_occupation]">
                                    @foreach($occupations as $occ) <option value="{{ $occ }}">{{ $occ }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: PREVIOUS SCHOOL -->
                <div class="tab-pane" id="previousTab">
                    <div class="form-section">
                        <div class="form-section-title"><span><i class="fas fa-graduation-cap"></i> Previous Academic History</span></div>
                        <div class="form-row">
                            <div class="form-group" style="grid-column: span 2;"><label>Last School Name</label><input type="text" name="previous[school_name]" id="adm_prev_school"></div>
                            <div class="form-group"><label>Previous Class</label><input type="text" name="previous[class]"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>TC / Transfer Certificate No.</label><input type="text" name="previous[tc_no]"></div>
                            <div class="form-group"><label>Year of Passing</label><input type="text" name="previous[passing_year]" placeholder="e.g. 2024"></div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: UPLOAD PHOTO -->
                <div class="tab-pane" id="photoTab">
                    <div class="form-section">
                        <div class="form-section-title"><span><i class="fas fa-camera"></i> Student Photograph</span></div>
                        <div class="photo-upload-container" onclick="document.getElementById('student_photo_input').click()">
                            <div class="photo-preview-circle" id="photoPreview">
                                <i class="fas fa-user-circle" style="font-size: 5rem; color: #cbd5e1;"></i>
                            </div>
                            <div style="text-align: center;">
                                <h4 style="margin-bottom: 5px; color: var(--primary-blue);">Click to upload Student Photo</h4>
                                <p style="font-size: 0.8rem; color: #64748b;">Supported: JPG, PNG (Max 2MB)</p>
                            </div>
                            <input type="file" name="photo" id="student_photo_input" style="display: none;" onchange="previewImage(this)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHARED ACTION BUTTONS -->
            <div class="action-buttons">
                <button type="button" class="btn btn-outline prev-tab" id="prevActionBtn" style="display: none;"><i class="fas fa-arrow-left"></i> Previous</button>
                <div style="flex: 1;"></div>
                <button type="button" class="btn btn-blue next-tab" id="nextActionBtn">Next <i class="fas fa-arrow-right"></i></button>
                <button type="submit" class="btn btn-blue" id="saveAdmissionBtn" style="display: none;">Save Admission</button>
            </div>
        </form>
    </div>
</div>

<!-- Admission Fee Modal -->
<div class="modal-overlay" id="feeModal">
    <div class="modal-container" style="max-width: 850px;">
        <div class="modal-header">
            <h2><i class="fas fa-hand-holding-usd"></i> Assign Admission Fees: <span id="feeStudentName" style="color: var(--primary-orange)"></span></h2>
            <button class="close-modal" id="closeFeeModal">&times;</button>
        </div>
        <div id="feeModalBody" style="padding: 35px; overflow-y: auto; flex: 1; max-height: calc(92vh - 120px);">
            <div class="form-section-title"><span><i class="fas fa-list-ul"></i> Fee Particulars & Breakdown</span></div>
            
            <div style="background: #f8fbff; border-radius: 20px; padding: 20px; border: 1px solid #eef2ff; margin-bottom: 25px;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                    <thead>
                        <tr style="text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
                            <th style="padding: 0 15px;">Fee Type</th>
                            <th style="padding: 0 15px;">Standard Amount</th>
                            <th style="padding: 0 15px;">Benefit/Concession</th>
                            <th style="padding: 0 15px; text-align: right;">Net Payable</th>
                        </tr>
                    </thead>
                    <tbody id="admissionFeeTableBody">
                    @foreach($admissionFeeTypes as $ft)
                        <tr data-name="{{ $ft->name }}" data-default="{{ $ft->default_amount ?? '0.00' }}" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                            <td style="padding: 15px; border-radius: 12px 0 0 12px; font-weight: 700; color: var(--primary-blue);">{{ $ft->name }}</td>
                            <td style="padding: 15px;"><input type="number" step="0.01" class="form-control fee-amount" value="{{ $ft->default_amount ?? '0.00' }}" onchange="calcTotal(this)" style="width: 120px; height: 38px;"></td>
                            <td style="padding: 15px;"><input type="number" step="0.01" class="form-control fee-concession" value="0.00" onchange="calcTotal(this)" style="width: 120px; height: 38px; color: #ef4444; font-weight: 700;"></td>
                            <td style="padding: 15px; border-radius: 0 12px 12px 0; text-align: right;">
                                <input type="number" step="0.01" class="form-control fee-total" value="{{ $ft->default_amount ?? '0.00' }}" readonly style="width: 120px; height: 38px; text-align: right; background: #f0fdf4; border-color: #bbf7d0; color: #166534; font-weight: 800;">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="action-buttons">
                <button type="button" class="btn btn-outline" id="cancelFeeModal">Discard Changes</button>
                <button type="button" class="btn btn-blue" id="saveFeeBtn"><i class="fas fa-check-circle"></i> Confirm & Save Fees</button>
            </div>
        </div>
    </div>
</div>

<!-- Admission Receipt Modal -->
<div class="modal-overlay" id="receiptModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Admission Payment Receipt</h2>
            <button class="close-modal" id="closeReceiptModal">&times;</button>
        </div>
        <div class="modal-body" id="receiptModalBody">
            <!-- Populated by JS -->
        </div>
        <div class="action-buttons">
            <button class="btn btn-outline" id="waReceiptBtn"><i class="fab fa-whatsapp"></i> WhatsApp</button>
            <button class="btn btn-blue" id="actualPrintBtn"><i class="fas fa-print"></i> Print Receipt</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const studentModal = document.getElementById('studentModal');
    const openModalBtn = document.getElementById('addStudentBtn');
    const closeModalBtn = document.getElementById('closeStudentModal');
    const tabs = document.querySelectorAll('.details-tab');
    const panes = document.querySelectorAll('.tab-pane');
    let isEditing = false;
    let isViewOnly = false;
    let editStudentId = null;

    // Modal Control
    function openModal() { 
        studentModal.classList.add('open'); 
        document.body.style.overflow = 'hidden';
        switchTab('basic'); // ensure nav btn dataset is set
    }
    function closeModal() { 
        studentModal.classList.remove('open'); 
        document.body.style.overflow = '';
        const form = document.getElementById('studentAdmissionForm');
        form.reset();
        document.getElementById('photoPreview').innerHTML = '<i class="fas fa-camera" style="font-size: 3rem; color: #cbd5e1;"></i>';
        
        // Enabling fields for next use
        Array.from(form.elements).forEach(el => el.disabled = false);
        
        switchTab('basic');
        isEditing = false;
        isViewOnly = false;
        editStudentId = null;
    }

    openModalBtn.addEventListener('click', () => {
        isEditing = false;
        isViewOnly = false;
        document.querySelector('.modal-header h2').innerText = 'New Student Admission';
        openModal();
    });
    closeModalBtn.addEventListener('click', closeModal);

    function switchTab(tabId) {
        tabs.forEach(t => t.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
        document.getElementById(`${tabId}Tab`).classList.add('active');
        
        // Dynamic visibility of Nav buttons
        const prevBtn = document.getElementById('prevActionBtn');
        const nextBtn = document.getElementById('nextActionBtn');
        const saveBtn = document.getElementById('saveAdmissionBtn');
        
        if (isViewOnly) {
            prevBtn.style.display = (tabId === 'basic') ? 'none' : 'block';
            nextBtn.style.display = (tabId === 'photo') ? 'none' : 'block';
            saveBtn.style.display = 'none';
        } else if (tabId === 'basic') {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'block';
            saveBtn.style.display = 'none';
        } else if (tabId === 'photo') {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'none';
            saveBtn.style.display = 'block';
        } else {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'block';
            saveBtn.style.display = 'none';
        }

        // Set next target
        if (tabId === 'basic') nextBtn.dataset.next = 'parent';
        else if (tabId === 'parent') { nextBtn.dataset.next = 'previous'; prevBtn.dataset.prev = 'basic'; }
        else if (tabId === 'previous') { nextBtn.dataset.next = 'photo'; prevBtn.dataset.prev = 'parent'; }
        else if (tabId === 'photo') prevBtn.dataset.prev = 'previous';
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => switchTab(tab.dataset.tab));
    });

    const nextActionBtn = document.getElementById('nextActionBtn');
    if(nextActionBtn) {
        nextActionBtn.addEventListener('click', function() {
            if(this.dataset.next) switchTab(this.dataset.next);
        });
    }

    const prevActionBtn = document.getElementById('prevActionBtn');
    if(prevActionBtn) {
        prevActionBtn.addEventListener('click', function() {
            if(this.dataset.prev) switchTab(this.dataset.prev);
        });
    }

    // Registration No Auto-fill via AJAX
    const regInput = document.getElementById('registrationDropdown');
    let regLookupTimeout = null;
    if(regInput) {
        regInput.addEventListener('input', function() {
            clearTimeout(regLookupTimeout);
            const val = this.value.trim();
            if(!val) return;
            regLookupTimeout = setTimeout(() => lookupRegistration(val), 600);
        });
        regInput.addEventListener('blur', function() {
            const val = this.value.trim();
            if(val) lookupRegistration(val);
        });
    }

    function lookupRegistration(regNo) {
        fetch(`{{ url('admin/registration') }}/lookup?reg_no=` + encodeURIComponent(regNo), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success && data.student) {
                const s = data.student;
                // Auto-fill Basic Info
                document.getElementById('registration_student_id').value = s.id || '';
                document.getElementById('adm_student_name').value = s.student_name || s.name || '';
                document.getElementById('adm_dob').value = s.dob || s.date_of_birth || '';
                document.getElementById('adm_gender').value = s.gender || 'Male';
                document.getElementById('adm_mobile').value = s.father_mobile || s.mobile || '';
                document.getElementById('adm_email').value = s.email || '';
                document.getElementById('adm_address').value = [s.address1, s.address2, s.city].filter(Boolean).join(' ');
                // Auto-fill Parent Info
                document.getElementById('adm_father_name').value = s.father_name || '';
                document.getElementById('adm_father_mobile').value = s.father_mobile || '';
                document.getElementById('adm_mother_name').value = s.mother_name || '';
                document.getElementById('adm_mother_mobile').value = s.mother_mobile || '';
                // Auto-fill previous school
                if(document.getElementById('adm_prev_school'))
                    document.getElementById('adm_prev_school').value = s.previous_school || s.last_school || '';
                // Auto-select class
                const className = s.class_name || s.class || '';
                const classSelect = document.getElementById('admissionClass');
                if(classSelect && className) {
                    for(let i=0; i<classSelect.options.length; i++) {
                        if(classSelect.options[i].text.trim() === className.trim()) {
                            classSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
                // Visual feedback
                regInput.style.borderColor = '#22c55e';
                regInput.style.boxShadow = '0 0 0 3px rgba(34,197,94,0.15)';
            } else {
                // Invalid reg no
                regInput.style.borderColor = '#ef4444';
                regInput.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.15)';
                document.getElementById('registration_student_id').value = '';
            }
        })
        .catch(() => {
            // Silently fail if endpoint doesn't exist
        });
    }

    // Photo Preview
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form Submission
    document.getElementById('studentAdmissionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Structure data for controller (nesting into basic, parent, previous)
        const structuredData = new FormData();
        structuredData.append('_token', '{{ csrf_token() }}');
        if(isEditing) structuredData.append('_method', 'PUT');

        // This is a bit manual but ensures the nested structure expected by StudentAdmissionController
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('parent[') || key.startsWith('previous[')) {
                structuredData.append(key, value);
            } else if (key !== 'photo' && key !== '_token') {
                structuredData.append(`basic[${key}]`, value);
            }
        }
        if(formData.get('photo')) structuredData.append('photo', formData.get('photo'));

        const baseUrl = '{{ url('admin/student-admission') }}';
        const url = isEditing ? `${baseUrl}/${editStudentId}` : baseUrl;

        Swal.fire({
            title: isEditing ? 'Updating Admission...' : 'Saving Admission...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(url, {
            method: 'POST',
            body: structuredData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire('Success!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', data.message || 'Validation failed', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Something went wrong on the server', 'error');
        });
    });

    // Global tools
    window.confirmDeleteStudent = function(id) {
        Swal.fire({
            title: 'Delete Student Admission?',
            text: "This will permanently remove the student record and associated fee data.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4d4d',
            confirmButtonText: 'Yes, Delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/student-admission/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                    }
                });
            }
        });
    }

    window.editStudent = function(id) {
        isEditing = true;
        isViewOnly = false;
        editStudentId = id;
        document.querySelector('#studentModal .modal-header h2').innerHTML = '<i class="fas fa-edit"></i> Edit Student Admission';
        
        // Remove view-only styling if it was applied
        document.querySelectorAll('#studentAdmissionForm label').forEach(l => l.classList.remove('view-only-label'));
        
        Swal.fire({ title: 'Fetching Details...', didOpen: () => { Swal.showLoading(); } });

        fetch(`{{ url('admin/student-admission') }}/${id}/show`)
        .then(res => res.json())
        .then(data => {
            Swal.close();
            const form = document.getElementById('studentAdmissionForm');
            Array.from(form.elements).forEach(el => el.disabled = false);
            
            // Set session etc
            form.querySelector('[name="admission_no"]').value = data.admission_no || '';
            form.querySelector('[name="admission_date"]').value = data.admission_date || '';
            form.querySelector('[name="registration_no"]').value = data.registration_no || '';
            form.querySelector('[name="registration_student_id"]').value = data.registration_student_id || '';
            form.querySelector('[name="class_id"]').value = data.class_id || '';
            form.querySelector('[name="section_id"]').value = data.section_id || '';
            form.querySelector('[name="session"]').value = data.session || '';
            form.querySelector('[name="student_name"]').value = data.student_name || '';
            form.querySelector('[name="gender"]').value = data.gender || 'Male';
            form.querySelector('[name="dob"]').value = data.dob || '';
            form.querySelector('[name="mobile"]').value = data.mobile || '';
            form.querySelector('[name="email"]').value = data.email || '';
            form.querySelector('[name="aadhar_no"]').value = data.aadhar_no || '';
            form.querySelector('[name="address"]').value = data.address || '';

            // Fill Parent Info
            if(data.parent) {
                form.querySelector('[name="parent[father_name]"]').value = data.parent.father_name || '';
                form.querySelector('[name="parent[father_phone]"]').value = data.parent.father_phone || '';
                form.querySelector('[name="parent[father_occupation]"]').value = data.parent.father_occupation || '';
                form.querySelector('[name="parent[mother_name]"]').value = data.parent.mother_name || '';
                form.querySelector('[name="parent[mother_phone]"]').value = data.parent.mother_phone || '';
                form.querySelector('[name="parent[mother_occupation]"]').value = data.parent.mother_occupation || '';
            }

            // Fill Previous Info
            if(data.previous_school) {
                form.querySelector('[name="previous[school_name]"]').value = data.previous_school.school_name || '';
                form.querySelector('[name="previous[class]"]').value = data.previous_school.previous_class || '';
                form.querySelector('[name="previous[tc_no]"]').value = data.previous_school.tc_no || '';
            }

            if(data.photo_path) {
                document.getElementById('photoPreview').innerHTML = `<img src="/storage/${data.photo_path}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }

            openModal();
        });
    }

    window.viewStudent = function(id) {
        isViewOnly = true;
        isEditing = false;
        document.querySelector('#studentModal .modal-header h2').innerHTML = '<i class="fas fa-eye"></i> View Student Details';
        
        // Apply view-only styling
        document.querySelectorAll('#studentAdmissionForm label').forEach(l => l.classList.add('view-only-label'));

        Swal.fire({ title: 'Loading Details...', didOpen: () => { Swal.showLoading(); } });

        fetch(`{{ url('admin/student-admission') }}/${id}/show`)
        .then(res => res.json())
        .then(data => {
            Swal.close();
            const form = document.getElementById('studentAdmissionForm');
            
            // Fill fields
            form.querySelector('[name="admission_no"]').value = data.admission_no || 'N/A';
            form.querySelector('[name="admission_date"]').value = data.admission_date || 'N/A';
            form.querySelector('[name="registration_no"]').value = data.registration_no || 'N/A';
            form.querySelector('[name="class_id"]').value = data.class_id || '';
            form.querySelector('[name="section_id"]').value = data.section_id || '';
            form.querySelector('[name="session"]').value = data.session || 'N/A';
            form.querySelector('[name="student_name"]').value = data.student_name || 'N/A';
            form.querySelector('[name="gender"]').value = data.gender || 'Male';
            form.querySelector('[name="dob"]').value = data.dob || 'N/A';
            form.querySelector('[name="mobile"]').value = data.mobile || 'N/A';
            form.querySelector('[name="email"]').value = data.email || 'N/A';
            form.querySelector('[name="aadhar_no"]').value = data.aadhar_no || 'N/A';
            form.querySelector('[name="address"]').value = data.address || 'N/A';

            if(data.parent) {
                form.querySelector('[name="parent[father_name]"]').value = data.parent.father_name || 'N/A';
                form.querySelector('[name="parent[father_phone]"]').value = data.parent.father_phone || 'N/A';
                form.querySelector('[name="parent[mother_name]"]').value = data.parent.mother_name || 'N/A';
            }
            
            if(data.photo_path) {
                document.getElementById('photoPreview').innerHTML = `<img src="/storage/${data.photo_path}" style="width: 100%; height: 100%; object-fit: cover;">`;
            } else {
                document.getElementById('photoPreview').innerHTML = '<i class="fas fa-user-circle" style="font-size: 5rem; color: #cbd5e1;"></i>';
            }

            // Disable all fields
            Array.from(form.elements).forEach(el => el.disabled = true);
            
            switchTab('basic'); 
            openModal();
        });
    }

    // Fee Modal logic
    const feeModal = document.getElementById('feeModal');
    let currentFeeStudentId = null;

    document.querySelectorAll('.fee-icon').forEach(btn => {
        btn.addEventListener('click', function() {
            currentFeeStudentId = this.dataset.id;
            document.getElementById('feeStudentName').innerText = this.dataset.student;
            
            Swal.fire({ title: 'Loading Fees...', didOpen: () => { Swal.showLoading(); } });
            fetch(`{{ url('admin/student-admission') }}/${currentFeeStudentId}/get-fees`)
                .then(res => res.json())
                .then(data => {
                    Swal.close();
                    document.querySelectorAll('#admissionFeeTableBody tr').forEach(tr => {
                        const feeName = tr.dataset.name;
                        const matchingFee = data.success && data.fees ? data.fees.find(f => f.fee_name === feeName) : null;
                        if(matchingFee) {
                            tr.querySelector('.fee-amount').value = matchingFee.amount;
                            tr.querySelector('.fee-concession').value = matchingFee.concession;
                            tr.querySelector('.fee-total').value = matchingFee.total;
                        } else {
                            // Auto-load default amount from fee structure if available
                            const defaultAmt = tr.dataset.default || '0.00';
                            tr.querySelector('.fee-amount').value = defaultAmt;
                            tr.querySelector('.fee-concession').value = '0.00';
                            tr.querySelector('.fee-total').value = defaultAmt;
                        }
                    });
                    feeModal.classList.add('open');
                })
                .catch(() => { Swal.close(); feeModal.classList.add('open'); });
        });
    });

    document.getElementById('closeFeeModal').addEventListener('click', () => feeModal.classList.remove('open'));
    document.getElementById('cancelFeeModal').addEventListener('click', () => feeModal.classList.remove('open'));

    window.calcTotal = function(input) {
        const tr = input.closest('tr');
        const amt = parseFloat(tr.querySelector('.fee-amount').value) || 0;
        const conc = parseFloat(tr.querySelector('.fee-concession').value) || 0;
        tr.querySelector('.fee-total').value = (amt - conc).toFixed(2);
    };

    document.getElementById('saveFeeBtn').addEventListener('click', function() {
        const fees = [];
        document.querySelectorAll('#admissionFeeTableBody tr').forEach(tr => {
            const amt = parseFloat(tr.querySelector('.fee-amount').value) || 0;
            if(amt > 0) {
                fees.push({
                    name: tr.dataset.name,
                    amount: amt,
                    concession: parseFloat(tr.querySelector('.fee-concession').value) || 0
                });
            }
        });

        Swal.fire({
            title: 'Assigning Fees...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(`{{ url('admin/student-admission') }}/${currentFeeStudentId}/save-fees`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ fees: fees })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire('Success!', data.message, 'success').then(() => feeModal.classList.remove('open'));
            } else {
                Swal.fire('Error', data.message || 'Saving failed', 'error');
            }
        });
    });
    // --- Admission Receipt Logic ---
    const receiptModal = document.getElementById('receiptModal');
    const closeReceiptModal = document.getElementById('closeReceiptModal');
    const receiptModalBody = document.getElementById('receiptModalBody');
    const waReceiptBtn = document.getElementById('waReceiptBtn');

    const schoolInfo = {
        name: 'Hazrat Ali Academy',
        addr: 'Pakki Sarai Chandwara, Muzaffarpur',
        phone: '9102277998, 9835281616',
        udise: '10140614302',
        session: '2025-2026',
        logo: 'https://decentdemo.in/school/images/school-logo.png'
    };

    window.showAdmissionReceipt = function(id) {
        Swal.fire({ title: 'Generating Receipt...', didOpen: () => { Swal.showLoading(); } });

        fetch(`{{ url('admin/student-admission') }}/${id}/show`)
        .then(res => res.json())
        .then(student => {
            fetch(`{{ url('admin/student-admission') }}/${id}/fees`)
            .then(res => res.json())
            .then(data => {
                Swal.close();
                const fees = data.fees || [];
                renderReceipt(student, fees);
                receiptModal.classList.add('open');

                waReceiptBtn.onclick = () => {
                   const msg = buildWAMessage(student, fees);
                   window.open(`https://wa.me/91${student.mobile}?text=${encodeURIComponent(msg)}`, '_blank');
                };
            });
        });
    };

    closeReceiptModal.addEventListener('click', () => receiptModal.classList.remove('open'));

    function renderReceipt(student, fees) {
        const totalPaid = fees.reduce((sum, f) => sum + parseFloat(f.paid || 0), 0);
        const subTotal = fees.reduce((sum, f) => sum + parseFloat(f.total || 0), 0);
        const totalDues = subTotal - totalPaid;
        const receiptNo = `ADM-${student.session || '2025'}-${String(student.id).padStart(5, '0')}`;
        const dateStr = student.admission_date || new Date().toISOString().split('T')[0];

        const feeRows = fees.map((f, i) => `
            <tr>
                <td>${i + 1}</td>
                <td>${f.fee_name}</td>
                <td style="text-align:right">₹${parseFloat(f.amount).toFixed(2)}</td>
            </tr>
        `).join('');

        const buildHalf = (type) => {
            const isOff = type === 'office';
            const label = isOff ? '🏫 FOR OFFICE COPY' : '👨‍👩‍👦 FOR PARENTS COPY';
            const badge = isOff ? 'OFFICE' : 'PARENTS';
            
            return `
            <div class="receipt ${type}">
                <div class="rc-bar">
                    <span class="rc-bar-label">${label}</span>
                    <span class="rc-bar-badge">${badge}</span>
                    <span class="rc-bar-rcno">Receipt No: <b>${receiptNo}</b></span>
                </div>
                <div class="rc-hdr">
                    <div class="rc-logo"><img src="${schoolInfo.logo}" alt="logo"></div>
                    <div>
                        <div class="rc-sname">${schoolInfo.name}</div>
                        <div class="rc-saddr">${schoolInfo.addr}<br>UDISE: ${schoolInfo.udise} | ${schoolInfo.phone}</div>
                    </div>
                </div>
                <div class="rc-adm-banner"><div class="rc-adm-title">Admission Payment Receipt</div></div>
                <div class="rc-info">
                    <div class="rc-ic"><span class="rc-lbl">Session:</span><span class="rc-val">${student.session || 'N/A'}</span></div>
                    <div class="rc-ic"><span class="rc-lbl">Adm Date:</span><span class="rc-val">${dateStr}</span></div>
                    <div class="rc-ic"><span class="rc-lbl">Student Name:</span><span class="rc-val">${student.student_name}</span></div>
                    <div class="rc-ic"><span class="rc-lbl">Father's Name:</span><span class="rc-val">${(student.parent?.father_name || student.parent_name) || 'N/A'}</span></div>
                    <div class="rc-ic"><span class="rc-lbl">Class:</span><span class="rc-val">${(student.class_info?.name || student.class_info?.name) || (student.classInfo?.name || 'N/A')} - ${(student.section_info?.name || student.section_info?.name) || (student.sectionInfo?.name || 'N/A')}</span></div>
                    <div class="rc-ic"><span class="rc-lbl">Adm No:</span><span class="rc-val">${student.admission_no}</span></div>
                    <div class="rc-ic"><span class="rc-lbl">Mobile:</span><span class="rc-val">${student.mobile}</span></div>
                    <div class="rc-ic"><span class="rc-lbl">Reg No:</span><span class="rc-val">${student.registration_no || 'N/A'}</span></div>
                </div>
                <table class="rc-tbl">
                    <thead><tr><th>#</th><th>Particulars</th><th style="text-align:right">Amount</th></tr></thead>
                    <tbody>${feeRows}</tbody>
                    <tfoot>
                        <tr><td colspan="2" style="text-align:right">Sub Total:</td><td style="text-align:right">₹${subTotal.toFixed(2)}</td></tr>
                        <tr><td colspan="2" style="text-align:right; color:green;">Amount Paid:</td><td style="text-align:right; color:green;">₹${totalPaid.toFixed(2)}</td></tr>
                        <tr><td colspan="2" style="text-align:right; color:red;">Total Dues:</td><td style="text-align:right; color:red;">₹${totalDues.toFixed(2)}</td></tr>
                    </tfoot>
                </table>
                <div class="rc-ftr">
                    <div class="rc-fnote">Computer generated receipt. Valid proof of payment.</div>
                    <div>
                        <div class="rc-sig-line"></div>
                        <div class="rc-sig-lbl">Authorized Signatory</div>
                    </div>
                </div>
            </div>`;
        };

        receiptModalBody.innerHTML = `
            <div class="rc-outer">
                ${buildHalf('office')}
                <div class="rc-cut">
                    <div class="rc-cut-line"></div>
                    <i class="fas fa-cut"></i>
                    <span>CUT HERE - PARENTS COPY BELOW</span>
                    <div class="rc-cut-line"></div>
                </div>
                ${buildHalf('parents')}
            </div>
        `;

        document.getElementById('actualPrintBtn').onclick = () => {
            const printWindow = window.open('', '_blank');
            const styles = Array.from(document.querySelectorAll('style')).map(s => s.innerHTML).join('\n');
            const fonts = '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">';
            
            printWindow.document.write(`
                <html>
                <head>
                    <title>Admission Receipt - ${student.student_name}</title>
                    ${fonts}
                    <style>
                        ${styles}
                        body { background: #fff !important; margin: 0; padding: 0; }
                        .rc-outer { padding: 0.5in !important; background: #fff !important; }
                        .receipt { border: 2.5px solid #1e3a5f !important; margin-bottom: 30px; }
                        @media print {
                            .rc-cut { margin: 30px 0 !important; }
                        }
                    </style>
                </head>
                <body>
                    ${receiptModalBody.innerHTML}
                    <script>
                        window.onload = () => {
                            window.print();
                            // window.close();  // Removed for better focus on preview
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        };
    }

    function buildWAMessage(student, fees) {
        const totalPaid = fees.reduce((sum, f) => sum + parseFloat(f.paid || 0), 0);
        return `🏫 *ADMISSION RECEIPT - ${schoolInfo.name}*\n` +
               `------------------------------\n` +
               `Student: *${student.student_name}*\n` +
               `Class: ${student.class_info?.name || 'N/A'}\n` +
               `Adm No: ${student.admission_no}\n` +
               `Total Paid: ₹${totalPaid.toFixed(2)}\n` +
               `------------------------------\n` +
               `Thank you for choosing ${schoolInfo.name}!`;
    }
</script>
@endpush
