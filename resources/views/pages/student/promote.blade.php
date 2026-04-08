@extends('layouts.app')

@section('title', 'Promote Student')
@section('page_icon', 'fas fa-user-graduate')

@push('styles')
<style>
    /* Premium Design Tokens */
    .promote-container {
        max-width: 100%;
        margin: 0 auto;
    }

    /* Standardized Filter Panel - Pill Design */
    .filter-card {
        background: white;
        border-radius: 40px;
        padding: 8px 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(72, 143, 228, 0.1);
    }

    .filter-item {
        display: flex;
        align-items: center;
        background: #f8fbff;
        border-radius: 30px;
        padding: 4px 18px;
        border: 1px solid #e0e7f0;
        flex: 1;
        min-width: 160px;
    }

    .filter-item label {
        font-size: 0.7rem;
        font-weight: 800;
        color: var(--blue);
        text-transform: uppercase;
        margin-right: 10px;
        white-space: nowrap;
    }

    .filter-item select {
        border: none;
        background: transparent;
        padding: 10px 0;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--txt-dark);
        width: 100%;
        outline: none;
        cursor: pointer;
    }

    .search-btn {
        background: var(--orange);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 30px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 145, 59, 0.3);
    }

    .search-btn:hover {
        background: #e67e22;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 145, 59, 0.4);
    }

    /* Table & Content Area */
    .content-card {
        background: white;
        border-radius: 32px;
        box-shadow: var(--shadow);
        padding: 24px;
        border: 1px solid rgba(72, 143, 228, 0.08);
    }

    .table-wrapper {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #edf2f7;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8fbff;
        color: var(--blue);
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
        padding: 16px 12px;
        text-align: left;
        border-bottom: 2px solid #edf2f7;
    }

    td {
        padding: 12px;
        background: white;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #4a5568;
    }

    tr:hover td {
        background: #fcfdfe;
    }

    /* Action Inputs */
    .table-input {
        background: #f8fbff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 6px 10px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--blue);
        width: 100%;
        outline: none;
    }

    .table-input:focus {
        border-color: var(--orange);
        background: white;
    }

    .update-row-btn {
        background: #f0f7ff;
        color: var(--blue);
        border: 1px solid rgba(72, 143, 228, 0.2);
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        cursor: pointer;
        width: 100%;
    }

    .update-row-btn:hover {
        background: var(--blue);
        color: white;
    }

    /* Batch Actions & Footer */
    .footer-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fbff;
        padding: 15px 20px;
        border-radius: 20px;
        margin-top: 10px;
    }

    .batch-btn {
        background: var(--orange);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 800;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(255, 145, 59, 0.2);
    }

    .batch-btn:hover { background: #e67e22; transform: translateY(-1px); }

    .pagination-pills { display: flex; gap: 6px; }
    .page-pill {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: white; color: var(--blue); font-weight: 700;
        cursor: pointer; border: 1px solid #e2e8f0; font-size: 0.8rem;
    }
    .page-pill.active { background: var(--blue); color: white; border-color: var(--blue); }

    .empty-state {
        text-align: center; padding: 80px 20px;
        background: white; border-radius: 32px;
        border: 2px dashed #e2e8f0; margin-top: 10px;
    }
</style>
@endpush

@section('content')
<div class="promote-container">
    <!-- Premium Filter Pill Bar -->
    <div class="filter-card">
        <div class="filter-item">
            <label>Current Class</label>
            <select id="currentClass">
                <option value="">Select Class</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-item">
            <label>Section</label>
            <select id="currentSection">
                <option value="">Select Section</option>
                @foreach($sections as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="search-btn" id="loadStudentsBtn">
            <i class="fas fa-search"></i> Search Students
        </button>
    </div>

    <!-- Student Promotion Logic -->
    <div class="content-card" id="tableCard" style="display: none;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th width="80">SID</th>
                        <th width="100">ADM NO</th>
                        <th>STUDENT NAME</th>
                        <th>CURRENT CLASS/SEC</th>
                        <th>NEW CLASS</th>
                        <th>NEW SECTION</th>
                        <th width="80">ROLL</th>
                        <th width="120">ACTION</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody"></tbody>
            </table>
        </div>

        <div class="footer-actions">
            <div style="display: flex; align-items:center; gap:25px;">
                <div id="paginationInfo" style="font-weight:800; color:var(--blue); font-size: 0.75rem; text-transform: uppercase;"></div>
                <div class="pagination-pills" id="pageNumbersContainer"></div>
            </div>
            
            <button class="batch-btn" id="updateAllBtn">
                <i class="fas fa-forward"></i> PROMOT ALL STUDENTS
            </button>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="empty-state">
        <i class="fas fa-user-graduate" style="font-size: 3.5rem; color: #f1f5f9; margin-bottom: 20px; display: block;"></i>
        <h2 style="color: var(--blue); font-weight: 800; margin-bottom: 10px;">Promote Students</h2>
        <p style="color: #64748b; font-size: 0.95rem;">Select current class and section to manage promotions.</p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    $classesList = $classes->map(function($c) { return ['id' => $c->id, 'name' => $c->name]; });
    $sectionsList = $sections->map(function($s) { return ['id' => $s->id, 'name' => $s->name]; });
@endphp

<script>
    (function() {
        const classOptions = @json($classesList);
        const sectionOptions = @json($sectionsList);

        const currentClass = document.getElementById('currentClass');
        const currentSection = document.getElementById('currentSection');
        const loadBtn = document.getElementById('loadStudentsBtn');
        const tableCard = document.getElementById('tableCard');
        const emptyState = document.getElementById('emptyState');
        const tbody = document.getElementById('studentTableBody');
        const updateAllBtn = document.getElementById('updateAllBtn');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbersContainer = document.getElementById('pageNumbersContainer');

        let currentStudents = [];
        let currentPage = 1;
        let pageSize = 10;

        function notify(msg, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: type,
                title: msg
            });
        }

        loadBtn.addEventListener('click', function() {
            const classVal = currentClass.value;
            const sectionVal = currentSection.value;
            
            if (!classVal || !sectionVal) {
                notify('Please select both class and section.', 'warning');
                return;
            }
            
            loadBtn.disabled = true;
            loadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            $.ajax({
                url: "{{ route('admin.promote-student.search') }}",
                type: "GET",
                data: { class: classVal, section: sectionVal },
                success: function(response) {
                    if (response.success) {
                        currentStudents = response.students;
                        currentPage = 1;
                        if (currentStudents.length > 0) {
                            renderTable();
                            tableCard.style.display = 'block';
                            emptyState.style.display = 'none';
                        } else {
                            tableCard.style.display = 'none';
                            emptyState.style.display = 'block';
                            notify('No students found for this selection.', 'info');
                        }
                    }
                },
                complete: function() {
                    loadBtn.disabled = false;
                    loadBtn.innerHTML = '<i class="fas fa-search"></i> Search Students';
                }
            });
        });

        function createOptions(options, selected) {
            return options.map(opt => `<option value="${opt.id}" ${opt.name === selected ? 'selected' : ''}>${opt.name}</option>`).join('');
        }

        function renderTable() {
            const total = currentStudents.length;
            const totalPages = Math.ceil(total / pageSize) || 1;
            const start = (currentPage - 1) * pageSize;
            const end = Math.min(start + pageSize, total);
            const data = currentStudents.slice(start, end);

            let html = '';
            data.forEach(s => {
                html += `
                <tr data-id="${s.id}">
                    <td style="font-weight:800; color:var(--blue);">${s.sid}</td>
                    <td><span style="background:#f1f5f9; padding:4px 10px; border-radius:8px; font-weight:700;">${s.adm || 'N/A'}</span></td>
                    <td style="font-weight:700;">${s.name}</td>
                    <td><span style="color:#64748b; font-size:0.8rem; font-weight:600;">${s.class} (${s.section})</span></td>
                    <td><select class="table-input new-class">${createOptions(classOptions, s.class)}</select></td>
                    <td><select class="table-input new-section">${createOptions(sectionOptions, s.section)}</select></td>
                    <td><input type="text" class="table-input new-roll" value="${s.roll}" style="text-align:center;"></td>
                    <td><button class="update-row-btn" data-id="${s.id}">UPDATE</button></td>
                </tr>`;
            });
            tbody.innerHTML = html;

            document.querySelectorAll('.update-row-btn').forEach(btn => {
                btn.onclick = (e) => updateStudent(e.target.dataset.id);
            });

            updatePagination(total, totalPages);
        }

        function updatePagination(total, totalPages) {
            paginationInfo.innerText = `SHOWN: ${Math.min((currentPage-1)*pageSize+1, total)} - ${Math.min(currentPage*pageSize, total)} OF ${total}`;
            pageNumbersContainer.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const p = document.createElement('div');
                p.className = `page-pill ${i === currentPage ? 'active' : ''}`;
                p.innerText = i;
                p.onclick = () => { currentPage = i; renderTable(); };
                pageNumbersContainer.appendChild(p);
            }
        }

        function updateStudent(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const btn = row.querySelector('.update-row-btn');
            
            const data = {
                _token: "{{ csrf_token() }}",
                id: id,
                new_class: row.querySelector('.new-class').value,
                new_section: row.querySelector('.new-section').value,
                new_roll: row.querySelector('.new-roll').value
            };

            btn.disabled = true; btn.innerText = '...';

            $.ajax({
                url: "{{ route('admin.promote-student.update') }}",
                type: "POST",
                data: data,
                success: function(res) {
                    if (res.success) notify(res.message);
                    else notify(res.message, 'error');
                },
                complete: function() { btn.disabled = false; btn.innerText = 'UPDATE'; }
            });
        }

        updateAllBtn.onclick = function() {
            const rows = document.querySelectorAll('#studentTableBody tr');
            let students = [];
            rows.forEach(r => {
                students.push({
                    id: r.dataset.id,
                    new_class: r.querySelector('.new-class').value,
                    new_section: r.querySelector('.new-section').value,
                    new_roll: r.querySelector('.new-roll').value
                });
            });

            if (!students.length) return;

            updateAllBtn.disabled = true;
            updateAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> PROCESSING...';

            $.ajax({
                url: "{{ route('admin.promote-student.update-all') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", students: students },
                success: function(res) {
                    if (res.success) {
                        Swal.fire('Promoted', res.message, 'success');
                        loadBtn.click();
                    }
                },
                complete: function() {
                    updateAllBtn.disabled = false;
                    updateAllBtn.innerHTML = '<i class="fas fa-forward"></i> PROMOT ALL STUDENTS';
                }
            });
        };
    })();
</script>
@endpush
