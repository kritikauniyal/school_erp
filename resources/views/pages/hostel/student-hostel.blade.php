@extends('layouts.app')

@section('title', 'Allotment Manager')
@section('page_icon', 'fas fa-bed')



@section('content')

<style>
    /* Hostel Specific modal overrides - others in erp.css */
    .readonly-info { background: var(--bg); border: 1px solid var(--border); border-radius: var(--r2); padding: 16px; margin: 15px 0; font-size: .85rem; line-height: 1.6; }
    .readonly-info strong { color: var(--blue); width: 120px; display: inline-block; font-weight: 700; }
    .search-results-list { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid var(--border); border-radius: var(--r2); box-shadow: var(--sh2); z-index: 100; max-height: 250px; overflow-y: auto; }
    .search-item { padding: 10px 14px; border-bottom: 1px solid var(--border); cursor: pointer; transition: background .15s; }
    .search-item:hover { background: var(--blue-lt); }
    .search-item .sub-text { font-size: .75rem; color: var(--txt3); display: block; margin-top: 2px; }
</style>
    
    </style>
<div class="card" style="margin-top: 20px;">
    <div class="card-head">
        <div>
            <h2><i class="fas fa-bed"></i> Allotment Manager</h2>
            <p class="card-sub">Manage student hostel room allocations and services efficiently.</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-blue" onclick="exportToExcel()"><i class="fas fa-file-excel"></i> Export Excel</button>
            <button class="btn btn-orange" id="addAllocationBtn"><i class="fas fa-plus-circle"></i> New Allocation</button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="fg">
            <label>Search Text</label>
            <input type="text" id="searchFilter" placeholder="Name/Reg No/Roll No">
        </div>
        <div class="fg">
            <label>Class</label>
            <select id="classFilter">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="fg">
            <label>Section</label>
            <select id="sectionFilter">
                <option value="">All Sections</option>
            </select>
        </div>
        <div class="fg">
            <label>Status</label>
            <select id="statusFilter">
                <option value="alloted">Alloted</option>
                <option value="discharged">Discharged</option>
                <option value="">All Statuses</option>
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn btn-blue" id="searchBtn"><i class="fas fa-search"></i> Search</button>
        </div>
    </div>

    <div class="table-wrap">
        <table id="allotmentTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Allotment No</th>
                    <th>Room Name</th>
                    <th>Class-Section-Roll</th>
                    <th>Reg No.</th>
                    <th>Student Name</th>
                    <th>Father's Name</th>
                    <th>Mobile</th>
                    <th>Alloted</th>
                    <th>Discharged</th>
                    <th>Charge</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @include('pages.hostel.hostel-table-rows', ['allotments' => $allotments])
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;" id="paginationWrapper">
        {!! $allotments->links() !!}
    </div>
