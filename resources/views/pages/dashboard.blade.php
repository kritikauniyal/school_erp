@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* Dashboard Specific Layout */
    .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    @media (max-width: 1200px) { .dashboard-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .dashboard-grid { grid-template-columns: 1fr; } }

    /* Stat Cards */
    .stat-card {
        background: white; border-radius: 16px; padding: 20px;
        box-shadow: var(--shadow); display: flex; align-items: center; gap: 16px;
        transition: var(--transition); border: 1px solid transparent;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: rgba(72,143,228,0.1); }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center;
        justify-content: center; font-size: 1.3rem; flex-shrink: 0;
    }
    .stat-info { display: flex; flex-direction: column; }
    .stat-label { font-size: 0.65rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .stat-value { font-size: 1.4rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
    .stat-trend { font-size: 0.65rem; margin-top: 6px; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .stat-trend.up { color: #22c55e; }
    .stat-trend.down { color: #ef4444; }

    /* Section Header */
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; margin-top: 8px; }
    .section-header h2 { font-size: 1rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 8px; }
    .section-header h2 i { font-size: 0.9rem; color: var(--primary-orange); }
    .view-all-link { font-size: 0.75rem; font-weight: 600; color: var(--primary-blue); text-decoration: none; }
    .view-all-link:hover { text-decoration: underline; }

    /* Quick Actions */
    .quick-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 16px; margin-bottom: 24px; }
    @media (max-width: 1300px) { .quick-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 768px) { .quick-grid { grid-template-columns: repeat(2, 1fr); } }

    .quick-tile {
        background: white; border-radius: 14px; padding: 18px 10px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        text-decoration: none; transition: var(--transition); box-shadow: var(--shadow);
        border: 1px solid #f1f5f9; text-align: center;
    }
    .quick-tile:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary-blue); }
    .quick-icon-box {
        width: 42px; height: 42px; background: #f0f7ff; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: var(--primary-blue); font-size: 1rem; margin-bottom: 12px; transition: 0.3s;
    }
    .quick-tile:hover .quick-icon-box { background: var(--primary-blue); color: white; }
    .quick-label { font-size: 0.72rem; font-weight: 600; color: var(--text-dark); white-space: nowrap; }

    /* Standard Cards (Charts) */
    .chart-card { background: white; border-radius: 16px; padding: 20px; box-shadow: var(--shadow); border: 1px solid #f8fafc; height: 100%; position: relative; }
    .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .card-title { font-size: 0.88rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--primary-orange); font-size: 0.9rem; width: 14px; }
    .card-badge { font-size: 0.65rem; font-weight: 700; color: var(--primary-blue); background: #f0f7ff; padding: 4px 10px; border-radius: 20px; }

    .charts-row { display: grid; grid-template-columns: 1fr 1.5fr 1.5fr; gap: 20px; margin-bottom: 24px; }
    @media (max-width: 1200px) { .charts-row { grid-template-columns: 1fr; } }

    .charts-row-v2 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    @media (max-width: 1200px) { .charts-row-v2 { grid-template-columns: 1fr; } }

    /* Birthday Widget Tabs */
    .bd-tabs { display: flex; gap: 4px; background: #f1f5f9; padding: 4px; border-radius: 10px; margin-bottom: 16px; }
    .bd-tab {
        flex: 1; padding: 6px; text-align: center; font-size: 0.72rem; font-weight: 700;
        color: var(--text-muted); cursor: pointer; border-radius: 8px; transition: 0.2s;
    }
    .bd-tab.active { background: white; color: var(--text-dark); box-shadow: 0 2px 6px rgba(0,0,0,0.05); }

    .bd-list { display: flex; flex-direction: column; gap: 12px; }
    .bd-item { display: flex; align-items: center; gap: 12px; padding: 8px; border-radius: 12px; transition: 0.2s; }
    .bd-item:hover { background: #f8fafc; }
    .bd-img { width: 36px; height: 36px; border-radius: 50%; background: #e2e8f0; border: 2px solid white; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .bd-content { flex: 1; }
    .bd-name { font-size: 0.78rem; font-weight: 700; color: var(--text-dark); }
    .bd-info { font-size: 0.65rem; color: var(--text-muted); }
    .bd-date { font-size: 0.65rem; font-weight: 700; color: var(--primary-blue); background: #f0f7ff; padding: 4px 8px; border-radius: 6px; }
</style>
@endpush

@section('content')
<!-- Top Stats Row -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--blue-light); color: var(--primary-blue);"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <span class="stat-label">{{ date('F') }} Collection</span>
            <span class="stat-value">₹{{ number_format($stats['revenue_this_month'] ?? 0) }}</span>
            <div class="stat-trend up"><i class="fas fa-caret-up"></i> +12%</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--orange-light); color: var(--primary-orange);"><i class="fas fa-coins"></i></div>
        <div class="stat-info">
            <span class="stat-label">Total Collection</span>
            <span class="stat-value">₹{{ number_format($stats['total_revenue'] ?? 0) }}</span>
            <div class="stat-trend up"><i class="fas fa-caret-up"></i> Session total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef2f2; color: #ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <span class="stat-label">Total Dues</span>
            <span class="stat-value">₹{{ number_format($stats['pending_fees'] ?? 0) }}</span>
            <div class="stat-trend down"><i class="fas fa-caret-down"></i> Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f0fdf4; color: #22c55e;"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-info">
            <span class="stat-label">Total Students</span>
            <span class="stat-value">{{ number_format($stats['students'] ?? 0) }}</span>
            <div class="stat-trend up"><i class="fas fa-caret-up"></i> Live Students</div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="section-header">
    <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
    <a href="#" class="view-all-link">View all <i class="fas fa-chevron-right" style="font-size: 0.6rem;"></i></a>
</div>
<div class="quick-grid">
    <a href="{{ route('students.student-details') }}" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-id-card"></i></div>
        <span class="quick-label">Student Details</span>
    </a>
    <a href="{{ route('admin.collect-fee.index') }}" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-hand-holding-usd"></i></div>
        <span class="quick-label">Collect Fee</span>
    </a>
    <a href="{{ route('admin.demand-slip.index') }}" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-file-invoice"></i></div>
        <span class="quick-label">Demand Slip</span>
    </a>
    <a href="{{ route('registration.index') }}" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-user-edit"></i></div>
        <span class="quick-label">Registration</span>
    </a>
    <a href="{{ route('admin.student-admission.index') }}" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-user-plus"></i></div>
        <span class="quick-label">Admission</span>
    </a>
    <a href="#" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-id-badge"></i></div>
        <span class="quick-label">Admit Card</span>
    </a>
    <a href="#" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-address-card"></i></div>
        <span class="quick-label">ID Card</span>
    </a>
    <a href="{{ route('admin.fee-report.index') }}" class="quick-tile">
        <div class="quick-icon-box"><i class="fas fa-chart-line"></i></div>
        <span class="quick-label">Report Card</span>
    </a>
</div>

<!-- First Charts Row -->
<div class="charts-row">
    <div class="chart-card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-venus-mars"></i> Gender Split</div>
            <span class="card-badge">{{ array_sum($chartData['gender']['data'] ?? [0,0]) }} total</span>
        </div>
        <div style="height: 200px;"><canvas id="genderChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-bus"></i> Transport</div>
            <span class="card-badge">{{ array_sum($chartData['transport']['data'] ?? [0,0]) }}</span>
        </div>
        <div style="height: 200px;"><canvas id="transportChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-calendar-check"></i> Attendance</div>
            <span class="card-badge">Today</span>
        </div>
        <div style="height: 200px;"><canvas id="attendanceChart"></canvas></div>
    </div>
</div>

<!-- Second Charts Row -->
<div class="charts-row-v2">
    <div class="chart-card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-rupee-sign"></i> Fee Snapshot</div>
            <span class="card-badge">This month</span>
        </div>
        <div style="height: 200px;"><canvas id="feeChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-users-cog"></i> Staff Attendance</div>
            <span class="card-badge">Today</span>
        </div>
        <div style="height: 200px;"><canvas id="staffChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-user-check"></i> New Admissions</div>
            <span class="card-badge">Session</span>
        </div>
        <div style="height: 200px;"><canvas id="admissionChart"></canvas></div>
    </div>
</div>

<!-- Bottom Section: Birthdays & Alerts -->
@php
    $uBirthdays = $upcomingBirthdays ?? collect();
    $stuBirthdays = $uBirthdays->where('is_student', true);
    $empBirthdays = $uBirthdays->where('is_student', false);
@endphp
<div class="charts-row-v2">
    <div class="chart-card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-cake-candles"></i> Upcoming Birthdays</div>
        </div>
        <div class="bd-tabs">
            <div class="bd-tab active" onclick="switchBirthdayTab('stu')">Students ({{ $stuBirthdays->count() }})</div>
            <div class="bd-tab" onclick="switchBirthdayTab('emp')">Employees ({{ $empBirthdays->count() }})</div>
        </div>
        <div class="bd-list" id="bd-list-stu">
            @forelse($stuBirthdays as $b)
            <div class="bd-item">
                <div class="bd-img"><i class="fas fa-user" style="opacity: 0.3;"></i></div>
                <div class="bd-content">
                    <div class="bd-name">{{ $b->name ?? $b->student_name }}</div>
                    <div class="bd-info">Class: {{ $b->class }}</div>
                </div>
                <div class="bd-date">{{ $b->dob->format('d M') }}</div>
            </div>
            @empty
            <div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.75rem;">No student birthdays</div>
            @endforelse
        </div>
        <div class="bd-list" id="bd-list-emp" style="display: none;">
            @forelse($empBirthdays as $b)
            <div class="bd-item">
                <div class="bd-img"><i class="fas fa-user-tie" style="opacity: 0.3;"></i></div>
                <div class="bd-content">
                    <div class="bd-name">{{ $b->name }}</div>
                    <div class="bd-info">Department: HR</div>
                </div>
                <div class="bd-date">{{ $b->dob->format('d M') }}</div>
            </div>
            @empty
            <div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.75rem;">No employee birthdays</div>
            @endforelse
        </div>
    </div>
    
    <div class="chart-card" style="grid-column: span 2;">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-bell"></i> Important Alerts</div>
            <span class="card-badge">Latest</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="padding: 12px; border-radius: 12px; background: #fff8f1; border-left: 4px solid var(--primary-orange); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.8rem; font-weight: 700; color: #9a3412;">Fee Demand Generation</div>
                    <div style="font-size: 0.65rem; color: #c2410c;">Demand slips for current Session are pending.</div>
                </div>
                <button style="padding: 6px 12px; border: none; background: var(--primary-orange); color: white; border-radius: 6px; font-size: 0.65rem; font-weight: 700; cursor: pointer;">Action</button>
            </div>
            <div style="padding: 12px; border-radius: 12px; background: #f0fdfa; border-left: 4px solid var(--primary-green); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.8rem; font-weight: 700; color: #134e4a;">Staff Salary Credited</div>
                    <div style="font-size: 0.65rem; color: #0f766e;">Salary for previous month has been successfully processed.</div>
                </div>
                <i class="fas fa-check-circle" style="color: var(--primary-green);"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function switchBirthdayTab(type) {
        document.querySelectorAll('.bd-tab').forEach(t => t.classList.remove('active'));
        if(event) event.target.classList.add('active');
        
        if(type === 'stu') {
            document.getElementById('bd-list-stu').style.display = 'flex';
            document.getElementById('bd-list-emp').style.display = 'none';
        } else {
            document.getElementById('bd-list-stu').style.display = 'none';
            document.getElementById('bd-list-emp').style.display = 'flex';
        }
    }

    // Charting Configuration
    const blue = '#488fe4', orange = '#ff913b', green = '#22c55e', red = '#ef4444';

    // 1. Gender Split Chart
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: @json($chartData['gender']['labels'] ?? []),
            datasets: [{
                data: @json($chartData['gender']['data'] ?? []),
                backgroundColor: [blue, orange, green],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10, weight: '600' } } } },
            maintainAspectRatio: false
        }
    });

    // 2. Transport Chart
    new Chart(document.getElementById('transportChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['transport']['labels'] ?? []),
            datasets: [{
                data: @json($chartData['transport']['data'] ?? []),
                backgroundColor: [blue, orange],
                borderRadius: 8,
                barThickness: 50
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 9 } } }, x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' } } } },
            maintainAspectRatio: false
        }
    });

    // 3. Attendance Chart
    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['student_attendance']['labels'] ?? []),
            datasets: [{
                data: @json($chartData['student_attendance']['data'] ?? []),
                backgroundColor: [blue, orange],
                borderRadius: 8,
                barThickness: 50
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, border: { dash: [5, 5] }, ticks: { font: { size: 9 } } }, x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' } } } },
            maintainAspectRatio: false
        }
    });

    // 4. Fee Snapshot
    new Chart(document.getElementById('feeChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['fees_snapshot']['labels'] ?? []),
            datasets: [{
                data: @json($chartData['fees_snapshot']['data'] ?? []),
                backgroundColor: [blue, orange],
                borderRadius: 8,
                barThickness: 60
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 9 } } }, x: { grid: { display: false }, ticks: { font: { size: 9, weight: '600' } } } },
            maintainAspectRatio: false
        }
    });

    // 5. Staff Attendance
    new Chart(document.getElementById('staffChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['staff_attendance']['labels'] ?? []),
            datasets: [{
                data: @json($chartData['staff_attendance']['data'] ?? []),
                backgroundColor: [blue, orange, red],
                borderRadius: 6,
                barThickness: 35
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 9 } } }, x: { grid: { display: false }, ticks: { font: { size: 9, weight: '600' } } } },
            maintainAspectRatio: false
        }
    });

    // 6. New Admissions
    new Chart(document.getElementById('admissionChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['new_admissions']['labels'] ?? []),
            datasets: [{
                data: @json($chartData['new_admissions']['data'] ?? []),
                backgroundColor: [blue, orange, blue],
                borderRadius: 6,
                barThickness: 40
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, border: { dash: [4, 4] }, ticks: { font: { size: 9 } } }, x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' } } } },
            maintainAspectRatio: false
        }
    });
</script>
@endpush

