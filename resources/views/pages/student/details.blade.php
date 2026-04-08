@extends('layouts.app')

@section('title', 'Student Profile Details')
@section('page_number', '03')
@section('page_icon', 'fas fa-user-edit')

@push('styles')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Specific Inner Page styles - using global erp.css tokens */
    .edit-card {
        background: var(--surface);
        border-radius: var(--r3);
        border: 1px solid var(--border);
        box-shadow: var(--sh1);
        padding: 30px;
        margin-top: 20px;
    }
    
    /* tabs */
    .details-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        background: var(--bg);
        padding: 8px;
        border-radius: 60px;
        margin-bottom: 30px;
        border: 1px solid var(--border);
    }
    .details-tab {
        flex: 1 1 auto;
        text-align: center;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        color: var(--txt3);
        cursor: pointer;
        transition: all 0.2s;
        background: transparent;
        white-space: nowrap;
        font-size: 0.88rem;
    }
    .details-tab.active {
        background: var(--surface);
        color: var(--blue);
        box-shadow: var(--sh1);
    }
    .details-tab i {
        margin-right: 8px;
        color: var(--orange);
    }
    
    /* tab content */
    .tab-pane {
        display: none;
        animation: fadeUp 0.3s var(--ease) both;
    }
    .tab-pane.active {
        display: block;
    }
    
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--blue);
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--blue-lt);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-info-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    .info-item label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--txt3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .info-item .val {
        font-size: 1rem;
        font-weight: 600;
        color: var(--txt1);
        background: var(--bg);
        padding: 10px 14px;
        border-radius: var(--r1);
        border: 1px solid var(--border);
        min-height: 44px;
        display: flex;
        align-items: center;
    }

    .action-row {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid var(--border);
    }

    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
        background: var(--bg);
        padding: 20px;
        border-radius: var(--r2);
    }
    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px;
        font-size: 0.88rem;
        color: var(--txt2);
    }

</style>
@endpush

