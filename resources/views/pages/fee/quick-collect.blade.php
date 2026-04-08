@extends('layouts.app')

@section('title', 'Quick Collect')
@section('page_icon', 'fas fa-bolt')

@push('styles')
<style>
    /* Quick Collect specific adjustments */
    /* Quick Collect Premium Styles */
    .profile-card { background: var(--surface); border-radius: var(--r3); padding: 26px; box-shadow: var(--sh2); border: 1px solid var(--border); }
    .filter-bar { 
        background: #f8fcff; 
        padding: 10px; 
        border-radius: 50px; 
        margin-bottom: 25px; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        border: 1.5px solid #e0eafc;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .filter-group-item {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid #e0e7f0;
        border-radius: 40px;
        padding: 4px 4px 4px 16px;
        transition: var(--transition);
        flex: 1;
    }
    .filter-group-item:focus-within { border-color: var(--blue); box-shadow: 0 0 0 4px rgba(61,132,245,0.1); }
    .filter-group-item i { color: var(--txt3); font-size: 0.9rem; margin-right: 10px; }
    .filter-group-item select, .filter-group-item input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.9rem;
        padding: 8px 0;
        color: var(--txt1);
        width: 100%;
        font-weight: 500;
    }
    .filter-select-wrap {
        min-width: 150px;
        border-right: 1px solid #eee;
        padding-right: 10px;
        margin-right: 10px;
    }
    .search-input-wrap { flex: 3; }
    .search-btn {
        background: var(--blue);
        color: white;
        border: none;
        border-radius: 40px;
        padding: 10px 24px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        white-space: nowrap;
    }
    .search-btn:hover { background: var(--orange); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(255,99,48,0.2); }
    
    .student-summary { background: linear-gradient(145deg, #f8fcff, #ffffff); border-radius: 24px; padding: 18px 25px; margin-bottom: 25px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; border: 1px solid rgba(72,143,228,0.15); box-shadow: var(--sh1); }
    .student-info { display: flex; flex-wrap: wrap; gap: 20px; }
    .info-item { display: flex; flex-direction: column; }
    .info-item .label { font-size: 0.65rem; text-transform: uppercase; color: var(--primary-blue); font-weight: 700; }
    .info-item .value { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-top: 2px; }
    .student-photo { width: 60px; height: 60px; border-radius: 50%; background: #eef3ff; display: flex; align-items: center; justify-content: center; color: var(--blue); font-size: 1.8rem; border: 3px solid var(--orange); }

    /* Right aligned dues bar and orange button */
    .dues-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 25px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }
    .total-dues { font-weight: 700; color: var(--txt2); font-size: 1.1rem; }
    .total-dues span { color: var(--orange); font-size: 1.5rem; margin-left: 8px; font-weight: 800; }
    .collect-btn {
        background: var(--orange);
        color: white;
        border: none;
        border-radius: 40px;
        padding: 14px 40px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: var(--transition);
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(255,99,48,0.2);
    }
    .collect-btn i { font-size: 1.3rem; }
    .collect-btn:hover { background: #e35520; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,99,48,0.3); }
</style>
@endpush
@section('content')

<div class="profile-card">

<!-- Header filters -->
<form method="GET" action="{{ route('admin.quick-collect.index') }}" class="filter-bar">

    <div class="filter-group-item search-input-wrap">
        <i class="fas fa-search"></i>
        <div class="filter-select-wrap">
            <select name="class_id">
                <option value="">Select Class</option>
                @foreach($globalClasses as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                {{ $class->name }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="filter-select-wrap" style="min-width: 130px;">
            <select name="section_id">
                <option value="">Select Section</option>
                @if(isset($sections))
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                    {{ $section->name }}
                    </option>
                    @endforeach
                @endif
            </select>
        </div>

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Student by Name or Reg Number...">
        
        <button type="submit" class="search-btn">
            <i class="fas fa-search"></i> Search
        </button>
    </div>

</form>


<!-- Student summary -->

@if(isset($student))

<div class="student-summary">

<div class="student-info">

<div class="info-item">
<span class="label">Student</span>
<span class="value">{{ $student->student_name }}</span>
</div>

<div class="info-item">
<span class="label">Class</span>
<span class="value">{{ $student->class ? $student->class->name : '-' }}</span>
</div>

<div class="info-item">
<span class="label">Section</span>
<span class="value">{{ $student->section ? $student->section->name : '-' }}</span>
</div>

<div class="info-item">
<span class="label">Father</span>
<span class="value">{{ $student->father_name ?? $student->parent_name ?? '-' }}</span>
</div>

<div class="info-item">
<span class="label">SID</span>
<span class="value">{{ $student->admission_no }}</span>
</div>

</div>

<div class="student-photo">
<i class="fas fa-child"></i>
</div>

</div>

@endif


<div class="content-section-title">
Monthly Fee Breakdown (April - March)
</div>


<div class="table-wrapper">

<table>

<thead>

<tr>
<th class="checkbox-col">
<input type="checkbox" id="selectAll">
</th>

<th>MONTH</th>
<th>FEE (₹)</th>
<th>CONS.</th>
<th>FINE (₹)</th>
<th>TOTAL (₹)</th>
<th>PAID (₹)</th>
<th>DUES (₹)</th>
<th>ACTION</th>

</tr>

</thead>

<tbody id="feeTableBody">
</tbody>

</table>

</div>


<div class="dues-bar">

<div class="total-dues">
Total Dues
<span id="totalDuesSpan">₹0</span>
</div>

<button class="collect-btn" id="collectBtn">
<i class="fas fa-hand-holding-usd"></i>
Collect Fees
</button>

</div>

</div>

<!-- Detail Modal -->
<div class="modal-overlay" id="detailModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="detailMonthTitle">Fee Details</h3>
            <button class="close-modal" id="closeDetailModal">&times;</button>
        </div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>MONTH</th>
                    <th>FEE HEAD</th>
                    <th>AMOUNT (₹)</th>
                    <th>PAID (₹)</th>
                    <th>DUE (₹)</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody id="detailBody">
            </tbody>
        </table>
    </div>
</div>

<!-- Collect Modal -->
<div class="modal-overlay" id="collectModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Complete Payment</h3>
            <button class="close-modal" id="closeCollectModal">&times;</button>
        </div>
        <div class="amount-row">
            <div class="amount-item">
                <div class="form-group">
                    <label>Subtotal</label>
                    <div style="font-weight:700; font-size:1.1rem;" id="modalSubtotal">₹0</div>
                </div>
            </div>
            <div class="amount-item">
                <div class="form-group">
                    <label>Discount (₹)</label>
                    <input type="number" id="modalDiscount" value="0">
                </div>
            </div>
        </div>
        <div class="amount-row">
            <div class="amount-item">
                <div class="form-group">
                    <label>Net Total</label>
                    <div style="font-weight:700; font-size:1.1rem; color:var(--primary-blue);" id="modalTotal">₹0</div>
                </div>
            </div>
            <div class="amount-item">
                <div class="form-group">
                    <label>Paying Now (₹)</label>
                    <input type="number" id="modalPayAmount" value="0">
                </div>
            </div>
        </div>
        <div class="amount-row">
            <div class="amount-item">
                <div class="form-group">
                    <label>Remaining Dues (₹)</label>
                    <input type="text" id="modalDues" value="0" readonly style="background:#f0f5fa; border:none; font-weight:700; color:var(--text-muted);">
                </div>
            </div>
            <div class="amount-item">
                <div class="form-group">
                    <label>Payment Mode</label>
                    <select id="modalMode">
                        <option value="Cash">Cash</option>
                        <option value="UPI">UPI</option>
                        <option value="Card">Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn" id="cancelCollect">Cancel</button>
            <button class="btn btn-primary" id="payNowBtn"><i class="fas fa-check"></i> Process Payment</button>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    (function() {
        // Month order: April = 0, March = 11
        const months = [
            "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER",
            "OCTOBER", "NOVEMBER", "DECEMBER", "JANUARY", "FEBRUARY", "MARCH"
        ];

        @if(isset($student) && isset($months))
            const feeDataTemp = @json($months);
            const feeData = feeDataTemp.map(m => ({
                fee: m.fee,
                cons: m.concession,
                fine: m.late_fine || 0,
                total: m.total,
                paid: m.paid,
                dues: m.dues,
                paidStatus: m.dues <= 0,
                asterisk: (m.late_fine || 0) > 0
            }));
            
            // Generate basic monthDetails for the modal view based on single fee record
            const monthDetails = {};
            months.forEach((month, idx) => {
                monthDetails[month] = [{
                    type: "Total Fee",
                    amount: feeData[idx].fee,
                    paid: feeData[idx].paid,
                    dues: feeData[idx].dues
                }];
            });
        @else
            const feeData = [];
            const monthDetails = {};
        @endif


        const tbody = document.getElementById('feeTableBody');
        const totalDuesSpan = document.getElementById('totalDuesSpan');
        const selectAllCheckbox = document.getElementById('selectAll');

        // Determine current month index (0-based, April=0). For demo, we set current month to February (index 10)
        const currentMonthIndex = 10; // February

        function renderTable() {
            let html = '';
            
            if (feeData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding: 30px; color: var(--text-muted);">Please search for a student to view and collect fees.</td></tr>';
                return;
            }

            months.forEach((month, idx) => {
                const d = feeData[idx];
                if (!d) return;

                const paidDisplay = d.paid + (d.asterisk ? '<span class="asterisk">*</span>' : '');
                let actionHtml = '';

                if (d.paidStatus) {
                    actionHtml = `<span class="paid-badge"><i class="fas fa-check-circle"></i> Paid</span> 
                        <div class="action-icons">
                            <i class="fas fa-eye view-icon" title="View" data-month="${month}"></i>
                            <i class="fas fa-edit edit-icon" title="Edit" data-month="${month}"></i>
                            <i class="fas fa-trash delete-icon" title="Delete" data-month="${month}"></i>
                            <i class="fab fa-whatsapp whatsapp-icon" title="WhatsApp" data-month="${month}"></i>
                        </div>`;
                } else {
                    actionHtml = `<div class="action-icons">
                            <i class="fas fa-info-circle info-icon" title="View Details" data-month="${month}"></i>
                        </div>`;
                }

                html += `<tr>
                    <td class="checkbox-col"><input type="checkbox" class="month-checkbox" value="${idx}" data-dues="${d.dues}" ${d.dues === 0 ? 'checked disabled' : ''}></td>
                    <td><strong>${month}</strong> ${d.asterisk ? '<i class="fas fa-clock text-warning" title="Late Fine Applied"></i>' : ''}</td>
                    <td>₹${d.fee}</td>
                    <td>${d.cons}</td>
                    <td>₹${d.fine}</td>
                    <td>₹${d.total}</td>
                    <td>${paidDisplay}</td>
                    <td>${d.dues}</td>
                    <td>${actionHtml}</td>
                </tr>`;
            });
            tbody.innerHTML = html;

            // Attach event listeners to all view/info icons
            document.querySelectorAll('.fa-eye, .fa-info-circle').forEach(icon => {
                icon.addEventListener('click', (e) => {
                    const month = icon.dataset.month;
                    showDetailModal(month);
                });
            });

            // Demo for other icons (edit, delete, whatsapp)
            document.querySelectorAll('.fa-edit').forEach(icon => {
                icon.addEventListener('click', () => alert('Edit action (demo)'));
            });
            document.querySelectorAll('.fa-trash').forEach(icon => {
                icon.addEventListener('click', () => alert('Delete action (demo)'));
            });
            document.querySelectorAll('.fa-whatsapp').forEach(icon => {
                icon.addEventListener('click', () => alert('Share via WhatsApp (demo)'));
            });

            // Add change event to each checkbox to update total
            document.querySelectorAll('.month-checkbox').forEach(cb => {
                cb.addEventListener('change', updateTotalDues);
            });

            // Set default selection: select all unpaid months with index <= currentMonthIndex
            document.querySelectorAll('.month-checkbox').forEach(cb => {
                if (cb.disabled) return; // Skip already paid/checked months
                const idx = parseInt(cb.value);
                if (idx <= currentMonthIndex) {
                    cb.checked = true;
                }
            });

            // Update selectAll state
            updateSelectAllState();
            // Update total dues display
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
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && Array.from(allCheckboxes).some(cb => cb.checked);
        }

        // Select All functionality
        selectAllCheckbox.addEventListener('change', function(e) {
            document.querySelectorAll('.month-checkbox:not(:disabled)').forEach(cb => {
                cb.checked = e.target.checked;
            });
            updateTotalDues();
        });

        // Also update selectAll when individual checkboxes change
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

        function showDetailModal(month) {
            detailMonthTitle.innerText = month + ' Fee Details';
            const details = monthDetails[month] || [{ type: 'Tuition Fee', amount: feeData[months.indexOf(month)]?.fee || 0, paid: 0, dues: feeData[months.indexOf(month)]?.dues || 0 }];
            let rows = '';
            details.forEach(item => {
                rows += `<tr>
                    <td>${month}</td>
                    <td>${item.type}</td>
                    <td>₹${item.amount}</td>
                    <td>₹${item.paid}</td>
                    <td>₹${item.dues}</td>
                    <td><span class="due-badge">Due</span></td>
                </tr>`;
            });
            detailBody.innerHTML = rows;
            detailModal.classList.add('show');
        }

        closeDetail.addEventListener('click', () => detailModal.classList.remove('show'));
        window.addEventListener('click', (e) => { if (e.target === detailModal) detailModal.classList.remove('show'); });

        // Collect modal logic
        const collectModal = document.getElementById('collectModal');
        const closeCollect = document.getElementById('closeCollectModal');
        const cancelCollect = document.getElementById('cancelCollect');
        const payNowBtn = document.getElementById('payNowBtn');
        const modalSubtotal = document.getElementById('modalSubtotal');
        const modalDiscount = document.getElementById('modalDiscount');
        const modalTotal = document.getElementById('modalTotal');
        const modalPayAmount = document.getElementById('modalPayAmount');
        const modalDues = document.getElementById('modalDues');
        const modalMode = document.getElementById('modalMode');

        function updateCollectModal() {
            const sub = parseFloat(modalSubtotal.innerText.replace('₹','').replace(/,/g,'')) || 0;
            const disc = parseFloat(modalDiscount.value) || 0;
            const total = sub - disc;
            modalTotal.innerText = '₹' + total.toFixed(2);
            const pay = parseFloat(modalPayAmount.value) || 0;
            const dues = total - pay;
            modalDues.value = dues.toFixed(2);
        }

        modalDiscount.addEventListener('input', updateCollectModal);
        modalPayAmount.addEventListener('input', updateCollectModal);

        document.getElementById('collectBtn').addEventListener('click', () => {
            const selected = Array.from(document.querySelectorAll('.month-checkbox:checked')).map(cb => months[parseInt(cb.value)]);
            if (selected.length === 0) {
                alert('Please select at least one month with dues.');
                return;
            }
            let totalDues = 0;
            selected.forEach(month => {
                const idx = months.indexOf(month);
                totalDues += feeData[idx].dues;
            });
            modalSubtotal.innerText = '₹' + totalDues.toLocaleString('en-IN');
            modalDiscount.value = 0;
            modalPayAmount.value = totalDues;
            updateCollectModal();
            collectModal.classList.add('show');
        });

        closeCollect.addEventListener('click', () => collectModal.classList.remove('show'));
        cancelCollect.addEventListener('click', () => collectModal.classList.remove('show'));
        payNowBtn.addEventListener('click', () => {
            const pay = parseFloat(modalPayAmount.value) || 0;
            const mode = modalMode.value;
            alert(`Payment of ₹${pay} via ${mode} processed (demo).`);
            collectModal.classList.remove('show');
        });
        window.addEventListener('click', (e) => { if (e.target === collectModal) collectModal.classList.remove('show'); });

        renderTable();
    })();
</script>
        
@endpush