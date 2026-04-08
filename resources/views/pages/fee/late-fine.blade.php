@extends('layouts.app')

@section('title', 'Late Fine Fee')
@section('page_icon', 'fas fa-clock')

@push('styles')
<style>
    .settings-card {
        background: white;
        border-radius: 32px;
        box-shadow: var(--shadow);
        padding: 24px 28px;
        border: 1px solid rgba(72, 143, 228, 0.08);
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

    /* filter bar */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }
    .filter-left {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }
    .filter-group {
        display: flex;
        align-items: center;
        background: #f8fcff;
        border-radius: 40px;
        padding: 4px 4px 4px 16px;
        border: 1px solid #e0e7f0;
    }
    .filter-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary-blue);
        margin-right: 8px;
    }
    .filter-group select {
        border: none;
        background: transparent;
        padding: 10px 12px 10px 0;
        font-size: 0.9rem;
        outline: none;
        color: var(--text-dark);
    }
    .search-btn {
        background: var(--primary-blue);
        border: none;
        color: white;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }
    .search-btn:hover {
        background: var(--primary-orange);
    }
    .btn {
        background: white;
        border: 1px solid var(--primary-blue);
        color: var(--primary-blue);
        padding: 10px 22px;
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
        min-width: 900px;
    }
    th {
        background: var(--primary-blue);
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 12px 8px;
        border: 1px solid #3a6fa8;
        text-align: left;
    }
    td {
        padding: 10px 8px;
        border: 1px solid #d9e2ec;
        color: var(--text-dark);
        font-size: 0.85rem;
        vertical-align: middle;
    }
    tr:hover td {
        background: #f8fcff;
    }
    .class-list {
        max-width: 200px;
        word-wrap: break-word;
    }
    .delete-icon {
        color: #e74c3c;
        cursor: pointer;
        font-size: 1.1rem;
        transition: 0.2s;
    }
    .delete-icon:hover {
        transform: scale(1.2);
    }

    /* modal */
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
        border-radius: 28px;
        max-width: 600px;
        width: 100%;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px -12px rgba(0,0,0,0.25);
        padding: 24px;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .modal-header h3 {
        font-size: 1.5rem;
        color: var(--primary-blue);
    }
    .close-modal {
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: var(--text-muted);
    }
    .modal-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-blue);
        margin: 16px 0 8px;
    }
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 8px;
        background: #f8fcff;
        padding: 12px;
        border-radius: 16px;
        margin-bottom: 12px;
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid #e0e7f0;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
    }
    .checkbox-item input {
        accent-color: var(--primary-orange);
        width: 16px;
        height: 16px;
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-group {
        flex: 1 1 200px;
    }
    .form-group label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 4px;
        display: block;
    }
    .form-group input {
        width: 100%;
        background: var(--bg-light);
        border: 1px solid #e0e7f0;
        border-radius: 14px;
        padding: 10px 12px;
        font-size: 0.85rem;
        outline: none;
    }
    .form-group input:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
    }
</style>
@endpush

