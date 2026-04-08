@extends('layouts.app')

@section('title', 'Admission Fee Structure')
@section('page_number', '09')
@section('page_icon', 'fas fa-rupee-sign')



@push('styles')
<style>
    .fee-types-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-bottom: 25px; }
    .ft-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--r2); padding: 15px; display: flex; justify-content: space-between; align-items: center; transition: .2s; }
    .ft-card:hover { border-color: var(--blue); background: #fff; box-shadow: var(--sh1); }
    .ft-name { font-size: .88rem; font-weight: 700; color: var(--txt1); }
    .ft-acts { display: flex; gap: 10px; color: var(--txt3); }
    .ft-acts i { cursor: pointer; transition: .2s; font-size: .9rem; }
    .ft-acts i:hover { color: var(--blue); }
    .ft-acts .fa-trash:hover { color: var(--red); }

    .amt-in { width: 90px; padding: 6px 10px; border: 1px solid var(--border); border-radius: 8px; font-size: .85rem; outline: none; transition: .2s; text-align: right; }
    .amt-in:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-lt); }
    .tot-col { font-weight: 800; color: var(--blue); text-align: right; font-size: .9rem; }

    /* Fix modal positioning to middle of screen */
    .modal-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(10, 20, 60, 0.4) !important;
        backdrop-filter: blur(4px) !important;
        z-index: 99999 !important;
        display: none;
        align-items: center !important;
        justify-content: center !important;
        padding: 20px !important;
    }
    .modal-overlay.open {
        display: flex !important;
    }
    .modal-overlay .modal {
        margin: auto !important; /* Forces centering in flex context */
        background: #fff !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3) !important;
        width: 100% !important;
        max-width: 420px !important;
        max-height: 85vh !important;
        overflow-y: auto !important;
        animation: modalSwoopIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
    }
    @keyframes modalSwoopIn {
        from { opacity: 0; transform: translateY(30px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>
@endpush

@section('content')

<div class="card">
    <div class="card-head">
        <div>
            <h2><i class="fas fa-hand-holding-usd"></i> Fee Type Management</h2>
            <p style="font-size: .82rem; color: var(--txt3); margin-top: 4px;">Define and manage one-time admission fee categories</p>
        </div>
    </div>

    <!-- Dynamic Fee Type Cards -->
    <div class="fee-types-grid" id="feeTypesContainer"></div>

    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button class="btn btn-orange" id="addFeeTypeBtnInner"><i class="fas fa-plus-circle"></i> Add New Category</button>
        <button class="btn" id="historyBtn"><i class="fas fa-history"></i> Modification Log</button>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h2><i class="fas fa-table"></i> Class-wise Fee Configuration</h2>
        <button class="btn btn-blue" id="saveAllBtn"><i class="fas fa-save"></i> Save All Changes</button>
    </div>

    <div class="table-wrap">
        <table class="data-table" id="feeTable">
            <thead id="tableHeader"></thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="feeTypeModal">
    <div class="modal" style="max-width: 420px;">
        <div class="modal-head">
            <h3 id="modalTitle">New Fee Category</h3>
            <button class="modal-close" id="closeModalBtn">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Category Name <span class="text-red">*</span></label>
                <input type="text" id="feeNameInput" placeholder="e.g. Prospectus Fee" class="form-control">
            </div>
            <div class="form-group">
                <label>Default Amount (₹)</label>
                <input type="number" id="defaultAmountInput" value="0" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" id="cancelModalBtn">Cancel</button>
            <button class="btn btn-blue" id="saveFeeTypeBtn">Apply Changes</button>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal-overlay" id="historyModal">
    <div class="modal">
        <div class="modal-head">
            <h3>Configuration Log</h3>
            <button class="modal-close" id="closeHistoryModal">&times;</button>
        </div>
        <div class="modal-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody id="historyBody"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const classes = ["Nursery", "KG", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
        let feeTypes = [
            { id: 1, name: "Registration Fee", defaultAmount: 500 },
            { id: 2, name: "Admission Fee", defaultAmount: 20000 },
            { id: 3, name: "Annual Charges", defaultAmount: 1000 },
            { id: 4, name: "Exam Fee", defaultAmount: 1000 },
            { id: 5, name: "Transport Fee", defaultAmount: 2000 }
        ];
        let lastUpdate = {};
        feeTypes.forEach(ft => lastUpdate[ft.id] = "28 Feb 2026, 11:45 AM");

        let amountsArr = classes.map(c => feeTypes.map(f => f.defaultAmount));

        function renderFeeTypes() {
            const container = document.getElementById('feeTypesContainer');
            container.innerHTML = feeTypes.map(ft => `
                <div class="ft-card">
                    <span class="ft-name">${ft.name}</span>
                    <div class="ft-acts">
                        <i class="fas fa-edit" onclick="openFeeModal(${ft.id})"></i>
                        <i class="fas fa-trash" onclick="deleteFeeType(${ft.id})"></i>
                    </div>
                </div>
            `).join('');
        }

        function renderTable() {
            const thead = document.getElementById('tableHeader');
            const tbody = document.getElementById('tableBody');

            thead.innerHTML = `<tr><th>Grade/Class</th>${feeTypes.map(ft => `<th>${ft.name}</th>`).join('')}<th>Total Amount</th></tr>`;

            tbody.innerHTML = classes.map((cls, cIdx) => {
                let rowTotal = 0;
                const cells = feeTypes.map((ft, fIdx) => {
                    const val = amountsArr[cIdx][fIdx] || 0;
                    rowTotal += val;
                    return `<td><input type="number" class="amt-in" oninput="updateRow(${cIdx}, ${fIdx}, this.value)" value="${val}"></td>`;
                }).join('');
                return `<tr><td class="fw-700">${cls}</td>${cells}<td class="tot-col" id="total-${cIdx}">₹${rowTotal.toLocaleString()}</td></tr>`;
            }).join('');
        }

        window.updateRow = function(cIdx, fIdx, val) {
            amountsArr[cIdx][fIdx] = parseFloat(val) || 0;
            const rowTotal = amountsArr[cIdx].reduce((a, b) => a + b, 0);
            document.getElementById(`total-${cIdx}`).innerText = `₹${rowTotal.toLocaleString()}`;
        };

        const modal = document.getElementById('feeTypeModal');
        let editingId = null;

        window.openFeeModal = function(id = null) {
            editingId = id;
            if (id) {
                const ft = feeTypes.find(f => f.id === id);
                document.getElementById('modalTitle').innerText = 'Edit Category';
                document.getElementById('feeNameInput').value = ft.name;
                document.getElementById('defaultAmountInput').value = ft.defaultAmount;
            } else {
                document.getElementById('modalTitle').innerText = 'New Category';
                document.getElementById('feeNameInput').value = '';
                document.getElementById('defaultAmountInput').value = 0;
            }
            modal.classList.add('open');
        };

        document.querySelectorAll('#addFeeTypeBtn, #addFeeTypeBtnInner').forEach(b => b.onclick = () => openFeeModal());
        document.getElementById('closeModalBtn').onclick = document.getElementById('cancelModalBtn').onclick = () => modal.classList.remove('open');

        document.getElementById('saveFeeTypeBtn').onclick = () => {
            const name = document.getElementById('feeNameInput').value.trim();
            const def = parseFloat(document.getElementById('defaultAmountInput').value) || 0;
            if (!name) return;

            if (editingId) {
                const ft = feeTypes.find(f => f.id === editingId);
                ft.name = name; ft.defaultAmount = def;
            } else {
                const newId = Date.now();
                feeTypes.push({ id: newId, name, defaultAmount: def });
                amountsArr.forEach(row => row.push(def));
            }
            renderFeeTypes(); renderTable(); modal.classList.remove('open');
        };

        window.deleteFeeType = function(id) {
            if(confirm('Delete this category?')) {
                const idx = feeTypes.findIndex(f => f.id === id);
                feeTypes.splice(idx, 1);
                amountsArr.forEach(row => row.splice(idx, 1));
                renderFeeTypes(); renderTable();
            }
        };

        document.getElementById('saveAllBtn').onclick = () => alert('Configured Admission Fee Structure Saved!');
        
        document.getElementById('historyBtn').onclick = () => {
             document.getElementById('historyBody').innerHTML = feeTypes.map(ft => `
                <tr><td>${ft.name}</td><td><span class="badge badge-blue">Active</span></td><td class="text-muted">${lastUpdate[ft.id] || 'N/A'}</td></tr>
             `).join('');
             document.getElementById('historyModal').classList.add('open');
        };
        document.getElementById('closeHistoryModal').onclick = () => document.getElementById('historyModal').classList.remove('open');

        renderFeeTypes(); renderTable();
    })();
</script>
@endpush
