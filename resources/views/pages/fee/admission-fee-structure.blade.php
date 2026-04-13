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

            <div class="modal-overlay" id="historyModal">
    <div class="modal" style="max-width: 98%; width: 98%;">
        <div class="modal-head">
            <h3><i class="fas fa-history"></i> Configuration & Modification Log</h3>
            <button class="modal-close" id="closeHistoryModal">&times;</button>
        </div>
        <div class="modal-body" style="padding: 0;">
            <div class="table-wrap">
                <table class="data-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Previous</th>
                            <th>New Value</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody id="historyBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const classes = {!! json_encode($classes->pluck('name')) !!};
        let feeTypes = {!! json_encode($feeTypes) !!};
        let amountsArr = {!! json_encode($amounts) !!};

        function renderFeeTypes() {
            const container = document.getElementById('feeTypesContainer');
            container.innerHTML = feeTypes.length ? feeTypes.map(ft => `
                <div class="ft-card" data-id="${ft.id}">
                    <span class="ft-name">${ft.name}</span>
                    <div class="ft-acts">
                        <i class="fas fa-edit" onclick="openFeeModal(${ft.id})"></i>
                        <i class="fas fa-trash" onclick="deleteFeeType(${ft.id})"></i>
                    </div>
                </div>
            `).join('') : '<p style="padding: 20px; color: var(--txt3); font-style: italic;">No fee categories defined. Click "Add New Category" to start.</p>';
        }

        function renderTable() {
            const thead = document.getElementById('tableHeader');
            const tbody = document.getElementById('tableBody');

            if (!feeTypes.length) {
                thead.innerHTML = '';
                tbody.innerHTML = '<tr><td colspan="100%" style="text-align: center; padding: 40px; color: var(--txt3);">Please add at least one fee category above first.</td></tr>';
                return;
            }

            thead.innerHTML = `<tr><th>Grade/Class</th>${feeTypes.map(ft => `<th>${ft.name}</th>`).join('')}<th>Total Amount</th></tr>`;

            tbody.innerHTML = classes.map((cls) => {
                let rowTotal = 0;
                const cells = feeTypes.map((ft) => {
                    const val = amountsArr[cls] ? (amountsArr[cls][ft.id] || 0) : 0;
                    rowTotal += parseFloat(val);
                    return `<td><input type="number" class="amt-in" oninput="updateRow('${cls}', ${ft.id}, this.value)" value="${val}"></td>`;
                }).join('');
                return `<tr><td class="fw-700">${cls}</td>${cells}<td class="tot-col" id="total-${cls.replace(/\s+/g, '-')}">₹${rowTotal.toLocaleString()}</td></tr>`;
            }).join('');
        }

        window.updateRow = function(cls, ftId, val) {
            if (!amountsArr[cls]) amountsArr[cls] = {};
            amountsArr[cls][ftId] = parseFloat(val) || 0;
            
            let rowTotal = 0;
            feeTypes.forEach(ft => {
                rowTotal += (amountsArr[cls][ft.id] || 0);
            });
            document.getElementById(`total-${cls.replace(/\s+/g, '-')}`).innerText = `₹${rowTotal.toLocaleString()}`;
        };

        const modal = document.getElementById('feeTypeModal');
        let editingId = null;

        window.openFeeModal = function(id = null) {
            editingId = id;
            if (id) {
                const ft = feeTypes.find(f => f.id === id);
                document.getElementById('modalTitle').innerText = 'Edit Category';
                document.getElementById('feeNameInput').value = ft.name;
                document.getElementById('defaultAmountInput').value = ft.default_amount || ft.defaultAmount;
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

            const url = editingId 
                ? `{{ url('admin/admission-fee-structure/fee-type') }}/${editingId}`
                : `{{ route('admin.admission-fee-structure.store-fee-type') }}`;
            const method = editingId ? 'PUT' : 'POST';

            Swal.fire({ title: 'Saving Category...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, default_amount: def })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Confirmed', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Check consolidation failed.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Communication with server failed.', 'error');
            });
        };

        window.deleteFeeType = function(id) {
            Swal.fire({
                title: 'Delete this category?',
                text: "This will remove this fee type from all classes!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--red)',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('admin/admission-fee-structure/fee-type') }}/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.location.reload();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        };

        document.getElementById('saveAllBtn').onclick = () => {
            Swal.fire({ title: 'Saving Configuration...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch("{{ route('admin.admission-fee-structure.save-structure') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amounts: amountsArr })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Success', text: data.message, timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Failed to save configuration.', 'error');
            });
        };
        
        document.getElementById('historyBtn').onclick = () => {
             Swal.fire({ title: 'Loading History...', didOpen: () => Swal.showLoading() });
             fetch("{{ url('admin/admission-fee-structure?get_history=1') }}")
                .then(res => res.json())
                .then(data => {
                    Swal.close();
                    if(data.success) {
                        document.getElementById('historyBody').innerHTML = data.logs.length ? data.logs.map(log => {
                            const oldV = log.old_values ? Object.entries(log.old_values).map(([k,v]) => `<div style="font-size:0.7rem; word-break: break-all;"><b>${k}:</b> ${v}</div>`).join('') : '-';
                            const newV = log.new_values ? Object.entries(log.new_values).map(([k,v]) => `<div style="font-size:0.7rem; word-break: break-all;"><b>${k}:</b> ${v}</div>`).join('') : '-';
                            return `
                                <tr>
                                    <td style="width: 120px;"><div class="fw-700">${log.user?.name || 'Admin'}</div></td>
                                    <td style="width: 100px;"><span class="badge ${log.action === 'created' ? 'badge-green' : 'badge-blue'}">${log.action.toUpperCase()}</span></td>
                                    <td style="max-width: 200px; font-size: 0.8rem; color: #64748b">${log.reason || 'N/A'}</td>
                                    <td>${oldV}</td>
                                    <td><div style="color:var(--blue); font-weight:600">${newV}</div></td>
                                    <td class="text-muted" style="white-space:nowrap; width: 180px;">${new Date(log.created_at).toLocaleString()}</td>
                                </tr>
                            `;
                        }).join('') : '<tr><td colspan="100%" class="text-center py-5">No modification logs found.</td></tr>';
                        document.getElementById('historyModal').classList.add('open');
                    }
                });
        };
        document.getElementById('closeHistoryModal').onclick = () => document.getElementById('historyModal').classList.remove('open');

        renderFeeTypes(); renderTable();
    })();
</script>
@endpush
