@extends('layouts.app')

@section('title', 'Transport Management')
@section('page_icon', 'fas fa-bus')

@push('styles')
<style>
    .transport-card {
        background: white;
        border-radius: 32px;
        box-shadow: var(--shadow);
        padding: 24px 28px;
    }
    .header-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }
    .header-title h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-blue);
    }
    .header-title i {
        font-size: 1.8rem;
        color: var(--primary-orange);
    }
    .header-sub {
        color: var(--text-muted);
        margin-bottom: 24px;
        margin-left: 10px;
        font-size: 0.9rem;
    }
    .transport-section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-blue);
        margin: 24px 0 16px;
        border-left: 5px solid var(--primary-orange);
        padding-left: 14px;
    }

    /* form styles */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
        align-items: flex-end;
    }
    .form-group {
        flex: 1 1 200px;
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 4px;
    }
    .form-group input, .form-group select {
        background: var(--bg-light);
        border: 1px solid #e0e7f0;
        border-radius: 14px;
        padding: 10px 14px;
        font-size: 0.85rem;
        color: var(--text-dark);
        outline: none;
        width: 100%;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
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
        white-space: nowrap;
    }
    .btn i {
        font-size: 1rem;
    }
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

    /* search and pagination */
    .search-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid #e0e7f0;
        border-radius: 40px;
        padding: 4px 4px 4px 16px;
        min-width: 250px;
    }
    .search-box i {
        color: var(--text-muted);
    }
    .search-box input {
        border: none;
        background: transparent;
        padding: 8px 10px;
        font-size: 0.85rem;
        width: 100%;
        outline: none;
    }
    .search-box button {
        background: var(--primary-blue);
        border: none;
        color: white;
        padding: 6px 16px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
    }
    .pagination-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 16px;
        margin-top: 10px;
    }
    .pagination {
        display: flex;
        gap: 6px;
    }
    .page-btn {
        width: 34px;
        height: 34px;
        border-radius: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #e2eaf5;
        color: var(--primary-blue);
        font-weight: 600;
        font-size: 0.8rem;
        transition: 0.2s;
        cursor: pointer;
    }
    .page-btn.active-page {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
    }
    .page-btn:hover:not(.active-page) {
        background: var(--blue-light);
        border-color: var(--primary-blue);
    }
    .page-info {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    /* tables */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 20px;
        background: white;
        box-shadow: var(--shadow);
        margin-bottom: 10px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }
    th {
        background: var(--primary-blue);
        color: white;
        font-weight: 600;
        font-size: 0.7rem;
        padding: 12px 8px;
        border: 1px solid #3a6fa8;
        text-align: left;
    }
    td {
        padding: 10px 8px;
        border: 1px solid #d9e2ec;
        color: var(--text-dark);
        font-size: 0.8rem;
        vertical-align: middle;
    }
    tr:hover td {
        background: #f8fcff;
    }
    .action-icons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .action-icons i {
        cursor: pointer;
        font-size: 1rem;
        transition: 0.2s;
    }
    .action-icons i:hover {
        transform: scale(1.1);
    }
    .edit-icon { color: #f39c12; }
    .delete-icon { color: #e74c3c; }

    /* assignment panel */
    .assign-panel {
        background: #f8fcff;
        border-radius: 24px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .route-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        background: white;
        padding: 16px;
        border-radius: 16px;
        border: 1px solid #e0e7f0;
        margin: 16px 0;
        max-height: 300px;
        overflow-y: auto;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
    }
    .checkbox-item input {
        accent-color: var(--primary-orange);
        width: 16px;
        height: 16px;
    }
    .selected-vehicle-info {
        background: var(--blue-light);
        padding: 8px 16px;
        border-radius: 30px;
        display: inline-block;
        font-size: 0.85rem;
    }
    .assign-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: flex-end;
        align-items: center;
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
<div class="transport-card">
    <div class="header-title">
        <i class="fas fa-bus"></i>
        <h1>Transport Management</h1>
    </div>
    <div class="header-sub">
        Manage routes, vehicles, and assignments
    </div>

    <!-- ========== ROUTES SECTION ========== -->
    <div class="transport-section-title">Routes</div>
    <form id="routeForm" class="form-row">
        @csrf
        <input type="hidden" id="routeId" name="id">
        <div class="form-group">
            <label>Route Name</label>
            <input type="text" id="routeNameInput" name="name" placeholder="e.g., Downtown" required>
        </div>
        <div class="form-group">
            <label>Route Charges (₹)</label>
            <input type="number" id="routeChargesInput" name="monthly_charge" value="0" step="0.01" required>
        </div>
        <div style="display: flex; align-items: flex-end; gap: 10px;">
            <button type="submit" class="btn btn-orange" id="routeSubmitBtn"><i class="fas fa-plus-circle"></i> Add Route</button>
            <button type="button" class="btn" id="routeCancelBtn" style="display:none;">Cancel</button>
        </div>
    </form>

    <div class="search-header">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="routeSearchInput" placeholder="Search routes...">
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Route Name</th>
                    <th>Charges (₹)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="routeTableBody">
                @foreach($routes as $route)
                <tr data-id="{{ $route->id }}" data-name="{{ $route->name }}" data-charge="{{ $route->monthly_charge }}">
                    <td>{{ $route->id }}</td>
                    <td>{{ $route->name }}</td>
                    <td>₹{{ number_format($route->monthly_charge, 2) }}</td>
                    <td class="action-icons">
                        <i class="fas fa-edit edit-icon edit-route" title="Edit Route"></i>
                        <i class="fas fa-trash delete-icon delete-route" title="Delete Route"></i>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ========== VEHICLES SECTION ========== -->
    <div class="transport-section-title">Vehicles</div>
    <form id="vehicleForm" class="form-row">
        @csrf
        <input type="hidden" id="vehicleId" name="id">
        <div class="form-group">
            <label>Vehicle No.</label>
            <input type="text" id="vehicleNoInput" name="vehicle_no" placeholder="e.g., MH12AB1234" required>
        </div>
        <div class="form-group">
            <label>Driver's Name</label>
            <input type="text" id="driverNameInput" name="driver_name" placeholder="Driver name" required>
        </div>
        <div class="form-group">
            <label>Driver's Mobile</label>
            <input type="tel" id="driverMobileInput" name="driver_phone" placeholder="10-digit mobile" required>
        </div>
        <div class="form-group">
            <label>Vehicle Type</label>
            <select id="vehicleTypeInput" name="vehicle_type" required>
                <option value="Bus">Bus</option>
                <option value="Car">Car</option>
                <option value="Magic">Magic</option>
                <option value="Auto">Auto</option>
                <option value="Maximo">Maximo</option>
            </select>
        </div>
        <div style="display: flex; align-items: flex-end; gap: 10px;">
            <button type="submit" class="btn btn-orange" id="vehicleSubmitBtn"><i class="fas fa-plus-circle"></i> Add Vehicle</button>
            <button type="button" class="btn" id="vehicleCancelBtn" style="display:none;">Cancel</button>
        </div>
    </form>

    <div class="search-header">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="vehicleSearchInput" placeholder="Search vehicles...">
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vehicle No.</th>
                    <th>Driver's Name</th>
                    <th>Mobile</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="vehicleTableBody">
                @foreach($vehicles as $vehicle)
                <tr data-id="{{ $vehicle->id }}" data-no="{{ $vehicle->vehicle_no }}" data-driver="{{ $vehicle->driver_name }}" data-phone="{{ $vehicle->driver_phone }}" data-type="{{ $vehicle->vehicle_type }}">
                    <td>{{ $vehicle->id }}</td>
                    <td>{{ $vehicle->vehicle_no }}</td>
                    <td>{{ $vehicle->driver_name }}</td>
                    <td>{{ $vehicle->driver_phone }}</td>
                    <td>{{ $vehicle->vehicle_type }}</td>
                    <td class="action-icons">
                        <i class="fas fa-edit edit-icon edit-vehicle" title="Edit Vehicle"></i>
                        <i class="fas fa-trash delete-icon delete-vehicle" title="Delete Vehicle"></i>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ========== ASSIGN VEHICLE TO ROUTES ========== -->
    <div class="transport-section-title">Assign Vehicle to Routes</div>
    <div class="assign-panel">
        <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center; margin-bottom: 16px;">
            <div class="form-group" style="min-width: 250px;">
                <label>Select Vehicle</label>
                <select id="assignVehicleSelect">
                    <option value="">-- Choose Vehicle --</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" data-routes='@json($vehicle->busStops->pluck("id"))'>{{ $vehicle->vehicle_no }} - {{ $vehicle->driver_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <span class="selected-vehicle-info" id="selectedVehicleInfo">No vehicle selected</span>
            </div>
        </div>

        <div class="search-box" style="margin-bottom: 10px; max-width: 300px;">
            <i class="fas fa-search"></i>
            <input type="text" id="routeFilterInput" placeholder="Filter routes...">
        </div>

        <div id="routeCheckboxContainer" class="route-checkboxes">
            @foreach($routes as $route)
            <label class="checkbox-item route-cb-item">
                <input type="checkbox" class="route-checkbox" value="{{ $route->id }}"> <span class="cb-name">{{ $route->name }}</span> (₹{{ number_format($route->monthly_charge, 2) }})
            </label>
            @endforeach
        </div>

        <div class="assign-actions">
            <button class="btn" id="clearAssignBtn"><i class="fas fa-undo"></i> Clear</button>
            <button class="btn btn-orange" id="assignBtn"><i class="fas fa-link"></i> Assign Routes</button>
        </div>
    </div>

    <!-- ========== CURRENT ASSIGNMENTS ========== -->
    <div class="transport-section-title">Current Assignments</div>
    <div class="search-header">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="assignmentSearchInput" placeholder="Search assignments...">
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Vehicle No.</th>
                    <th>Driver</th>
                    <th>Assigned Routes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="assignmentTableBody">
                @foreach($assignments as $a)
                <tr data-id="{{ $a->id }}">
                    <td>{{ $a->vehicle_no }}</td>
                    <td>{{ $a->driver_name }}</td>
                    <td>{{ $a->busStops->pluck('name')->implode(', ') }}</td>
                    <td class="action-icons">
                        <i class="fas fa-edit edit-icon edit-assignment" data-id="{{ $a->id }}" title="Edit Assignment"></i>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const csrfToken = '{{ csrf_token() }}';

        // --- Route CRUD ---
        const routeForm = document.getElementById('routeForm');
        routeForm.onsubmit = async (e) => {
            e.preventDefault();
            const id = document.getElementById('routeId').value;
            const url = id ? `{{ url('/admin/transport-management/route') }}/${id}` : `{{ route('admin.transport-management.route.store') }}`;
            const method = id ? 'PUT' : 'POST';

            const formData = new FormData(routeForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(data)
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message || 'Error saving route', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Communication failure', 'error');
            }
        };

        document.querySelectorAll('.edit-route').forEach(icon => {
            icon.onclick = () => {
                const tr = icon.closest('tr');
                document.getElementById('routeId').value = tr.dataset.id;
                document.getElementById('routeNameInput').value = tr.dataset.name;
                document.getElementById('routeChargesInput').value = tr.dataset.charge;
                document.getElementById('routeSubmitBtn').innerHTML = '<i class="fas fa-save"></i> Update Route';
                document.getElementById('routeCancelBtn').style.display = 'inline-flex';
                routeForm.scrollIntoView({ behavior: 'smooth' });
            };
        });

        document.getElementById('routeCancelBtn').onclick = () => {
            routeForm.reset();
            document.getElementById('routeId').value = '';
            document.getElementById('routeSubmitBtn').innerHTML = '<i class="fas fa-plus-circle"></i> Add Route';
            document.getElementById('routeCancelBtn').style.display = 'none';
        };

        document.querySelectorAll('.delete-route').forEach(icon => {
            icon.onclick = async () => {
                const id = icon.closest('tr').dataset.id;
                const result = await Swal.fire({
                    title: 'Delete Route?',
                    text: 'This will affect history and current assignments.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                });

                if (result.isConfirmed) {
                    const response = await fetch(`{{ url('/admin/transport-management/route') }}/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                    const res = await response.json();
                    if (res.success) {
                        Swal.fire('Deleted', res.message, 'success').then(() => location.reload());
                    }
                }
            };
        });

        // --- Vehicle CRUD ---
        const vehicleForm = document.getElementById('vehicleForm');
        vehicleForm.onsubmit = async (e) => {
            e.preventDefault();
            const id = document.getElementById('vehicleId').value;
            const url = id ? `{{ url('/admin/transport-management/vehicle') }}/${id}` : `{{ route('admin.transport-management.vehicle.store') }}`;
            const method = id ? 'PUT' : 'POST';

            const formData = new FormData(vehicleForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(data)
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message || 'Error saving vehicle', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Communication failure', 'error');
            }
        };

        document.querySelectorAll('.edit-vehicle').forEach(icon => {
            icon.onclick = () => {
                const tr = icon.closest('tr');
                document.getElementById('vehicleId').value = tr.dataset.id;
                document.getElementById('vehicleNoInput').value = tr.dataset.no;
                document.getElementById('driverNameInput').value = tr.dataset.driver;
                document.getElementById('driverMobileInput').value = tr.dataset.phone;
                document.getElementById('vehicleTypeInput').value = tr.dataset.type;
                document.getElementById('vehicleSubmitBtn').innerHTML = '<i class="fas fa-save"></i> Update Vehicle';
                document.getElementById('vehicleCancelBtn').style.display = 'inline-flex';
                vehicleForm.scrollIntoView({ behavior: 'smooth' });
            };
        });

        document.getElementById('vehicleCancelBtn').onclick = () => {
            vehicleForm.reset();
            document.getElementById('vehicleId').value = '';
            document.getElementById('vehicleSubmitBtn').innerHTML = '<i class="fas fa-plus-circle"></i> Add Vehicle';
            document.getElementById('vehicleCancelBtn').style.display = 'none';
        };

        document.querySelectorAll('.delete-vehicle').forEach(icon => {
            icon.onclick = async () => {
                const id = icon.closest('tr').dataset.id;
                const result = await Swal.fire({
                    title: 'Delete Vehicle?',
                    text: 'This will remove its assignments.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                });

                if (result.isConfirmed) {
                    const response = await fetch(`{{ url('/admin/transport-management/vehicle') }}/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                    const res = await response.json();
                    if (res.success) {
                        Swal.fire('Deleted', res.message, 'success').then(() => location.reload());
                    }
                }
            };
        });

        // --- Assignments ---
        const assignSelect = document.getElementById('assignVehicleSelect');
        const infoSpan = document.getElementById('selectedVehicleInfo');
        const checkboxes = document.querySelectorAll('.route-checkbox');

        const updateCheckboxes = (assignedIds) => {
            checkboxes.forEach(cb => {
                cb.checked = assignedIds.includes(parseInt(cb.value));
            });
        };

        assignSelect.onchange = () => {
            const val = assignSelect.value;
            if (!val) {
                infoSpan.innerText = 'No vehicle selected';
                checkboxes.forEach(cb => cb.checked = false);
                return;
            }
            const opt = assignSelect.options[assignSelect.selectedIndex];
            infoSpan.innerText = `Selected: ${opt.text}`;
            const assignedIds = JSON.parse(opt.dataset.routes || '[]');
            updateCheckboxes(assignedIds);
        };

        document.getElementById('clearAssignBtn').onclick = () => {
            checkboxes.forEach(cb => cb.checked = false);
        };

        document.getElementById('assignBtn').onclick = async () => {
            const vehicleId = assignSelect.value;
            if (!vehicleId) { Swal.fire('Error', 'Please select a vehicle', 'error'); return; }

            const routeIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

            try {
                const response = await fetch(`{{ route('admin.transport-management.assign') }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ vehicle_id: vehicleId, route_ids: routeIds })
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                }
            } catch (err) {
                console.error(err);
            }
        };

        document.querySelectorAll('.edit-assignment').forEach(icon => {
            icon.onclick = () => {
                const vehicleId = icon.dataset.id;
                assignSelect.value = vehicleId;
                assignSelect.dispatchEvent(new Event('change'));
                document.querySelector('.assign-panel').scrollIntoView({ behavior: 'smooth' });
            };
        });

        // --- Filters ---
        document.getElementById('routeSearchInput').oninput = (e) => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('#routeTableBody tr').forEach(tr => {
                tr.style.display = tr.innerText.toLowerCase().includes(val) ? '' : 'none';
            });
        };

        document.getElementById('vehicleSearchInput').oninput = (e) => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('#vehicleTableBody tr').forEach(tr => {
                tr.style.display = tr.innerText.toLowerCase().includes(val) ? '' : 'none';
            });
        };

        document.getElementById('routeFilterInput').oninput = (e) => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('.route-cb-item').forEach(item => {
                const name = item.querySelector('.cb-name').innerText.toLowerCase();
                item.style.display = name.includes(val) ? 'flex' : 'none';
            });
        };

        document.getElementById('assignmentSearchInput').oninput = (e) => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('#assignmentTableBody tr').forEach(tr => {
                tr.style.display = tr.innerText.toLowerCase().includes(val) ? '' : 'none';
            });
        };
    })();
</script>
@endpush
