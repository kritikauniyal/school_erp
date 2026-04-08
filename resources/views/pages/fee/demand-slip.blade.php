@extends('layouts.app')

@section('title','Demand Slip')

@section('page-title','Demand Slip')

@push('styles')
    <style>
        /* Specific Inner Page styles */
        .demand-slip {
            width: 100%;
        }
        .action-icons {
            display: flex;
            gap: 12px;
        }
        .action-icons i {
            cursor: pointer;
            font-size: 1.1rem;
            transition: 0.2s;
        }
        .view-icon { color: #2a86da; }
        .print-icon { color: #27ae60; }
        .action-icons i:hover {
            transform: scale(1.2);
        }

        /* print log table */
        .log-section {
            margin-top: 30px;
            background: #f8fcff;
            border-radius: 24px;
            padding: 20px;
        }
        .content-section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 16px 0 12px;
            border-left: 5px solid var(--primary-orange);
            padding-left: 12px;
        }

        /* modal for individual slip - compact */
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
            border-radius: 24px;
            max-width: 600px;
            width: 100%;
            max-height: 95vh;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.25);
            padding: 12px;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: var(--text-muted);
            float: right;
            margin-top: -5px;
            margin-right: -5px;
        }

        /* compact demand slip */
        .demand-slip {
            font-family: 'Inter', sans-serif;
            background: white;
            padding: 8px;
            border-radius: 12px;
            border: 1px solid #d9e2ec;
            font-size: 0.75rem;
            line-height: 1.2;
        }
        .demand-slip .slip-header {
            text-align: center;
            margin-bottom: 6px;
        }
        .demand-slip .slip-header h2 {
            color: var(--primary-blue);
            font-size: 1.1rem;
            margin-bottom: 2px;
        }
        .demand-slip .slip-header .school-info {
            font-size: 0.6rem;
            color: var(--text-muted);
        }
        .demand-slip .slip-title {
            text-align: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--primary-orange);
            margin: 6px 0;
            border-top: 1px dashed var(--orange-light);
            border-bottom: 1px dashed var(--orange-light);
            padding: 2px 0;
        }
        .demand-slip .month-session {
            display: flex;
            justify-content: space-between;
            font-size: 0.65rem;
            margin-bottom: 6px;
        }
        .demand-slip .student-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            background: #f8fcff;
            padding: 6px;
            border-radius: 10px;
            margin-bottom: 8px;
            font-size: 0.65rem;
        }
        .demand-slip .student-details div span {
            color: var(--primary-blue);
            font-weight: 600;
        }

        /* Fee table without Paid column - FIXED width */
        .demand-slip .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 0.6rem;
            table-layout: fixed;
        }
        .demand-slip .fee-table th,
        .demand-slip .fee-table td {
            padding: 3px 2px;
            border: 1px solid #d9e2ec;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            text-align: left;
        }
        .demand-slip .fee-table th:nth-child(1) { width: 44%; }  /* Fee Type */
        .demand-slip .fee-table th:nth-child(2) { width: 28%; }  /* Fee */
        .demand-slip .fee-table th:nth-child(3) { width: 28%; }  /* Dues */

        /* Attractive totals card */
        .totals-card {
            background: linear-gradient(135deg, #f0f7ff 0%, #e3f0ff 100%);
            border-radius: 12px;
            padding: 8px;
            margin: 10px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid var(--primary-blue);
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px dashed rgba(72,143,228,0.3);
        }
        .totals-row:last-child {
            border-bottom: none;
        }
        .totals-label {
            font-weight: 600;
            color: var(--primary-blue);
            font-size: 0.7rem;
        }
        .totals-value {
            font-weight: 700;
            color: var(--primary-orange);
            font-size: 0.85rem;
        }

        .demand-slip .footer-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.6rem;
            color: var(--text-muted);
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px dashed var(--orange-light);
        }

        /* Signature and WhatsApp inline */
        .signature-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }
        .signature {
            font-style: italic;
            font-size: 0.65rem;
        }
        .whatsapp-share {
            background: #25D366;
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.65rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: 0.2s;
        }
        .whatsapp-share:hover {
            background: #128C7E;
        }

        /* bulk print grid */
        .print-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            page-break-inside: avoid;
        }
        .print-container .demand-slip {
            border: 1px solid #ccc;
            page-break-inside: avoid;
            break-inside: avoid;
            margin: 0;
            font-size: 0.65rem;
        }
        @media print {
            body 
            .print-container, .print-container 
            .print-container {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }
            .demand-slip {
                border: none !important;
            }
        }

        /* ===== MOBILE FIXES ===== */
        @media (max-width: 700px) {
            .demand-card {
                padding: 20px 16px;
            }
            /* Class and Section in one row */
            .filter-row {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
            }
            .filter-row .filter-group {
                flex: 1 1 45%; /* side by side with small gap */
                min-width: 120px;
            }
            /* Month grid 2 columns */
            .month-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
                padding: 12px;
            }
            .month-item {
                min-width: 0;
                font-size: 0.8rem;
            }
        }
    
    </style>
