@extends('layouts.app')

@section('title', 'Student Data Manager')
@section('page_icon', 'fas fa-users')

@push('styles')
<style>
    /* Table Responsive & Styling */
    .header-group { margin-bottom: 24px; }
    .header-group h1 { font-size: 1.8rem; color: var(--primary-blue); font-weight: 700; display: flex; align-items: center; gap: 12px; }
    .header-group h1 i { color: var(--primary-orange); }
    .header-group p { color: #64748b; margin-top: 4px; font-size: 0.95rem; }

    .top-actions { display: flex; gap: 12px; margin-bottom: 24px; }
    .btn-pill { border-radius: 50px; padding: 10px 24px; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 8px; }
    .btn-orange { background: var(--primary-orange); color: white; }
    .btn-orange-light { background: #fff2e7; color: var(--primary-orange); }
    .btn-blue-outline { background: white; border: 1.5px solid var(--primary-blue); color: var(--primary-blue); }
    .btn-pill:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    /* Updated Filter Section */
    .filter-section { 
        background: #f8fbff; border-radius: 24px; padding: 20px 24px; 
        display: flex; align-items: flex-end; gap: 20px; margin-bottom: 24px;
        border: 1px solid #edf2f7;
    }
    .filter-item { flex: 1; display: flex; flex-direction: column; gap: 6px; }
    .filter-item label { font-size: 0.72rem; font-weight: 800; color: #488fe4; text-transform: uppercase; }
    .filter-item input, .filter-item select { 
        background: white; border: 1px solid #e2e8f0; border-radius: 14px; 
        padding: 9px 14px; font-size: 0.9rem; color: #475569;
    }

    /* Modal Styles */
    .modal-overlay { position: fixed; inset: 0; background: rgba(10, 20, 60, 0.45); backdrop-filter: blur(4px); z-index: 10001; display: none; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.open { display: flex; }
    .modal-container { 
        background: white; border-radius: 30px; width: 100%; max-width: 950px; 
        max-height: 90vh; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        display: flex; flex-direction: column; overflow: hidden; animation: modalSlideUp 0.3s ease;
    }
    @keyframes modalSlideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    .modal-header { padding: 24px 30px; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff; z-index: 10; flex-shrink: 0; }
    .modal-header h2 { font-size: 1.4rem; color: var(--primary-blue); font-weight: 800; display: flex; align-items: center; gap: 12px; margin: 0; }
    .modal-header h2 i { color: var(--primary-orange); }
    .close-modal { background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 10px; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; color: #64748b; }

    .modal-body { flex: 1; overflow-y: auto; padding: 30px; background: #fff; }
    .modal-actions { padding: 24px 30px; border-top: 2px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 12px; background: #f8fbff; z-index: 10; flex-shrink: 0; }

    .btn-modal { 
        padding: 10px 24px; border-radius: 14px; border: none; font-weight: 700; 
        font-size: 0.9rem; cursor: pointer; transition: 0.2s; display: flex; 
        align-items: center; justify-content: center; min-width: 130px;
    }
    .btn-modal.btn-outline { background: #eef2f7; color: #64748b; }
    .btn-modal.btn-orange { background: var(--primary-orange); color: white; }
    .btn-modal:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); opacity: 0.9; }

    /* Fix for form wrapping flex children */
    #studentEntryForm { display: flex; flex-direction: column; flex: 1; overflow: hidden; }

    /* Form Styles */
    .form-section { margin-bottom: 30px; }
    .form-section h3 { font-size: 0.85rem; text-transform: uppercase; color: var(--primary-blue); font-weight: 800; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
    .form-group input, .form-group select, .form-group textarea { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 14px; font-size: 0.9rem; transition: 0.3s; width: 100%; }
    .form-group input:focus { border-color: var(--primary-blue); background: white; box-shadow: 0 0 0 4px rgba(72,143,228,0.1); }
    .required::after { content: " *"; color: #ef4444; }
</style>
@endpush

@section('content')
<div class="card" style="padding: 30px; border-radius: 32px;">
    <div class="header-group">
        <h1><i class="fas fa-users"></i> Student Data View</h1>
        <p>View all students, add single entry or bulk import</p>
    </div>

    <div class="top-actions">
        <button class="btn-pill btn-orange" id="addStudentBtn"><i class="fas fa-user-plus"></i> Single Entry</button>
        <button class="btn-pill btn-orange-light"><i class="fas fa-file-excel"></i> Bulk Entry</button>
    </div>

    <div class="filter-section">
        <div class="filter-item">
            <label>Search</label>
            <input type="text" id="searchInput" placeholder="Name, Reg No...">
        </div>
        <div class="filter-item">
            <label>Class</label>
            <select id="filterClass">
                <option value="">All</option>
                @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
            </select>
        </div>
        <div class="filter-item">
            <label>Section</label>
            <select id="filterSection">
                <option value="">All</option>
                @foreach($sections as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
            </select>
        </div>
        <button class="btn-pill btn-blue-outline" onclick="filterTable()"><i class="fas fa-search"></i> Search</button>
    </div>

    <div class="table-wrapper" style="border:none; box-shadow:none; overflow-x: auto;">
        <table id="studentsTable" style="min-width: 1300px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">ADM NO</th>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">STUDENT NAME</th>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">CLASS</th>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">SECTION</th>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">FATHER'S NAME</th>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">MOBILE</th>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">TOTAL DUES (₹)</th>
                    <th style="background: #488fe4; color:white; border: 1px solid #3a6fa8; padding: 12px;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr data-id="{{ $student->id }}" data-class="{{ $student->class_id }}" data-section="{{ $student->section_id }}">
                    <td style="border: 1px solid #d9e2ec; padding: 12px;">{{ $student->admission_no }}</td>
                    <td style="border: 1px solid #d9e2ec; padding: 12px; font-weight: 500;">{{ $student->student_name }}</td>
                    <td style="border: 1px solid #d9e2ec; padding: 12px;">{{ $student->classInfo->name ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d9e2ec; padding: 12px;">{{ $student->sectionInfo->name ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d9e2ec; padding: 12px;">{{ $student->parent->father_name ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d9e2ec; padding: 12px;">{{ $student->mobile }}</td>
                    <td style="border: 1px solid #d9e2ec; padding: 12px; font-weight: 600;">₹ {{ number_format($student->dues ?? 0, 0) }}</td>
                    <td style="border: 1px solid #d9e2ec; padding: 12px;">
                        <div style="display: flex; gap: 12px; justify-content: center; align-items: center;">
                            <i class="fas fa-eye" style="color: #488fe4; cursor:pointer;" title="View" onclick="viewStudent({{ $student->id }})"></i>
                            <i class="fas fa-edit" style="color: #f9b000; cursor:pointer;" title="Edit" onclick="editStudent({{ $student->id }})"></i>
                            <i class="fas fa-trash" style="color: #ef4444; cursor:pointer;" title="Delete" onclick="deleteStudent({{ $student->id }})"></i>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Dynamic Pagination Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; color: #64748b; font-size: 0.88rem; border-top: 1px solid #f1f5f9; padding-top: 20px;">
        <div>
            @if($students->total() > 0)
                Page <strong>{{ $students->currentPage() }}</strong> of <strong>{{ $students->lastPage() }}</strong> | 
                <strong>{{ $students->firstItem() }}</strong>-<strong>{{ $students->lastItem() }}</strong> of <strong>{{ $students->total() }}</strong> records
            @else
                No records found.
            @endif
        </div>
        <div style="display: flex; gap: 8px;">
            @if ($students->hasPages())
                {{-- Previous --}}
                @if ($students->onFirstPage())
                    <span style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0; color: #cbd5e1; cursor: not-allowed;"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $students->previousPageUrl() }}" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: white; border: 1px solid #e2e8f0; color: #64748b; text-decoration:none;"><i class="fas fa-chevron-left"></i></a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($students->getUrlRange(max(1, $students->currentPage() - 2), min($students->lastPage(), $students->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" 
                       style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                       background: {{ $page == $students->currentPage() ? '#488fe4' : 'white' }}; 
                       color: {{ $page == $students->currentPage() ? 'white' : '#64748b' }}; 
                       border: 1px solid {{ $page == $students->currentPage() ? '#488fe4' : '#e2e8f0' }}; 
                       text-decoration:none; font-weight: 600;">
                       {{ $page }}
                    </a>
                @endforeach

                {{-- Next --}}
                @if ($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: white; border: 1px solid #e2e8f0; color: #64748b; text-decoration:none;"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0; color: #cbd5e1; cursor: not-allowed;"><i class="fas fa-chevron-right"></i></span>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="modal-overlay" id="studentModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitle">Add New Student</h2>
            <button class="close-modal">&times;</button>
        </div>
        <form id="studentEntryForm">
            @csrf
            <input type="hidden" name="id" id="student_id">
            <input type="hidden" name="_method" id="_method" value="POST">
            <div class="modal-body">
                <div class="form-section">
                    <h3>Basic Information</h3>
                    <div class="form-row">
                        <div class="form-group"><label class="required">Registration No.</label><input type="text" name="registration_no" id="registration_no" required></div>
                        <div class="form-group"><label class="required">Admission No.</label><input type="text" name="admission_no" id="admission_no" required></div>
                        <div class="form-group"><label>Admission Date</label><input type="date" name="admission_date" id="admission_date"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="required">Student Name</label><input type="text" name="student_name" id="student_name" required></div>
                        <div class="form-group"><label>Gender</label>
                            <select name="gender" id="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Date of Birth</label><input type="date" name="dob" id="dob"></div>
                    </div>
                </div>
                <div class="form-section">
                    <h3>Academic & Category</h3>
                    <div class="form-row">
                        <div class="form-group"><label class="required">Class</label>
                            <select name="class_id" id="class_id" required>
                                @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label class="required">Section</label>
                            <select name="section_id" id="section_id" required>
                                @foreach($sections as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label>Roll No.</label><input type="text" name="roll_no" id="roll_no"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Category</label>
                            <select name="category" id="category">
                                <option value="General">General</option><option value="OBC">OBC</option><option value="SC">SC</option><option value="ST">ST</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Religion</label>
                            <select name="religion" id="religion">
                                <option value="Hindu">Hindu</option><option value="Muslim">Muslim</option><option value="Christian">Christian</option><option value="Sikh">Sikh</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Caste</label><input type="text" name="caste" id="caste"></div>
                    </div>
                </div>
                <div class="form-section">
                    <h3>Guardian Information</h3>
                    <div class="form-row">
                        <div class="form-group"><label>Father's Name</label><input type="text" name="father_name" id="father_name"></div>
                        <div class="form-group"><label>Father's Mobile</label><input type="text" name="father_mobile" id="father_mobile"></div>
                        <div class="form-group"><label>Mother's Name</label><input type="text" name="mother_name" id="mother_name"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Mother's Mobile</label><input type="text" name="mother_mobile" id="mother_mobile"></div>
                        <div class="form-group"><label>Guardian's Name</label><input type="text" name="guardian_name" id="guardian_name"></div>
                        <div class="form-group"><label>Guardian's Mobile</label><input type="text" name="guardian_mobile" id="guardian_mobile"></div>
                    </div>
                </div>
                <div class="form-section" style="margin-bottom:0;">
                    <h3>Address & Contact</h3>
                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;"><label>Address</label><textarea name="address_1" id="address_1" rows="2"></textarea></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Mobile (Student)</label><input type="text" name="student_mobile" id="student_mobile"></div>
                        <div class="form-group"><label>City</label><input type="text" name="city" id="city"></div>
                        <div class="form-group"><label>PIN Code</label><input type="text" name="pin" id="pin"></div>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-outline close-modal">Cancel</button>
                <button type="submit" class="btn-modal btn-orange" id="saveBtn">Save Student Record</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const studentModal = document.getElementById('studentModal');
    const form = document.getElementById('studentEntryForm');
    let isEditing = false;
    let isViewOnly = false;

    function openModal(title = 'Add New Student') {
        document.getElementById('modalTitle').innerText = title;
        studentModal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        studentModal.classList.remove('open');
        document.body.style.overflow = '';
        form.reset();
        isEditing = false;
        isViewOnly = false;
        // Restore all fields
        Array.from(form.querySelectorAll('input, select, textarea')).forEach(el => el.disabled = false);
        document.getElementById('saveBtn').style.display = 'block';
    }

    document.getElementById('addStudentBtn').addEventListener('click', () => {
        isEditing = false;
        isViewOnly = false;
        document.getElementById('_method').value = 'POST';
        openModal();
    });

    document.querySelectorAll('.close-modal').forEach(btn => btn.addEventListener('click', closeModal));

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Confirm Save?',
            text: "Are you sure you want to save this student record?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#488fe4',
            cancelButtonColor: '#ff913b',
            confirmButtonText: 'Yes, Save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const id = document.getElementById('student_id').value;
                const method = document.getElementById('_method').value;
                const url = isEditing ? `{{ url('admin/student-entry') }}/${id}` : `{{ url('admin/student-entry') }}`;
                const formData = new FormData(form);

                Swal.fire({ title: isEditing ? 'Updating...' : 'Saving...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

                fetch(url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Success!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Validation failed', 'error');
                    }
                }).catch(err => {
                    Swal.fire('Error', 'Server Error. Please try again.', 'error');
                });
            }
        });
    });

    window.editStudent = function(id, viewOnly = false) {
        isEditing = !viewOnly;
        isViewOnly = viewOnly;
        document.getElementById('_method').value = 'PUT';
        document.getElementById('student_id').value = id;
        
        Swal.fire({ title: 'Loading...', didOpen: () => { Swal.showLoading(); }});
        fetch(`{{ url('admin/student-entry') }}/${id}/show`)
        .then(res => res.json())
        .then(data => {
            Swal.close();
            openModal(viewOnly ? 'View Student Details' : 'Edit Student Record');
            
            document.getElementById('registration_no').value = data.registration_no || '';
            document.getElementById('admission_no').value = data.admission_no || '';
            document.getElementById('admission_date').value = data.admission_date || '';
            document.getElementById('student_name').value = data.student_name || '';
            document.getElementById('gender').value = data.gender || 'Male';
            document.getElementById('dob').value = data.dob || '';
            document.getElementById('class_id').value = data.class_id || '';
            document.getElementById('section_id').value = data.section_id || '';
            document.getElementById('roll_no').value = data.roll_no || '';
            document.getElementById('category').value = data.category || 'General';
            document.getElementById('religion').value = data.religion || 'Hindu';
            document.getElementById('caste').value = data.caste || '';

            if(data.parent) {
                document.getElementById('father_name').value = data.parent.father_name || '';
                document.getElementById('father_mobile').value = data.parent.father_phone || '';
                document.getElementById('mother_name').value = data.parent.mother_name || '';
                document.getElementById('mother_mobile').value = data.parent.mother_phone || '';
                document.getElementById('guardian_name').value = data.parent.guardian_name || '';
                document.getElementById('guardian_mobile').value = data.parent.guardian_phone || '';
            }
            
            document.getElementById('address_1').value = data.address_1 || '';
            document.getElementById('student_mobile').value = data.mobile || '';
            document.getElementById('city').value = data.place || '';
            document.getElementById('pin').value = data.pin_code || '';

            if(viewOnly) {
                // Disable only data entry fields, not buttons
                Array.from(form.querySelectorAll('input:not([type="hidden"]), select, textarea')).forEach(el => el.disabled = true);
                document.getElementById('saveBtn').style.display = 'none';
            }
        });
    }

    window.viewStudent = function(id) { editStudent(id, true); }

    window.deleteStudent = function(id) {
        Swal.fire({
            title: 'Delete Record?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('admin/student-entry') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => { if(data.success) { Swal.fire('Deleted!', data.message, 'success').then(() => location.reload()); } });
            }
        });
    }

    window.filterTable = function() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const cls = document.getElementById('filterClass').value;
        const sec = document.getElementById('filterSection').value;
        document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
            const text = row.innerText.toLowerCase();
            const rowCls = row.dataset.class;
            const rowSec = row.dataset.section;
            row.style.display = (text.includes(search) && (!cls || rowCls === cls) && (!sec || rowSec === sec)) ? '' : 'none';
        });
    }
</script>
@endpush
