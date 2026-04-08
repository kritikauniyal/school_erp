@extends('layouts.app')

@section('content')

@section('title', 'Admission Manager')
@section('page_icon', 'fas fa-graduation-cap')

@push('styles')
<style>
    /* Premium ERP Variables */
    :root {
        --primary-blue: #488fe4;
        --primary-orange: #ff913b;
        --border-color: #e2e8f0;
        --bg-light: #f8fafc;
        --shadow: 0 4px 15px rgba(0,0,0,0.05);
        --transition: all 0.25s ease;
    }

    .admission-manager-container { width: 100%; padding: 0; }

    /* Modern Filter Utility */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 24px;
        border: 1px solid var(--border-color);
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 16px 20px;
    }

    .filter-group { display: flex; flex-direction: column; flex: 1 1 200px; }
    .filter-group label { font-size: 0.72rem; font-weight: 700; color: var(--primary-blue); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; }
    .filter-group input, .filter-group select { background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 12px; padding: 10px 14px; outline: none; transition: var(--transition); }
    .filter-group input:focus, .filter-group select:focus { border-color: var(--primary-blue); background: white; box-shadow: 0 0 0 4px rgba(72,143,228,0.1); }

    /* Tabs Design */
    .smart-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        padding: 6px;
        background: #f1f5f9;
        border-radius: 14px;
        width: fit-content;
    }
    .status-tab {
        padding: 8px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #64748b;
        text-decoration: none;
        transition: var(--transition);
    }
    .status-tab.active { background: white; color: var(--primary-blue); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .status-tab:hover:not(.active) { background: rgba(255,255,255,0.5); color: var(--primary-blue); }

    /* Table System */
    .table-container {
        background: white;
        border-radius: 20px;
        padding: 10px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        overflow-x: auto;
    }
    table { width: 100%; border-collapse: collapse; min-width: 1100px; }
    th { background: #f8fafc; padding: 14px; text-align: left; font-weight: 700; font-size: 0.75rem; color: #475569; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
    td { padding: 12px 14px; font-size: 0.88rem; color: #1e293b; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:hover td { background-color: #f8fafc; }

    /* Standardized Acts */
    .acts { display: flex; align-items: center; gap: 8px; }
    .act-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #64748b; background: #f1f5f9;
        transition: var(--transition); text-decoration: none; border: none;
    }
    .act-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); background: white; }
    .act-icon.view:hover { color: #3b82f6; }
    .act-icon.edit:hover { color: #f59e0b; }

    /* Action Button for Enrollment */
    .enroll-btn {
        background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
        color: white; border: none; padding: 8px 16px; border-radius: 10px;
        font-weight: 700; font-size: 0.78rem; cursor: pointer; transition: var(--transition);
        box-shadow: 0 4px 10px rgba(59,130,246,0.2);
    }
    .enroll-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(59,130,246,0.3); }

    .status-badge { padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 0.72rem; }
    .bg-pending { background: #fff7ed; color: #9a3412; }
    .bg-enrolled { background: #f0fdf4; color: #15803d; }
</style>
@endpush

<div class="card">
    <div class="card-head">
        <div>
            <h2><i class="fas fa-graduation-cap"></i> Admission Manager</h2>
            <p class="card-sub">Review and finalize student admissions from registrations.</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.student-admission.index') }}" class="btn btn-orange"><i class="fas fa-user-plus"></i> New Admission</a>
        </div>
    </div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8e1e1; color: #a92222; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #eababa;">
        {{ session('error') }}
    </div>
@endif

<div class="admission-manager-container">
    <div class="filter-card">
        <form action="{{ route('admin.admission-manager.index') }}" method="GET" style="display: contents;">
            <div class="filter-group">
                <label>SEARCH TEXT</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Adm No. or Name...">
            </div>
            <div class="filter-group">
                <label>STATUS</label>
                <select name="status">
                    <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                    <option value="Pending Payment" {{ request('status') == 'Pending Payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="Converted to Student" {{ request('status') == 'Converted to Student' ? 'selected' : '' }}>Converted to Student</option>
                </select>
            </div>
            <div class="filter-group" style="flex: 0 0 auto;">
                <button type="submit" class="enroll-btn" style="padding: 12px 30px; font-size: 0.9rem;"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>
    </div>

    <!-- status tabs -->
    <div class="smart-tabs">
        <a href="{{ route('admin.admission-manager.index', ['status' => 'All']) }}" class="status-tab {{ request('status', 'All') == 'All' ? 'active' : '' }}">All Admissions</a>
        <a href="{{ route('admin.admission-manager.index', ['status' => 'Pending Payment']) }}" class="status-tab {{ request('status') == 'Pending Payment' ? 'active' : '' }}">Pending Payment</a>
        <a href="{{ route('admin.admission-manager.index', ['status' => 'Converted to Student']) }}" class="status-tab {{ request('status') == 'Converted to Student' ? 'active' : '' }}">Enrolled Students</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Adm No</th>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Father's Name</th>
                    <th>Class</th>
                    <th>Session</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admissions as $adm)
                    <tr>
                        <td><span style="font-weight: 700; color: var(--primary-blue);">{{ $adm->admission_no }}</span></td>
                        <td>{{ $adm->date ? $adm->date->format('d M Y') : '' }}</td>
                        <td><span style="font-weight: 600;">{{ $adm->registrationStudent ? $adm->registrationStudent->name : 'N/A' }}</span></td>
                        <td>{{ $adm->registration ? $adm->registration->father_name : 'N/A' }}</td>
                        <td><span class="badge badge-blue">{{ $adm->registrationStudent ? $adm->registrationStudent->class : 'N/A' }}</span></td>
                        <td>{{ $adm->session }}</td>
                        <td>
                            @if($adm->status == 'Converted to Student')
                                <span class="status-badge bg-enrolled"><i class="fas fa-check-circle"></i> Enrolled</span>
                            @else
                                <span class="status-badge bg-pending"><i class="fas fa-clock"></i> Pending Payment</span>
                            @endif
                        </td>
                        <td>
                            <div class="acts">
                                @if($adm->status == 'Pending Payment')
                                    <button class="enroll-btn" onclick="openEnrollmentModal({{ $adm->id }}, '{{ $adm->registrationStudent ? $adm->registrationStudent->name : '' }}', '{{ $adm->registrationStudent ? $adm->registrationStudent->class : '' }}')">
                                        <i class="fas fa-user-plus"></i> Enroll
                                    </button>
                                @endif
                                <a href="javascript:void(0)" onclick="viewAdmission({{ $adm->id }})" class="act-icon view"><i class="fas fa-eye" title="View"></i></a>
                                <a href="javascript:void(0)" onclick="editAdmission({{ $adm->id }})" class="act-icon edit"><i class="fas fa-edit" title="Edit"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 60px; color: #94a3b8;">
                            <i class="fas fa-folder-open" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                            No admissions found matching these criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top:20px;">
        {{ $admissions->links('pagination::bootstrap-4') }}
    </div>
    </div>
</div>

<!-- Modal for Viewing Admission Details -->
<div class="modal-overlay" id="viewAdmissionModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: none; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; width: 90%; max-width: 600px; padding: 30px; position: relative;">
        <button onclick="closeViewModal()" style="position: absolute; top: 20px; right: 20px; border: none; background: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        <h3 style="color: var(--primary-blue); margin-bottom: 20px;"><i class="fas fa-info-circle"></i> Admission Details</h3>
        <div id="admissionDetailsContent" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 0.9rem;">
            <!-- Filled by JS -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function openEnrollmentModal(id, studentName, className) {
        let defaultFee = "0";
        
        // Show loading state first
        Swal.fire({
            title: 'Preparing Enrollment...',
            text: `Fetching fee structure for ${className}`,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const currentSession = @json(request('session', '2025-2026'));
            const res = await fetch(`{{ url('admin/admission-fee-structure') }}/${className}/get?session=${currentSession}`);
            const data = await res.json();
            if(data.status === 'success' && data.data) {
                let total = 0;
                data.data.forEach(item => total += parseFloat(item.amount) || 0);
                defaultFee = total.toFixed(2);
            }
        } catch(e) {
            console.error('Fee fetch failed', e);
        }

        Swal.fire({
            title: 'Finalize Enrollment',
            html: `
                <div style="text-align: left; font-size: 0.9rem; color: #64748b; margin-bottom: 15px;">
                    You are about to enroll <strong>${studentName}</strong>. Please confirm the admission fee collected.
                </div>
                <div style="text-align: left;">
                    <label style="font-weight: 700; font-size: 0.75rem; color: #488fe4; text-transform: uppercase;">Admission Fee Collected (₹)</label>
                    <input type="number" id="swal-fee" class="swal2-input" style="margin-top: 8px; width: 100%; box-sizing: border-box;" value="${defaultFee}">
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#ff913b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Confirm & Enroll Student',
            preConfirm: () => {
                const fee = document.getElementById('swal-fee').value;
                if (!fee || isNaN(fee)) {
                    Swal.showValidationMessage('Please enter a valid fee amount');
                }
                return { fee_amount: fee };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form via standard POST or AJAX
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/admission-manager/${id}/convert`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const feeInput = document.createElement('input');
                feeInput.type = 'hidden';
                feeInput.name = 'fee_amount';
                feeInput.value = result.value.fee_amount;
                form.appendChild(feeInput);
                
                document.body.appendChild(form);
                
                Swal.fire({
                    title: 'Enrolling Student...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                form.submit();
            }
        });
    }
    function viewAdmission(id) {
        Swal.fire({ title: 'Loading...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        fetch(`/admin/admission-manager/${id}/show`)
            .then(res => res.json())
            .then(data => {
                Swal.close();
                const content = document.getElementById('admissionDetailsContent');
                content.innerHTML = `
                    <div><strong>Adm No:</strong> ${data.admission_no}</div>
                    <div><strong>Date:</strong> ${new Date(data.date).toLocaleDateString()}</div>
                    <div><strong>Student:</strong> ${data.registration_student ? data.registration_student.name : 'N/A'}</div>
                    <div><strong>Class:</strong> ${data.registration_student ? data.registration_student.class : 'N/A'}</div>
                    <div><strong>Father:</strong> ${data.registration ? data.registration.father_name : 'N/A'}</div>
                    <div><strong>Mobile:</strong> ${data.registration ? data.registration.father_mobile : 'N/A'}</div>
                    <div style="grid-column: span 2;"><strong>Status:</strong> ${data.status}</div>
                `;
                document.getElementById('viewAdmissionModal').style.display = 'flex';
            });
    }

    function closeViewModal() {
        document.getElementById('viewAdmissionModal').style.display = 'none';
    }

    function editAdmission(id) {
        // Typically leads back to registration for edits or shows a similar modal
        Swal.fire('Info', 'To edit admission details, please update the corresponding Registration record.', 'info');
    }
</script>
@endpush