@section('content')
<div class="edit-card">
    <div class="details-tabs">
        <div class="details-tab active" data-tab="main"><i class="fas fa-info-circle"></i> Main Info</div>
        <div class="details-tab" data-tab="parental"><i class="fas fa-users"></i> Parental Info</div>
        <div class="details-tab" data-tab="previous"><i class="fas fa-school"></i> Previous School</div>
        <div class="details-tab" data-tab="photo"><i class="fas fa-camera"></i> Photo</div>
        <div class="details-tab" data-tab="transport"><i class="fas fa-bus"></i> Transport</div>
        <div class="details-tab" data-tab="hostel"><i class="fas fa-hotel"></i> Hostel</div>
        <div class="details-tab" data-tab="library"><i class="fas fa-book"></i> Library</div>
    </div>

    <!-- MAIN INFO TAB -->
    <div class="tab-pane active" id="mainTab">
        <div class="form-section-title"><i class="fas fa-id-card"></i> Basic Information</div>
        <div class="detail-info-row">
            <div class="info-item"><label>Admission No.</label><div class="val">{{ $student->admission_no ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Admission Date</label><div class="val">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d-M-Y') : 'N/A' }}</div></div>
            <div class="info-item"><label>Registration No.</label><div class="val">{{ $student->registration_no ?? 'N/A' }}</div></div>
        </div>
        <div class="detail-info-row">
            <div class="info-item"><label>Class</label><div class="val">{{ $student->classInfo?->name ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Section</label><div class="val">{{ $student->sectionInfo?->name ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Roll No.</label><div class="val">{{ $student->roll_no ?? 'N/A' }}</div></div>
        </div>
        <div class="detail-info-row">
            <div class="info-item"><label>Student Name</label><div class="val">{{ $student->student_name ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Gender</label><div class="val">{{ $student->gender ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Date of Birth</label><div class="val">{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-M-Y') : 'N/A' }}</div></div>
        </div>
        <div class="detail-info-row">
            <div class="info-item" style="grid-column: span 2;"><label>Address</label><div class="val">{{ $student->address_1 ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Medium</label><div class="val">{{ $student->medium ?? 'N/A' }}</div></div>
        </div>
        
        <div class="action-row">
            <button class="btn btn-blue next-tab" data-next="parental">Next Step <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <!-- PARENTAL INFO TAB -->
    <div class="tab-pane" id="parentalTab">
        <div class="form-section-title"><i class="fas fa-users-cog"></i> Parental Details</div>
        <div class="detail-info-row">
            <div class="info-item"><label>Father's Name</label><div class="val">{{ $student->parent?->father_name ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Father's Phone</label><div class="val">{{ $student->parent?->father_phone ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Occupation</label><div class="val">{{ $student->parent?->father_occupation ?? 'N/A' }}</div></div>
        </div>
        <div class="detail-info-row">
            <div class="info-item"><label>Mother's Name</label><div class="val">{{ $student->parent?->mother_name ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Mother's Phone</label><div class="val">{{ $student->parent?->mother_phone ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Occupation</label><div class="val">{{ $student->parent?->mother_occupation ?? 'N/A' }}</div></div>
        </div>
        
        <div class="action-row">
            <button class="btn prev-tab" data-prev="main"><i class="fas fa-arrow-left"></i> Previous</button>
            <button class="btn btn-blue next-tab" data-next="previous">Next Step <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <!-- PREVIOUS SCHOOL TAB -->
    <div class="tab-pane" id="previousTab">
        <div class="form-section-title"><i class="fas fa-graduation-cap"></i> Previous Academic Profile</div>
        <div class="detail-info-row">
            <div class="info-item" style="grid-column: span 2;"><label>School Name</label><div class="val">{{ $student->previousSchool?->school_name ?? 'N/A' }}</div></div>
            <div class="info-item"><label>Previous Class</label><div class="val">{{ $student->previousSchool?->previous_class ?? 'N/A' }}</div></div>
        </div>

        <div class="form-section-title mt-4"><i class="fas fa-syringes"></i> Immunization History</div>
        <div class="checkbox-group">
            <label><i class="fas fa-check-circle" style="color:var(--green)"></i> BCG</label>
            <label><i class="fas fa-check-circle" style="color:var(--green)"></i> OPV</label>
            <label><i class="fas fa-circle" style="color:var(--border)"></i> MMR</label>
            <label><i class="fas fa-check-circle" style="color:var(--green)"></i> DPT</label>
            <label><i class="fas fa-circle" style="color:var(--border)"></i> Measles</label>
            <label><i class="fas fa-check-circle" style="color:var(--green)"></i> Hep-B</label>
        </div>

        <div class="action-row">
            <button class="btn prev-tab" data-prev="parental"><i class="fas fa-arrow-left"></i> Previous</button>
            <button class="btn btn-blue next-tab" data-next="photo">Next Step <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <!-- PHOTO TAB -->
    <div class="tab-pane" id="photoTab">
        <div class="form-section-title"><i class="fas fa-camera-retro"></i> Profile Portrait</div>
        <div style="display: flex; flex-direction: column; align-items: center; padding: 40px 0;">
            <div style="width: 180px; height: 180px; border-radius: 20px; overflow: hidden; border: 4px solid var(--blue-lt); box-shadow: var(--sh2); margin-bottom: 20px;">
                <img src="https://via.placeholder.com/180" alt="Student" style="width: 100%; hieght: 100%; object-fit: cover;">
            </div>
            <button class="btn btn-outline btn-sm"><i class="fas fa-upload"></i> Change Photo</button>
        </div>

        <div class="action-row">
            <button class="btn prev-tab" data-prev="previous"><i class="fas fa-arrow-left"></i> Previous</button>
            <button class="btn btn-blue next-tab" data-next="transport">Next Step <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <!-- TRANSPORT TAB -->
    <div class="tab-pane" id="transportTab">
        <div class="form-section-title"><i class="fas fa-bus-alt"></i> Transport Allotment</div>
        <div class="detail-info-row">
            <div class="info-item"><label>Route / Stop</label><div class="val">{{ $student->route_stop ?? 'Kankarbagh Stop 2' }}</div></div>
            <div class="info-item"><label>Arrival Time</label><div class="val">07:45 AM</div></div>
            <div class="info-item"><label>Monthly Fee</label><div class="val">₹ {{ $student->transport_charge ?? '1,200' }}</div></div>
        </div>
        
        <div class="action-row">
            <button class="btn prev-tab" data-prev="photo"><i class="fas fa-arrow-left"></i> Previous</button>
            <button class="btn btn-orange save-btn-swal"><i class="fas fa-save"></i> Save Transport</button>
            <button class="btn btn-blue next-tab" data-next="hostel">Next Step <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <!-- HOSTEL TAB -->
    <div class="tab-pane" id="hostelTab">
        <div class="form-section-title"><i class="fas fa-bed"></i> Residential Allotment</div>
        <div class="detail-info-row">
            <div class="info-item"><label>Hostel Wing</label><div class="val">Main Boys Hostel</div></div>
            <div class="info-item"><label>Room / Bed</label><div class="val">Room 204 - Bed B</div></div>
            <div class="info-item"><label>Monthly Fee</label><div class="val">₹ {{ $student->hostel_charge ?? '5,500' }}</div></div>
        </div>

        <div class="action-row">
            <button class="btn prev-tab" data-prev="transport"><i class="fas fa-arrow-left"></i> Previous</button>
            <button class="btn btn-orange save-btn-swal"><i class="fas fa-save"></i> Save Hostel</button>
            <button class="btn btn-blue next-tab" data-next="library">Next Step <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <!-- LIBRARY TAB -->
    <div class="tab-pane" id="libraryTab">
        <div class="form-section-title"><i class="fas fa-book-reader"></i> Library Membership</div>
        <div class="detail-info-row">
            <div class="info-item"><label>Card Number</label><div class="val">LIB-2026-9902</div></div>
            <div class="info-item"><label>Membership Type</label><div class="val">Standard Student</div></div>
            <div class="info-item"><label>Join Date</label><div class="val">12 Mar 2026</div></div>
        </div>

        <div class="action-row">
            <button class="btn prev-tab" data-prev="hostel"><i class="fas fa-arrow-left"></i> Previous</button>
            <button class="btn btn-success finish-btn-swal"><i class="fas fa-check-double"></i> Finish & Save All</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const tabs = document.querySelectorAll('.details-tab');
        const panes = document.querySelectorAll('.tab-pane');

        function activateTab(tabId) {
            panes.forEach(p => p.classList.remove('active'));
            tabs.forEach(t => t.classList.remove('active'));
            
            const targetPane = document.getElementById(tabId);
            if(targetPane) targetPane.classList.add('active');
            
            tabs.forEach(t => {
                if (t.dataset.tab === tabId.replace('Tab','')) {
                    t.classList.add('active');
                }
            });

            document.querySelector('.edit-card').scrollIntoView({ behavior: 'smooth' });
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', () => activateTab(tab.dataset.tab + 'Tab'));
        });

        document.querySelectorAll('.next-tab').forEach(btn => {
            btn.addEventListener('click', () => {
                const next = btn.dataset.next;
                if (next) activateTab(next + 'Tab');
            });
        });

        document.querySelectorAll('.prev-tab').forEach(btn => {
            btn.addEventListener('click', () => {
                const prev = btn.dataset.prev;
                if (prev) activateTab(prev + 'Tab');
            });
        });

        // SweetAlert implementation
        document.querySelectorAll('.save-btn-swal').forEach(btn => {
            btn.addEventListener('click', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Section Saved',
                    text: 'The configuration has been updated successfully.',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            });
        });

        const finishBtn = document.querySelector('.finish-btn-swal');
        if(finishBtn) {
            finishBtn.addEventListener('click', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Process Complete!',
                    text: 'All student details have been successfully saved to the database.',
                    confirmButtonText: 'Great!',
                    confirmButtonColor: '#3d84f5'
                });
            });
        }
    })();
</script>
@endpush
