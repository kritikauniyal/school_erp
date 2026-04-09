<aside class="sidebar" id="sidebar">
  <div class="sb-head">
    <div class="sb-logo">
      <img src="https://decentwork.in/schoolerp/public/images/school-logo.png" alt="Smart School ERP">
    </div>
    <button class="sb-close" id="sbClose"><i class="fas fa-times"></i></button>
  </div>

  <nav class="sb-nav">

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}" class="menu-item {{ Route::is('dashboard') ? 'active-page' : '' }}">
      <span class="m-ico"><i class="fas fa-chart-pie"></i></span>Dashboard
    </a>

    <!-- Enquiry & Registration -->
    <div class="section-title {{ Request::is('admin/enquiry-manager*') || Request::is('admin/registration-manager*') ? 'open' : '' }}" data-target="m-enq">
      <span class="s-ico"><i class="fas fa-clipboard-list"></i></span>
      <span class="s-txt">Enquiry &amp; Registration</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/enquiry-manager*') || Request::is('admin/registration-manager*') ? 'open' : '' }}" id="m-enq">
      <a href="{{ route('enquiry.index') }}" class="sub-item {{ Route::is('enquiry.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-phone-volume"></i></span>Enquiry
      </a>
      <a href="{{ route('registration.index') }}" class="sub-item {{ Route::is('registration.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-user-plus"></i></span>Registration Manager
      </a>
    </div>

    <!-- Student Management -->
    <div class="section-title {{ Request::is('admin/student*') || Request::is('admin/classes*') || Request::is('admin/admission-fee-structure*') || Request::is('admin/promote-student*') ? 'open' : '' }}" data-target="m-stu">
      <span class="s-ico"><i class="fas fa-user-graduate"></i></span>
      <span class="s-txt">Student Management</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/student*') || Request::is('admin/classes*') || Request::is('admin/admission-fee-structure*') || Request::is('admin/promote-student*') ? 'open' : '' }}" id="m-stu">
      <a href="{{ route('admin.student-admission.index') }}" class="sub-item {{ Route::is('admin.student-admission.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-user-plus"></i></span>Admission Master
      </a>
      <a href="{{ route('admin.student-entry.index') }}" class="sub-item {{ Route::is('admin.student-entry.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-pen-to-square"></i></span>Student Entry
      </a>
      <a href="{{ route('students.student-details') }}" class="sub-item {{ Route::is('students.student-details') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-id-card"></i></span>Students Details
      </a>
      @if(Route::has('admin.admission-report.index'))
      <a href="{{ route('admin.admission-report.index') }}" class="sub-item {{ Route::is('admin.admission-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-file-invoice"></i></span>Admission Report
      </a>
      @endif
      <a href="{{ route('admin.admission-fee-structure.index') }}" class="sub-item {{ Route::is('admin.admission-fee-structure.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-rupee-sign"></i></span>Admission Fee Structure
      </a>
      <a href="{{ route('admin.promote-student.index') }}" class="sub-item {{ Route::is('admin.promote-student.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-level-up-alt"></i></span>Promote Student
      </a>
      <a href="{{ route('classes.index') }}" class="sub-item {{ Route::is('classes.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-layer-group"></i></span>Class &amp; Section Manager
      </a>
    </div>

    <!-- Fee Details -->
    <div class="section-title {{ Request::is('admin/collect-fee*') || Request::is('admin/quick-collect*') || Request::is('admin/demand-slip*') || Request::is('admin/manage-dues*') || Request::is('admin/fee*') || Request::is('admin/late-fine*') ? 'open' : '' }}" data-target="m-fee">
      <span class="s-ico"><i class="fas fa-coins"></i></span>
      <span class="s-txt">Fee Details</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/collect-fee*') || Request::is('admin/quick-collect*') || Request::is('admin/demand-slip*') || Request::is('admin/manage-dues*') || Request::is('admin/fee*') || Request::is('admin/late-fine*') ? 'open' : '' }}" id="m-fee">
      <a href="{{ route('admin.collect-fee.index') }}" class="sub-item {{ Route::is('admin.collect-fee.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-hand-holding-usd"></i></span>Collect Fee
      </a>
      <a href="{{ route('admin.demand-slip.index') }}" class="sub-item {{ Route::is('admin.demand-slip.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-receipt"></i></span>Demand Slip
      </a>
      <a href="{{ route('admin.manage-dues.index') }}" class="sub-item {{ Route::is('admin.manage-dues.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-exclamation-circle"></i></span>Manage Due Fee
      </a>
      <a href="{{ route('admin.fee-structure-manager.index') }}" class="sub-item {{ Route::is('admin.fee-structure-manager.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-sitemap"></i></span>Structure Manager
      </a>
      <a href="{{ route('admin.fee-concession.index') }}" class="sub-item {{ Route::is('admin.fee-concession.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-tag"></i></span>Fee Concession
      </a>
      <a href="{{ route('admin.late-fine.index') }}" class="sub-item {{ Route::is('admin.late-fine.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-clock"></i></span>Late Fine Fee
      </a>
      <a href="#" class="sub-item">
        <span class="si"><i class="fas fa-baby"></i></span>Day Care Manager
      </a>
    </div>

    <!-- Transport Management -->
    <div class="section-title {{ Request::is('admin/transport*') ? 'open' : '' }}" data-target="m-trn">
      <span class="s-ico"><i class="fas fa-bus"></i></span>
      <span class="s-txt">Transport Management</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/transport*') ? 'open' : '' }}" id="m-trn">
      @if(Route::has('admin.transport-assign2.index'))
      <a href="{{ route('admin.transport-assign2.index') }}" class="sub-item {{ Route::is('admin.transport-assign2.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-user-tag"></i></span>Transport Assign
      </a>
      @endif
      <a href="{{ route('admin.transport-management.index') }}" class="sub-item {{ Route::is('admin.transport-management.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-route"></i></span>Route – Vehicle Manager
      </a>
    </div>

    <!-- Hostel Manager -->
    <div class="section-title {{ Request::is('admin/student-hostel*') ? 'open' : '' }}" data-target="m-hst">
      <span class="s-ico"><i class="fas fa-hotel"></i></span>
      <span class="s-txt">Hostel Manager</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/student-hostel*') ? 'open' : '' }}" id="m-hst">
      <a href="{{ route('admin.student-hostel.index') }}" class="sub-item {{ Route::is('admin.student-hostel.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-bed"></i></span>Student Hostel
      </a>
      <a href="{{ route('admin.student-hostel.index') }}" class="sub-item">
        <span class="si"><i class="fas fa-rupee-sign"></i></span>Assign Hostel Charges
      </a>
    </div>

    <!-- Attendance -->
    <div class="section-title {{ Request::is('admin/attendance*') ? 'open' : '' }}" data-target="m-att">
      <span class="s-ico"><i class="fas fa-calendar-check"></i></span>
      <span class="s-txt">Attendance</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/attendance*') ? 'open' : '' }}" id="m-att">
      <a href="{{ route('attendance.index') }}" class="sub-item {{ Route::is('attendance.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-calendar-check"></i></span>Attendance Manager
      </a>
      <a href="#" class="sub-item">
        <span class="si"><i class="fas fa-chart-pie"></i></span>Attendance Report
      </a>
    </div>

    <!-- Examination (New) -->
    <div class="section-title" data-target="m-exam">
      <span class="s-ico"><i class="fas fa-pencil-alt"></i></span>
      <span class="s-txt">Examination</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu" id="m-exam">
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-book"></i></span>Add Subject</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-pen-square"></i></span>Add Exam Name</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-tasks"></i></span>Assign Subject</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-plus-square"></i></span>Add Exam</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-id-badge"></i></span>Print Admit Card</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-table"></i></span>Marks Register</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-star-half-alt"></i></span>Grade Form</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-file-excel"></i></span>Tabulation &amp; Marksheet</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-file-alt"></i></span>Final Marksheet</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-sliders-h"></i></span>Set Division Marks</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-graduation-cap"></i></span>Set Grading Marks</a>
    </div>

    <!-- Reports -->
    <div class="section-title {{ Request::is('admin/*report*') ? 'open' : '' }}" data-target="m-rep">
      <span class="s-ico"><i class="fas fa-chart-line"></i></span>
      <span class="s-txt">Reports</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/*report*') ? 'open' : '' }}" id="m-rep">
      <a href="{{ route('admin.fee-report.index') }}" class="sub-item {{ Route::is('admin.fee-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-rupee-sign"></i></span>Fee Report
      </a>
      @if(Route::has('admin.admission-report.index'))
      <a href="{{ route('admin.admission-report.index') }}" class="sub-item {{ Route::is('admin.admission-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-user-plus"></i></span>Admission Report
      </a>
      @endif
      <a href="{{ route('admin.transport-report.index') }}" class="sub-item {{ Route::is('admin.transport-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-bus"></i></span>Transport Report
      </a>
      <a href="{{ route('admin.hostel-report.index') }}" class="sub-item {{ Route::is('admin.hostel-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-hotel"></i></span>Hostel Report
      </a>
    </div>

    <!-- HR & Payroll (New) -->
    <div class="section-title" data-target="m-hr">
      <span class="s-ico"><i class="fas fa-users"></i></span>
      <span class="s-txt">HR &amp; Payroll</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu" id="m-hr">
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-user-clock"></i></span>Staff Attendance</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-user-tie"></i></span>Staff Management</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-file-alt"></i></span>Staff Report</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-wallet"></i></span>Expense Management</a>
      <a href="#" class="sub-item"><span class="si"><i class="fas fa-user-shield"></i></span>Assign Role to User</a>
    </div>

    <div class="nav-divider"></div>

    <!-- Settings -->
    <a href="{{ route('admin.school-setting.index') }}" class="menu-item {{ Route::is('admin.school-setting.index') ? 'active-page' : '' }}">
      <span class="m-ico"><i class="fas fa-cogs"></i></span>School Setting
    </a>
    <a href="#" class="menu-item">
      <span class="m-ico"><i class="fas fa-database"></i></span>Backup Database
    </a>
    <a href="#" class="menu-item">
      <span class="m-ico"><i class="fas fa-user-circle"></i></span>My Profile
    </a>
    
    <form action="{{ route('logout') }}" method="POST" id="sidebar-logout-form" style="display: none;">
      @csrf
    </form>
    <a href="javascript:void(0)" onclick="document.getElementById('sidebar-logout-form').submit()" class="menu-item logout">
      <span class="m-ico"><i class="fas fa-sign-out-alt"></i></span>Logout
    </a>

    <div style="height:24px"></div>
  </nav>
</aside>
