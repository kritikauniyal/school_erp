@extends('layouts.app')

@section('title', 'Student Admission Manager')
@section('page_icon', 'fas fa-user-graduate')

@push('styles')
<style>
    /* Modal & Tabs Specific styles */
    .modal-overlay { position: fixed; inset: 0; background: rgba(10, 20, 60, 0.45); backdrop-filter: blur(4px); z-index: 10001; display: none; align-items: flex-start; justify-content: center; padding: 40px 20px; overflow-y: auto; }
    .modal-overlay.open { display: flex; }
    .modal-container { 
        background: white; border-radius: 24px; width: 100%; max-width: 1200px; 
        max-height: 90vh; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        position: relative; margin-bottom: 40px; display: flex; flex-direction: column; overflow: hidden;
    }

    .modal-header { padding: 20px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff; flex-shrink: 0; }
    .modal-header h2 { font-size: 1.3rem; color: var(--primary-blue); font-weight: 700; }
    .close-modal { background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 10px; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; color: #64748b; }

    #studentAdmissionForm { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
    
    .details-tabs { 
        display: flex; gap: 10px; padding: 14px 30px; background: #f8fbff; 
        border-bottom: 1px solid #f1f5f9; flex-shrink: 0;
    }
    .details-tab { 
        padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; 
        color: #94a3b8; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 8px;
    }
    .details-tab.active { background: white; color: var(--primary-blue); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    .tab-content-area { flex: 1; overflow-y: auto; padding: 30px; }

    .tab-pane { display: none; }
    .tab-pane.active { display: block; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from{opacity:0; transform: translateY(5px);} to{opacity:1; transform: translateY(0);} }

    .form-section { margin-bottom: 35px; }
    .form-section h2 { 
        font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; 
        color: var(--primary-blue); font-weight: 800; border-bottom: 1px solid #f1f5f9; 
        padding-bottom: 10px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
    }

    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; margin-bottom: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
    #studentAdmissionForm .form-group input, #studentAdmissionForm .form-group select, #studentAdmissionForm .form-group textarea { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 14px; font-size: 0.9rem; transition: 0.3s; }
    #studentAdmissionForm .form-group input:focus, #studentAdmissionForm .form-group select:focus { border-color: var(--primary-blue); background: white; box-shadow: 0 0 0 4px rgba(72,143,228,0.1); }

    .action-buttons { 
        padding: 24px 30px; border-top: 1px solid #f1f5f9; background: #fff; 
        display: flex; justify-content: flex-end; gap: 12px; flex-shrink: 0;
    }
    
    .status-badge { padding: 4px 10px; border-radius: 30px; font-weight: 700; font-size: 0.7rem; }
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
                <th>Roll / Session</th>
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
                <td>{{ ($student->roll_no ?? 'N/A') . ' / ' . ($student->session ?? 'N/A') }}</td>
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
                        <h2>Basic Information</h2>
                        <div class="form-row">
                            <div class="form-group"><label class="required">Admission No.</label><input type="text" name="admission_no" id="adm_no" value="{{ $nextAdmissionNo }}"></div>
                            <div class="form-group"><label class="required">Admission Date</label><input type="date" name="admission_date" id="adm_date" value="{{ date('Y-m-d') }}"></div>
                            <div class="form-group">
                                <label class="required">Registration No.</label>
                                <input type="hidden" name="registration_student_id" id="registration_student_id">
                                <input type="text" name="registration_no" id="registrationDropdown">
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
                            <div class="form-group" style="grid-column: span 2;"><label>Full Address</label><textarea name="address" id="adm_address" rows="2"></textarea></div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PARENT INFO -->
                <div class="tab-pane" id="parentTab">
                    <div class="form-section">
                        <h2>Father's Information</h2>
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
                        <h2>Mother's Information</h2>
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
                        <h2>Previous Academic Info</h2>
                        <div class="form-row">
                            <div class="form-group" style="grid-column: span 2;"><label>School Name</label><input type="text" name="previous[school_name]" id="adm_prev_school"></div>
                            <div class="form-group"><label>Class</label><input type="text" name="previous[class]"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>TC No.</label><input type="text" name="previous[tc_no]"></div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: UPLOAD PHOTO -->
                <div class="tab-pane" id="photoTab">
                    <div class="form-section">
                        <h2>Student Photograph</h2>
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 15px; padding: 20px;">
                            <div id="photoPreview" style="width: 150px; height: 150px; border-radius: 20px; border: 2px dashed var(--border-color); display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8fafc;">
                                <i class="fas fa-camera" style="font-size: 3rem; color: #cbd5e1;"></i>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('student_photo_input').click()"><i class="fas fa-upload"></i> Select Photo</button>
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
    <div class="modal-container" style="max-width: 800px;">
        <div class="modal-header">
            <h2>Assign Admission Fees: <span id="feeStudentName"></span></h2>
            <button class="close-modal" id="closeFeeModal">&times;</button>
        </div>
        <div style="padding: 30px;">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="background: #f8fbff; color: var(--blue);">Fee Type</th>
                        <th style="background: #f8fbff; color: var(--blue);">Amount</th>
                        <th style="background: #f8fbff; color: var(--blue);">Concession</th>
                        <th style="background: #f8fbff; color: var(--blue);">Total</th>
                    </tr>
                </thead>
                <tbody id="admissionFeeTableBody">
                @foreach($admissionFeeTypes as $ft)
                    <tr data-name="{{ $ft->name }}" data-default="{{ $ft->default_amount ?? '0.00' }}">
                        <td style="font-weight: 700;">{{ $ft->name }}</td>
                        <td><input type="number" step="0.01" class="form-control fee-amount" value="{{ $ft->default_amount ?? '0.00' }}" onchange="calcTotal(this)"></td>
                        <td><input type="number" step="0.01" class="form-control fee-concession" value="0.00" onchange="calcTotal(this)"></td>
                        <td><input type="number" step="0.01" class="form-control fee-total" value="{{ $ft->default_amount ?? '0.00' }}" readonly></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="action-buttons">
                <button type="button" class="btn btn-outline" id="cancelFeeModal">Cancel</button>
                <button type="button" class="btn btn-blue" id="saveFeeBtn">Save Fees</button>
            </div>
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
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            saveBtn.style.display = 'none';
            return;
        }

        if (tabId === 'basic') {
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
        document.querySelector('.modal-header h2').innerText = 'Edit Student Admission';
        
        Swal.fire({ title: 'Loading Data...', didOpen: () => { Swal.showLoading(); } });

        fetch(`{{ url('admin/student-admission') }}/${id}/show`)
        .then(res => res.json())
        .then(data => {
            Swal.close();
            const form = document.getElementById('studentAdmissionForm');
            Array.from(form.elements).forEach(el => el.disabled = false);
            
            // Fill Basic Info
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
        document.querySelector('.modal-header h2').innerText = 'View Student Details';
        Swal.fire({ title: 'Loading Data...', didOpen: () => { Swal.showLoading(); } });

        fetch(`{{ url('admin/student-admission') }}/${id}/show`)
        .then(res => res.json())
        .then(data => {
            Swal.close();
            const form = document.getElementById('studentAdmissionForm');
            
            // Fill fields
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

            if(data.parent) {
                form.querySelector('[name="parent[father_name]"]').value = data.parent.father_name || '';
                form.querySelector('[name="parent[father_phone]"]').value = data.parent.father_phone || '';
                form.querySelector('[name="parent[mother_name]"]').value = data.parent.mother_name || '';
            }
            
            if(data.photo_path) {
                document.getElementById('photoPreview').innerHTML = `<img src="/storage/${data.photo_path}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }

            // Disable all fields
            Array.from(form.elements).forEach(el => el.disabled = true);
            
            switchTab('basic'); // Ensure nav buttons are hidden correctly via isViewOnly
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
</script>
@endpush
