<aside class="sidebar" id="sidebar">
  <div class="sb-head">
    <div class="sb-logo">
      <img src="https://decentwork.in/schoolerp/public/images/school-logo.png" alt="Smart School ERP">
    </div>
    <button class="sb-close" id="sbClose"><i class="fas fa-times"></i></button>
  </div>

  <nav class="sb-nav">

    <a href="{{ route('dashboard') }}" class="menu-item {{ Route::is('dashboard') ? 'active-page' : '' }}">
      <span class="m-ico"><i class="fas fa-chart-pie"></i></span>Dashboard
    </a>

    <!-- Enquiry & Registration -->
    <div class="section-title {{ Request::is('admin/enquiry-manager*') || Request::is('admin/registration-manager*') || Request::is('admin/admission-report*') ? 'open' : '' }}" data-target="m-enq">
      <span class="s-ico"><i class="fas fa-clipboard-list"></i></span>
      <span class="s-txt">Enquiry &amp; Registration</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/enquiry-manager*') || Request::is('admin/registration-manager*') || Request::is('admin/admission-report*') ? 'open' : '' }}" id="m-enq">
      <a href="{{ route('enquiry.index') }}" class="sub-item {{ Route::is('enquiry.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-phone-volume"></i></span>Enquiry
      </a>
      <a href="{{ route('registration.index') }}" class="sub-item {{ Route::is('registration.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-user-plus"></i></span>Registration Manager
      </a>
      @if(Route::has('admin.admission-report.index'))
      <a href="{{ route('admin.admission-report.index') }}" class="sub-item {{ Route::is('admin.admission-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-file-alt"></i></span>Report
      </a>
      @endif
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
    <div class="section-title {{ Request::is('admin/collect-fee*') || Request::is('admin/quick-collect*') || Request::is('admin/demand-slip*') || Request::is('admin/manage-dues*') || Request::is('admin/fee*') ? 'open' : '' }}" data-target="m-fee">
      <span class="s-ico"><i class="fas fa-coins"></i></span>
      <span class="s-txt">Fee Details</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/collect-fee*') || Request::is('admin/quick-collect*') || Request::is('admin/demand-slip*') || Request::is('admin/manage-dues*') || Request::is('admin/fee*') ? 'open' : '' }}" id="m-fee">
      <a href="{{ route('admin.collect-fee.index') }}" class="sub-item {{ Route::is('admin.collect-fee.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-hand-holding-usd"></i></span>Collect Fee
      </a>
      <!--<a href="{{ route('quick.collect') }}" class="sub-item {{ Route::is('quick.collect') ? 'active-page' : '' }}">-->
      <!--  <span class="si"><i class="fas fa-bolt"></i></span>Quick Collect-->
      <!--</a>-->
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
      <a href="{{ route('admin.fee-report.index') }}" class="sub-item {{ Route::is('admin.fee-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-chart-bar"></i></span>Fee Report
      </a>
    </div>

    <!-- Transport -->
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
      <a href="{{ route('admin.transport-report.index') }}" class="sub-item {{ Route::is('admin.transport-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-clipboard-list"></i></span>Transport Report
      </a>
    </div>
    <!-- Hostel Manager -->
    <div class="section-title {{ Request::is('admin/student-hostel*') || Request::is('admin/hostel-report*') ? 'open' : '' }}" data-target="m-hst">
      <span class="s-ico"><i class="fas fa-hotel"></i></span>
      <span class="s-txt">Hostel Manager</span>
      <i class="fas fa-chevron-right chev"></i>
    </div>
    <div class="submenu {{ Request::is('admin/student-hostel*') || Request::is('admin/hostel-report*') ? 'open' : '' }}" id="m-hst">
      <a href="{{ route('admin.student-hostel.index') }}" class="sub-item {{ Route::is('admin.student-hostel.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-bed"></i></span>Student Hostel
      </a>
      <a href="{{ route('admin.student-hostel.index') }}" class="sub-item">
        <span class="si"><i class="fas fa-rupee-sign"></i></span>Assign Hostel Charges
      </a>
      <a href="{{ route('admin.hostel-report.index') }}" class="sub-item {{ Route::is('admin.hostel-report.index') ? 'active-page' : '' }}">
        <span class="si"><i class="fas fa-file-medical-alt"></i></span>Hostel Report
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
    </div>

    <div class="nav-divider"></div>

    <a href="{{ route('admin.school-setting.index') }}" class="menu-item {{ Route::is('admin.school-setting.index') ? 'active-page' : '' }}">
      <span class="m-ico"><i class="fas fa-cogs"></i></span>School Setting
    </a>
    <a href="#" class="menu-item">
      <span class="m-ico"><i class="fas fa-database"></i></span>Backup Database
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