@section('content')
<div class="settings-card">
    <div class="header-title">
        <i class="fas fa-sliders-h"></i>
        <h1>Fine Settings</h1>
    </div>
    <div class="header-sub">
        Configure fine amounts and date ranges for different classes and months
    </div>

    <div class="filter-bar">
        <div class="filter-left">
            <div class="filter-group">
                <label>Fee Month</label>
                <select id="feeMonthFilter">
                    <option value="All">All Months</option>
                    @foreach(['April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March'] as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <button class="search-btn" id="searchFilterBtn"><i class="fas fa-search"></i> Search</button>
        </div>
        <button class="btn btn-orange" id="addFineBtn"><i class="fas fa-plus-circle"></i> Add New Fine</button>
    </div>

    <div class="table-wrapper">
        <table id="fineTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Class</th>
                    <th>Month</th>
                    <th>From Date</th>
                    <th>Up To Date</th>
                    <th>Fine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="fineTableBody">
                @forelse($fines as $index => $fine)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="class-list">
                            @php
                                $classData = $fine->classes;
                            @endphp
                            
                            @if(is_array($classData) && count($classData) > 0)
                                {{ implode(', ', $classData) }}
                            @elseif(!empty($classData))
                                {{ $classData }}
                            @else
                                <span class="text-muted small">No class selected</span>
                            @endif
                        </td>
                        <td>{{ $fine->month }}</td>
                        <td>{{ $fine->from_date->format('d-M-Y') }}</td>
                        <td>{{ $fine->to_date->format('d-M-Y') }}</td>
                        <td>₹{{ number_format($fine->amount, 0) }}</td>
                        <td>
                            <i class="fas fa-trash delete-icon text-danger" 
                               onclick="deleteFine({{ $fine->id }})" 
                               title="Delete"></i>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No fine settings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Fine Modal -->
<div class="modal-overlay" id="fineModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Add New Fine</h3>
            <button class="close-modal" id="closeModalBtn">&times;</button>
        </div>

        <div class="modal-section-title">Select Classes</div>
        <div class="checkbox-grid" id="classCheckboxes">
            @foreach($classes as $class)
                <label class="checkbox-item">
                    <input type="checkbox" name="classes[]" value="{{ $class->name }}" checked> 
                    {{ $class->name }}
                </label>
            @endforeach
        </div>

        <div class="modal-section-title">Select Months</div>
        <div class="checkbox-grid" id="monthCheckboxes">
            @foreach(['April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March'] as $m)
                <label class="checkbox-item">
                    <input type="checkbox" name="months[]" value="{{ $m }}"> 
                    {{ $m }}
                </label>
            @endforeach
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>From Date</label>
                <input type="date" id="fromDate" value="{{ date('Y-m-01') }}">
            </div>
            <div class="form-group">
                <label>Up To Date</label>
                <input type="date" id="toDate" value="{{ date('Y-m-10') }}">
            </div>
        </div>
        <div class="form-group">
            <label>Fine Amount (₹)</label>
            <input type="number" id="fineAmount" value="50" step="1">
        </div>

        <div class="modal-actions">
            <button class="btn" id="cancelModalBtn">Cancel</button>
            <button class="btn btn-orange" id="submitFineBtn">Submit</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('fineModal');
    const addBtn = document.getElementById('addFineBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const submitBtn = document.getElementById('submitFineBtn');

    addBtn.addEventListener('click', () => modal.classList.add('show'));
    const closeModal = () => modal.classList.remove('show');
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    submitBtn.addEventListener('click', function() {
        const selectedClasses = Array.from(document.querySelectorAll('input[name="classes[]"]:checked')).map(cb => cb.value);
        const selectedMonths = Array.from(document.querySelectorAll('input[name="months[]"]:checked')).map(cb => cb.value);
        
        if (selectedClasses.length === 0) return Swal.fire('Error', 'Select at least one class', 'error');
        if (selectedMonths.length === 0) return Swal.fire('Error', 'Select at least one month', 'error');

        const data = {
            classes: selectedClasses,
            months: selectedMonths,
            from_date: document.getElementById('fromDate').value,
            to_date: document.getElementById('toDate').value,
            amount: document.getElementById('fineAmount').value,
            _token: '{{ csrf_token() }}'
        };

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        // Fetch updated table content
        function refreshTable() {
            fetch('{{ route("admin.late-fine.index") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('fineTableBody').innerHTML = html;
            });
        }

        fetch('{{ route("admin.late-fine.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                refreshTable();
                closeModal();
            } else {
                Swal.fire('Error', res.message || 'Something went wrong', 'error');
            }
            this.disabled = false;
            this.innerText = 'Submit';
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Connection error', 'error');
            this.disabled = false;
            this.innerText = 'Submit';
        });
    });

    function deleteFine(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This setting will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff913b',
            cancelButtonColor: '#488fe4',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('admin/late-fine/delete') }}/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        refreshTable();
                    }
                });
            }
        });
    }

    // Proxy function for global delete icon click if any
    window.deleteFine = deleteFine;

    function refreshTable() {
        fetch('{{ route("admin.late-fine.index") }}?ajax=1')
        .then(res => res.text())
        .then(html => {
            document.getElementById('fineTableBody').innerHTML = html;
        });
    }

    // Filter logic
    document.getElementById('searchFilterBtn').addEventListener('click', () => {
        const month = document.getElementById('feeMonthFilter').value;
        const rows = document.querySelectorAll('#fineTableBody tr');
        rows.forEach(row => {
            if (month === 'All') {
                row.style.display = '';
            } else {
                const rowMonth = row.cells[2].innerText;
                row.style.display = rowMonth === month ? '' : 'none';
            }
        });
    });
</script>
@endpush
@endsection
