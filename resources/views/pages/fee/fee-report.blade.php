@extends('layouts.app')

@section('title', 'Fee Report')
@section('page_icon', 'fas fa-chart-line')

@push('styles')
<style>
    .reports-card {
        background: white;
        border-radius: 36px;
        box-shadow: var(--shadow);
        padding: 28px 30px;
        transition: var(--transition);
        margin-bottom: 2rem;
    }
    .reports-card:hover { box-shadow: var(--shadow-hover); }
    .header-title { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .header-title h1 { font-size: 2rem; font-weight: 700; color: var(--primary-blue); }
    .header-title i { font-size: 2rem; color: var(--primary-orange); }
    .header-sub { color: var(--text-muted); margin-bottom: 24px; margin-left: 10px; font-size: 0.95rem; }

    /* filter panel */
    .filter-panel {
        background: #f2f8ff;
        border-radius: 28px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 16px 20px;
    }
    .filter-group { display: flex; flex-direction: column; min-width: 140px; flex: 1 1 160px; }
    .filter-group label { font-size: 0.7rem; text-transform: uppercase; font-weight: 700; color: var(--primary-blue); margin-bottom: 4px; }
    .filter-group select, .filter-group input {
        background: white; border: 1px solid #e0e7f0; border-radius: 16px; padding: 10px 14px;
        font-size: 0.9rem; color: var(--text-dark); outline: none; width: 100%;
    }
    .filter-group input:focus, .filter-group select:focus { border-color: var(--primary-orange); box-shadow: 0 0 0 3px rgba(255,145,59,0.2); }
    
    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-report {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid var(--border);
        background: white;
        color: var(--txt1);
    }
    .btn-report:hover {
        background: var(--bg);
        border-color: var(--blue);
        color: var(--blue);
        transform: translateY(-1px);
    }
    .btn-orange {
        background: var(--orange);
        border-color: var(--orange);
        color: white;
    }
    .btn-orange:hover {
        background: var(--orange);
        opacity: 0.9;
        color: white;
    }

    /* table */
    .table-wrapper { overflow-x: auto; border-radius: 24px; background: white; box-shadow: var(--shadow); margin-bottom: 20px; }
    .report-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .report-table th { background: var(--primary-blue); color: white; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; padding: 14px 8px; border: 2px solid #3a6fa8; text-align: left; }
    .report-table td { padding: 12px 8px; border: 2px solid #d9e2ec; color: var(--text-dark); font-size: 0.85rem; }
    .report-table tr:hover td { background: #f8fcff; }
    .total-row td { background: #f0f7ff; font-weight: 700; color: var(--primary-blue); }
    
    .action-icons { display: flex; gap: 8px; align-items: center; }
    .action-icons i { cursor: pointer; font-size: 1rem; transition: 0.2s; }
    .action-icons i:hover { transform: scale(1.1); }
    .view-icon { color: #2a86da; }
    .print-icon { color: #27ae60; }
    .whatsapp-icon { color: #25D366; }

    /* modal */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.3); backdrop-filter: blur(3px);
        display: none; align-items: center; justify-content: center;
        z-index: 10000; padding: 16px;
    }
    .modal-overlay.show { display: flex; }
    .modal-container { background: white; border-radius: 28px; max-width: 700px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 40px -12px rgba(0,0,0,0.25); padding: 24px; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-header h3 { font-size: 1.5rem; color: var(--primary-blue); }
    .close-modal { background: none; border: none; font-size: 2rem; cursor: pointer; color: var(--text-muted); }
    .student-info { display: flex; align-items: center; margin-bottom: 20px; }
    .student-photo { width: 80px; height: 80px; border-radius: 50%; background: var(--blue-light); display: flex; align-items: center; justify-content: center; color: var(--primary-blue); font-size: 2rem; border: 3px solid var(--primary-orange); margin-right: 20px; flex-shrink: 0; }
    .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f8fcff; padding: 16px; border-radius: 16px; margin-bottom: 20px; }
    .details-grid div span:first-child { color: var(--primary-blue); font-weight: 600; }
</style>
@endpush

@section('content')
<div class="reports-card">
    <div class="header-title">
        <i class="fas fa-chart-line"></i>
        <h1>Fee Reports</h1>
    </div>
    <div class="header-sub">
        Generate various fee reports with filters and export
    </div>

    <!-- Report type selector -->
    <div class="filter-panel" style="justify-content: space-between;">
        <div class="filter-group" style="min-width:250px;">
            <label>Report Type</label>
            <select id="reportTypeSelect">
                <option value="classwise">Classwise Report</option>
                <option value="collection">Fee Collection Report</option>
                <option value="ledger">Student Yearwise Ledger</option>
                <option value="overall">Overall Collection Report</option>
                <option value="heavy">Heavy Due Amount Report</option>
                <option value="feeType">Report by Fee Type</option>
                <option value="daily">Daily Collection Report</option>
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn-report btn-orange" id="generateBtn"><i class="fas fa-sync"></i> Generate</button>
            <button class="btn-report" id="exportPdfBtn"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn-report" id="exportExcelBtn"><i class="fas fa-file-excel"></i> Excel</button>
            <button class="btn-report" id="whatsappShareBtn"><i class="fab fa-whatsapp"></i> Share</button>
        </div>
    </div>

    <!-- Dynamic filter panel -->
    <div class="filter-panel" id="extraFilters"></div>

    <!-- Report table -->
    <div class="table-wrapper">
        <table class="report-table" id="reportTable">
            <thead id="tableHeader"></thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

<!-- Modal for Student Ledger Details -->
<div class="modal-overlay" id="ledgerModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalStudentName">Student Ledger Details</h3>
            <button class="close-modal" id="closeModalBtn">&times;</button>
        </div>
        <div class="student-info">
            <div class="student-photo"><i class="fas fa-user-graduate"></i></div>
            <div>
                <h4 id="modalStudentFull">--</h4>
                <p id="modalStudentClass">--</p>
                <p id="modalStudentRoll">--</p>
            </div>
        </div>
        <div class="details-grid">
            <div><span>Student ID:</span> <span id="modalSid">--</span></div>
            <div><span>Father's Name:</span> <span id="modalFather">--</span></div>
            <div><span>Session:</span> <span id="modalSession">--</span></div>
            <div><span>Total Paid:</span> <span id="modalTotal">₹0</span></div>
        </div>
        <div class="table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount (₹)</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="modalPaymentTable"></tbody>
            </table>
        </div>
        <div class="modal-actions d-flex justify-content-end gap-2 mt-3">
            <button class="btn-report" id="printLedgerBtn"><i class="fas fa-print"></i> Print</button>
            <button class="btn-report btn-orange" id="shareLedgerWhatsAppBtn"><i class="fab fa-whatsapp"></i> Share</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const reportType = document.getElementById('reportTypeSelect');
        const extraFiltersDiv = document.getElementById('extraFilters');
        const tableHeader = document.getElementById('tableHeader');
        const tableBody = document.getElementById('tableBody');
        const generateBtn = document.getElementById('generateBtn');
        const exportPdfBtn = document.getElementById('exportPdfBtn');
        const exportExcelBtn = document.getElementById('exportExcelBtn');
        const whatsappBtn = document.getElementById('whatsappShareBtn');

        const modal = document.getElementById('ledgerModal');
        const closeModal = document.getElementById('closeModalBtn');
        
        let currentReportData = [];

        closeModal.onclick = () => modal.classList.remove('show');
        window.onclick = (e) => { if (e.target === modal) modal.classList.remove('show'); };

        function renderExtraFilters() {
            const type = reportType.value;
            let html = '';
            const classesHtml = `<option value="">All</option>@foreach($classes as $c)<option value="{{ $c->name }}">{{ $c->name }}</option>@endforeach`;

            if (type === 'collection') {
                html = `
                    <div class="filter-group"><label>From Date</label><input type="date" id="fromDate" value="{{ date('Y-m-01') }}"></div>
                    <div class="filter-group"><label>To Date</label><input type="date" id="toDate" value="{{ date('Y-m-d') }}"></div>
                    <div class="filter-group"><label>Class</label><select id="filterClass">${classesHtml}</select></div>
                `;
            } else if (type === 'ledger') {
                html = `
                    <div class="filter-group"><label>Student Search</label><input type="text" id="ledgerSearch" placeholder="Name or ID"></div>
                    <div class="filter-group"><label>Session</label><select id="ledgerSession"><option value="2025-2026">2025-2026</option></select></div>
                `;
            } else if (type === 'daily') {
                html = `<div class="filter-group"><label>Date</label><input type="date" id="dailyDate" value="{{ date('Y-m-d') }}"></div>`;
            } else if (type === 'classwise') {
                html = `<div class="filter-group"><label>Class</label><select id="classwiseClass">${classesHtml}</select></div>`;
            }
            extraFiltersDiv.innerHTML = html;
        }

        async function fetchData() {
            const type = reportType.value;
            const params = new URLSearchParams({ type: type });
            
            if (type === 'collection') {
                params.append('fromDate', document.getElementById('fromDate').value);
                params.append('toDate', document.getElementById('toDate').value);
                params.append('class', document.getElementById('filterClass').value);
            } else if (type === 'ledger') {
                params.append('search', document.getElementById('ledgerSearch').value);
                params.append('session', document.getElementById('ledgerSession').value);
            } else if (type === 'daily') {
                params.append('date', document.getElementById('dailyDate').value);
            } else if (type === 'classwise') {
                params.append('class', document.getElementById('classwiseClass').value);
            }

            try {
                generateBtn.disabled = true;
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
                
                const response = await fetch(`{{ route('admin.fee-report.data') }}?${params.toString()}`);
                const data = await response.json();
                currentReportData = data;
                renderTable(type, data);
            } catch (error) {
                console.error('Error fetching report data:', error);
                Swal.fire('Error', 'Failed to fetch report data', 'error');
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<i class="fas fa-sync"></i> Generate';
            }
        }

        function renderTable(type, data) {
            let headerHtml = '', bodyHtml = '';

            if (type === 'classwise') {
                headerHtml = `<tr><th>Class</th><th>Students</th><th>Total Billed (₹)</th><th>Collected (₹)</th><th>Pending (₹)</th><th>Actions</th></tr>`;
                data.forEach(row => {
                    bodyHtml += `<tr><td>${row.class}</td><td>${row.students}</td><td>₹${row.totalFee}</td><td>₹${row.collected}</td><td>₹${row.pending}</td><td class="action-icons"><i class="fas fa-print print-icon" onclick="window.print()"></i></td></tr>`;
                });
            } else if (type === 'collection') {
                headerHtml = `<tr><th>Date</th><th>Receipt No.</th><th>Student</th><th>Class</th><th>Amount (₹)</th><th>Description</th></tr>`;
                data.forEach(row => {
                    bodyHtml += `<tr><td>${row.date}</td><td>${row.receiptNo}</td><td>${row.student}</td><td>${row.class}</td><td>₹${row.amount}</td><td>${row.mode}</td></tr>`;
                });
            } else if (type === 'ledger') {
                headerHtml = `<tr><th>Student ID</th><th>Name</th><th>Class</th><th>Total Paid (₹)</th><th>Actions</th></tr>`;
                if (!Array.isArray(data)) { // Single student object
                    const row = data;
                    bodyHtml = `<tr><td>${row.studentId}</td><td>${row.name}</td><td>${row.class}</td><td>₹${row.total}</td><td class="action-icons"><i class="fas fa-eye view-icon" onclick='showLedgerModal(${JSON.stringify(row)})'></i></td></tr>`;
                }
            } else if (type === 'overall') {
                headerHtml = `<tr><th>Month</th><th>Collected (₹)</th><th>Dues (₹)</th></tr>`;
                data.forEach(row => bodyHtml += `<tr><td>${row.month}</td><td>₹${row.collected}</td><td>₹${row.dues}</td></tr>`);
            } else if (type === 'daily') {
                headerHtml = `<tr><th>Date</th><th>Receipt No.</th><th>Student</th><th>Class</th><th>Online (₹)</th><th>Offline (₹)</th><th>Total (₹)</th></tr>`;
                data.forEach(row => {
                    bodyHtml += `<tr><td>${row.date}</td><td>${row.receiptNo}</td><td>${row.student}</td><td>${row.class}</td><td>₹${row.online}</td><td>₹${row.offline}</td><td>₹${row.total}</td></tr>`;
                });
            }

            tableHeader.innerHTML = headerHtml;
            tableBody.innerHTML = bodyHtml || '<tr><td colspan="10" class="text-center py-4">No data found</td></tr>';
        }

        window.showLedgerModal = (student) => {
            document.getElementById('modalStudentFull').innerText = student.name;
            document.getElementById('modalStudentClass').innerText = `Class ${student.class}`;
            document.getElementById('modalSid').innerText = student.studentId;
            document.getElementById('modalFather').innerText = student.father;
            document.getElementById('modalSession').innerText = student.year;
            document.getElementById('modalTotal').innerText = `₹${student.total}`;

            let paymentHtml = '';
            student.transactions.forEach(t => {
                paymentHtml += `<tr><td>${t.date}</td><td>${t.type}</td><td>₹${t.amount}</td><td>${t.desc}</td></tr>`;
            });
            document.getElementById('modalPaymentTable').innerHTML = paymentHtml;
            modal.classList.add('show');
        };

        generateBtn.onclick = fetchData;
        reportType.onchange = () => { renderExtraFilters(); };

        // Export logic
        exportPdfBtn.onclick = () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text(`${reportType.options[reportType.selectedIndex].text}`, 14, 15);
            doc.autoTable({ html: '#reportTable', startY: 20 });
            doc.save('report.pdf');
        };

        exportExcelBtn.onclick = () => {
            const wb = XLSX.utils.table_to_book(document.getElementById('reportTable'));
            XLSX.writeFile(wb, 'report.xlsx');
        };

        renderExtraFilters();
    })();
</script>
@endpush