@endpush

@section('content')

<div class="card">

<div class="content-section-title">
Monthly Fee Breakdown (April - March)
</div>
<div class="header-title">
<i class="fas fa-file-invoice"></i>
<h1>Demand Slip Manager</h1>
</div>

<div class="header-sub">
Generate and print demand slips for selected months
</div>

<!-- Filter Panel -->
<div class="filter-panel" style="background:transparent; padding:0;">

    <div class="row" style="margin-bottom: 20px; gap: 15px;">
        <div class="col-md-3">
            <select id="classSelect" class="form-control">
                <option value="">Select Class</option>
                @foreach($globalClasses as $class)
                    <option value="{{ $class->name }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="sectionSelect" class="form-control">
                <option value="">Select Section</option>
                @foreach($sections as $section)
                    <option value="{{ $section->name }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

<div class="filter-group">

<label>Select Months</label>

<div class="month-grid" id="monthCheckboxes">

</div>

</div>

<div class="action-bar">

<button class="btn" id="resetBtn">Reset</button>

<button class="btn btn-orange" id="printBtn">
<i class="fas fa-print"></i> Print
</button>

</div>

</div>


<!-- Student List -->

<div id="studentListContainer" style="display:none">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">

<h3 class="section-title" style="margin:0;">Student List</h3>

<button class="btn btn-orange" id="bulkPrintBtn">
<i class="fas fa-print"></i> Print All Demand
</button>

</div>


<div class="table-wrap">

<table class="data-table">

<thead>

<tr>
<th>SID</th>
<th>Student Name</th>
<th>Father Name</th>
<th>Roll No</th>
<th>Monthly Dues (₹)</th>
<th>Total Dues (₹)</th>
<th>Action</th>
</tr>

</thead>

<tbody id="studentTableBody">

</tbody>

</table>

</div>

</div>


<!-- Print Log -->

<div id="logSection" class="log-section" style="display:none">

<h3>Print History</h3>

<div class="table-wrap">

<table class="data-table">

<thead>

<tr>
<th>Date & Time</th>
<th>Class</th>
<th>Section</th>
<th>Demand Month(s)</th>
<th>Type</th>
</tr>

</thead>

<tbody id="logTableBody"></tbody>

</table>

</div>

</div>

</div>



<!-- Modal -->

<div class="modal-overlay" id="slipModal">

<div class="modal-container">

<button class="close-modal" id="closeSlipModal">&times;</button>

<div id="modalSlipContent"></div>

</div>

</div>

@endsection

