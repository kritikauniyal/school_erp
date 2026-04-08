@extends('layouts.app')

@section('title', 'Enquiry Manager')

@push('styles')
<style>
    /* Custom styles for Enquiry Manager if needed */
    .view-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .view-details-grid p {
        margin: 0;
        font-size: 0.9rem;
    }
    .view-details-grid strong {
        color: var(--txt3);
        font-size: 0.75rem;
        text-transform: uppercase;
        display: block;
        margin-bottom: 2px;
    }
    .whatsapp-preview {
        background: var(--blue-lt);
        padding: 15px;
        border-radius: var(--r2);
        margin-top: 20px;
        font-size: 0.85rem;
        line-height: 1.5;
        white-space: pre-line;
        border-left: 4px solid #25D366;
    }
    .conditional-fields {
        display: none;
    }
    .conditional-fields.show {
        display: block;
    }
</style>
@endpush

@section('content')

<div class="card">
    <div class="card-head">
        <div>
            <h2><i class="fas fa-clipboard-list"></i> Enquiry Manager</h2>
            <p class="card-sub">Manage all enquiries, track follow-ups, and share via WhatsApp</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-orange" id="addEnquiryBtn">
                <i class="fas fa-plus-circle"></i> Add Enquiry
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('enquiry.index') }}">
        <div class="filter-bar">
            <div class="fg">
                <label>Search</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Name, mobile, email...">
            </div>
            <div class="fg">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Follow-up" {{ request('status') == 'Follow-up' ? 'selected' : '' }}>Follow-up</option>
                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="fg">
                <label>Enquiry For</label>
                <select name="for">
                    <option value="">All Types</option>
                    <option value="admission" {{ request('for') == 'admission' ? 'selected' : '' }}>Admission</option>
                    <option value="other" {{ request('for') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-blue">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('enquiry.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <!-- Table Wrap -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Enq No.</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Date</th>
                    <th>Next Follow-up</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enquiries as $enq)
                <tr>
                    <td><span class="fw-700 text-blue">{{ $enq->enq_no }}</span></td>
                    <td><div class="fw-600">{{ $enq->name }}</div><div class="text-muted" style="font-size: .75rem">{{ $enq->email ?? '' }}</div></td>
                    <td>{{ $enq->mobile }}</td>
                    <td>{{ date('d M Y', strtotime($enq->date)) }}</td>
                    <td>
                        @if($enq->followup_date)
                            <span class="{{ strtotime($enq->followup_date) < time() && $enq->status != 'Closed' ? 'text-red fw-600' : '' }}">
                                {{ date('d M Y', strtotime($enq->followup_date)) }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($enq->for == 'admission')
                            @php
                                $classModel = $globalClasses->firstWhere('id', $enq->class);
                                $className = $classModel ? $classModel->name : $enq->class;
                            @endphp
                            <span class="badge badge-blue">Admission ({{ $className }})</span>
                        @else
                            <span class="badge badge-gray">Other ({{ $enq->other }})</span>
                        @endif
                    </td>
                    <td>
                        @if($enq->status == 'Closed')
                            <span class="badge badge-green">Closed</span>
                        @elseif($enq->status == 'Follow-up')
                            <span class="badge badge-yellow">Follow-up</span>
                        @else
                            <span class="badge badge-blue">Pending</span>
                        @endif
                    </td>
                    <td>
                        <div class="acts">
                            <button class="act-icon blue view-btn" data-id="{{ $enq->id }}" title="View"><i class="fas fa-eye"></i></button>
                            <button class="act-icon orange edit-btn" data-id="{{ $enq->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="act-icon green whatsapp-direct-btn" data-id="{{ $enq->id }}" title="WhatsApp"><i class="fab fa-whatsapp"></i></button>
                            <button class="act-icon blue followup-btn" data-id="{{ $enq->id }}" title="Follow-up"><i class="fas fa-calendar-alt"></i></button>
                            <button class="act-icon red delete-btn" data-id="{{ $enq->id }}" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 40px; color: var(--txt3)">
                        <i class="fas fa-folder-open" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3"></i>
                        No enquiries found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($enquiries->hasPages())
    <div class="pagination-wrap">
        {{ $enquiries->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="enquiryModal">
    <div class="modal" style="max-width: 800px;">
        <div class="modal-head">
            <h3 id="modalTitle"><i class="fas fa-plus-circle"></i> Add Enquiry</h3>
            <button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button>
        </div>
        <form id="enquiryForm" method="POST" action="{{ route('enquiry.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Full Name</label>
                        <input type="text" name="name" id="enqName" required placeholder="Enter student/parent name">
                    </div>
                    <div class="form-group">
                        <label class="required">Mobile Number</label>
                        <input type="text" name="mobile" id="enqMobile" required placeholder="10-digit mobile number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" id="enqEmail" placeholder="example@mail.com">
                    </div>
                    <div class="form-group">
                        <label class="required">Date of Enquiry</label>
                        <input type="date" name="date" id="enqDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Next Follow-up Date</label>
                        <input type="date" name="followup_date" id="enqFollowup">
                    </div>
                    <div class="form-group">
                        <label>Reference / Source</label>
                        <input type="text" name="reference" id="enqReference" placeholder="e.g. Social Media, Friend">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Status</label>
                        <select name="status" id="enqStatus">
                            <option value="Pending">Pending</option>
                            <option value="Follow-up">Follow-up</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">Enquiry For</label>
                        <select name="for" id="enqFor" required>
                            <option value="admission">Admission</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="conditional-fields" id="admissionFields">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Target Class</label>
                            <select name="class" id="enqClass">
                                <option value="">Select Class</option>
                                @foreach($globalClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Number of Children</label>
                            <input type="number" name="no_of_child" id="enqNoOfChild" value="1" min="1">
                        </div>
                    </div>
                </div>

                <div class="conditional-fields" id="otherFields">
                    <div class="form-group">
                        <label>Other Details / Purpose</label>
                        <input type="text" name="other" id="enqOther" placeholder="Specify purpose">
                    </div>
                </div>

                <div class="form-group mb-0" style="margin-top: 15px">
                    <label>Address</label>
                    <textarea name="address" id="enqAddress" rows="2" placeholder="Full address"></textarea>
                </div>
                <div class="form-group mb-0" style="margin-top: 15px">
                    <label>Description / Enquiry Details</label>
                    <textarea name="description" id="enqDescription" rows="2" placeholder="What is the enquiry about?"></textarea>
                </div>
                <div class="form-group mb-0" style="margin-top: 15px">
                    <label>Remarks / Internal Notes</label>
                    <textarea name="remarks" id="enqRemarks" rows="2" placeholder="Any internal notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelModal">Cancel</button>
                <button type="submit" class="btn btn-orange">Save Enquiry</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div class="modal-overlay" id="viewModal">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-head">
            <h3><i class="fas fa-eye text-blue"></i> Enquiry Details</h3>
            <button class="modal-close" id="closeViewBtn"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="viewDetails" class="view-details-grid">
                <!-- Details injected here -->
            </div>
            <div class="whatsapp-preview">
                <div style="font-weight: 700; color: #128C7E; margin-bottom: 5px; font-size: 0.7rem">WHATSAPP MESSAGE PREVIEW</div>
                <div id="whatsappMessage"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" id="cancelView">Close</button>
            <button type="button" class="btn btn-success" id="shareViewWhatsApp"><i class="fab fa-whatsapp"></i> Send on WhatsApp</button>
        </div>
    </div>
</div>

<!-- Followup Modal -->
<div class="modal-overlay" id="followupModal">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-head">
            <h3><i class="fas fa-calendar-alt text-orange"></i> Add Follow-up</h3>
            <button class="modal-close" id="closeFollowupModal"><i class="fas fa-times"></i></button>
        </div>
        <form id="followupForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="required">Next Follow-up Date</label>
                    <input type="date" name="followup_date" id="followupDate" required value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                <div class="form-group" style="margin-top: 15px">
                    <label>Follow-up Remarks</label>
                    <textarea name="remarks" id="followupRemarks" rows="3" placeholder="What was discussed?"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelFollowup">Cancel</button>
                <button type="submit" class="btn btn-orange">Save Follow-up</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function() {
        // Modal Selectors
        const addModal = document.getElementById('enquiryModal');
        const viewModal = document.getElementById('viewModal');
        const followupModal = document.getElementById('followupModal');
        
        // Form Selectors
        const enquiryForm = document.getElementById('enquiryForm');
        const followupForm = document.getElementById('followupForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');
        
        // Button Selectors
        const addBtn = document.getElementById('addEnquiryBtn');
        const enqForSelect = document.getElementById('enqFor');
        const admissionFields = document.getElementById('admissionFields');
        const otherFields = document.getElementById('otherFields');

        // Helpers
        const toggleModals = (modal, force) => {
            if (force === true) modal.classList.add('open');
            else if (force === false) modal.classList.remove('open');
            else modal.classList.toggle('open');
        };

        // Conditional Logic
        const handleEnqForChange = () => {
            if (enqForSelect.value === 'admission') {
                admissionFields.classList.add('show');
                otherFields.classList.remove('show');
            } else {
                admissionFields.classList.remove('show');
                otherFields.classList.add('show');
            }
        };
        enqForSelect.addEventListener('change', handleEnqForChange);

        // Open Add Modal
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                modalTitle.innerHTML = '<i class="fas fa-plus-circle"></i> Add Enquiry';
                enquiryForm.reset();
                enquiryForm.action = "{{ route('enquiry.store') }}";
                formMethod.value = "POST";
                document.getElementById('enqDate').value = new Date().toISOString().split('T')[0];
                handleEnqForChange();
                toggleModals(addModal, true);
            });
        }

        // Close Modals
        document.querySelectorAll('.modal-close, .btn-outline').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const m = e.target.closest('.modal-overlay');
                if (m) toggleModals(m, false);
            });
        });

        // AJAX Submission
        async function handleFormSubmit(form) {
            const formData = new FormData(form);
            const action = form.action;
            const method = formData.get('_method') || form.method;

            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    let errorMsg = data.message || 'Something went wrong';
                    if (data.errors) errorMsg = Object.values(data.errors).flat().join('<br>');
                    Swal.fire({ icon: 'error', title: 'Error', html: errorMsg });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Connection failed.' });
            }
        }

        if (enquiryForm) {
            enquiryForm.addEventListener('submit', (e) => {
                e.preventDefault();
                handleFormSubmit(enquiryForm);
            });
        }

        if (followupForm) {
            followupForm.addEventListener('submit', (e) => {
                e.preventDefault();
                handleFormSubmit(followupForm);
            });
        }

        // Action Buttons (Event Delegation)
        document.addEventListener('click', async (e) => {
            // EDIT
            const editBtn = e.target.closest('.edit-btn');
            if (editBtn) {
                const id = editBtn.dataset.id;
                modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Enquiry';
                enquiryForm.action = "{{ url('admin/enquiry-manager') }}/" + id;
                formMethod.value = "PUT";

                Swal.fire({ title: 'Loading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                try {
                    const res = await fetch(`{{ url('admin/enquiry-manager') }}/${id}/edit`);
                    const data = await res.json();
                    Swal.close();

                    document.getElementById('enqName').value = data.name;
                    document.getElementById('enqMobile').value = data.mobile;
                    document.getElementById('enqEmail').value = data.email || '';
                    document.getElementById('enqAddress').value = data.address || '';
                    document.getElementById('enqDescription').value = data.description || '';
                    document.getElementById('enqDate').value = data.date;
                    document.getElementById('enqFollowup').value = data.followup_date || '';
                    document.getElementById('enqRemarks').value = data.remarks || '';
                    document.getElementById('enqReference').value = data.reference || '';
                    document.getElementById('enqStatus').value = data.status || 'Pending';
                    enqForSelect.value = data.for || 'admission';
                    document.getElementById('enqClass').value = data.class || '';
                    document.getElementById('enqNoOfChild').value = data.no_of_child || 1;
                    document.getElementById('enqOther').value = data.other || '';
                    
                    handleEnqForChange();
                    toggleModals(addModal, true);
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load data.' });
                }
            }

            // VIEW
            const viewBtn = e.target.closest('.view-btn');
            if (viewBtn) {
                const id = viewBtn.dataset.id;
                Swal.fire({ title: 'Loading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                try {
                    const res = await fetch(`{{ url('admin/enquiry-manager') }}/${id}/show`);
                    const data = await res.json();
                    Swal.close();

                    const forDisplay = data.for === 'admission' ? `Admission (Class ${data.class})` : `Other (${data.other})`;
                    document.getElementById('viewDetails').innerHTML = `
                        <p><strong>Enquiry No</strong><span class="text-blue fw-700">${data.enq_no}</span></p>
                        <p><strong>Status</strong><span class="badge badge-blue">${data.status}</span></p>
                        <p><strong>Name</strong>${data.name}</p>
                        <p><strong>Mobile</strong>${data.mobile}</p>
                        <p><strong>Email</strong>${data.email || '-'}</p>
                        <p><strong>Date</strong>${data.date}</p>
                        <p><strong>Purpose</strong>${forDisplay}</p>
                        <p><strong>Reference</strong>${data.reference || '-'}</p>
                    `;

                    const msg = generateWelcomeMsg(data);
                    document.getElementById('whatsappMessage').innerText = msg;
                    document.getElementById('shareViewWhatsApp').onclick = () => sendWhatsApp(data.mobile, msg);
                    
                    toggleModals(viewModal, true);
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load details.' });
                }
            }

            // WHATSAPP DIRECT
            const waBtn = e.target.closest('.whatsapp-direct-btn');
            if (waBtn) {
                const id = waBtn.dataset.id;
                const res = await fetch(`{{ url('admin/enquiry-manager') }}/${id}/show`);
                const data = await res.json();
                sendWhatsApp(data.mobile, generateWelcomeMsg(data));
            }

            // FOLLOW-UP
            const fBtn = e.target.closest('.followup-btn');
            if (fBtn) {
                const id = fBtn.dataset.id;
                followupForm.action = `{{ url('admin/enquiry-manager') }}/${id}/followup`;
                followupForm.reset();
                toggleModals(followupModal, true);
            }

            // DELETE
            const dBtn = e.target.closest('.delete-btn');
            if (dBtn) {
                const id = dBtn.dataset.id;
                Swal.fire({
                    title: 'Delete Enquiry?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--red)',
                    confirmButtonText: 'Yes, delete it'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const res = await fetch(`{{ url('admin/enquiry-manager') }}/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'Deleted', timer: 1000, showConfirmButton: false })
                                .then(() => window.location.reload());
                        }
                    }
                });
            }
        });

        function generateWelcomeMsg(data) {
            let details = data.for === 'admission' ? `for admission to Class ${data.class}` : `regarding ${data.other}`;
            return `Dear ${data.name},\n\nThank you for choosing ATR Academy! 🏫\n\nYour enquiry ${details} has been received. We are excited to assist you.\n\nOur team will get back to you shortly. For any urgent queries, feel free to contact us at +91 9572551365.\n\nRegards,\nHazrat Ali Academy`;
        }

        function sendWhatsApp(mobile, msg) {
            window.open(`https://wa.me/91${mobile}?text=${encodeURIComponent(msg)}`, '_blank');
        }

        // Close by clicking outside
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) toggleModals(e.target, false);
        });
    })();
</script>
@endpush