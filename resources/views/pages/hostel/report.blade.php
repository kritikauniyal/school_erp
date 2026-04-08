@extends('layouts.app')

@section('title', 'Hostel Report')
@section('page_icon', 'fas fa-file-medical-alt')

@section('content')
<div class="reports-card" style="margin-top: 20px;">
    
    <div class="header-sub">Room‑wise details, collection & dues with date filters</div>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab active" data-tab="room"><i class="fas fa-door-open"></i> Room Details</div>
        <div class="tab" data-tab="collection"><i class="fas fa-coins"></i> Collection & Dues</div>
    </div>

    <!-- Filter Panel (shared) -->
    <div class="filter-panel">
        <div class="filter-group date-filter" style="display:none;">
            <label>From Date</label>
            <input type="date" id="fromDate" value="{{ date('Y-m-01') }}">
        </div>
        <div class="filter-group date-filter" style="display:none;">
            <label>To Date</label>
            <input type="date" id="toDate" value="{{ date('Y-m-d') }}">
        </div>
        <div class="filter-group block-filter">
            <label>Block</label>
            <select id="blockFilter">
                <option value="">All</option>
                <option value="Boys">Boys</option>
                <option value="Girls">Girls</option>
            </select>
        </div>
        <div class="filter-group room-filter">
            <label>Room</label>
            <select id="roomFilter">
                <option value="">All</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->room_no }} ({{ $room->hostel->name }})</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn btn-orange" id="generateBtn"><i class="fas fa-sync"></i> Generate</button>
            <button class="btn" id="exportPdfBtn"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn" id="exportExcelBtn"><i class="fas fa-file-excel"></i> Excel</button>
        </div>
    </div>

    <!-- Room Details Pane -->
    <div class="pane active" id="roomPane">
        <div class="table-wrapper">
            <table id="roomTable">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Hostel/Block</th>
                        <th>Student Name</th>
                        <th>Class-Section</th>
                        <th>Mobile</th>
                        <th>Monthly Fee (₹)</th>
                    </tr>
                </thead>
                <tbody id="roomBody">
                    <!-- Dynamic content -->
                </tbody>
            </table>
        </div>
        <div style="display: flex; justify-content: flex-end;">
            <button class="btn" onclick="window.print()"><i class="fas fa-print"></i> Print Report</button>
        </div>
    </div>

    <!-- Collection & Dues Pane -->
    <div class="pane" id="collectionPane">
        <div class="table-wrapper">
            <table id="collectionTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Description</th>
                        <th>Amount (₹)</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody id="collectionBody">
                    <!-- Dynamic content -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .reports-card {
        background: white; border-radius: 36px;
        box-shadow: var(--shadow); padding: 28px 30px; margin-top: 20px;
    }
    .header-title { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .header-title h1 { font-size: 2rem; font-weight: 700; color: var(--primary-blue); }
    .header-title i { font-size: 2rem; color: var(--primary-orange); }
    .header-sub { color: var(--text-muted); margin-bottom: 24px; margin-left: 10px; }

    .tabs { display: flex; gap: 4px; background: #eef3fa; padding: 6px; border-radius: 60px; margin-bottom: 28px; }
    .tab { flex: 1; text-align: center; padding: 10px 20px; border-radius: 50px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: 0.2s; }
    .tab.active { background: white; color: var(--primary-blue); box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    .tab i { margin-right: 6px; color: var(--primary-orange); }

    .pane { display: none; }
    .pane.active { display: block; }

    .filter-panel { background: #f2f8ff; border-radius: 28px; padding: 20px 24px; margin-bottom: 24px; display: flex; flex-wrap: wrap; align-items: flex-end; gap: 16px 20px; }
    .filter-group { display: flex; flex-direction: column; flex: 1 1 160px; }
    .filter-group label { font-size: 0.7rem; text-transform: uppercase; font-weight: 700; color: var(--primary-blue); margin-bottom: 4px; }
    .filter-group select, .filter-group input { background: white; border: 1px solid #e0e7f0; border-radius: 16px; padding: 10px 14px; font-size: 0.9rem; outline: none; }
    
    .table-wrapper { overflow-x: auto; border-radius: 24px; background: white; box-shadow: var(--shadow); margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    th { background: var(--primary-blue); color: white; padding: 14px 12px; text-align: left; border: 1px solid #3a6fa8; font-size: 0.8rem; }
    td { padding: 12px; border: 1px solid #d9e2ec; font-size: 0.85rem; }
    .total-row td { background: #f0f7ff; font-weight: 700; color: var(--primary-blue); }

    @media print {
        .sidebar, .inner-topbar, .tabs, .filter-panel, .btn-orange, .btn i { display: none !important; }
        .reports-card { box-shadow: none; padding: 0; }
        .table-wrapper { box-shadow: none; }
        th { background: #eee !important; color: black !important; border: 1px solid #ccc !important; }
        td { border: 1px solid #ccc !important; }
    }
</style>

@endsection

@push('scripts')
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>

<script>
    $(document).ready(function() {
        let currentTab = 'room';

        $('.tab').click(function() {
            $('.tab').removeClass('active');
            $(this).addClass('active');
            currentTab = $(this).data('tab');
            
            $('.pane').removeClass('active');
            $(`#${currentTab}Pane`).addClass('active');

            if (currentTab === 'collection') {
                $('.date-filter').show();
                $('.block-filter, .room-filter').hide();
            } else {
                $('.date-filter').hide();
                $('.block-filter, .room-filter').show();
            }
            generateReport();
        });

        $('#generateBtn').click(generateReport);

        function generateReport() {
            const data = {
                type: currentTab,
                from_date: $('#fromDate').val(),
                to_date: $('#toDate').val(),
                block: $('#blockFilter').val(),
                room_id: $('#roomFilter').val()
            };

            $.get('{{ route("admin.hostel-report.generate") }}', data, function(response) {
                $(`#${currentTab}Body`).html(response.html);
            });
        }

        $('#exportPdfBtn').click(function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            const title = currentTab === 'room' ? 'Hostel Room Details' : 'Hostel Collection & Dues';
            
            doc.text(title, 14, 16);
            doc.setFontSize(10);
            doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 24);

            doc.autoTable({
                html: currentTab === 'room' ? '#roomTable' : '#collectionTable',
                startY: 30,
                theme: 'grid',
                headStyles: { fillColor: [72, 143, 228] }
            });
            doc.save(`Hostel_Report_${currentTab}.pdf`);
        });

        $('#exportExcelBtn').click(function() {
            const table = document.getElementById(currentTab + 'Table');
            const wb = XLSX.utils.table_to_book(table, { sheet: "Hostel Report" });
            XLSX.writeFile(wb, `Hostel_Report_${currentTab}.xlsx`);
        });

        // Initial generation
        generateReport();
    });
</script>
@endpush
