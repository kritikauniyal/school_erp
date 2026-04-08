@extends('layouts.app')

@section('title', 'Fee Structure Manager')
@section('page_icon', 'fas fa-sitemap')

@push('styles')
<style>

    .manager-card {
        background: white;
        border-radius: 36px;
        box-shadow: var(--shadow);
        padding: 35px 40px;
        transition: var(--transition);
        margin-bottom: 30px;
        border: 1px solid rgba(72, 143, 228, 0.08);
    }
    .manager-card:hover {
        box-shadow: var(--shadow-hover);
    }
    .header-title {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 8px;
    }
    .header-title h1 {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary-blue);
        letter-spacing: -0.5px;
    }
    .header-title i {
        font-size: 2.2rem;
        color: var(--primary-orange);
        filter: drop-shadow(0 4px 6px rgba(255,145,59,0.2));
    }
    .header-sub {
        color: var(--text-muted);
        margin-bottom: 35px;
        margin-left: 10px;
        font-size: 1.05rem;
    }
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .action-left {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .btn {
        background: white;
        border: 2px solid var(--primary-blue);
        color: var(--primary-blue);
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(72, 143, 228, 0.1);
    }
    .btn i {
        font-size: 1.1rem;
    }
    .btn:hover {
        background: var(--primary-blue);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(72, 143, 228, 0.25);
    }
    .btn-orange {
        background: var(--primary-orange);
        border-color: var(--primary-orange);
        color: white;
        box-shadow: 0 4px 12px rgba(255, 145, 59, 0.2);
    }
    .btn-orange:hover {
        background: white;
        color: var(--primary-orange);
        box-shadow: 0 8px 20px rgba(255, 145, 59, 0.3);
    }
    /* Fee Types List */
    .fee-types-list {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 30px;
        background: #f8fcff;
        border-radius: 28px;
        padding: 20px;
    }
    .fee-type-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        flex: 1 1 250px;
        border: 1px solid #e0e7f0;
        position: relative;
        transition: all 0.2s;
    }
    .fee-type-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        border-color: var(--primary-blue);
    }
    .fee-type-card h3 {
        font-size: 1.1rem;
        color: var(--primary-blue);
        margin-bottom: 8px;
    }
    .fee-type-card .months {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }
    .month-badge {
        background: var(--orange-light);
        color: var(--primary-orange);
        padding: 2px 8px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .fee-type-card .actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    .fee-type-card .actions i {
        cursor: pointer;
        color: var(--text-muted);
        transition: 0.2s;
    }
    .fee-type-card .actions i:hover {
        color: var(--primary-orange);
    }

    .class-name-cell {
        font-weight: 800;
        color: var(--primary-blue);
        background: #f8fbff !important;
        width: 150px;
    }
    .grid-input {
        width: 100%;
        border: 2px solid #edf2f7;
        border-radius: 12px;
        padding: 8px 12px;
        font-weight: 600;
        color: var(--text-dark);
        transition: all 0.2s;
        text-align: center;
    }
    .grid-input:focus {
        border-color: var(--primary-orange);
        outline: none;
        box-shadow: 0 0 0 4px rgba(255,145,59,0.1);
    }
    /* modal base */
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
        max-width: 550px;
        width: 100%;
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
        line-height: 1;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 6px;
        display: block;
    }

    .month-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px 10px;
        padding: 20px 0;
    }
    .month-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }
    .month-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-orange);
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
    }
    /* History Table */
    .history-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        font-size: 0.9rem;
        border: 1px solid #d9e2ec;
    }
    .history-table th,
    .history-table td {
        padding: 12px 8px;
        border: 1px solid #d9e2ec;
        word-break: break-word;
    }
    .history-table th {
        background: var(--primary-blue);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="manager-card">
    <div class="header-title">
        <i class="fas fa-coins"></i>
        <h1>Fee Structure Manager</h1>
    </div>
    <div class="header-sub">
        Define fee types, select applicable months (Apr–Mar), and set class-wise amounts
    </div>

    <!-- Fee Types List -->
    <div class="fee-types-list" id="feeTypesContainer">
        <!-- populated by JS -->
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="action-left">
            <button class="btn btn-orange" id="addFeeTypeBtn"><i class="fas fa-plus-circle"></i> Add New Fee Type</button>
            <button class="btn" id="historyBtn"><i class="fas fa-history"></i> Fee Update History</button>
        </div>
        <button class="btn" id="saveAllBtn"><i class="fas fa-save"></i> Save All Changes</button>
    </div>

    <!-- Fee Structure Table -->
    <div class="table-wrap">
        <table id="feeTable">
            <thead id="tableHeader">
                <!-- populated by JS -->
            </thead>
            <tbody id="tableBody">
                <!-- populated by JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Fee Type Modal -->
<div class="modal-overlay" id="feeTypeModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Fee Type</h3>
            <button class="close-modal" id="closeModalBtn"><i class="fas fa-times"></i></button>
        </div>
        <div class="form-group" style="margin-bottom: 25px;">
            <label style="color:var(--primary-blue); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Fee Type Name *</label>
            <input type="text" id="feeNameInput" class="form-control" placeholder="e.g., Tuition Fee" style="margin-top: 8px;">
        </div>
        <div class="form-group" style="margin-bottom: 25px;">
            <label style="color:var(--primary-blue); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Default Amount (₹) *</label>
            <input type="number" id="defaultAmountInput" class="form-control" value="0" step="0.01" style="margin-top: 8px;">
        </div>
        <div class="form-group">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <label>Applicable Months (Academic Year: April – March)</label>
                <div style="font-size:0.75rem; font-weight: 600;">
                    <a href="javascript:void(0)" onclick="toggleAllMonths(true)" style="color:var(--primary-blue); text-decoration:none;">Select All</a> <span style="color:#cbd5e1;">|</span> 
                    <a href="javascript:void(0)" onclick="toggleAllMonths(false)" style="color:var(--primary-orange); text-decoration:none;">Clear All</a>
                </div>
            </div>
            <div class="month-grid" id="monthCheckboxes">
                <!-- populated by JS -->
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn" id="cancelModalBtn">Cancel</button>
            <button class="btn btn-orange" id="saveFeeTypeBtn">Save Fee Type</button>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal-overlay" id="historyModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Fee Update History</h3>
            <button class="close-modal" id="closeHistoryModal">&times;</button>
        </div>
        <div style="max-height: 400px; overflow-y: auto;">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>TARGET</th>
                        <th>ACTION</th>
                        <th>USER</th>
                        <th>DATE</th>
                    </tr>
                </thead>
                <tbody id="historyBody">
                    <!-- populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
    const monthNames = ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"];
    const fullMonthNames = ["April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March"];

    // Data from Laravel
    const classes = @json($classes->pluck('name'));
    let feeTypes = @json($feeTypes);
    let amounts = @json($amounts); // amounts[className][feeTypeId] = scalarRate
    const currentSession = @json($session);

    // DOM Elements
    const feeTypesContainer = document.getElementById('feeTypesContainer');
    const tableHeader = document.getElementById('tableHeader');
    const tableBody = document.getElementById('tableBody');
    const feeTypeModal = document.getElementById('feeTypeModal');
    const monthCheckboxesDiv = document.getElementById('monthCheckboxes');
    
    let editingFeeTypeId = null;

    // --- INITIALIZATION ---
    function init() {
        renderMonthCheckboxes();
        renderFeeTypes();
        renderTable();
    }

    function renderMonthCheckboxes() {
        monthCheckboxesDiv.innerHTML = fullMonthNames.map((name, i) => `
            <div class="month-item">
                <input type="checkbox" value="${i}" id="month_${i}">
                <label for="month_${i}" style="cursor:pointer">${name}</label>
            </div>
        `).join('');
    }

    window.toggleAllMonths = (checked) => {
        document.querySelectorAll('.month-item input').forEach(chk => chk.checked = checked);
    };

    function renderFeeTypes() {
        feeTypesContainer.innerHTML = '';
        feeTypes.forEach(ft => {
            const card = document.createElement('div');
            card.className = 'fee-type-card';
            const appMonths = Array.isArray(ft.applicable_months) ? ft.applicable_months : [];
            const sortedMonths = [...appMonths].sort((a,b) => a - b);
            
            card.innerHTML = `
                <h3>${ft.name}</h3>
                <div class="months">
                    ${sortedMonths.map(m => `<span class="month-badge">${monthNames[m]}</span>`).join('')}
                    ${sortedMonths.length === 0 ? '<span style="color:red; font-size:0.7rem;">No Months Defined</span>' : ''}
                </div>
                <div class="actions">
                    <i class="fas fa-edit" onclick="editFeeType(${ft.id})" title="Edit"></i>
                    <i class="fas fa-trash" onclick="deleteFeeType(${ft.id})" title="Delete"></i>
                </div>
            `;
            feeTypesContainer.appendChild(card);
        });
    }

    function renderTable() {
        if (feeTypes.length === 0) {
            tableHeader.innerHTML = '<tr><th style="border-radius: 20px 20px 0 0;">Structure Grid</th></tr>';
            tableBody.innerHTML = '<tr><td style="text-align:center; padding:60px; color:var(--text-muted); font-style:italic;">No fee types defined yet. Click "Add New Fee Type" to get started.</td></tr>';
            return;
        }

        let headerHtml = '<tr><th style="border-top-left-radius: 20px;">CLASS</th>';
        feeTypes.forEach(ft => {
            const appMonths = Array.isArray(ft.applicable_months) ? ft.applicable_months : [];
            const monthsList = appMonths.map(m => monthNames[m]).join(', ');
            headerHtml += `<th>${ft.name.toUpperCase()} <br><small style="font-weight:400; text-transform:none; opacity: 0.8;">(${monthsList || 'None'})</small></th>`;
        });
        headerHtml += '</tr>';
        tableHeader.innerHTML = headerHtml;

        let bodyHtml = '';
        classes.forEach(cls => {
            bodyHtml += `<tr><td class="class-name-cell">${cls}</td>`;
            feeTypes.forEach(ft => {
                const val = (amounts[cls] && amounts[cls][ft.id]) ? amounts[cls][ft.id] : ft.default_amount;
                bodyHtml += `<td><input type="number" class="grid-input" data-class="${cls}" data-feeid="${ft.id}" value="${val}" step="0.01"></td>`;
            });
            bodyHtml += '</tr>';
        });
        tableBody.innerHTML = bodyHtml;
    }

    // --- MODAL LOGIC ---
    window.editFeeType = (id) => {
        editingFeeTypeId = id;
        const ft = feeTypes.find(f => f.id == id);
        document.getElementById('modalTitle').innerText = 'Edit Fee Type';
        document.getElementById('feeNameInput').value = ft.name;
        document.getElementById('defaultAmountInput').value = ft.default_amount;
        
        const appMonths = Array.isArray(ft.applicable_months) ? ft.applicable_months : [];
        document.querySelectorAll('.month-item input').forEach(chk => {
            chk.checked = appMonths.includes(parseInt(chk.value));
        });
        feeTypeModal.classList.add('show');
    };

    document.getElementById('addFeeTypeBtn').onclick = () => {
        editingFeeTypeId = null;
        document.getElementById('modalTitle').innerText = 'Add New Fee Type';
        document.getElementById('feeNameInput').value = '';
        document.getElementById('defaultAmountInput').value = 0;
        document.querySelectorAll('.month-item input').forEach(chk => chk.checked = false);
        feeTypeModal.classList.add('show');
    };

    document.getElementById('closeModalBtn').onclick = document.getElementById('cancelModalBtn').onclick = () => {
        feeTypeModal.classList.remove('show');
    };

    document.getElementById('saveFeeTypeBtn').onclick = async function() {
        const btn = this;
        const originalText = btn.innerHTML;
        
        const name = document.getElementById('feeNameInput').value.trim();
        const default_amount = document.getElementById('defaultAmountInput').value;
        const applicable_months = Array.from(document.querySelectorAll('.month-item input:checked')).map(c => parseInt(c.value));

        if (!name || applicable_months.length === 0) {
            Swal.fire('Required', 'Please enter a name and select at least one month.', 'warning');
            return;
        }

        // Disable to prevent double click
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        const url = editingFeeTypeId 
            ? `{{ url('admin/fee-structure-manager/fee-type/update') }}/${editingFeeTypeId}` 
            : `{{ url('admin/fee-structure-manager/fee-type/store') }}`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name, default_amount, applicable_months })
            });

            if (!response.ok) {
                // If not OK, try to get JSON error or fallback to text
                let errorMsg = 'Server error occurred.';
                try {
                    const errorData = await response.json();
                    errorMsg = errorData.message || errorMsg;
                } catch (e) {
                    // Not JSON, maybe 419 or 500 HTML
                    if (response.status === 419) errorMsg = 'Session expired. Please refresh the page.';
                }
                throw new Error(errorMsg);
            }

            const data = await response.json();
            if (data.status === 'success') {
                feeTypeModal.classList.remove('show');
                Swal.fire({
                    title: 'Saved',
                    text: data.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        } catch (e) {
            console.error(e);
            Swal.fire('Error', e.message || 'Connection failed.', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };

    window.deleteFeeType = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete this fee type and all associated amount settings for all classes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff913b',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`{{ url('admin/fee-structure-manager/fee-type/delete') }}/${id}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire('Deleted', data.message, 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Deletions failed.', 'error');
                }
            }
        });
    };

    // --- GRID SAVE ---
    document.getElementById('saveAllBtn').onclick = async () => {
        const gridInputs = document.querySelectorAll('.grid-input');
        const payload = {};
        gridInputs.forEach(input => {
            const cls = input.dataset.class;
            const fid = input.dataset.feeid;
            if (!payload[cls]) payload[cls] = {};
            payload[cls][fid] = input.value;
        });

        const effective_from = document.getElementById('effectiveDate').value;

        Swal.fire({
            title: 'Save Grid Structure?',
            text: `Saving all class amounts for session ${currentSession} starting from ${effective_from}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#488fe4',
            confirmButtonText: 'Yes, Save All'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const btn = document.getElementById('saveAllBtn');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                try {
                    const resp = await fetch(`{{ url('admin/fee-structure-manager/save') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            session: currentSession,
                            effective_from: effective_from,
                            amounts: payload
                        })
                    });

                    if (!resp.ok) {
                        let errorMsg = 'Server error occurred.';
                        try {
                            const errorData = await resp.json();
                            errorMsg = errorData.message || errorMsg;
                        } catch (e) {
                            if (resp.status === 419) errorMsg = 'Session expired. Please refresh the page.';
                        }
                        throw new Error(errorMsg);
                    }

                    const data = await resp.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Success',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Unknown error');
                    }
                } catch (e) {
                    Swal.fire('Error', e.message || 'Failed to save layout.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            }
        });
    };

    // --- HISTORY ---
    document.getElementById('historyBtn').onclick = async () => {
        try {
            const resp = await fetch(`{{ url('admin/fee-structure-manager/history') }}`);
            const data = await resp.json();
            if (data.status === 'success') {
                const body = document.getElementById('historyBody');
                body.innerHTML = data.history.map(log => `
                    <tr>
                        <td>${log.target || '--'}</td>
                        <td><span class="badge ${log.action === 'Created' ? 'bg-success' : 'bg-info'}">${log.action}</span></td>
                        <td>${log.user}</td>
                        <td><small>${log.date}</small></td>
                    </tr>
                `).join('');
                document.getElementById('historyModal').classList.add('show');
            }
        } catch (e) {
            Swal.fire('Error', 'Could not load history.', 'error');
        }
    };

    document.getElementById('closeHistoryModal').onclick = () => {
        document.getElementById('historyModal').classList.remove('show');
    };

    window.onclick = (event) => {
        if (event.target == feeTypeModal) feeTypeModal.classList.remove('show');
        if (event.target == document.getElementById('historyModal')) document.getElementById('historyModal').classList.remove('show');
    };

    init();
})();
</script>
@endpush
