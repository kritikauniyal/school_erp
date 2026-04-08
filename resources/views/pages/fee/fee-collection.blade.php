@extends('layouts.app')

@section('title', 'Fee Collection')
@section('page_icon', 'fas fa-coins')

@push('styles')
<style>
    /* Fee Collection specific adjustments */
    .profile-card { background: white; border-radius: 28px; padding: 24px; box-shadow: var(--shadow); }
    .filter-bar { background: #f8fcff; padding: 16px; border-radius: 40px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; }
    .student-summary { background: linear-gradient(145deg, #f8fcff, #ffffff); border-radius: 24px; padding: 16px 20px; margin-bottom: 20px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; border: 1px solid rgba(72,143,228,0.2); }
    .student-info { display: flex; flex-wrap: wrap; gap: 20px; }
    .info-item { display: flex; flex-direction: column; }
    .info-item .label { font-size: 0.65rem; text-transform: uppercase; color: var(--primary-blue); font-weight: 700; }
    .info-item .value { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-top: 2px; }
    .student-photo { width: 60px; height: 60px; border-radius: 50%; background: var(--blue-light); display: flex; align-items: center; justify-content: center; color: var(--primary-blue); font-size: 1.8rem; border: 3px solid var(--primary-orange); }
</style>
@endpush

@section('content')

<div class="profile-card">
    <!-- Header filters -->
    <form action="{{ route('fee.collection') }}" method="GET" class="filter-bar">
        <select name="class_id" class="filter-select">
            <option value="">Select Class</option>
            @foreach($globalClasses as $cls)
                <option value="{{ $cls->id }}" {{ (isset($student) && $student->class_id == $cls->id) || request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
            @endforeach
        </select>
        <select name="section_id" class="filter-select">
            <option value="">Select Section</option>
            @if(isset($student) && $student->section)
                <option value="{{ $student->section->id }}" selected>{{ $student->section->name }}</option>
            @endif
        </select>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, admission no...">
            <button type="submit">Go</button>
        </div>
    </form>

    <!-- Student summary -->
    <div class="student-summary">
        <div class="student-info">
            @if($student)
                <div class="info-item"><span class="label">STUDENT</span><span class="value">{{ strtoupper($student->student_name) }}</span></div>
                <div class="info-item"><span class="label">CLASS</span><span class="value">{{ strtoupper($student->class->name ?? 'N/A') }}</span></div>
                <div class="info-item"><span class="label">SECTION</span><span class="value">{{ strtoupper($student->section->name ?? 'N/A') }}</span></div>
                <div class="info-item"><span class="label">FATHER</span><span class="value">{{ strtoupper($student->parent->father_name ?? 'N/A') }}</span></div>
                <div class="info-item"><span class="label">SID</span><span class="value">{{ strtoupper($student->admission_no ?? 'SID'.$student->id) }}</span></div>
            @else
                <p style="padding: 10px; color: #5f6b7a;">Please search and select a student to collect fees.</p>
            @endif
        </div>
        <div class="student-photo"><i class="fas fa-child"></i></div>
    </div>

    @if($student)
        <div class="content-section-title">MONTHLY FEE BREAKDOWN (APRIL - MARCH)</div>

        <!-- Fee table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" style="width:14px;height:14px;accent-color:var(--primary-orange);margin-right:8px;vertical-align:middle;"> MONTH</th>
                        <th>FEE (₹)</th>
                        <th>CONS. (₹)</th>
                        <th>TOTAL (₹)</th>
                        <th>PAID (₹)</th>
                        <th>DUES (₹)</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody id="feeTableBody"></tbody>
            </table>
        </div>

        <!-- Total dues and collect button -->
        <div class="dues-bar">
            <div class="total-dues">Total Dues <span id="totalDuesSpan">₹0</span></div>
            <button class="collect-btn" id="collectBtn"><i class="fas fa-hand-holding-usd"></i> Collect Fees</button>
        </div>
    @endif
</div>

@if($student)
<!-- Modal: Month details (for unpaid months) -->
<div class="modal-overlay" id="detailModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="detailMonthTitle">Fee Details</h3>
            <button type="button" class="close-modal" id="closeDetailModal">&times;</button>
        </div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Fee Type</th>
                    <th>Fee (₹)</th>
                    <th>Paid (₹)</th>
                    <th>Dues (₹)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="detailBody"></tbody>
        </table>
    </div>
</div>

<!-- Modal: Collect payment -->
<div class="modal-overlay" id="collectModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Collect Fee</h3>
            <button type="button" class="close-modal" id="closeCollectModal">&times;</button>
        </div>
        <form id="paymentForm" method="POST" action="{{ route('fee.pay', $student->id) }}">
            @csrf
            <div style="background:#f8fcff; padding:12px; border-radius:16px; margin-bottom:16px;">
                <div class="amount-row">
                    <div class="amount-item"><span style="font-size:0.7rem;color:var(--primary-blue);">Sub Total</span><div style="font-size:1.2rem;font-weight:700;" id="modalSubtotal">₹0</div></div>
                    <div class="amount-item"><span style="font-size:0.7rem;color:var(--primary-blue);">Discount (₹)</span><input type="number" name="discount" id="modalDiscount" value="0" step="0.01" style="width:100%; padding:6px; border-radius:12px; border:1px solid #e0e7f0;"></div>
                    <div class="amount-item"><span style="font-size:0.7rem;color:var(--primary-blue);">Total</span><div style="font-size:1.2rem;font-weight:700;" id="modalTotal">₹0</div></div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Pay Amount (₹)</label>
                <input type="number" name="amount" id="modalPayAmount" value="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Dues Amount (₹)</label>
                <input type="number" id="modalDues" value="0" step="0.01" readonly style="background:#f0f0f0;">
            </div>
            <div class="form-group">
                <label>Collection Mode</label>
                <select name="mode" id="modalMode" required>
                    <option value="Cash">Cash</option>
                    <option value="Online">Online/UPI</option>
                    <option value="Bank">Bank Transfer</option>
                    <option value="Cheque">Cheque</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" id="cancelCollect">Cancel</button>
                <button type="submit" class="btn btn-primary" id="payNowBtn">Pay Now</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        // Month order: April = 0, March = 11
        const months = [
            "April", "May", "June", "July", "August", "September",
            "October", "November", "December", "January", "February", "March"
        ];

        // Dynamic Fee Data from Controller
        const rawMonths = @json($months ?? []);
        const feeData = [];

        if (rawMonths && rawMonths.length === 12) {
            rawMonths.forEach(m => {
                const feeVal = parseFloat(m.fee) || 0;
                const consVal = parseFloat(m.concession) || 0;
                const totalVal = parseFloat(m.total) || 0;
                const paidVal = parseFloat(m.paid) || 0;
                const duesVal = parseFloat(m.dues) || 0;
                feeData.push({
                    fee: feeVal,
                    cons: consVal,
                    total: totalVal,
                    paid: paidVal,
                    dues: duesVal,
                    paidStatus: (duesVal === 0 && totalVal > 0),
                    asterisk: false 
                });
            });
        } else {
            for(let i = 0; i < 12; i++) {
                feeData.push({ fee: 0, cons: 0, total: 0, paid: 0, dues: 0, paidStatus: false, asterisk: false });
            }
        }

        const tbody = document.getElementById('feeTableBody');
        const totalDuesSpan = document.getElementById('totalDuesSpan');
        const selectAllCheckbox = document.getElementById('selectAll');

        // Estimate current month index for auto-checking (naive logic based on current date)
        const currentMonthIndex = (new Date().getMonth() + 9) % 12; // Adjusted to April=0

        function renderTable() {
            let html = '';
            months.forEach((month, idx) => {
                const d = feeData[idx];
                const paidDisplay = '₹' + d.paid + (d.asterisk ? '<span class="asterisk">*</span>' : '');
                let actionHtml = '';

                let monthLabel = month.toUpperCase();
                if (d.paidStatus) {
                    actionHtml = `<div style="display:flex; align-items:center; gap:8px;">
                            <span class="paid-badge"><i class="fas fa-check-circle"></i> Paid</span> 
                            <div class="action-icons">
                                <i class="fas fa-print" style="color:#ff913b;" title="Print"></i>
                                <i class="fas fa-trash delete-icon" title="Delete"></i>
                                <i class="fab fa-whatsapp whatsapp-icon" title="WhatsApp"></i>
                            </div>
                        </div>`;
                } else {
                    actionHtml = `<div class="action-icons">
                            <i class="fas fa-info-circle info-icon" title="View Details" data-month="${month}" data-idx="${idx}"></i>
                        </div>`;
                }

                html += `<tr>
                    <td>
                        <input type="checkbox" class="month-checkbox" value="${idx}" data-dues="${d.dues}" ${d.dues === 0 ? 'checked disabled' : ''} style="width:14px;height:14px;accent-color:var(--primary-orange);margin-right:8px;vertical-align:middle;">
                        <strong>${monthLabel}</strong>
                    </td>
                    <td>₹${d.fee}</td>
                    <td>₹${d.cons}</td>
                    <td>₹${d.total}</td>
                    <td>${paidDisplay}</td>
                    <td>₹${d.dues}</td>
                    <td>${actionHtml}</td>
                </tr>`;
            });
            tbody.innerHTML = html;

            // Attach event listeners to all view/info icons
            document.querySelectorAll('.fa-eye, .fa-info-circle').forEach(icon => {
                icon.addEventListener('click', (e) => {
                    const month = icon.dataset.month;
                    const idx = icon.dataset.idx;
                    showDetailModal(month, idx);
                });
            });

            // Add change event to each checkbox to update total
            document.querySelectorAll('.month-checkbox').forEach(cb => {
                cb.addEventListener('change', updateTotalDues);
            });

            // Set default selection: select all unpaid months up to the current month index
            document.querySelectorAll('.month-checkbox').forEach(cb => {
                if (cb.disabled) return; // Skip already paid/checked months
                const idx = parseInt(cb.value);
                if (idx <= currentMonthIndex) {
                    cb.checked = true;
                }
            });

            updateSelectAllState();
            updateTotalDues();
        }

        function updateTotalDues() {
            let total = 0;
            document.querySelectorAll('.month-checkbox:checked').forEach(cb => {
                total += parseFloat(cb.dataset.dues) || 0;
            });
            totalDuesSpan.innerText = '₹' + total.toLocaleString('en-IN');
        }

        function updateSelectAllState() {
            const allCheckboxes = document.querySelectorAll('.month-checkbox:not(:disabled)');
            if(allCheckboxes.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.disabled = true;
                return;
            }
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && Array.from(allCheckboxes).some(cb => cb.checked);
        }

        selectAllCheckbox.addEventListener('change', function(e) {
            document.querySelectorAll('.month-checkbox:not(:disabled)').forEach(cb => {
                cb.checked = e.target.checked;
            });
            updateTotalDues();
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('month-checkbox')) {
                updateSelectAllState();
            }
        });

        // Detail modal
        const detailModal = document.getElementById('detailModal');
        const closeDetail = document.getElementById('closeDetailModal');
        const detailMonthTitle = document.getElementById('detailMonthTitle');
        const detailBody = document.getElementById('detailBody');

        function showDetailModal(month, idx) {
            detailMonthTitle.innerText = month + ' Fee Details';
            const data = feeData[idx];
            let rows = `<tr>
                <td>${month}</td>
                <td>Standard Monthly Fee</td>
                <td>₹${data.total}</td>
                <td>₹${data.paid}</td>
                <td>₹${data.dues}</td>
                <td>${data.dues > 0 ? '<span class="due-badge">Due</span>' : '<span class="paid-badge">Paid</span>'}</td>
            </tr>`;
            detailBody.innerHTML = rows;
            detailModal.classList.add('show');
        }

        closeDetail.addEventListener('click', () => detailModal.classList.remove('show'));
        window.addEventListener('click', (e) => { if (e.target === detailModal) detailModal.classList.remove('show'); });

        // Collect modal logic
        const collectModal = document.getElementById('collectModal');
        const closeCollect = document.getElementById('closeCollectModal');
        const cancelCollect = document.getElementById('cancelCollect');
        const modalSubtotal = document.getElementById('modalSubtotal');
        const modalDiscount = document.getElementById('modalDiscount');
        const modalTotal = document.getElementById('modalTotal');
        const modalPayAmount = document.getElementById('modalPayAmount');
        const modalDues = document.getElementById('modalDues');

        function updateCollectModal() {
            const sub = parseFloat(modalSubtotal.innerText.replace('₹','').replace(/,/g,'')) || 0;
            const disc = parseFloat(modalDiscount.value) || 0;
            const total = sub - disc;
            modalTotal.innerText = '₹' + total.toFixed(2);
            
            // By default, assume paying the full discounted amount
            const pay = parseFloat(modalPayAmount.value);
            if(isNaN(pay) && document.activeElement !== modalPayAmount) {
                 modalPayAmount.value = total.toFixed(2);
            }
            
            const currentPay = parseFloat(modalPayAmount.value) || 0;
            const dues = total - currentPay;
            modalDues.value = dues.toFixed(2);
        }

        modalDiscount.addEventListener('input', updateCollectModal);
        modalPayAmount.addEventListener('input', updateCollectModal);

        document.getElementById('collectBtn').addEventListener('click', () => {
            const selected = Array.from(document.querySelectorAll('.month-checkbox:checked'));
            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please select at least one month with dues.'
                });
                return;
            }
            let totalDues = 0;
            selected.forEach(cb => {
                totalDues += parseFloat(cb.dataset.dues) || 0;
            });
            
            modalSubtotal.innerText = '₹' + totalDues.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            modalDiscount.value = 0;
            modalPayAmount.value = totalDues.toFixed(2);
            updateCollectModal();
            
            collectModal.classList.add('show');
        });

        closeCollect.addEventListener('click', () => collectModal.classList.remove('show'));
        cancelCollect.addEventListener('click', () => collectModal.classList.remove('show'));
        window.addEventListener('click', (e) => { if (e.target === collectModal) collectModal.classList.remove('show'); });

        renderTable();
    })();
</script>
@endif

@endsection