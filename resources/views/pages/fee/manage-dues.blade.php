@extends('layouts.app')

@section('title', 'Manage Due Fee')
@section('page_icon', 'fas fa-exclamation-circle')

@push('styles')
    <style>
        /* Specific Inner Page styles */
        .dues-card {
            max-width: 1400px;
            width: 100%;
            background: white;
            border-radius: 32px;
            box-shadow: var(--shadow);
            padding: 24px 28px;
        }
    </style>
    <style>

        
        
        
        .dues-card {
            max-width: 1400px;
            width: 100%;
            background: white;
            border-radius: 32px;
            box-shadow: var(--shadow);
            padding: 24px 28px;
        }
        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
        }
        .header-title i {
            font-size: 1.8rem;
            color: var(--primary-orange);
        }
        .header-sub {
            color: var(--text-muted);
            margin-bottom: 24px;
            margin-left: 10px;
            font-size: 0.9rem;
        }

        /* Premium Unified Filter Bar */
        .premium-filter-bar { 
            background: #f8fcff; 
            padding: 10px; 
            border-radius: 60px; 
            margin-bottom: 30px; 
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
            min-width: 250px;
        }
        .filter-item-wrap:focus-within { border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(61,132,245,0.1); }
        .filter-item-wrap i { color: var(--text-muted); font-size: 0.9rem; margin-right: 12px; }
        .filter-item-wrap select, .filter-item-wrap input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.9rem;
            padding: 10px 0;
            color: var(--text-dark);
            width: 100%;
            font-weight: 500;
        }
        .select-divider {
            min-width: 140px;
            border-right: 1px solid #eee;
            padding-right: 12px;
            margin-right: 12px;
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

        /* table */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 20px;
            background: white;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1300px; /* increased to accommodate inputs */
        }
        th {
            background: var(--primary-blue);
            color: white;
            font-weight: 600;
            font-size: 0.7rem;
            padding: 14px 8px;
            border: 1px solid #3a6fa8;
            text-align: left;
            text-transform: uppercase;
        }
        td {
            padding: 8px 8px;
            border: 1px solid #d9e2ec;
            color: var(--text-dark);
            font-size: 0.85rem;
            vertical-align: middle;
        }
        tr:hover td {
            background: #f8fcff;
        }
        .father-details {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .update-btn {
            background: var(--primary-orange);
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.7rem;
            cursor: pointer;
            transition: 0.2s;
            white-space: nowrap;
        }
        .update-btn:hover {
            background: var(--primary-blue);
        }
        .editable-input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #e0e7f0;
            border-radius: 12px;
            font-size: 0.8rem;
            outline: none;
            background: white;
        }
        .editable-input:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
        }
        .update-all-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
        }
        .btn {
            background: white;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
            padding: 12px 32px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
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

        /* mobile adjustments */
        @media (max-width: 700px) {
            .dues-card {
                padding: 16px;
            }
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-area {
                width: 100%;
            }
        }
    
    </style>
@endpush

@section('page_title', 'Manage Due Fee')

@section('content')
<div class="dues-card">
    <div class="header-title">
        <i class="fas fa-hand-holding-usd"></i>
        <h1>Manage Dues Fee</h1>
    </div>
    <div class="header-sub">
        Update student dues and roll numbers (editable fields)
    </div>

    <!-- Unified Premium Filter Bar -->
    <div class="premium-filter-bar">
        <div class="filter-item-wrap">
            <i class="fas fa-filter"></i>
            <div class="select-divider">
                <select id="classFilter">
                    <option value="Select">Select Class</option>
                    @foreach($globalClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="select-divider" style="min-width: 130px;">
                <select id="sectionFilter">
                    <option value="Select">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" id="searchInput" placeholder="Search by student name, admission number or mobile...">
            <button type="button" id="searchBtn" class="search-btn-premium">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>

    <!-- Student Dues Table -->
    <div class="table-wrapper">
        <table>
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
    <div class="update-all-container">
        <button type="button" class="btn btn-orange" id="updateAllBtn"><i class="fas fa-sync-alt"></i> Update All</button>
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
                        <td><input type="text" class="editable-input adm-input" value="${s.admNo}" placeholder="ADM No"></td>
                        <td>${s.studentName}</td>
                        <td>${s.class}</td>
                        <td>${s.section}</td>
                        <td><input type="text" class="editable-input roll-input" value="${s.roll}" placeholder="Roll"></td>
                        <td class="father-details">${fatherDetails}</td>
                        <td>${s.remarks || ''}</td>
                        <td><input type="number" class="editable-input dues-input" value="${s.dues}" placeholder="Dues"></td>
                        <td><button type="button" class="btn btn-orange btn-sm update-btn" data-sid="${s.id}">Update</button></td>
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
