<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\FeeController as AdminFeeController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SmsTemplateController as AdminSmsTemplateController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Fee\FeeController as FeeController;
Route::get('/assign-admin', function () {
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
    $user = \App\Models\User::first();
    if ($user) {
        $user->assignRole($role);
        return 'Admin role assigned to user: ' . $user->name;
    }
    return 'No user found in database.';
});

Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/login',[AuthController::class,'loginCheck']);

Route::get('/register',[AuthController::class,'register'])->name('register');
Route::post('/register',[AuthController::class,'registerStore']);

Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::get('dashboard', function () {
    $dashboardService = app(\App\Services\DashboardService::class);
    return view('pages.dashboard', [
        'stats' => $dashboardService->getStats(),
        'upcomingFees' => $dashboardService->getUpcomingFees(),
        'latestPayments' => $dashboardService->getLatestPayments(),
        'chartData' => $dashboardService->getChartData()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
});




Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class,'index'])
            ->name('admin.dashboard');

        Route::get('/students', [AdminStudentController::class, 'index'])
            ->name('students.index');
        Route::get('/students-details', [AdminStudentController::class, 'studentDetails'])
            ->name('students.student-details');

        Route::get('/classes', [AdminClassController::class, 'index'])->name('classes.index');
        Route::post('/classes', [AdminClassController::class, 'store'])->name('classes.store');
        Route::put('/classes/{id}', [AdminClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{id}', [AdminClassController::class, 'destroy'])->name('classes.destroy');

        Route::post('/sections', [\App\Http\Controllers\Admin\SectionController::class, 'store'])->name('sections.store');
        Route::put('/sections/{id}', [\App\Http\Controllers\Admin\SectionController::class, 'update'])->name('sections.update');
        Route::delete('/sections/{id}', [\App\Http\Controllers\Admin\SectionController::class, 'destroy'])->name('sections.destroy');

        Route::get('/attendance', [AdminAttendanceController::class, 'index'])
            ->name('attendance.index');

        Route::get('/fees', [AdminFeeController::class, 'index'])
            ->name('fees.index');

        Route::get('/exams', [AdminExamController::class, 'index'])
            ->name('exams.index');

        Route::get('/inventory', [AdminInventoryController::class, 'index'])
            ->name('inventory.index');

        Route::get('/reports', [AdminReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/sms-templates', [AdminSmsTemplateController::class, 'index'])
            ->name('sms-templates.index');
        Route::get('/enquiry-manager', [\App\Http\Controllers\Admin\EnquiryManagerController::class, 'index'])->name('enquiry.index');
        Route::post('/enquiry-manager', [\App\Http\Controllers\Admin\EnquiryManagerController::class, 'store'])->name('enquiry.store');
        Route::get('/enquiry-manager/{id}/show', [\App\Http\Controllers\Admin\EnquiryManagerController::class, 'show'])->name('enquiry.show');
        Route::get('/enquiry-manager/{id}/edit', [\App\Http\Controllers\Admin\EnquiryManagerController::class, 'edit'])->name('enquiry.edit');
        Route::put('/enquiry-manager/{id}', [\App\Http\Controllers\Admin\EnquiryManagerController::class, 'update'])->name('enquiry.update');
        Route::delete('/enquiry-manager/{id}', [\App\Http\Controllers\Admin\EnquiryManagerController::class, 'destroy'])->name('enquiry.delete');
        Route::post('/enquiry-manager/{id}/followup', [\App\Http\Controllers\Admin\EnquiryManagerController::class, 'followup'])->name('enquiry.followup');
        Route::get('/registration-manager', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'index'])->name('registration.index');
        Route::post('/registration-manager', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'store'])->name('registration.store');
        Route::get('/registration-manager/{id}/show', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'show'])->name('registration.show');
        Route::get('/registration-manager/{id}/edit', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'edit'])->name('registration.edit');
        Route::put('/registration-manager/{id}', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'update'])->name('registration.update');
        Route::delete('/registration-manager/{id}', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'destroy'])->name('registration.delete');
        Route::post('/registration-manager/{id}/confirm-admission', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'confirmAdmission'])->name('registration.confirm-admission');
        Route::get('/registration/lookup', [\App\Http\Controllers\Admin\RegistrationManagerController::class, 'lookup'])->name('registration.lookup');
        Route::get('/admission-report', [\App\Http\Controllers\Admin\AdmissionReportController::class, 'index'])->name('admin.admission-report.index');
        Route::get('/admission-report/create', [\App\Http\Controllers\Admin\AdmissionReportController::class, 'create'])->name('admin.admission-report.create');
        Route::post('/admission-report', [\App\Http\Controllers\Admin\AdmissionReportController::class, 'store'])->name('admin.admission-report.store');
        Route::get('/admission-report/{id}/show', [\App\Http\Controllers\Admin\AdmissionReportController::class, 'show'])->name('admin.admission-report.show');
        Route::get('/admission-report/{id}/edit', [\App\Http\Controllers\Admin\AdmissionReportController::class, 'edit'])->name('admin.admission-report.edit');
        Route::put('/admission-report/{id}', [\App\Http\Controllers\Admin\AdmissionReportController::class, 'update'])->name('admin.admission-report.update');
        Route::delete('/admission-report/{id}', [\App\Http\Controllers\Admin\AdmissionReportController::class, 'destroy'])->name('admin.admission-report.destroy');

        Route::get('/student-admission', [\App\Http\Controllers\Admin\StudentAdmissionController::class, 'index'])->name('admin.student-admission.index');
        Route::post('/student-admission', [\App\Http\Controllers\Admin\StudentAdmissionController::class, 'store'])->name('admin.student-admission.store');
        Route::get('/student-admission/{id}/show', [\App\Http\Controllers\Admin\StudentAdmissionController::class, 'show'])->name('admin.student-admission.show');
        Route::put('/student-admission/{id}', [\App\Http\Controllers\Admin\StudentAdmissionController::class, 'update'])->name('admin.student-admission.update');
        Route::delete('/student-admission/{id}', [\App\Http\Controllers\Admin\StudentAdmissionController::class, 'destroy'])->name('admin.student-admission.destroy');
        Route::get('/student-admission/{id}/fees', [\App\Http\Controllers\Admin\StudentAdmissionController::class, 'getFees'])->name('admin.student-admission.get-fees');
        Route::post('/student-admission/{id}/fees', [\App\Http\Controllers\Admin\StudentAdmissionController::class, 'saveFees'])->name('admin.student-admission.save-fees');
        
        Route::get('/student-entry', [\App\Http\Controllers\Admin\StudentEntryController::class, 'index'])->name('admin.student-entry.index');
        Route::post('/student-entry', [\App\Http\Controllers\Admin\StudentEntryController::class, 'store'])->name('admin.student-entry.store');
        Route::get('/student-entry/{id}/show', [\App\Http\Controllers\Admin\StudentEntryController::class, 'show'])->name('admin.student-entry.show');
        Route::put('/student-entry/{id}', [\App\Http\Controllers\Admin\StudentEntryController::class, 'update'])->name('admin.student-entry.update');
        Route::delete('/student-entry/{id}', [\App\Http\Controllers\Admin\StudentEntryController::class, 'destroy'])->name('admin.student-entry.destroy');
        Route::get('/admission-manager', [\App\Http\Controllers\Admin\AdmissionManagerController::class, 'index'])->name('admin.admission-manager.index');
        Route::post('/admission-manager/{id}/convert', [\App\Http\Controllers\Admin\AdmissionManagerController::class, 'convertToStudent'])->name('admin.admission-manager.convert');
        Route::get('/admission-manager/{id}/show', [\App\Http\Controllers\Admin\AdmissionManagerController::class, 'show'])->name('admin.admission-manager.show');

        Route::get('/admission-fee-structure', [\App\Http\Controllers\AdmissionFeeController::class, 'index'])->name('admin.admission-fee-structure.index');
        Route::post('/admission-fee-structure/fee-type', [\App\Http\Controllers\AdmissionFeeController::class, 'storeFeeType'])->name('admin.admission-fee-structure.store-fee-type');
        Route::put('/admission-fee-structure/fee-type/{id}', [\App\Http\Controllers\AdmissionFeeController::class, 'updateFeeType'])->name('admin.admission-fee-structure.update-fee-type');
        Route::delete('/admission-fee-structure/fee-type/{id}', [\App\Http\Controllers\AdmissionFeeController::class, 'destroyFeeType'])->name('admin.admission-fee-structure.destroy-fee-type');
        Route::post('/admission-fee-structure/save-structure', [\App\Http\Controllers\AdmissionFeeController::class, 'saveStructure'])->name('admin.admission-fee-structure.save-structure');
        Route::get('/admission-fee-structure/{class_name}/get', [\App\Http\Controllers\AdmissionFeeController::class, 'getStructureByClass'])->name('admin.admission-fee-structure.get-structure');
        Route::get('/promote-student', [\App\Http\Controllers\Admin\PromoteStudentController::class, 'index'])->name('admin.promote-student.index');
        Route::get('/promote-student/search', [\App\Http\Controllers\Admin\PromoteStudentController::class, 'search'])->name('admin.promote-student.search');
        Route::post('/promote-student/update', [\App\Http\Controllers\Admin\PromoteStudentController::class, 'updateSingle'])->name('admin.promote-student.update');
        Route::post('/promote-student/update-all', [\App\Http\Controllers\Admin\PromoteStudentController::class, 'updateAll'])->name('admin.promote-student.update-all');
        Route::get('/collect-fee', [\App\Http\Controllers\Admin\CollectFeeController::class, 'index'])->name('admin.collect-fee.index');
        Route::get('/collect-fee/details/{id}', [\App\Http\Controllers\Admin\CollectFeeController::class, 'getStudentFeeDetails'])->name('admin.collect-fee.details');
        Route::get('/quick-collect', [\App\Http\Controllers\Admin\QuickCollectController::class, 'index'])->name('admin.quick-collect.index');
        Route::get('/demand-slip', [\App\Http\Controllers\Admin\DemandSlipController::class, 'index'])->name('admin.demand-slip.index');
        Route::post('/demand-slip/students', [\App\Http\Controllers\Admin\DemandSlipController::class, 'getStudents'])->name('admin.demand-slip.students');
        Route::get('/manage-dues', [\App\Http\Controllers\Admin\ManageDuesController::class, 'index'])->name('admin.manage-dues.index');
        Route::post('/manage-dues/students', [\App\Http\Controllers\Admin\ManageDuesController::class, 'getStudents'])->name('admin.manage-dues.students');
        Route::post('/manage-dues/update', [\App\Http\Controllers\Admin\ManageDuesController::class, 'updateStudents'])->name('admin.manage-dues.update');
        Route::get('/fee-structure-manager', [\App\Http\Controllers\Admin\FeeStructureManagerController::class, 'index'])->name('admin.fee-structure-manager.index');
        Route::post('/fee-structure-manager/save', [\App\Http\Controllers\Admin\FeeStructureManagerController::class, 'saveStructure'])->name('admin.fee-structure-manager.save-structure');
        Route::get('/fee-structure-manager/history', [\App\Http\Controllers\Admin\FeeStructureManagerController::class, 'history'])->name('admin.fee-structure-manager.history');
        Route::post('/fee-structure-manager/fee-type/store', [\App\Http\Controllers\Admin\FeeStructureManagerController::class, 'storeFeeType'])->name('admin.fee-structure-manager.fee-type.store');
        Route::post('/fee-structure-manager/fee-type/update/{id}', [\App\Http\Controllers\Admin\FeeStructureManagerController::class, 'updateFeeType'])->name('admin.fee-structure-manager.fee-type.update');
        Route::post('/fee-structure-manager/fee-type/delete/{id}', [\App\Http\Controllers\Admin\FeeStructureManagerController::class, 'destroyFeeType'])->name('admin.fee-structure-manager.fee-type.delete');
        Route::get('/fee-concession', [\App\Http\Controllers\Admin\FeeConcessionController::class, 'index'])->name('admin.fee-concession.index');
        Route::post('/fee-concession/store', [\App\Http\Controllers\Admin\FeeConcessionController::class, 'store'])->name('admin.fee-concession.store');
        Route::post('/fee-concession/bulk-store', [\App\Http\Controllers\Admin\FeeConcessionController::class, 'bulkStore'])->name('admin.fee-concession.bulk-store');
        Route::get('/late-fine', [\App\Http\Controllers\Admin\LateFineController::class, 'index'])->name('admin.late-fine.index');
        Route::post('/late-fine/store', [\App\Http\Controllers\Admin\LateFineController::class, 'store'])->name('admin.late-fine.store');
        Route::post('/late-fine/delete/{id}', [\App\Http\Controllers\Admin\LateFineController::class, 'destroy'])->name('admin.late-fine.delete');
        Route::get('/fee-report', [\App\Http\Controllers\Admin\FeeReportController::class, 'index'])->name('admin.fee-report.index');
        Route::get('/fee-report/data', [\App\Http\Controllers\Admin\FeeReportController::class, 'getData'])->name('admin.fee-report.data');
        Route::get('/transport-assign2', [\App\Http\Controllers\Admin\TransportAssign2Controller::class, 'index'])->name('admin.transport-assign2.index');
        Route::post('/transport-assign2/store', [\App\Http\Controllers\Admin\TransportAssign2Controller::class, 'store'])->name('admin.transport-assign2.store');
        Route::post('/transport-assign2/stop/{id}', [\App\Http\Controllers\Admin\TransportAssign2Controller::class, 'stop'])->name('admin.transport-assign2.stop');
        Route::get('/students/search', [\App\Http\Controllers\Admin\TransportAssign2Controller::class, 'searchStudents'])->name('admin.students.search');
        Route::get('/transport-management', [\App\Http\Controllers\Admin\TransportManagementController::class, 'index'])->name('admin.transport-management.index');
        Route::post('/transport-management/route', [\App\Http\Controllers\Admin\TransportManagementController::class, 'storeRoute'])->name('admin.transport-management.route.store');
        Route::put('/transport-management/route/{id}', [\App\Http\Controllers\Admin\TransportManagementController::class, 'updateRoute'])->name('admin.transport-management.route.update');
        Route::delete('/transport-management/route/{id}', [\App\Http\Controllers\Admin\TransportManagementController::class, 'deleteRoute'])->name('admin.transport-management.route.delete');
        Route::post('/transport-management/vehicle', [\App\Http\Controllers\Admin\TransportManagementController::class, 'storeVehicle'])->name('admin.transport-management.vehicle.store');
        Route::put('/transport-management/vehicle/{id}', [\App\Http\Controllers\Admin\TransportManagementController::class, 'updateVehicle'])->name('admin.transport-management.vehicle.update');
        Route::delete('/transport-management/vehicle/{id}', [\App\Http\Controllers\Admin\TransportManagementController::class, 'deleteVehicle'])->name('admin.transport-management.vehicle.delete');
        Route::post('/transport-management/assign', [\App\Http\Controllers\Admin\TransportManagementController::class, 'assignRoutes'])->name('admin.transport-management.assign');
        Route::get('/transport-report', [\App\Http\Controllers\Admin\TransportReportController::class, 'index'])->name('admin.transport-report.index');
        Route::post('/transport-report/generate', [\App\Http\Controllers\Admin\TransportReportController::class, 'generate'])->name('admin.transport-report.generate');
        Route::get('/student-hostel', [\App\Http\Controllers\Admin\StudentHostelController::class, 'index'])->name('admin.student-hostel.index');
        Route::post('/student-hostel', [\App\Http\Controllers\Admin\StudentHostelController::class, 'store'])->name('admin.student-hostel.store');
        Route::put('/student-hostel/{id}', [\App\Http\Controllers\Admin\StudentHostelController::class, 'update'])->name('admin.student-hostel.update');
        Route::post('/student-hostel/stop/{id}', [\App\Http\Controllers\Admin\StudentHostelController::class, 'stop'])->name('admin.student-hostel.stop');
        Route::get('/student-hostel/search-students', [\App\Http\Controllers\Admin\StudentHostelController::class, 'searchStudents'])->name('admin.student-hostel.search-students');
        Route::get('/hostel-report', [\App\Http\Controllers\Admin\HostelReportController::class, 'index'])->name('admin.hostel-report.index');
        Route::get('/hostel-report/generate', [\App\Http\Controllers\Admin\HostelReportController::class, 'generate'])->name('admin.hostel-report.generate');
        Route::get('/staff-attendance', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'index'])->name('admin.staff-attendance.index');
        Route::get('/staff-hr-payroll', [\App\Http\Controllers\Admin\StaffHrPayrollController::class, 'index'])->name('admin.staff-hr-payroll.index');
        Route::get('/expenses-manager', [\App\Http\Controllers\Admin\ExpensesManagerController::class, 'index'])->name('admin.expenses-manager.index');
        Route::get('/staff-permissions', [\App\Http\Controllers\Admin\StaffPermissionsController::class, 'index'])->name('admin.staff-permissions.index');
        Route::get('/school-setting', [\App\Http\Controllers\Admin\SchoolSettingController::class, 'index'])->name('admin.school-setting.index');

});

