@extends('layouts.app')

@section('title', 'Admission Report')
@section('page_number', '10')
@section('page_icon', 'fas fa-user-plus')



@push('styles')
<style>
    .filter-card { margin-bottom: 25px; }
    .report-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px; }
    .total-row { background: var(--blue-lt) !important; font-weight: 800; color: var(--blue); }
    .action-icons { display: flex; gap: 12px; justify-content: center; }
    .action-icons i { cursor: pointer; transition: .2s; font-size: 1rem; }
    .view-icon { color: var(--blue); }
    .print-icon { color: var(--orange); }
    
    @media print {
        .sidebar, .desk-topbar, .filter-card, .page-actions, .topnav { display: none !important; }
        .main-wrap { margin-left: 0 !important; padding: 0 !important; }
        .page-inner { padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endpush

@section('content')

<!-- Filter Panel -->
<div class="card filter-card">
    <div class="card-head">
        <h2><i class="fas fa-filter"></i> Report Filters</h2>
    </div>
    <form action="{{ route('admin.admission-report.index') }}" method="GET">
        <div class="form-row">
            <div class="form-group">
                <label>Academic Session</label>
                <select name="session">
                    <option value="" {{ request('session') == '' ? 'selected' : '' }}>Select Session</option>
                    <option value="2025-2026" {{ request('session', '2025-2026') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                    <option value="2026-2027" {{ request('session') == '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                </select>
            </div>
            <div class="form-group">
                <label>From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="form-group">
                <label>To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="form-group">
                <label>Grade/Class</label>
                <select name="class_name">
                    <option value="">All Classes</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->name }}" {{ request('class_name') == $cls->name ? 'selected' : '' }}>{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="report-actions">
            <button type="submit" class="btn btn-orange"><i class="fas fa-sync"></i> Generate Report</button>
            <button type="button" class="btn" id="exportPdfBtn"><i class="fas fa-file-pdf"></i> Export PDF</button>
            <button type="button" class="btn" id="exportExcelBtn"><i class="fas fa-file-excel"></i> Excel</button>
            <button type="button" class="btn" id="whatsappBtn"><i class="fab fa-whatsapp"></i> WhatsApp Share</button>
        </div>
    </form>
</div>

<!-- Report Results -->
<div class="card">
    <div class="card-head">
        <div>
            <h2><i class="fas fa-file-invoice"></i> Summary by Class</h2>
            <p style="font-size: .82rem; color: var(--txt3); margin-top: 4px;">Grouped statistics for the selected criteria</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-blue" onclick="window.print()"><i class="fas fa-print"></i> Print Results</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table" id="reportTable">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th class="text-center">Total Admissions</th>
                    <th class="text-right">Estimated Fee Collection (₹)</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $totalStudents = 0; $totalFee = 0; @endphp
                @forelse($summaryData as $class => $data)
                    @php 
                        $totalStudents += $data['students']; 
                        $totalFee += $data['totalFee'];
                    @endphp
                    <tr>
                        <td class="fw-700">{{ $class }}</td>
                        <td class="text-center">{{ $data['students'] }}</td>
                        <td class="text-right">₹{{ number_format($data['totalFee'], 2) }}</td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-eye view-icon" title="View Students" onclick="alert('Viewing detail for {{ $class }}')"></i>
                                <i class="fas fa-print print-icon" title="Print Class List" onclick="window.print()"></i>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">No records found for the selected filters.</td>
                    </tr>
                @endforelse
                
                @if(count($summaryData) > 0)
                    <tr class="total-row">
                        <td class="fw-800">GRAND TOTAL</td>
                        <td class="text-center">{{ $totalStudents }}</td>
                        <td class="text-right">₹{{ number_format($totalFee, 2) }}</td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
<!-- Detailed Admission List -->
<div class="card" style="margin-top: 25px;">
    <div class="card-head">
        <div>
            <h2><i class="fas fa-list"></i> Detailed Admission List</h2>
            <p style="font-size: .82rem; color: var(--txt3); margin-top: 4px;">Individual student records for the selected period</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table" id="admissionDetailTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Adm No.</th>
                    <th>Student Name</th>
                    <th>Grade/Class</th>
                    <th>Session</th>
                    <th>Fee (₹)</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admissions as $adm)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($adm->date)->format('d-M-Y') }}</td>
                        <td class="fw-700" style="color: var(--blue);">{{ $adm->admission_no }}</td>
                        <td class="fw-600">{{ $adm->student_name }}</td>
                        <td><span class="badge badge-outline-blue">{{ $adm->class_name }}</span></td>
                        <td>{{ $adm->session }}</td>
                        <td class="fw-700">₹{{ number_format($adm->fee_collected, 2) }}</td>
                        <td class="text-center">
                            @if($adm->status == 'Converted to Student')
                                <span class="badge badge-blue">Enrolled</span>
                            @else
                                <span class="badge badge-orange">{{ $adm->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">No detail records to display.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    document.getElementById('exportPdfBtn').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text('Smart School ERP - Admission Report', 14, 15);
        doc.setFontSize(10);
        doc.text('Session: {{ request('session', '2025-2026') }} | Date Range: {{ request('from_date', date('Y-04-01')) }} to {{ request('to_date', date('Y-03-31')) }}', 14, 22);
        
        doc.autoTable({ 
            html: '#reportTable', 
            startY: 30,
            theme: 'grid',
            headStyles: { fillColor: [61, 132, 245] }
        });
        doc.save('Admission_Report_{{ date('Ymd') }}.pdf');
    });

    document.getElementById('exportExcelBtn').addEventListener('click', function() {
        const wb = XLSX.utils.table_to_book(document.getElementById('reportTable'), { sheet: "Admissions" });
        XLSX.writeFile(wb, 'Admission_Report_{{ date('Ymd') }}.xlsx');
    });

    document.getElementById('whatsappBtn').addEventListener('click', function() {
        let msg = '*Admission Report ({{ date('d M Y') }})*\n\n';
        msg += 'Session: {{ request('session', '2025-2026') }}\n';
        msg += '---------------------------\n';
        
        const rows = document.querySelectorAll('#reportTable tbody tr');
        rows.forEach(r => {
            const cells = r.querySelectorAll('td');
            if (cells.length > 2) {
                msg += `${cells[0].innerText}: ${cells[1].innerText} Adm | ${cells[2].innerText}\n`;
            }
        });
        
        window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, '_blank');
    });
</script>
@endpush
