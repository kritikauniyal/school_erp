<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School ERP') }}</title>

    <!-- Bootstrap 5 & App styles (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-blue: #488fe4;
            --primary-orange: #ff913b;
            --blue-light: #e3f0ff;
            --orange-light: #ffefdb;
            --bg-light: #f5f9ff;
            --text-dark: #1e293b;
            --text-muted: #5f6b7a;
            --sidebar-bg: #435471;
            --sidebar-text: #ffffff;
            --sidebar-hover: rgba(255,255,255,0.15);
            --shadow: 0 8px 20px rgba(0,20,50,0.05);
            --shadow-hover: 0 15px 30px rgba(72,143,228,0.15);
            --transition: all 0.2s ease;
        }

        /* ---------- GRADIENT SCROLLBAR ---------- */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f0f4ff;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(145deg, var(--primary-blue), var(--primary-orange));
            border-radius: 10px;
            border: 2px solid #ffffff;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(145deg, var(--primary-orange), var(--primary-blue));
        }
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-orange) #f0f4ff;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            animation: fadeIn 0.6s ease;
            overflow-x: hidden;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        #app {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ---------- SIDEBAR (new background #435471, white text, compact) ---------- */
        .sidebar {
            width: 300px;
            background: var(--sidebar-bg);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            border-right: 1px solid rgba(255,255,255,0.15);
            transition: transform 0.4s cubic-bezier(0.2, 0.9, 0.3, 1), box-shadow 0.3s;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            flex-shrink: 0;
            font-size: 1rem;
            animation: slideInLeft 0.5s ease;
            color: var(--sidebar-text);
        }

        @keyframes slideInLeft {
            0% { transform: translateX(-20px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }

        .sidebar:hover {
            box-shadow: 8px 0 25px rgba(0,0,0,0.2);
        }

        .sidebar.collapsed {
            width: 0;
            transform: translateX(-100%);
            position: absolute;
        }

        .sidebar-header {
            padding: 24px 20px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 2px solid var(--primary-orange);
            background: rgba(0,0,0,0.1);
            backdrop-filter: blur(2px);
        }

        .sidebar-header img {
            max-width: 180px;
            max-height: 60px;
            object-fit: contain;
        }

        .close-sidebar {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            display: none;
            align-self: flex-end;
            margin-left: auto;
        }

        /* menu sections with dotted separators and reduced spacing */
        .menu-section {
            margin: 4px 0;
            animation: fadeInUp 0.4s ease backwards;
            animation-delay: calc(0.05s * var(--i, 0));
            position: relative;
        }

        /* dotted line above each section except the first */
        .menu-section:not(:first-of-type) {
            border-top: 1px dotted rgba(255,255,255,0.3);
            margin-top: 6px;
            padding-top: 6px;
        }

        .section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 20px 8px 24px;
            color: white;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
            transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
            border-radius: 0 30px 30px 0;
            margin-right: 8px;
        }

        .section-title:hover {
            background: var(--sidebar-hover);
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .section-title i {
            color: white;
            font-size: 1.1rem;
        }

        .section-title .chevron {
            margin-left: auto;
            font-size: 0.8rem;
            color: white;
            transition: transform 0.3s;
        }

        .section-title.open .chevron {
            transform: rotate(90deg);
        }

        .submenu-items {
            display: none;
            margin-left: 8px;
            padding: 4px 0;
        }

        .submenu-items.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 20px 8px 24px;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.25s cubic-bezier(0.2,0.9,0.3,1);
            border-left: 3px solid transparent;
            margin: 2px 8px 2px 0;
            border-radius: 0 30px 30px 0;
        }

        .menu-item i {
            width: 22px;
            font-size: 1.1rem;
            text-align: center;
            color: white;
            transition: transform 0.2s, color 0.2s;
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--primary-orange);
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            color: white;
        }

        .menu-item:hover i {
            color: var(--primary-orange);
            transform: scale(1.15) rotate(2deg);
        }

        /* ---- Sub Items with icon pill ---- */
        .sub-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 16px;
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.22s ease;
            border-left: 2px solid transparent;
            margin: 2px 8px 2px 6px;
            border-radius: 0 20px 20px 0;
            letter-spacing: 0.15px;
        }

        .sub-icon-wrap {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.22s ease;
        }

        .sub-icon-wrap i {
            font-size: 0.68rem;
            color: rgba(255,255,255,0.8);
            width: auto !important;
        }

        .sub-item:hover {
            background: rgba(255,255,255,0.11);
            border-left-color: var(--primary-orange);
            transform: translateX(5px);
            color: white;
        }

        .sub-item:hover .sub-icon-wrap {
            background: var(--primary-orange);
            box-shadow: 0 3px 8px rgba(255,145,59,0.4);
        }

        .sub-item:hover .sub-icon-wrap i {
            color: white;
            transform: scale(1.1);
        }

        .active-menu, .active-page {
            background: var(--sidebar-hover);
            border-left: 3px solid var(--primary-orange) !important;
            font-weight: 600;
            color: white !important;
        }

        .active-menu i, .active-page i {
            color: var(--primary-orange);
        }

        .sub-item {
            padding-left: 54px;
            font-size: 0.9rem;
        }

        .sub-item i {
            font-size: 0.6rem;
            width: 16px;
        }

        .standalone-item .menu-item {
            margin-top: 2px;
        }

        /* ---------- MAIN CONTENT ---------- */
        .main-content {
            flex: 1;
            transition: margin-left 0.4s cubic-bezier(0.2, 0.9, 0.3, 1);
            width: calc(100% - 300px);
            max-width: 100%;
            animation: fadeInRight 0.6s ease;
        }

        @keyframes fadeInRight {
            0% { opacity: 0; transform: translateX(20px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        .menu-toggle {
            background: white;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
            width: 44px;
            height: 44px;
            border-radius: 14px;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            box-shadow: var(--shadow);
        }

        .menu-toggle:hover {
            background: var(--primary-blue);
            color: white;
            transform: rotate(90deg) scale(1.1);
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .sidebar.show-mobile { transform: translateX(0); }
            .close-sidebar { display: block !important; position: absolute; right: 15px; top: 20px; }
            .main-content { margin-left: 0 !important; width: 100% !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light">
    <div id="app" class="d-flex">
        <!-- Sidebar -->
         <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="https://decentdemo.in/school/images/school-logo.png" alt="Smart School ERP Logo">
            <button class="close-sidebar" id="closeSidebar"><i class="fas fa-times"></i></button>
        </div>
        <div class="menu-section" style="--i:1;">
            <a href="{{ route('dashboard') }}" class="menu-item" data-page="index"><i class="fas fa-home"></i><span>Dashboard</span></a>
        </div>
        <div class="menu-section" style="--i:2;">
            <div class="section-title" data-target="enquiryReg"><i class="fas fa-clipboard-list"></i> Enquiry &amp; Registration<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="enquiryReg">
                <a href="{{ route('enquiry.index') }}" class="sub-item" data-page="enquiry-manager"><span class="sub-icon-wrap"><i class="fas fa-phone-volume"></i></span>Enquiry</a>
                <a href="{{ route('registration.index') }}" class="sub-item" data-page="registration-manager"><span class="sub-icon-wrap"><i class="fas fa-user-plus"></i></span>Registration Manager</a>
                <a href="{{ route('admin.admission-report.index') }}" class="sub-item" data-page="admission-report"><span class="sub-icon-wrap"><i class="fas fa-file-alt"></i></span>Report</a>
            </div>
        </div>
        <div class="menu-section" style="--i:3;">
            <div class="section-title" data-target="studentMaster"><i class="fas fa-user-graduate"></i> Student Management<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="studentMaster">
                <a href="{{ route('admin.student-admission.index') }}" class="sub-item" data-page="student-admission"><span class="sub-icon-wrap"><i class="fas fa-user-plus"></i></span>Add / Admission Manager</a>
                <a href="{{ route('admin.student-entry.index') }}" class="sub-item active-page" data-page="student-entry"><span class="sub-icon-wrap"><i class="fas fa-pen-to-square"></i></span>Student Entry</a>
                <a href="{{route('students.student-details') }}" class="sub-item" data-page="student-details"><span class="sub-icon-wrap"><i class="fas fa-id-card"></i></span>Student Details</a>
                <a href="{{ route('admin.admission-report.index') }}" class="sub-item" data-page="admission-report"><span class="sub-icon-wrap"><i class="fas fa-file-invoice"></i></span>Admission Report</a>
                <a href="{{ route('admin.admission-fee-structure.index') }}" class="sub-item" data-page="admission-fee-structure"><span class="sub-icon-wrap"><i class="fas fa-rupee-sign"></i></span>Admission Fee Structure</a>
                <a href="{{ route('admin.promote-student.index') }}" class="sub-item" data-page="promote-student"><span class="sub-icon-wrap"><i class="fas fa-level-up-alt"></i></span>Promote Student</a>
                <a href="{{ route('classes.index') }}" class="sub-item" data-page="class-section-manager"><span class="sub-icon-wrap"><i class="fas fa-layer-group"></i></span>Class &amp; Section Manager</a>
            </div>
        </div>
        <div class="menu-section" style="--i:4;">
            <div class="section-title" data-target="feeMaster"><i class="fas fa-coins"></i> Fee Details<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="feeMaster">
                <a href="{{ route('fee.collect') }}" class="sub-item" data-page="collect-fee"><span class="sub-icon-wrap"><i class="fas fa-hand-holding-usd"></i></span>Collect Fee</a>
                <a href="{{ route('quick.collect') }}" class="sub-item" data-page="quick-collect"><span class="sub-icon-wrap"><i class="fas fa-bolt"></i></span>Quick Collect</a>
                <a href="{{ route('fee.demand') }}" class="sub-item" data-page="demand-slip"><span class="sub-icon-wrap"><i class="fas fa-receipt"></i></span>Demand Slip</a>
                <a href="{{ route('admin.manage-dues.index') }}" class="sub-item" data-page="manage-dues"><span class="sub-icon-wrap"><i class="fas fa-exclamation-circle"></i></span>Manage Due Fee</a>
                <a href="{{ route('admin.fee-structure-manager.index') }}" class="sub-item" data-page="fee-structure-manager"><span class="sub-icon-wrap"><i class="fas fa-sitemap"></i></span>Structure Manager</a>
                <a href="{{ route('fee.concession') }}" class="sub-item" data-page="fee-concession"><span class="sub-icon-wrap"><i class="fas fa-tag"></i></span>Fee Consession</a>
                <a href="{{ route('admin.late-fine.index') }}" class="sub-item" data-page="late-fine"><span class="sub-icon-wrap"><i class="fas fa-clock"></i></span>Late Fine Fee</a>
                <a href="{{ route('admin.fee-report.index') }}" class="sub-item" data-page="fee-report"><span class="sub-icon-wrap"><i class="fas fa-chart-bar"></i></span>Fee Report</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-baby"></i></span>Day Care Manager</a>
            </div>
        </div>
        <div class="menu-section" style="--i:5;">
            <div class="section-title" data-target="transport"><i class="fas fa-bus"></i> Transport Management<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="transport">
                <a href="{{ route('admin.transport-assign2.index') }}" class="sub-item" data-page="transport-assign2"><span class="sub-icon-wrap"><i class="fas fa-user-tag"></i></span>Transport Assign</a>
                <a href="{{ route('admin.transport-management.index') }}" class="sub-item" data-page="transport-management"><span class="sub-icon-wrap"><i class="fas fa-route"></i></span>Transport Route - Vehicle Manager</a>
                <a href="{{ route('admin.transport-report.index') }}" class="sub-item" data-page="transport-report"><span class="sub-icon-wrap"><i class="fas fa-clipboard-list"></i></span>Transport Report</a>
            </div>
        </div>
        <div class="menu-section" style="--i:6;">
            <div class="section-title" data-target="hostel"><i class="fas fa-hotel"></i> Hostel Manager<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="hostel">
                <a href="{{ route('admin.student-hostel.index') }}" class="sub-item" data-page="student-hostel"><span class="sub-icon-wrap"><i class="fas fa-bed"></i></span>Student Hostel</a>
                <a href="{{ route('admin.student-hostel.index') }}" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-rupee-sign"></i></span>Assign Hostel Charges</a>
                <a href="{{ route('admin.hostel-report.index') }}" class="sub-item" data-page="hostel-report"><span class="sub-icon-wrap"><i class="fas fa-file-medical-alt"></i></span>Hostel Report</a>
            </div>
        </div>
        <div class="menu-section" style="--i:7;">
            <div class="section-title" data-target="attendance"><i class="fas fa-calendar-check"></i> Attendance<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="attendance">
                <a href="{{ route('attendance.index') }}" class="sub-item" data-page="attendance-manager"><span class="sub-icon-wrap"><i class="fas fa-calendar-check"></i></span>Attendance Manager</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-chart-pie"></i></span>Attendance Report</a>
            </div>
        </div>
        <div class="menu-section" style="--i:8;">
            <div class="section-title" data-target="exam"><i class="fas fa-pencil-alt"></i> Examination<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="exam">
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-book"></i></span>Add Subject</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-pen-square"></i></span>Add Exam Name</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-tasks"></i></span>Assign Subject</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-plus-square"></i></span>Add Exam</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-id-badge"></i></span>Print Admit Card</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-table"></i></span>Marks Register</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-star-half-alt"></i></span>Grade Form</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-file-excel"></i></span>Tabulation &amp; Marksheet</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-file-alt"></i></span>Final Marksheet</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-sliders-h"></i></span>Set Division Marks</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-graduation-cap"></i></span>Set Grading Marks</a>
            </div>
        </div>
        <div class="menu-section" style="--i:9;">
            <div class="section-title" data-target="reports"><i class="fas fa-chart-line"></i> Reports<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="reports">
                <a href="{{ route('admin.fee-report.index') }}" class="sub-item" data-page="fee-report"><span class="sub-icon-wrap"><i class="fas fa-rupee-sign"></i></span>Fee Report</a>
                <a href="{{ route('admin.admission-report.index') }}" class="sub-item" data-page="admission-report"><span class="sub-icon-wrap"><i class="fas fa-user-plus"></i></span>Admission Report</a>
                <a href="{{ route('admin.transport-report.index') }}" class="sub-item" data-page="transport-report"><span class="sub-icon-wrap"><i class="fas fa-bus"></i></span>Transport Report</a>
                <a href="{{ route('admin.hostel-report.index') }}" class="sub-item" data-page="hostel-report"><span class="sub-icon-wrap"><i class="fas fa-hotel"></i></span>Hostel Report</a>
            </div>
        </div>
        <div class="menu-section" style="--i:10;">
            <div class="section-title" data-target="hr"><i class="fas fa-users"></i> HR &amp; Payroll<i class="fas fa-chevron-right chevron"></i></div>
            <div class="submenu-items" id="hr">
                <a href="{{ route('admin.staff-attendance.index') }}" class="sub-item" data-page="staff-attendance"><span class="sub-icon-wrap"><i class="fas fa-user-clock"></i></span>Staff Attendance</a>
                <a href="{{ route('admin.staff-hr-payroll.index') }}" class="sub-item" data-page="staff-hr-payroll"><span class="sub-icon-wrap"><i class="fas fa-user-tie"></i></span>Staff Management</a>
                <a href="javascript:void(0)" class="sub-item"><span class="sub-icon-wrap"><i class="fas fa-file-alt"></i></span>Staff Report</a>
                <a href="{{ route('admin.expenses-manager.index') }}" class="sub-item" data-page="expenses-manager"><span class="sub-icon-wrap"><i class="fas fa-wallet"></i></span>Expense Management</a>
                <a href="{{ route('admin.staff-permissions.index') }}" class="sub-item" data-page="staff-permissions"><span class="sub-icon-wrap"><i class="fas fa-user-shield"></i></span>Assign Role to User</a>
            </div>
        </div>
        <div class="menu-section standalone-item" style="--i:11;">
            <a href="{{ route('admin.school-setting.index') }}" class="menu-item" data-page="school-setting"><i class="fas fa-cogs"></i><span>School Setting</span></a>
        </div>
        <div class="menu-section standalone-item" style="--i:12;">
            <a href="javascript:void(0)" class="menu-item"><i class="fas fa-database"></i><span>Backup Database</span></a>
        </div>
        <div class="menu-section standalone-item" style="--i:13;">
            <a href="{{ route('profile') }}" class="menu-item"><i class="fas fa-user-circle"></i><span>My Profile</span></a>
        </div>
        <div class="menu-section standalone-item" style="--i:14;">
            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 d-inline-block w-100">
                @csrf
                <button type="submit" class="menu-item bg-transparent border-0 text-start w-100" style="color:#ff8080;"><i class="fas fa-sign-out-alt" style="color:#ff8080;"></i><span>Logout</span></button>
            </form>
        </div>
        <div style="height:20px;"></div>
    </aside>

        <!-- Main content -->
        <div class="main-content flex-grow-1" style=" width: calc(100% - 300px); min-height: 100vh;">
            <!-- Top navbar -->
            <nav class="navbar navbar-expand navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary d-md-none me-2" id="sidebarToggle">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <span class="navbar-brand mb-0 h6 d-none d-md-inline">
                        @yield('page_title', 'Dashboard')
                    </span>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <!-- Notification bell dropdown -->
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="javascript:void(0)" id="notificationsDropdown"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                @if(auth()->check() && auth()->user()->unreadNotifications->count())
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg p-0">
                                <div class="dropdown-header px-3 py-2 d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Notifications</span>
                                    <a href="{{ route('notifications.index') }}" class="small">View all</a>
                                </div>
                                <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;">
                                    @if(auth()->check())
                                        @forelse(auth()->user()->notifications->take(10) as $notification)
                                            <a href="{{ route('notifications.show', $notification->id) }}"
                                               class="list-group-item list-group-item-action small {{ $notification->read_at ? '' : 'fw-semibold' }}">
                                                {{ $notification->data['message'] ?? 'Notification' }}
                                                <div class="text-muted small">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>
                                            </a>
                                        @empty
                                            <div class="p-3 text-muted small">No notifications.</div>
                                        @endforelse
                                    @else
                                        <div class="p-3 text-muted small">Please login to view notifications.</div>
                                    @endif
                                </div>
                            </div>
                        </li>

                        <!-- User dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="userDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                {{ auth()->check() ? auth()->user()->name : 'Guest' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Page content -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const closeSidebar = document.getElementById('closeSidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.add('show-mobile');
                });
            }

            if (closeSidebar && sidebar) {
                closeSidebar.addEventListener('click', function() {
                    sidebar.classList.remove('show-mobile');
                });
            }

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 700 && sidebar && !sidebar.contains(e.target) && sidebarToggle && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show-mobile');
                }
            });

            // Submenu accordion toggle
            document.querySelectorAll('.section-title').forEach(function(title) {
                title.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);
                    if (submenu) {
                        submenu.classList.toggle('show');
                        this.classList.toggle('open');
                    }
                });
            });

            // Auto-highlight active page & auto-open its parent section
            const pagePath = window.location.pathname;
            document.querySelectorAll('[data-page]').forEach(function(el) {
                const pageAttr = el.getAttribute('data-page');
                // Check if current URL contains the page property
                if (pagePath.includes(pageAttr)) {
                    el.classList.add('active-menu');
                    el.style.background = 'rgba(255,145,59,0.18)';
                    el.style.borderLeft = '3px solid var(--primary-orange)';
                    el.style.color = 'white';
                    
                    // Open parent submenu
                    const parentSub = el.closest('.submenu-items');
                    if (parentSub) {
                        parentSub.classList.add('show');
                        const parentTitle = parentSub.previousElementSibling;
                        if (parentTitle) parentTitle.classList.add('open');
                    }
                }
            });
        });

        // Global SweetAlert handler for Laravel session flash messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