Route::middleware(['auth','role:teacher'])
    ->prefix('teacher')
    ->group(function () {

        Route::get('/dashboard', [TeacherDashboard::class,'index'])
            ->name('teacher.dashboard');

});
Route::middleware(['auth','role:student'])
    ->prefix('student')
    ->group(function () {

        Route::get('/dashboard',
            [\App\Http\Controllers\Student\DashboardController::class,'index']
        )->name('student.dashboard');

});
Route::middleware(['auth','role:parent'])
    ->prefix('parent')
    ->group(function () {

        Route::get('/dashboard',
            [\App\Http\Controllers\Parent\DashboardController::class,'index']
        );

});
Route::middleware(['auth','role:accountant'])
    ->prefix('accountant')
    ->group(function () {

        Route::get('/dashboard',
            [\App\Http\Controllers\Accountant\DashboardController::class,'index']
        );

});


Route::prefix('fee')->group(function () {

    Route::get('/concession', [FeeController::class, 'concession'])->name('fee.concession');
    Route::get('/concession/{id}/edit', [FeeController::class, 'editConcession'])->name('fee.concession.edit');
    Route::delete('/concession/{id}', [FeeController::class, 'deleteConcession'])->name('fee.concession.delete');

    Route::get('/report', [FeeController::class, 'report'])->name('fee.report');

    Route::get('/structure-manager', [FeeController::class, 'structure'])->name('fee.structure');

    Route::get('/collect-fee', [FeeController::class, 'collectFee'])->name('fee.collect');

    Route::get('/demand-slip', [FeeController::class, 'demandSlip'])->name('fee.demand');

    Route::get('/fee-collection/{id?}', [FeeController::class, 'feeCollection'])->name('fee.collection');
    Route::post('/fee-collection/{id}/pay', [FeeController::class, 'processPayment'])->name('fee.pay');
    Route::get('/quick-collect',[FeeController::class,'quickCollect'])->name('quick.collect');

});