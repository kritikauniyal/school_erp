@extends('layouts.app')

@section('title', 'Dashboard')
<!-- @section('page_icon', 'fas fa-chart-pie') -->

@push('styles')
<style>
    /* Dashboard Specific Styles */
    .stat-card {
        background: white; border-radius: 28px; padding: 20px 18px; box-shadow: var(--shadow);
        transition: all 0.3s cubic-bezier(0.2,0.9,0.3,1); display: flex; align-items: center; justify-content: space-between;
        border: 1px solid rgba(72,143,228,0.1); animation: fadeInUp 0.5s ease backwards;
    }
    .stat-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: var(--shadow-hover); border-color: var(--primary-orange); }
    .stat-info h3 { font-size: 0.9rem; font-weight: 500; color: var(--text-muted); margin-bottom: 6px; }
    .stat-number { font-size: 1.8rem; font-weight: 800; color: var(--primary-blue); line-height: 1; }
    .stat-icon {
        background: var(--orange-light); width: 50px; height: 50px; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; color: var(--primary-orange); font-size: 1.5rem; transition: 0.3s;
    }
    .stat-card:hover .stat-icon { background: var(--primary-orange); color: white; transform: rotate(5deg) scale(1.1); }

    .quick-actions-full { background: white; border-radius: 28px; padding: 24px; box-shadow: var(--shadow); margin-bottom: 30px; transition: 0.3s; }
    .quick-actions-full h3 { color: var(--primary-blue); margin-bottom: 20px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
    .quick-actions-full h3 i { color: var(--primary-orange); }
    
    .action-grid-8 { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 16px; }
    .action-tile {
        background: var(--blue-light); border-radius: 20px; padding: 16px 6px; text-align: center; text-decoration: none;
        color: var(--text-dark); transition: all 0.3s cubic-bezier(0.2,0.9,0.3,1); border: 1px solid transparent;
        display: flex; flex-direction: column; align-items: center; gap: 8px; animation: fadeInUp 0.4s ease backwards;
    }
    .action-tile i {
        font-size: 1.6rem; color: var(--primary-orange); background: white; width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center; border-radius: 16px; box-shadow: 0 5px 12px rgba(0,0,0,0.02); transition: 0.3s;
    }
    .action-tile span { font-weight: 600; font-size: 0.8rem; }
    .action-tile:hover { background: white; border-color: var(--primary-orange); transform: translateY(-6px) scale(1.02); box-shadow: var(--shadow-hover); }
    .action-tile:hover i { background: var(--primary-orange); color: white; transform: rotate(5deg) scale(1.1); }

    .graph-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .chart-card {
        background: white; border-radius: 28px; padding: 18px; box-shadow: var(--shadow);
        transition: 0.3s; border: 1px solid rgba(72,143,228,0.1); animation: fadeInUp 0.5s ease backwards;
    }
    .chart-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); border-color: var(--primary-blue); }
    .chart-title { font-size: 0.9rem; font-weight: 700; color: var(--primary-blue); margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
    .chart-title i { color: var(--primary-orange); }
    .chart-container { position: relative; height: 160px; width: 100%; }

    .bottom-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .info-card { background: white; border-radius: 24px; padding: 20px; box-shadow: var(--shadow); border-left: 5px solid var(--primary-orange); transition: 0.3s; }
    .info-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }

    .birthday-card { grid-column: span 1; min-height: 250px; }
    .birthday-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
    .birthday-header h4 { font-size: 0.95rem; font-weight: 700; color: var(--primary-blue); display: flex; align-items: center; gap: 6px; }
    .birthday-header h4 i { color: var(--primary-orange); }
    .birthday-tabs { display: flex; gap: 5px; background: var(--bg-light); padding: 4px; border-radius: 30px; }
    .birthday-tab { padding: 4px 10px; font-size: 0.75rem; font-weight: 600; border-radius: 30px; cursor: pointer; transition: 0.2s; color: var(--text-muted); }
    .birthday-tab.active { background: var(--primary-orange); color: white; }
    
    .birthday-item { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px dashed #eee; }
    .birthday-item:last-child { border-bottom: none; }
    .avatar-letter {
        width: 36px; height: 36px; background: var(--primary-blue); color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0;
    }
    .birthday-name { font-weight: 700; font-size: 0.85rem; color: var(--text-dark); }
    .birthday-class { font-size: 0.72rem; color: var(--text-muted); }
    .birthday-day { display: flex; flex-direction: column; align-items: flex-end; font-size: 0.7rem; }
    .day-badge { background: var(--orange-light); color: var(--primary-orange); font-weight: 700; padding: 2px 8px; border-radius: 20px; margin-bottom: 2px; }

    @media (max-width: 1200px) { .graph-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .graph-row { grid-template-columns: 1fr; } .bottom-cards { grid-template-columns: 1fr; } }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
    <!-- STAT CARDS (4) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="animation-delay: 0.1s;">
            <div class="stat-info">
                <h3>{{ date('F') }} Collection</h3>
                <div class="stat-number">₹{{ number_format($stats['revenue_this_month'] ?? 0, 0) }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        </div>
        <div class="stat-card" style="animation-delay: 0.2s;">
            <div class="stat-info">
                <h3>Total Collection</h3>
                <div class="stat-number">₹{{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-coins"></i></div>
        </div>
        <div class="stat-card" style="animation-delay: 0.3s;">
            <div class="stat-info">
                <h3>Total Dues</h3>
                <div class="stat-number">₹{{ number_format($stats['pending_fees'] ?? 0, 0) }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
        <div class="stat-card" style="animation-delay: 0.4s;">
            <div class="stat-info">
                <h3>Total Students</h3>
                <div class="stat-number">{{ number_format($stats['students'] ?? 0) }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="quick-actions-full">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="action-grid-8">
            <a href="{{ route('students.student-details') }}" class="action-tile" style="animation-delay: 0.05s;">
                <i class="fas fa-id-card"></i><span>Student Details</span>
            </a>
            <a href="{{ route('admin.collect-fee.index') }}" class="action-tile" style="animation-delay: 0.1s;">
                <i class="fas fa-hand-holding-usd"></i><span>Collect Fee</span>
            </a>
            <a href="{{ route('admin.demand-slip.index') }}" class="action-tile" style="animation-delay: 0.15s;">
                <i class="fas fa-file-invoice-dollar"></i><span>Demand Slip</span>
            </a>
            <a href="{{ route('registration.index') }}" class="action-tile" style="animation-delay: 0.2s;">
                <i class="fas fa-user-edit"></i><span>Registration</span>
            </a>
            <a href="{{ route('admin.student-admission.index') }}" class="action-tile" style="animation-delay: 0.25s;">
                <i class="fas fa-user-plus"></i><span>Admission</span>
            </a>
            <a href="#" class="action-tile" style="animation-delay: 0.3s;">
                <i class="fas fa-ticket-alt"></i><span>Admit Card</span>
            </a>
            <a href="#" class="action-tile" style="animation-delay: 0.35s;">
                <i class="fas fa-id-badge"></i><span>ID Card</span>
            </a>
            <a href="{{ route('admin.fee-report.index') }}" class="action-tile" style="animation-delay: 0.4s;">
                <i class="fas fa-chart-line"></i><span>Reports</span>
            </a>
        </div>
    </div>

    <!-- SIX GRAPHS -->
    <div class="graph-row">
        <div class="chart-card" style="animation-delay: 0.1s;">
            <div class="chart-title"><i class="fas fa-venus-mars"></i> Gender Distribution</div>
            <div class="chart-container"><canvas id="genderChart"></canvas></div>
        </div>
        <div class="chart-card" style="animation-delay: 0.2s;">
            <div class="chart-title"><i class="fas fa-bus"></i> Transport Summary</div>
            <div class="chart-container"><canvas id="transportChart"></canvas></div>
        </div>
        <div class="chart-card" style="animation-delay: 0.3s;">
            <div class="chart-title"><i class="fas fa-calendar-check"></i> Student Attendance</div>
            <div class="chart-container"><canvas id="attendanceChart"></canvas></div>
        </div>
        <div class="chart-card" style="animation-delay: 0.4s;">
            <div class="chart-title"><i class="fas fa-hand-holding-usd"></i> Fees Snapshot</div>
            <div class="chart-container"><canvas id="feesChart"></canvas></div>
        </div>
        <div class="chart-card" style="animation-delay: 0.5s;">
            <div class="chart-title"><i class="fas fa-user-tie"></i> Staff Attendance</div>
            <div class="chart-container"><canvas id="staffChart"></canvas></div>
        </div>
        <div class="chart-card" style="animation-delay: 0.6s;">
            <div class="chart-title"><i class="fas fa-user-check"></i> New Admissions</div>
            <div class="chart-container"><canvas id="admissionChart"></canvas></div>
        </div>
    </div>

    <!-- BOTTOM WIDGETS -->
    <div class="bottom-cards">
        <div class="info-card">
            <i class="fas fa-user-graduate" style="color: var(--primary-orange); font-size: 1.8rem;"></i>
            <h4 style="margin: 12px 0 6px; font-weight: 700;">New Admissions</h4>
            <p style="color: var(--primary-blue); font-weight: 800; font-size: 1.2rem;">+28 <small style="font-size: 0.7rem; color: var(--text-muted); font-weight: 500;">this month</small></p>
        </div>
        <div class="info-card">
            <i class="fas fa-clock" style="color: var(--primary-orange); font-size: 1.8rem;"></i>
            <h4 style="margin: 12px 0 6px; font-weight: 700;">Due Alerts</h4>
            <p style="color: var(--primary-blue); font-weight: 800; font-size: 1.2rem;">143 <small style="font-size: 0.7rem; color: var(--text-muted); font-weight: 500;">students</small></p>
        </div>
        <div class="info-card birthday-card">
            @php
                $upcoming = $upcomingBirthdays ?? collect();
                $sBirthdays = $upcoming->where('is_student', true);
                $eBirthdays = $upcoming->where('is_student', false);
            @endphp
            <div class="birthday-header">
                <h4><i class="fas fa-cake-candles"></i> Upcoming Birthdays</h4>
                <div class="birthday-tabs">
                    <span class="birthday-tab active" data-tab="students">Students ({{ $sBirthdays->count() }})</span>
                    <span class="birthday-tab" data-tab="employees">Employees ({{ $eBirthdays->count() }})</span>
                </div>
            </div>
            <div id="student-birthday-list" class="birthday-tab-content">
                @forelse($sBirthdays as $b)
                <div class="birthday-item">
                    <div class="avatar-letter">{{ substr($b->student_name, 0, 1) }}</div>
                    <div style="flex: 1;">
                        <div class="birthday-name">{{ $b->student_name }}</div>
                        <div class="birthday-class">{{ $b->class }} &bull; Roll: {{ $b->roll_no ?? 'N/A' }}</div>
                    </div>
                    <div class="birthday-day">
                        <span class="day-badge">{{ $b->dob->day == now()->day ? 'Today' : ($b->dob->day == now()->addDay()->day ? 'Tomorrow' : 'Upcoming') }}</span>
                        <span style="color: var(--text-muted);">{{ $b->dob->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <div style="text-align:center; padding: 40px 10px; color: var(--text-muted); font-size: 0.8rem;">No student birthdays</div>
                @endforelse
            </div>
            <div id="employee-birthday-list" class="birthday-tab-content" style="display:none">
                @forelse($eBirthdays as $b)
                <div class="birthday-item">
                    <div class="avatar-letter" style="background: var(--primary-orange);">{{ substr($b->name, 0, 1) }}</div>
                    <div style="flex: 1;">
                        <div class="birthday-name">{{ $b->name }}</div>
                        <div class="birthday-class">Employee</div>
                    </div>
                    <div class="birthday-day">
                        <span class="day-badge" style="background: var(--blue-light); color: var(--primary-blue);">Upcoming</span>
                        <span style="color: var(--text-muted);">{{ $b->dob->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <div style="text-align:center; padding: 40px 10px; color: var(--text-muted); font-size: 0.8rem;">No employee birthdays</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const blue = '#488fe4', orange = '#ff913b', green = '#22c55e', red = '#f43f5e';
        
        // Shared chart config
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.size = 11;
        Chart.defaults.color = '#5f6b7a';

        const chartOptions = {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f4f8' }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        };

        // 1. Gender Chart
        new Chart(document.getElementById('genderChart'), {
            type: 'pie',
            data: {
                labels: @json($chartData['gender']['labels'] ?? []),
                datasets: [{ 
                    data: @json($chartData['gender']['data'] ?? []), 
                    backgroundColor: [blue, orange, green], borderWidth: 0 
                }]
            },
            options: { ...chartOptions, plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } } } }
        });

        // 2. Transport Chart
        new Chart(document.getElementById('transportChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['transport']['labels'] ?? []),
                datasets: [{ data: @json($chartData['transport']['data'] ?? []), backgroundColor: [blue, orange], borderRadius: 8 }]
            },
            options: chartOptions
        });

        // 3. Attendance Chart
        new Chart(document.getElementById('attendanceChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['student_attendance']['labels'] ?? []),
                datasets: [{ data: @json($chartData['student_attendance']['data'] ?? []), backgroundColor: [blue, orange], borderRadius: 8 }]
            },
            options: chartOptions
        });

        // 4. Fees Snapshot
        new Chart(document.getElementById('feesChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['fees_snapshot']['labels'] ?? []),
                datasets: [{ data: @json($chartData['fees_snapshot']['data'] ?? []), backgroundColor: [blue, orange, green], borderRadius: 8 }]
            },
            options: chartOptions
        });

        // 5. Staff Attendance
        new Chart(document.getElementById('staffChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['staff_attendance']['labels'] ?? []),
                datasets: [{ data: @json($chartData['staff_attendance']['data'] ?? []), backgroundColor: [blue, orange, red], borderRadius: 8 }]
            },
            options: chartOptions
        });

        // 6. Admission Chart
        new Chart(document.getElementById('admissionChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['new_admissions']['labels'] ?? []),
                datasets: [{ data: @json($chartData['new_admissions']['data'] ?? []), backgroundColor: [blue, orange, blue], borderRadius: 8 }]
            },
            options: chartOptions
        });

        // Birthday Tabs logic
        const tabs = document.querySelectorAll('.birthday-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                if(this.dataset.tab === 'students') {
                    document.getElementById('student-birthday-list').style.display = 'block';
                    document.getElementById('employee-birthday-list').style.display = 'none';
                } else {
                    document.getElementById('student-birthday-list').style.display = 'none';
                    document.getElementById('employee-birthday-list').style.display = 'block';
                }
            });
        });
    });
</script>
@endpush

