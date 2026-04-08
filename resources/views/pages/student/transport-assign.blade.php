@extends('layouts.app')

@section('title', 'Transport Assign')
@section('page_icon', 'fas fa-user-tag')

@push('styles')
<style>
    .manager-card {
        background: white;
        border-radius: 36px;
        box-shadow: var(--shadow);
        padding: 28px 30px;
        transition: var(--transition);
    }
    .manager-card:hover { box-shadow: var(--shadow-hover); }
    .header-title { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .header-title h1 { font-size: 2rem; font-weight: 700; color: var(--primary-blue); }
    .header-title i { font-size: 2rem; color: var(--primary-orange); }
    .header-sub { color: var(--text-muted); margin-bottom: 24px; margin-left: 10px; }

    .action-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
    .btn {
        background: white; border: 1px solid var(--primary-blue); color: var(--primary-blue);
        padding: 10px 22px; border-radius: 30px; font-weight: 600; font-size: 0.9rem;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.2s;
    }
    .btn:hover { background: var(--primary-blue); color: white; transform: translateY(-2px); box-shadow: var(--shadow-hover); }
    .btn-orange { background: var(--primary-orange); border-color: var(--primary-orange); color: white; }
    .btn-orange:hover { background: white; color: var(--primary-orange); }

    .filter-panel {
        background: #f2f8ff; border-radius: 28px; padding: 20px 24px; margin-bottom: 30px;
        display: flex; flex-wrap: wrap; align-items: flex-end; gap: 16px 20px;
    }
    .filter-group { display: flex; flex-direction: column; min-width: 140px; flex: 1 1 150px; }
    .filter-group label { font-size: 0.7rem; text-transform: uppercase; font-weight: 700; color: var(--primary-blue); margin-bottom: 4px; }
    .filter-group input, .filter-group select {
        background: white; border: 1px solid #e0e7f0; border-radius: 16px; padding: 10px 14px;
        font-size: 0.9rem; color: var(--text-dark); outline: none; width: 100%;
    }
    .filter-group input:focus, .filter-group select:focus { border-color: var(--primary-orange); box-shadow: 0 0 0 3px rgba(255,145,59,0.2); }
    
    .table-wrapper { overflow-x: auto; margin-bottom: 24px; border-radius: 24px; background: white; box-shadow: var(--shadow); }
    table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    th { background: var(--primary-blue); color: white; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; padding: 14px 12px; text-align: left; border: 1px solid #3a6fa8; }
    td { padding: 14px 12px; border: 1px solid #d9e2ec; color: var(--text-dark); font-size: 0.9rem; vertical-align: top; }
    tr:hover td { background: #f8fcff; }
    
    .allotment-detail { font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; }
    .student-detail { font-size: 0.8rem; color: var(--text-muted); }
    .action-links { display: flex; gap: 16px; margin-top: 6px; }
    .action-links span { color: var(--primary-blue); font-weight: 500; cursor: pointer; font-size: 0.8rem; text-decoration: underline; }
    .action-links span:hover { color: var(--primary-orange); }
    
    .status-badge { background: var(--orange-light); color: var(--primary-orange); padding: 4px 12px; border-radius: 30px; font-weight: 600; font-size: 0.75rem; display: inline-block; }
    .status-badge.stop { background: #f8d7da; color: #721c24; }

    /* modal */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.3); backdrop-filter: blur(3px);
        display: none; align-items: center; justify-content: center;
        z-index: 10000; padding: 16px;
    }
    .modal-overlay.show { display: flex; }
    .modal-container { background: white; border-radius: 32px; max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 24px; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-header h3 { font-size: 1.6rem; color: var(--primary-blue); }
    .close-modal { background: none; border: none; font-size: 2rem; cursor: pointer; color: var(--text-muted); }
    .form-group { margin-bottom: 18px; position: relative; }
    .form-group label { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--primary-blue); margin-bottom: 4px; display: block; }
    .required::after { content: " *"; color: #e53e3e; }

    .search-results {
        position: absolute; top: 100%; left: 0; right: 0; background: white;
        border: 1px solid #e0e7f0; border-radius: 0 0 16px 16px; z-index: 100;
        max-height: 200px; overflow-y: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: none;
    }
    .search-item { padding: 10px 14px; cursor: pointer; font-size: 0.9rem; }
    .search-item:hover { background: #f2f8ff; color: var(--primary-blue); }
</style>
@endpush

@section('content')
<div class="manager-card">
    <div class="header-title">
        <i class="fas fa-bus"></i>
        <h1>Student Transport Manager</h1>
    </div>
    <div class="header-sub">
        Manage student transportation services and bus allocations
    </div>

    <div class="action-bar">
        <div></div>
        <button class="btn btn-orange" id="createTransportBtn"><i class="fas fa-plus-circle"></i> Create New Transport</button>
    </div>

    <div class="filter-panel">
        <div class="filter-group">
            <label>Search Text</label>
            <input type="text" id="filterSearch" placeholder="Search...">
        </div>
        <div class="filter-group">
            <label>Class</label>
            <select id="filterClass">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Bus</label>
            <select id="filterBus">
                <option value="">All Buses</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_no }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select id="filterStatus">
                <option value="">All</option>
                <option value="Start">Start</option>
                <option value="Stop">Stop</option>
            </select>
        </div>
        <div class="filter-actions" style="margin-left: auto;">
            <button class="btn" id="searchBtn"><i class="fas fa-search"></i> Search</button>
        </div>
    </div>

    <div class="table-wrapper">
        <table id="transportTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ALLOTMENT NO</th>
                    <th>STUDENT</th>
                    <th>BUS STOP / VEHICLES</th>
                    <th>START - END DATE</th>
                    <th>CHARGE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($transports as $index => $t)
                <tr data-id="{{ $t->id }}" data-full='@json($t)'>
                    <td>{{ $transports->firstItem() + $index }}</td>
                    <td>
                        {{ $t->allotment_no }}
                        <div class="allotment-detail">Date: {{ $t->created_at->format('d-M-Y') }}</div>
                        <div class="action-links">
                            <span class="edit-link">Edit</span>
                            @if($t->status === 'Start')
                                <span class="stop-link">Stop</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        {{ $t->student->user->name ?? 'N/A' }}
                        <div class="student-detail">RegNo: {{ $t->student->admission_no ?? 'N/A' }}</div>
                        <div class="student-detail">Class: {{ $t->student->class->name ?? 'N/A' }} ({{ $t->student->section->name ?? 'N/A' }})</div>
                    </td>
                    <td>
                        {{ $t->busStop->name ?? 'N/A' }}
                        <div class="student-detail">Arr: {{ $t->arrivalVehicle->vehicle_no ?? 'N/A' }}</div>
                        <div class="student-detail">Dep: {{ $t->departureVehicle->vehicle_no ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $t->start_date }} {{ $t->end_date ? ' - '.$t->end_date : '' }}</td>
                    <td>₹{{ number_format($t->monthly_charge, 2) }}</td>
                    <td><span class="status-badge {{ strtolower($t->status) }}">{{ $t->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4">No transport assignments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="pagination-info">Showing {{ $transports->firstItem() ?? 0 }} to {{ $transports->lastItem() ?? 0 }} of {{ $transports->total() }} entries</div>
        {{ $transports->appends(request()->all())->links() }}
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal-overlay" id="transportModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle">Assign Transport</h3>
            <button class="close-modal" id="closeModalBtn">&times;</button>
        </div>
        <form id="transportForm">
            @csrf
            <input type="hidden" name="id" id="transportIdx">
            <div class="form-group">
                <label class="required">Student Search</label>
                <input type="text" id="studentSearchInput" placeholder="Type name or reg no..." autocomplete="off">
                <input type="hidden" name="student_id" id="student_id">
                <div class="search-results" id="searchResults"></div>
            </div>
            <div class="form-group">
                <label class="required">Bus Stop</label>
                <select name="bus_stop_id" id="bus_stop_id" required>
                    <option value="">Select Bus Stop</option>
                    @foreach($busStops as $stop)
                        <option value="{{ $stop->id }}" data-charge="{{ $stop->monthly_charge }}">{{ $stop->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">Arrival Bus</label>
                        <select name="arrival_vehicle_id" id="arrival_vehicle_id" required>
                            <option value="">Select Bus</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">Departure Bus</label>
                        <select name="departure_vehicle_id" id="departure_vehicle_id" required>
                            <option value="">Select Bus</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Monthly Charge</label>
                        <input type="number" name="monthly_charge" id="monthly_charge" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-actions d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn" id="cancelModal">Cancel</button>
                <button type="submit" class="btn btn-orange" id="saveTransport">Save Assignment</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const modal = document.getElementById('transportModal');
        const transportForm = document.getElementById('transportForm');
        const studentSearch = document.getElementById('studentSearchInput');
        const searchResults = document.getElementById('searchResults');
        
        // Open modal
        document.getElementById('createTransportBtn').onclick = () => {
            transportForm.reset();
            document.getElementById('transportIdx').value = '';
            document.getElementById('modalTitle').innerText = 'Assign New Transport';
            modal.classList.add('show');
        };

        // Close modal
        document.getElementById('closeModalBtn').onclick = 
        document.getElementById('cancelModal').onclick = () => modal.classList.remove('show');

        // Student Search Logic
        let searchTimeout;
        studentSearch.oninput = () => {
            clearTimeout(searchTimeout);
            const val = studentSearch.value.trim();
            if (val.length < 2) { searchResults.style.display = 'none'; return; }
            
            searchTimeout = setTimeout(async () => {
                const response = await fetch(`{{ route('admin.students.search') }}?q=${val}`);
                const data = await response.json();
                
                if (data.length) {
                    searchResults.innerHTML = data.map(s => `<div class="search-item" data-id="${s.id}" data-text="${s.text}">${s.text}</div>`).join('');
                    searchResults.style.display = 'block';
                } else {
                    searchResults.innerHTML = '<div class="p-2 text-muted small">No students found</div>';
                    searchResults.style.display = 'block';
                }
            }, 300);
        };

        searchResults.onclick = (e) => {
            if (e.target.classList.contains('search-item')) {
                studentSearch.value = e.target.dataset.text;
                document.getElementById('student_id').value = e.target.dataset.id;
                searchResults.style.display = 'none';
            }
        };

        // Auto-fill charge on stop selection
        document.getElementById('bus_stop_id').onchange = (e) => {
            const opt = e.target.options[e.target.selectedIndex];
            if (opt.dataset.charge) {
                document.getElementById('monthly_charge').value = opt.dataset.charge;
            }
        };

        // Edit functionality
        document.querySelectorAll('.edit-link').forEach(link => {
            link.onclick = () => {
                const row = link.closest('tr');
                const t = JSON.parse(row.dataset.full);
                
                document.getElementById('transportIdx').value = t.id;
                document.getElementById('student_id').value = t.student_id;
                studentSearch.value = `${t.student.user.name} [${t.student.admission_no || 'N/A'}]`;
                document.getElementById('bus_stop_id').value = t.bus_stop_id;
                document.getElementById('arrival_vehicle_id').value = t.arrival_vehicle_id;
                document.getElementById('departure_vehicle_id').value = t.departure_vehicle_id;
                document.getElementById('monthly_charge').value = t.monthly_charge;
                document.getElementById('start_date').value = t.start_date;
                
                document.getElementById('modalTitle').innerText = 'Edit Transport Assignment';
                modal.classList.add('show');
            };
        });

        // Stop functionality
        document.querySelectorAll('.stop-link').forEach(link => {
            link.onclick = async () => {
                const id = link.closest('tr').dataset.id;
                const confirm = await Swal.fire({
                    title: 'Stop Transport?',
                    text: 'This will stop the transport service for this student today.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff913b',
                    confirmButtonText: 'Yes, stop it!'
                });

                if (confirm.isConfirmed) {
                    const response = await fetch(`{{ url('/admin/transport-assign2/stop') }}/${id}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const res = await response.json();
                    if (res.success) {
                        Swal.fire('Stopped', res.message, 'success').then(() => location.reload());
                    }
                }
            };
        });

        // Save functionality
        transportForm.onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData(transportForm);
            
            try {
                const response = await fetch(`{{ route('admin.transport-assign2.store') }}`, {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();
                
                if (res.success) {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message || 'Something went wrong', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Communication failure', 'error');
            }
        };

        // Filter Logic
        document.getElementById('searchBtn').onclick = () => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', document.getElementById('filterSearch').value);
            url.searchParams.set('class_id', document.getElementById('filterClass').value);
            url.searchParams.set('bus_id', document.getElementById('filterBus').value);
            url.searchParams.set('status', document.getElementById('filterStatus').value);
            window.location.href = url.toString();
        };

        // Close search results on click away
        window.onclick = (e) => { if (!e.target.closest('.form-group')) searchResults.style.display = 'none'; };
    })();
</script>
@endpush
