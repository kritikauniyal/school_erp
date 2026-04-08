@extends('layouts.app')

@section('title', 'Transport Report')
@section('page_icon', 'fas fa-clipboard-list')

@push('styles')
<style>
    .reports-card {
        background: white;
        border-radius: 36px;
        box-shadow: var(--shadow);
        padding: 28px 30px;
        transition: var(--transition);
    }
    .header-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }
    .header-title h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-blue);
    }
    .header-title i {
        font-size: 2rem;
        color: var(--primary-orange);
    }
    .header-sub {
        color: var(--text-muted);
        margin-bottom: 24px;
        margin-left: 10px;
    }
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
    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 140px;
        flex: 1 1 160px;
    }
    .filter-group label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 4px;
    }
    .filter-group select, .filter-group input {
        background: white;
        border: 1px solid #e0e7f0;
        border-radius: 16px;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: var(--text-dark);
        outline: none;
        width: 100%;
    }
    .filter-group input:focus, .filter-group select:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
    }
    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-left: auto;
    }
    .btn {
        background: white;
        border: 1px solid var(--primary-blue);
        color: var(--primary-blue);
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn i { font-size: 1rem; }
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
    .table-wrapper {
        overflow-x: auto;
        border-radius: 24px;
        background: white;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    th {
        background: var(--primary-blue);
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 14px 8px;
        border: 2px solid #3a6fa8;
        text-align: left;
    }
    td {
        padding: 12px 8px;
        border: 2px solid #d9e2ec;
        color: var(--text-dark);
        font-size: 0.85rem;
    }
    tr:hover td {
        background: #f8fcff;
    }
    .total-row td {
        background: #f0f7ff;
        font-weight: 700;
        color: var(--primary-blue);
    }
</style>
@endpush

@section('content')
<div class="reports-card">
    <div class="header-title">
        <i class="fas fa-bus"></i>
        <h1>Transport Report</h1>
    </div>
    <div class="header-sub">Route‑wise, vehicle‑wise, class‑wise & overall transport data</div>

    <div class="filter-panel">
        <div class="filter-group">
            <label>Report Type</label>
            <select id="reportType">
                <option value="route">Route Wise</option>
                <option value="vehicle">Vehicle Wise</option>
                <option value="class">Class Wise</option>
                <option value="overall">Overall</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Session</label>
            <select id="session">
                @foreach($sessions as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group" id="classFilterGroup" style="display:none;">
            <label>Class</label>
            <select id="classFilter">
                <option value="">All Classes</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn btn-orange" id="generateBtn"><i class="fas fa-sync"></i> Generate</button>
            <button class="btn" id="exportPdfBtn"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn" id="exportExcelBtn"><i class="fas fa-file-excel"></i> Excel</button>
            <button class="btn" id="whatsappBtn"><i class="fab fa-whatsapp"></i> Share</button>
        </div>
    </div>

    <div class="table-wrapper">
        <table id="reportTable">
            <thead id="tableHeader"></thead>
            <tbody id="tableBody">
                <tr><td colspan="5" class="text-center py-4 text-muted small">Select criteria and click Generate to view report</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const reportType = document.getElementById('reportType');
    const classFilterGroup = document.getElementById('classFilterGroup');
    const generateBtn = document.getElementById('generateBtn');
    const tableHeader = document.getElementById('tableHeader');
    const tableBody = document.getElementById('tableBody');
    
    reportType.onchange = () => {
        classFilterGroup.style.display = reportType.value === 'class' ? 'block' : 'none';
    };

    generateBtn.onclick = async () => {
        const type = reportType.value;
        const session = document.getElementById('session').value;
        const classId = document.getElementById('classFilter').value;

        generateBtn.disabled = true;
        generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

        try {
            const response = await fetch(`{{ route('admin.transport-report.generate') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type, session, class_id: classId })
            });
            const res = await response.json();
            
            if (res.success) {
                renderReport(type, res.data);
            } else {
                Swal.fire('Error', 'Failed to generate report', 'error');
            }
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Communication failure', 'error');
        } finally {
            generateBtn.disabled = false;
            generateBtn.innerHTML = '<i class="fas fa-sync"></i> Generate';
        }
    };

    function renderReport(type, data) {
        let header = '', body = '';

        if (type === 'route') {
            header = '<tr><th>Route</th><th>Vehicle</th><th>Driver</th><th>Students</th><th>Collection (₹)</th></tr>';
            data.forEach(r => {
                body += `<tr><td>${r.route}</td><td>${r.vehicle}</td><td>${r.driver}</td><td>${r.students}</td><td>₹${parseFloat(r.collection).toFixed(2)}</td></tr>`;
            });
            const totalStudents = data.reduce((s, r) => s + parseInt(r.students), 0);
            const totalCollection = data.reduce((s, r) => s + parseFloat(r.collection), 0);
            body += `<tr class="total-row"><td colspan="3">Total</td><td>${totalStudents}</td><td>₹${totalCollection.toFixed(2)}</td></tr>`;
        } else if (type === 'vehicle') {
            header = '<tr><th>Vehicle</th><th>Driver</th><th>Routes</th><th>Students</th><th>Collection (₹)</th></tr>';
            data.forEach(v => {
                body += `<tr><td>${v.vehicle}</td><td>${v.driver}</td><td>${v.routes}</td><td>${v.students}</td><td>₹${parseFloat(v.collection).toFixed(2)}</td></tr>`;
            });
            const totalStudents = data.reduce((s, v) => s + parseInt(v.students), 0);
            const totalCollection = data.reduce((s, v) => s + parseFloat(v.collection), 0);
            body += `<tr class="total-row"><td colspan="3">Total</td><td>${totalStudents}</td><td>₹${totalCollection.toFixed(2)}</td></tr>`;
        } else if (type === 'class') {
            header = '<tr><th>Class</th><th>Students</th><th>Collection (₹)</th></tr>';
            data.forEach(c => {
                body += `<tr><td>${c.class}</td><td>${c.students}</td><td>₹${parseFloat(c.collection).toFixed(2)}</td></tr>`;
            });
            const totalStudents = data.reduce((s, c) => s + parseInt(c.students), 0);
            const totalCollection = data.reduce((s, c) => s + parseFloat(c.collection), 0);
            body += `<tr class="total-row"><td>Total</td><td>${totalStudents}</td><td>₹${totalCollection.toFixed(2)}</td></tr>`;
        } else { // overall
            header = '<tr><th>Metric</th><th>Value</th></tr>';
            data.forEach(m => {
                body += `<tr><td>${m.metric}</td><td>${m.value}</td></tr>`;
            });
        }

        tableHeader.innerHTML = header;
        tableBody.innerHTML = body;
    }

    // Export PDF
    document.getElementById('exportPdfBtn').onclick = () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text('Transport Report - ' + reportType.options[reportType.selectedIndex].text, 14, 16);
        doc.autoTable({ html: '#reportTable', startY: 25, theme: 'grid', headStyles: { fillStyle: '#488fe4' } });
        doc.save('transport_report.pdf');
    };

    // Export Excel
    document.getElementById('exportExcelBtn').onclick = () => {
        const wb = XLSX.utils.table_to_book(document.getElementById('reportTable'), { sheet: "Transport" });
        XLSX.writeFile(wb, 'transport_report.xlsx');
    };

    // WhatsApp Share
    document.getElementById('whatsappBtn').onclick = () => {
        const rows = document.querySelectorAll('#tableBody tr');
        let msg = '*Transport Report (' + reportType.options[reportType.selectedIndex].text + ')*\n\n';
        rows.forEach(r => {
            const cells = r.querySelectorAll('td');
            if (cells.length) msg += Array.from(cells).map(c => c.innerText).join(' | ') + '\n';
        });
        window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, '_blank');
    };
})();
</script>
@endpush
