@extends('layouts.app')

@section('title', 'Class & Section Manager')

@push('styles')
<style>
<style>
        .manager-card {
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 24px;
            transition: var(--transition);
        }
    </style>
    <style>

        
        
        
        .manager-card {
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 36px;
            box-shadow: var(--shadow);
            padding: 28px 30px;
            transition: var(--transition);
        }
        .manager-card:hover {
            box-shadow: var(--shadow-hover);
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
        /* tabs */
        .tabs {
            display: flex;
            gap: 8px;
            background: #eef3fa;
            padding: 8px;
            border-radius: 60px;
            margin-bottom: 28px;
        }
        .tab {
            flex: 1;
            text-align: center;
            padding: 12px 16px;
            border-radius: 50px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: 0.2s;
        }
        .tab.active {
            background: white;
            color: var(--primary-blue);
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }
        .tab i {
            margin-right: 8px;
            color: var(--primary-orange);
        }
        /* tab panes */
        .pane {
            display: none;
            animation: fade 0.25s ease;
        }
        .pane.active {
            display: block;
        }
        @keyframes fade { 0% { opacity:0.5; } 100% { opacity:1; } }
        /* search/action bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 20px;
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e0e7f0;
            border-radius: 40px;
            padding: 4px 4px 4px 16px;
            flex: 1 1 300px;
        }
        .search-box input {
            border: none;
            background: transparent;
            padding: 8px 0;
            font-size: 0.9rem;
            width: 100%;
            outline: none;
        }
        .search-box button {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .search-box button:hover {
            background: var(--primary-orange);
        }
        .btn {
            background: white;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
            padding: 10px 22px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
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
        /* table */
        .table-wrapper {
            overflow-x: auto;
            margin-bottom: 24px;
            border-radius: 24px;
            background: white;
            box-shadow: var(--shadow);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }
        th {
            background: var(--primary-blue);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 14px 12px;
            text-align: left;
            border: 1px solid #3a6fa8;
        }
        td {
            padding: 14px 12px;
            border: 1px solid #d9e2ec;
            color: var(--text-dark);
            font-size: 0.9rem;
            vertical-align: middle;
        }
        tr:hover td {
            background: #f8fcff;
        }
        .action-icons {
            display: flex;
            gap: 12px;
            color: var(--text-muted);
        }
        .action-icons i {
            cursor: pointer;
            font-size: 1.1rem;
            transition: 0.2s;
        }
        .action-icons i:hover {
            color: var(--primary-orange);
            transform: scale(1.2);
        }
        /* modal */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(3px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 16px;
        }
        .modal-overlay.show {
            display: flex;
        }
        .modal-container {
            background: white;
            border-radius: 28px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.25);
            padding: 24px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-header h3 {
            font-size: 1.5rem;
            color: var(--primary-blue);
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: var(--text-muted);
            line-height: 1;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 6px;
            display: block;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            background: var(--bg-light);
            border: 1px solid #e0e7f0;
            border-radius: 16px;
            padding: 12px 14px;
            font-size: 0.9rem;
            outline: none;
        }
        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255,145,59,0.2);
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }
        /* mobile */
        @media (max-width: 700px) {
            .manager-card {
                padding: 20px 16px;
            }
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                width: 100%;
            }
        }
    
    </style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="manager-card">
    <div class="header-title">
        <i class="fas fa-layer-group"></i>
        <h1>Class & Section Manager</h1>
    </div>
    <div class="header-sub">
        View, add, and manage classes and sections
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab active" data-tab="classes"><i class="fas fa-school"></i> Classes</div>
        <div class="tab" data-tab="sections"><i class="fas fa-columns"></i> Sections</div>
    </div>

    <!-- Classes Pane -->
    <div class="pane active" id="classesPane">
        <div class="action-bar">
            <div class="search-box">
                <input type="text" placeholder="Search classes...">
                <button><i class="fas fa-search"></i> Search</button>
            </div>
            <button class="btn btn-orange" id="addClassBtn"><i class="fas fa-plus-circle"></i> Add New Class</button>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Class Name</th>
                        <th>Numeric Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td style="font-weight:600;">{{ $c->name }}</td>
                        <td>{{ $c->numeric_value }}</td>
                        <td class="action-icons">
                            <button class="act-icon orange" title="Edit" onclick="editClass({{ $c->id }}, '{{ addslashes($c->name) }}', {{ $c->numeric_value }})"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('classes.destroy', $c->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-icon red" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">No Classes Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sections Pane -->
    <div class="pane" id="sectionsPane">
        <div class="action-bar">
            <div class="search-box">
                <input type="text" placeholder="Search sections...">
                <button><i class="fas fa-search"></i> Search</button>
            </div>
            <button class="btn btn-orange" id="addSectionBtn"><i class="fas fa-plus-circle"></i> Add New Section</button>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Class</th>
                        <th>Section Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $hasSections = false; @endphp
                    @foreach($classes as $c)
                        @foreach($c->sections as $s)
                        @php $hasSections = true; @endphp
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ $c->name }}</td>
                            <td style="font-weight:600;">{{ $s->name }}</td>
                            <td>{{ $s->description ?? 'N/A' }}</td>
                            <td class="action-icons">
                                <button class="act-icon orange" title="Edit" onclick="editSection({{ $s->id }}, {{ $c->id }}, '{{ addslashes($s->name) }}', '{{ addslashes($s->description) }}')"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('sections.destroy', $s->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this section?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-icon red" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    @if(!$hasSections)
                        <tr><td colspan="5" class="text-center">No Sections Found</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Add/Edit Class -->
    <div class="modal-overlay" id="classModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="classModalTitle">Add New Class</h3>
                <button class="close-modal" id="closeClassModal">&times;</button>
            </div>
            <form id="classForm" action="{{ route('classes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="classMethod" value="POST">
                <div class="form-group">
                    <label>Class Name *</label>
                    <input type="text" name="name" id="classNameInput" placeholder="e.g., Nursery, I, II" required>
                </div>
                <div class="form-group">
                    <label>Numeric Value (for ordering)</label>
                    <input type="number" name="numeric_value" id="classNumericInput" placeholder="0,1,2...">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-light-custom" id="cancelClassModal">Cancel</button>
                    <button type="submit" class="btn-orange" id="saveClassModal">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Add/Edit Section -->
    <div class="modal-overlay" id="sectionModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="sectionModalTitle">Add New Section</h3>
                <button class="close-modal" id="closeSectionModal">&times;</button>
            </div>
            <form id="sectionForm" action="{{ route('sections.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="sectionMethod" value="POST">
                <div class="form-group">
                    <label>Class *</label>
                    <select name="class_id" id="sectionClassSelect" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $clsOp)
                            <option value="{{ $clsOp->id }}">{{ $clsOp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Section Name *</label>
                    <input type="text" name="name" id="sectionNameInput" placeholder="e.g., A, B, C" required>
                </div>
                <div class="form-group">
                    <label>Description (optional)</label>
                    <input type="text" name="description" id="sectionDescInput" placeholder="Morning/Afternoon etc.">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-light-custom" id="cancelSectionModal">Cancel</button>
                    <button type="submit" class="btn-orange" id="saveSectionModal">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tab switching
    const tabs = document.querySelectorAll('.tab');
    const panes = document.querySelectorAll('.pane');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            panes.forEach(p => p.classList.remove('active'));
            if (tab.dataset.tab === 'classes') {
                document.getElementById('classesPane').classList.add('active');
            } else {
                document.getElementById('sectionsPane').classList.add('active');
            }
        });
    });

    // Class Modal
    const classModal = document.getElementById('classModal');
    const closeClass = () => classModal.classList.remove('show');
    document.getElementById('addClassBtn').addEventListener('click', () => {
        document.getElementById('classModalTitle').innerText = 'Add New Class';
        document.getElementById('classForm').action = "{{ route('classes.store') }}";
        document.getElementById('classMethod').value = 'POST';
        document.getElementById('classNameInput').value = '';
        document.getElementById('classNumericInput').value = '';
        classModal.classList.add('show');
    });
    document.getElementById('closeClassModal').addEventListener('click', closeClass);
    document.getElementById('cancelClassModal').addEventListener('click', closeClass);

    function editClass(id, name, numeric) {
        document.getElementById('classModalTitle').innerText = 'Edit Class';
        document.getElementById('classForm').action = "/admin/classes/" + id;
        document.getElementById('classMethod').value = 'PUT';
        document.getElementById('classNameInput').value = name;
        document.getElementById('classNumericInput').value = numeric;
        classModal.classList.add('show');
    }

    // Section Modal
    const sectionModal = document.getElementById('sectionModal');
    const closeSection = () => sectionModal.classList.remove('show');
    document.getElementById('addSectionBtn').addEventListener('click', () => {
        document.getElementById('sectionModalTitle').innerText = 'Add New Section';
        document.getElementById('sectionForm').action = "{{ route('sections.store') }}";
        document.getElementById('sectionMethod').value = 'POST';
        document.getElementById('sectionClassSelect').value = '';
        document.getElementById('sectionNameInput').value = '';
        document.getElementById('sectionDescInput').value = '';
        sectionModal.classList.add('show');
    });
    document.getElementById('closeSectionModal').addEventListener('click', closeSection);
    document.getElementById('cancelSectionModal').addEventListener('click', closeSection);

    function editSection(id, classId, name, desc) {
        document.getElementById('sectionModalTitle').innerText = 'Edit Section';
        document.getElementById('sectionForm').action = "/admin/sections/" + id;
        document.getElementById('sectionMethod').value = 'PUT';
        document.getElementById('sectionClassSelect').value = classId;
        document.getElementById('sectionNameInput').value = name;
        document.getElementById('sectionDescInput').value = desc;
        sectionModal.classList.add('show');
    }

    // Close modals on outside click
    window.addEventListener('click', (e) => {
        if (e.target === classModal) closeClass();
        if (e.target === sectionModal) closeSection();
    });
</script>
@endsection