</div>

    <!-- Create New Hostel Allocation Modal -->
    <div class="modal-overlay" id="createModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Create New Hostel Allocation</h3>
                <button class="close-modal" id="closeCreateModal">&times;</button>
            </div>
            <form id="createForm">
                @csrf
                <div class="form-group">
                    <label class="required">Student (Search by name/reg no)</label>
                    <input type="text" id="studentSearchInput" placeholder="Type to search..." autocomplete="off">
                    <input type="hidden" name="student_id" id="selectedStudentId">
                    <div id="studentSearchResults" class="search-results-list" style="display:none;"></div>
                </div>
                <div id="selectedStudentInfo" class="readonly-info" style="display:none;">
                    <p><strong>Name:</strong> <span id="infoName"></span></p>
                    <p><strong>Class:</strong> <span id="infoClass"></span></p>
                    <p><strong>Father:</strong> <span id="infoFather"></span></p>
                </div>
                <div class="form-group">
                    <label class="required">Room</label>
                    <select name="room_id" required>
                        <option value="">Select Room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->room_no }} ({{ $room->hostel->name }} - {{ $room->type }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="required">Allotment Date</label>
                    <input type="date" name="allotment_date" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="required">Monthly Charge</label>
                    <input type="number" name="monthly_charge" placeholder="Amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" placeholder="Optional remarks"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn" id="cancelCreate">Cancel</button>
                    <button type="submit" class="btn btn-orange">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Hostel Allocation Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Edit Hostel Allocation</h3>
                <button class="close-modal" id="closeEditModal">&times;</button>
            </div>
            <form id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editAllotmentId">
                <!-- Read-only student info -->
                <div class="readonly-info">
                    <p><strong>Student Name:</strong> <span id="editInfoName"></span></p>
                    <p><strong>Class:</strong> <span id="editInfoClass"></span></p>
                    <p><strong>Reg No:</strong> <span id="editInfoReg"></span></p>
                    <p><strong>Father:</strong> <span id="editInfoFather"></span></p>
                </div>
                <div class="form-group">
                    <label class="required">Room</label>
                    <select name="room_id" id="editRoomId" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->room_no }} ({{ $room->hostel->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="required">Allotment Date</label>
                    <input type="date" name="allotment_date" id="editDate" required>
                </div>
                <div class="form-group">
                    <label class="required">Monthly Charge</label>
                    <input type="number" name="monthly_charge" id="editCharge" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" id="editRemarks" placeholder="Optional remarks"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn" id="cancelEdit">Cancel</button>
                    <button type="submit" class="btn btn-orange">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const createModal = $('#createModal');
        const editModal = $('#editModal');

        // Open/Close Modals
        $('#addAllocationBtn').click(() => createModal.addClass('show'));
        $('#closeCreateModal, #cancelCreate').click(() => createModal.removeClass('show'));
        $('#closeEditModal, #cancelEdit').click(() => editModal.removeClass('show'));

        // Class to Section change
        $('#classFilter').change(function() {
            const classId = $(this).val();
            const sectionSelect = $('#sectionFilter');
            sectionSelect.html('<option value="">All Sections</option>');
            
            if (classId) {
                $.get(`/admin/sections/by-class/${classId}`, function(sections) {
                    sections.forEach(section => {
                        sectionSelect.append(`<option value="${section.id}">${section.section_name}</option>`);
                    });
                });
            }
        });

        // Search & Pagination Logic
        function loadData(page = 1) {
            const data = {
                page: page,
                search: $('#searchFilter').val(),
                class_id: $('#classFilter').val(),
                section_id: $('#sectionFilter').val(),
                status: $('#statusFilter').val()
            };

            $.get('{{ route("admin.student-hostel.index") }}', data, function(response) {
                $('#tableBody').html(response.html);
                $('#paginationWrapper').html(response.pagination);
            });
        }

        $('#searchBtn').click(() => loadData(1));
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            loadData($(this).attr('href').split('page=')[1]);
        });

        // Student Search Logic
        let searchTimeout;
        $('#studentSearchInput').on('input', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val();
            const resultsBox = $('#studentSearchResults');

            if (query.length < 2) {
                resultsBox.hide();
                return;
            }

            searchTimeout = setTimeout(() => {
                $.get('{{ route("admin.student-hostel.search-students") }}', { search: query }, function(students) {
                    resultsBox.empty().show();
                    if (students.length === 0) {
                        resultsBox.append('<div class="search-item">No students found</div>');
                    } else {
                        students.forEach(s => {
                            resultsBox.append(`
                                <div class="search-item" data-id="${s.id}" data-name="${s.name}" data-class="${s.class}" data-father="${s.father}">
                                    <strong>${s.name}</strong> (${s.reg_no})
                                    <span class="sub-text">${s.class}-${s.section} | Father: ${s.father}</span>
                                </div>
                            `);
                        });
                    }
                });
            }, 300);
        });

        $(document).on('click', '.search-item', function() {
            const id = $(this).data('id');
            if (!id) return;

            $('#selectedStudentId').val(id);
            $('#studentSearchInput').val($(this).data('name'));
            $('#infoName').text($(this).data('name'));
            $('#infoClass').text($(this).data('class'));
            $('#infoFather').text($(this).data('father'));
            $('#selectedStudentInfo').show();
            $('#studentSearchResults').hide();
        });

        // Create Submit
        $('#createForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route("admin.student-hostel.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        Swal.fire('Success', res.message, 'success');
                        createModal.removeClass('show');
                        loadData();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        });

        // Edit Modal Opening
        $(document).on('click', '.edit-link', function() {
            const d = $(this).data();
            $('#editAllotmentId').val(d.id);
            $('#editInfoName').text(d.student);
            $('#editInfoClass').text(d.class);
            $('#editInfoReg').text(d.reg);
            $('#editInfoFather').text(d.father);
            $('#editRoomId').val(d.room);
            $('#editCharge').val(d.charge);
            $('#editDate').val(d.date);
            $('#editRemarks').val(d.remarks);
            editModal.addClass('show');
        });

        // Edit Submit
        $('#editForm').submit(function(e) {
            e.preventDefault();
            const id = $('#editAllotmentId').val();
            $.ajax({
                url: `/admin/student-hostel/${id}`,
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        Swal.fire('Updated', res.message, 'success');
                        editModal.removeClass('show');
                        loadData();
                    }
                }
            });
        });

        // Stop/Discharge
        $(document).on('click', '.stop-link', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Student will be discharged as of today.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff913b',
                confirmButtonText: 'Yes, stop it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/admin/student-hostel/stop/${id}`, { _token: '{{ csrf_token() }}' }, function(res) {
                        if (res.success) {
                            Swal.fire('Stopped', res.message, 'success');
                            loadData();
                        }
                    });
                }
            });
        });

        // Close search results when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.form-group').length) {
                $('#studentSearchResults').hide();
            }
        });
    });

    function exportToExcel() {
        const table = document.getElementById("allotmentTable");
        const wb = XLSX.utils.table_to_book(table, { sheet: "Hostel Allotments" });
        XLSX.writeFile(wb, "Hostel_Allotments.xlsx");
    }
</script>
@endpush
