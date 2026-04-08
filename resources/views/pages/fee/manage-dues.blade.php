@extends('layouts.app')

@section('title', 'Manage Due Fee')
@section('page_icon', 'fas fa-exclamation-circle')

@section('page_title', 'Manage Due Fee')

@section('content')
<div class="card">
    <div class="header-title">
        <i class="fas fa-hand-holding-usd"></i>
        <h1>Manage Dues Fee</h1>
    </div>
    <div class="header-sub">
        Update student dues and roll numbers (editable fields)
    </div>

    <!-- Unified Premium Filter Bar -->
    <div class="row" style="margin-bottom: 20px; gap: 15px;">
        <div class="col-md-3">
            <select id="classFilter" class="form-control">
                <option value="Select">Select Class</option>
                @foreach($globalClasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="sectionFilter" class="form-control">
                <option value="Select">Select Section</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by student name, adm no or mobile...">
        </div>
        <div class="col-md-2">
            <button type="button" id="searchBtn" class="btn btn-primary d-block w-100">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>

    <!-- Student Dues Table -->
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>SID</th>
                    <th>ADM No</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Roll</th>
                    <th>Father Details</th>
                    <th>Remarks</th>
                    <th>Dues (₹)</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <!-- Data populated by JS -->
            </tbody>
        </table>
    </div>

    <!-- Update All button -->
    <div class="update-all-container mt-3" style="display: flex; justify-content: flex-end;">
        <button type="button" class="btn btn-primary" id="updateAllBtn"><i class="fas fa-sync-alt"></i> Update All</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('studentTableBody');
        let students = [];
        let currentFiltered = [];

        function renderTable(filteredStudents = students) {
            currentFiltered = filteredStudents;
            let html = '';
            
            if (filteredStudents.length === 0) {
                html = `<tr><td colspan="10" class="text-center py-4 text-muted">No students found matching your criteria. Select a Class and Section.</td></tr>`;
            } else {
                filteredStudents.forEach(s => {
                    const fatherDetails = `${s.father} (${s.mobile1}${s.mobile2 ? ', ' + s.mobile2 : ''})`;
                    html += `<tr data-sid="${s.id}">
                        <td>${s.sid}</td>
                        <td><input type="text" class="form-control form-control-sm adm-input" value="${s.admNo}" placeholder="ADM No"></td>
                        <td>${s.studentName}</td>
                        <td>${s.class}</td>
                        <td>${s.section}</td>
                        <td><input type="text" class="form-control form-control-sm roll-input" value="${s.roll}" placeholder="Roll"></td>
                        <td class="father-details">${fatherDetails}</td>
                        <td>${s.remarks || ''}</td>
                        <td><input type="number" class="form-control form-control-sm dues-input" value="${s.dues}" placeholder="Dues"></td>
                        <td><button type="button" class="btn btn-primary btn-sm update-btn" data-sid="${s.id}">Update</button></td>
                    </tr>`;
                });
            }
            
            tbody.innerHTML = html;

            document.querySelectorAll('.update-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.sid;
                    const row = document.querySelector(`tr[data-sid="${id}"]`);
                    const admNo = row.querySelector('.adm-input').value.trim();
                    const roll = row.querySelector('.roll-input').value.trim();
                    const dues = parseFloat(row.querySelector('.dues-input').value) || 0;

                    const data = [{ id: parseInt(id), admNo, roll, dues }];
                    updateStudentsReq(data);
                });
            });
        }

        function updateStudentsReq(studentsData) {
            fetch("{{ route('admin.manage-dues.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ students: studentsData })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success', 
                        title: 'Updated Successfully!', 
                        toast: true, 
                        position: 'top-end', 
                        showConfirmButton: false, 
                        timer: 3000
                    });
                    
                    // Update our local array visually so we aren't replacing typed inputs unnecessarily unless we hard refresh
                    // But typically a clean fetch is better if there are business rules inside the ledger.
                    fetchStudents(); 
                } else {
                    Swal.fire('Error', data.message || 'Error updating data', 'error');
                }
            }).catch(e => {
                console.error(e);
                Swal.fire('Error', 'Network error', 'error');
            });
        }

        function fetchStudents() {
            const classId = document.getElementById('classFilter').value;
            const sectionId = document.getElementById('sectionFilter').value;

            if(classId === 'Select' && sectionId === 'Select') {
               // Don't auto fetch on pure load if no class is selected
               renderTable([]);
               return;
            }

            fetch("{{ route('admin.manage-dues.students') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ class: classId, section: sectionId })
            })
            .then(res => res.json())
            .then(data => {
                students = data || [];
                applySearch();
            })
            .catch(e => {
                console.error(e);
                Swal.fire('Error', 'Could not load students', 'error');
            });
        }

        function applySearch() {
            const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
            let filtered = students;
            
            if (searchTerm) {
                filtered = students.filter(s => {
                    const inName = (s.studentName || '').toLowerCase().includes(searchTerm);
                    const inSid = (s.sid || '').toLowerCase().includes(searchTerm);
                    const inAdm = (s.admNo || '').toLowerCase().includes(searchTerm);
                    const inMobile = (s.mobile1 || '').includes(searchTerm) || (s.mobile2 && s.mobile2.includes(searchTerm));
                    return inName || inSid || inAdm || inMobile;
                });
            }
            
            renderTable(filtered);
        }

        document.getElementById('searchBtn').addEventListener('click', applySearch);
        document.getElementById('searchInput').addEventListener('input', applySearch);
        document.getElementById('classFilter').addEventListener('change', fetchStudents);
        document.getElementById('sectionFilter').addEventListener('change', fetchStudents);

        document.getElementById('updateAllBtn').addEventListener('click', () => {
            const rows = document.querySelectorAll('#studentTableBody tr[data-sid]');
            const data = [];
            rows.forEach(row => {
                const id = row.dataset.sid;
                const admNo = row.querySelector('.adm-input').value.trim();
                const roll = row.querySelector('.roll-input').value.trim();
                const dues = parseFloat(row.querySelector('.dues-input').value) || 0;
                
                data.push({ id: parseInt(id), admNo, roll, dues });
            });

            if(data.length > 0) {
                updateStudentsReq(data);
            }
        });

        // Initial setup
        renderTable([]);
    });
</script>
@endpush