@push('scripts')
<script>
    (function() {
        // ---------- Data ----------
        const monthNames = ["April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March"];

        // Data will be fetched dynamically via AJAX
        let currentStudents = [];

        // School details
        const school = {
            name: 'K.N.S. Public School',
            address: 'V.P.O – Kushahar, Tariyani, Sheohar',
            phones: '+91 8709462198, +91 9631977242',
            email: 'knssheohar@gmail.com',
            website: 'www.knspublicschool.in'
        };

        // Print log array
        let printLog = [];

        // DOM elements
        const monthContainer = document.getElementById('monthCheckboxes');
        const classSelect = document.getElementById('classSelect');
        const sectionSelect = document.getElementById('sectionSelect');
        const studentListContainer = document.getElementById('studentListContainer');
        const studentTableBody = document.getElementById('studentTableBody');
        const printBtn = document.getElementById('printBtn');
        const resetBtn = document.getElementById('resetBtn');
        const bulkPrintBtn = document.getElementById('bulkPrintBtn');
        const slipModal = document.getElementById('slipModal');
        const closeSlipModal = document.getElementById('closeSlipModal');
        const modalSlipContent = document.getElementById('modalSlipContent');
        const logSection = document.getElementById('logSection');
        const logTableBody = document.getElementById('logTableBody');

        // Populate months
        function populateMonths() {
            let html = '';
            monthNames.forEach(m => {
                html += `<label class="month-item"><input type="checkbox" class="month-checkbox" value="${m}"> ${m}</label>`;
            });
            monthContainer.innerHTML = html;
        }

        // Get selected months
        function getSelectedMonths() {
            return Array.from(document.querySelectorAll('.month-checkbox:checked')).map(cb => cb.value);
        }

        // Compute total monthly fee for a student
        function getMonthlyTotal(student) {
            return Object.values(student.monthlyFee).reduce((a, b) => a + b, 0);
        }

        // Get current class/section/months
        function getCurrentSelection() {
            return {
                class: classSelect.value,
                section: sectionSelect.value,
                months: getSelectedMonths()
            };
        }

        // Add log entry
        function addLog(type, details = '') {
            const sel = getCurrentSelection();
            if (!sel.class || !sel.section) return;
            const now = new Date();
            const dateTime = now.toLocaleDateString('en-GB') + ' ' + now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            let typeText = type;
            if (type === 'Bulk') typeText = `Bulk (${details} students)`;
            else if (type === 'Single') typeText = `Single: ${details}`;
            else typeText = type;
            printLog.push({
                dateTime,
                class: sel.class,
                section: sel.section,
                months: sel.months.join(', '),
                type: typeText
            });
            renderLog();
        }

        function renderLog() {
            if (printLog.length > 0) {
                logSection.style.display = 'block';
                let html = '';
                printLog.slice().reverse().forEach(entry => {
                    html += `<tr>
                        <td>${entry.dateTime}</td>
                        <td>${entry.class}</td>
                        <td>${entry.section}</td>
                        <td>${entry.months}</td>
                        <td>${entry.type}</td>
                    </tr>`;
                });
                logTableBody.innerHTML = html;
            }
        }

        // Generate student list table
        function renderStudentList(studentsData) {
            currentStudents = studentsData;
            const sel = getCurrentSelection();

            let html = '';
            studentsData.forEach(s => {
                const monthlyTotal = getMonthlyTotal(s);
                const totalDues = (monthlyTotal * sel.months.length) + s.backDues;
                html += `<tr>
                    <td>${s.sid}</td>
                    <td>${s.name}</td>
                    <td>${s.father}</td>
                    <td>${s.roll}</td>
                    <td>₹${monthlyTotal}</td>
                    <td>₹${totalDues}</td>
                    <td class="action-icons">
                        <i class="fas fa-eye view-icon" title="View Slip" data-sid="${s.sid}"></i>
                        <i class="fas fa-print print-icon" title="Print Single" data-sid="${s.sid}"></i>
                    </td>
                </tr>`;
            });
            studentTableBody.innerHTML = html;
            studentListContainer.style.display = 'block';

            // Attach view events
            document.querySelectorAll('.fa-eye').forEach(icon => {
                icon.addEventListener('click', (e) => {
                    const sid = e.target.dataset.sid;
                    const student = currentStudents.find(s => s.sid === sid);
                    showSlipModal(student, sel.months);
                });
            });
            // Attach single print events
            document.querySelectorAll('.fa-print').forEach(icon => {
                icon.addEventListener('click', (e) => {
                    const sid = e.target.dataset.sid;
                    const student = currentStudents.find(s => s.sid === sid);
                    printSingleSlip(student, sel.months);
                    addLog('Single', student.name);
                });
            });
        }

        // Build slip HTML for a student (compact version, no Paid column, WhatsApp inline)
        function buildSlipHTML(student, months) {
            const monthList = months.join(', ');
            const feeTypes = Object.keys(student.monthlyFee);
            let feeRows = '';
            let subtotal = 0;
            feeTypes.forEach(ft => {
                const perMonth = student.monthlyFee[ft];
                const total = perMonth * months.length;
                subtotal += total;
                feeRows += `<tr><td>${ft}</td><td>₹${total}</td><td>₹${total}</td></tr>`;
            });

            const whatsappText = encodeURIComponent(
                `*${school.name}*\n${school.address}\n${school.phones}\n\n` +
                `*Demand Slip*\nMonth(s): ${monthList} | Session: 2025-2026\n\n` +
                `Name: ${student.name}\nFather: ${student.father}\nClass: ${classSelect.value} (${sectionSelect.value})\nRoll No: ${student.roll}\nStd ID: ${student.sid}\n\n` +
                `Fee Details:\n${feeTypes.map(ft => `${ft}: ₹${student.monthlyFee[ft] * months.length}`).join('\n')}\n\n` +
                `Total Dues: ₹${subtotal}`
            );

            return `
                <div class="demand-slip">
                    <div class="slip-header">
                        <h2>${school.name}</h2>
                        <div class="school-info">${school.address}</div>
                        <div class="school-info">${school.phones}</div>
                    </div>
                    <div class="slip-title">Demand Slip</div>
                    <div class="month-session">
                        <span><strong>Month(s):</strong> ${monthList}</span>
                        <span><strong>Session:</strong> 2025-2026</span>
                    </div>
                    <div class="student-details">
                        <div><span>Name:</span> ${student.name}</div>
                        <div><span>F. Name:</span> ${student.father}</div>
                        <div><span>Class:</span> ${classSelect.value} (${sectionSelect.value})</div>
                        <div><span>Address:</span> ${student.address}</div>
                        <div><span>Std ID:</span> ${student.sid}</div>
                        <div><span>Roll No:</span> ${student.roll}</div>
                    </div>
                    <table class="fee-table">
                        <thead><tr><th>Fee Type</th><th>Fee (₹)</th><th>Dues (₹)</th></tr></thead>
                        <tbody>
                            ${feeRows}
                        </tbody>
                    </table>
                    <div class="totals-card">
                        <div class="totals-row">
                            <span class="totals-label">Sub Total</span>
                            <span class="totals-value">₹${subtotal}</span>
                        </div>
                        <div class="totals-row">
                            <span class="totals-label">Back Dues</span>
                            <span class="totals-value">₹${student.backDues}</span>
                        </div>
                        <div class="totals-row">
                            <span class="totals-label">Total Dues</span>
                            <span class="totals-value">₹${subtotal + student.backDues}</span>
                        </div>
                    </div>
                    <div class="footer-info">
                        <span>Date: ${new Date().toLocaleDateString('en-GB')}</span>
                        <span>Email: ${school.email}</span>
                        <span>Website: ${school.website}</span>
                    </div>
                    <div class="signature-row">
                        <span class="signature">Signature</span>
                        <button class="whatsapp-share" onclick="window.open('https://wa.me/?text=${whatsappText}', '_blank')"><i class="fab fa-whatsapp"></i> Share</button>
                    </div>
                </div>
            `;
        }

        // Show modal with slip
        function showSlipModal(student, months) {
            modalSlipContent.innerHTML = buildSlipHTML(student, months);
            slipModal.classList.add('show');
        }

        closeSlipModal.addEventListener('click', () => slipModal.classList.remove('show'));
        window.addEventListener('click', (e) => {
            if (e.target === slipModal) slipModal.classList.remove('show');
        });

        // Print single slip
        function printSingleSlip(student, months) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html><head><title>Demand Slip - ${student.name}</title>
                <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap">
                <style>
                    body { font-family: 'Inter', sans-serif; padding: 20px; background: white; }
                    .demand-slip { max-width: 600px; margin:0 auto; font-size:0.8rem; }
                </style>
                </head><body>${buildSlipHTML(student, months)}</body></html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }

        // Bulk print: 4 slips per page
        function bulkPrint(students, months) {
            if (!students || students.length === 0) return;
            const printWindow = window.open('', '_blank');
            let allSlips = '';
            for (let i = 0; i < students.length; i++) {
                allSlips += buildSlipHTML(students[i], months);
                if ((i + 1) % 4 === 0 && i !== students.length - 1) {
                    allSlips += '<div style="page-break-after: always;"></div>';
                }
            }
            printWindow.document.write(`
                <html><head><title>Bulk Demand Slips</title>
                <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap">
                <style>
                    body { font-family: 'Inter', sans-serif; background: white; margin:0; padding:5px; }
                    .bulk-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 5px;
                    }
                    .demand-slip {
                        border: 1px solid #ccc;
                        padding: 6px;
                        font-size: 0.7rem;
                        break-inside: avoid;
                        page-break-inside: avoid;
                    }
                </style>
                </head>
                <body>
                <div class="bulk-grid">
                    ${allSlips}
                </div>
                </body></html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }

        // Print button (formerly Generate)
        printBtn.addEventListener('click', () => {
            const sel = getCurrentSelection();
            if (!sel.class || !sel.section) {
                alert('Please select class and section.');
                return;
            }
            if (sel.months.length === 0) {
                alert('Select at least one month.');
                return;
            }
            
            // AJAX Fetch LIVE Data from Ledgers using Vanilla JS
            fetch("{{ route('admin.demand-slip.students') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    class: sel.class,
                    section: sel.section
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.length === 0) {
                    alert('No active students found linking this class and section.');
                    return;
                }
                renderStudentList(data);
                addLog('Print List'); // log the action
            })
            .catch(error => {
                console.error('Error fetching students:', error);
                alert('Could not fetch student data from database.');
            });
        });

        // Bulk Print button Event
        bulkPrintBtn.addEventListener('click', () => {
            if (currentStudents.length === 0) {
                alert('Please load students first by selecting filters.');
                return;
            }
            bulkPrint(currentStudents, getCurrentSelection().months);
            addLog('Bulk', currentStudents.length);
        });

        // Reset
        resetBtn.addEventListener('click', () => {
            classSelect.value = '';
            sectionSelect.value = '';
            document.querySelectorAll('.month-checkbox').forEach(cb => cb.checked = false);
            studentListContainer.style.display = 'none';
        });

        // Initial months
        populateMonths();
    })();
</script>
@endpush