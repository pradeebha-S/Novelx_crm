<?php
use App\Http\Controllers\Admin\AdminBugController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\PerformanceController;
use App\Http\Controllers\HR\HrController;
use App\Http\Controllers\Staff\AuthenticationController;
use App\Http\Controllers\Staff\BugController;
use App\Http\Controllers\Staff\PerformanceTrackerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ExpensesController;

use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Intern\InternController;
use App\Http\Controllers\Admin\BasicController;
use App\Http\Controllers\Admin\ProjectServiceController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Staff\UserDetailsController;
use GuzzleHttp\Client;
// use App\Http\Controllers\Staff\BankController;
use App\Models\User;
use App\Notifications\SimplePushNotification;
Route::post('/save-subscription', [PushSubscriptionController::class, 'store'])
    ->name('push.subscription.store');
Route::get('/push-test/{id}', function ($id) {
    $sent = webpushnotify(
        $id,
        'Test Notification',
        'Novelx push notification',
    );
    return response()->json([
        'user_id' => $id,
        'sent' => $sent
    ]);
});

Route::get('/test-brevo', [UserDetailsController::class, 'testBrevo']);
Route::get('/', function () {
    return view('welcome');
});
Route::get('/check-idle-users', [AdminController::class, 'checkIdleUsers'])->name('checkIdleUsers');
//basic routes
Route::get('/logs', [BasicController::class, 'logs'])->name('logs');
Route::get('/logs_clear', [BasicController::class, 'logs_clear'])->name('logs_clear');
Route::get('/logs_replace', [BasicController::class, 'logs_replace'])->name('logs_replace');
Route::get('/check_time', [BasicController::class, 'checkTime'])->name('check_time');
Route::get('/optimizeAndAnalyzeTables', [BasicController::class, 'optimizeAndAnalyzeTables'])->name('optimizeAndAnalyzeTables');
Route::get('/file_db_backup', [BasicController::class, 'file_db_backup']);
Route::get('/clear_cache', [BasicController::class, 'clear_cache']);
Route::get('/migrate', [BasicController::class, 'migrate']);
Route::get('/db_seed', [BasicController::class, 'db_seed']);
Route::get('/privacy_policy', [BasicController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/terms_conditions', [BasicController::class, 'terms_conditions'])->name('terms_conditions');
Route::get('/delete_user_account', [BasicController::class, 'delete_user_account'])->name('delete_user_account');
Route::prefix('admin')->group(function () {


    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('login_sendotp', [AuthController::class, 'login_sendotp'])->name('login_sendotp');
    Route::post('/login_verifyotp', [AuthController::class, 'login_verifyotp'])->name('login_verifyotp');
    Route::get('/forget_password', [AdminController::class, 'forget_password'])->name('admin.forget_password');
    Route::post('/forget_sendotp', [AuthController::class, 'forget_sendotp'])->name('forget_sendotp');
    Route::post('/forget_verifyotp', [AuthController::class, 'forget_verifyotp'])->name('forget_verifyotp');
    Route::get('/change_password', [AdminController::class, 'change_password'])->name('admin.change_password');
    Route::post('/new_password', [AuthController::class, 'new_password'])->name('new_password');
    Route::middleware('AdminLogin')->group(function () {


        Route::get('/popup_manager', [AdminController::class, 'popup_manager'])->name('popup_manager');
        Route::get('/popup_manager_form', [AdminController::class, 'popup_manager_form'])->name('popup_manager_form');

        Route::get('/create_communication', [AdminController::class, 'create_communication'])->name('create_communication');
        Route::post('store_communication', [AdminController::class, 'store_communication'])
            ->name('store_communication');
        Route::get('/mail-report-data', [AdminController::class, 'mail_report_data'])->name('mail_report_data');
        Route::get('/mail_table', [AdminController::class, 'mail_table'])->name('mail_table');
        Route::get('/view_mail/{id}', [AdminController::class, 'view_mail'])->name('view_mail');
        Route::get('/reply/{id}', [AdminController::class, 'reply'])
            ->name('reply');
        // Route::get('/create_doc', [AdminController::class, 'create_doc'])->name('create_doc');
        Route::get('/create_doc/{project_id?}', [AdminController::class, 'create_doc'])
    ->name('create_doc');
    Route::post('document_store', [ProjectServiceController::class, 'document_store'])->name('document_store');
        Route::get('/sent_mail', [AdminController::class, 'sent_mail'])->name('sent_mail');

        //bill
        Route::get('/completed_invoice', [BankController::class, 'completed_invoice'])->name('completed_invoice');
        Route::get('/completed_invoice_data', [BankController::class, 'completed_invoice_data'])->name('completed_invoice_data');
        Route::get('/bill_form', [BankController::class, 'bill_form'])->name('bill_form');
        Route::get('/bill_table', [BankController::class, 'bill_table'])->name('bill_table');
        Route::get('invoice-data', [BankController::class, 'invoiceData'])->name('invoice.data');
        Route::get('/payment_report/{id}', [BankController::class, 'payment_report'])->name('payment_report');
        Route::get('/payment-report-data/{id}', [BankController::class, 'payment_report_data'])->name('payment_report.data');
        Route::get('/view_bill/{id}', [BankController::class, 'view_bill'])->name('view_bill');
        Route::get('/invoice/{id}', [BankController::class, 'invoice'])->name('invoice');
        Route::post('/invoice_update/{id}', [BankController::class, 'invoice_update'])->name('invoice_update');
        Route::get('/get-project/{id}', [BankController::class, 'getProject']);
        Route::post('/create_invoice', [BankController::class, 'create_invoice'])->name('create_invoice');
        Route::get('/invoice/{id}/print', [BankController::class, 'downloadInvoice'])
            ->name('invoice.print');
        Route::get('/invoice/balance/{id}', [BankController::class, 'getBalance']);
        Route::post('payment_store', [BankController::class, 'payment_store'])->name('payment_store');
        Route::get('invoice_pdf', [BankController::class, 'invoice_pdf'])
            ->name('invoice_pdf');
        Route::get('/invoice-download/{id}', [BankController::class, 'downloadInvoice'])
            ->name('invoice.download');




        //bank
        Route::get('/add_bank', [AdminController::class, 'add_bank'])->name('add_bank');
        Route::post('/store_bank', [BankController::class, 'store_bank'])->name('store_bank');
        Route::get('bank-data', [BankController::class, 'bank_data'])->name('bank_data');
        Route::post('delete_bank', [BankController::class, 'delete_bank'])->name('delete_bank');
        Route::get('/edit-bank/{id}', [BankController::class, 'edit_bank'])->name('edit_bank');
        Route::post('/update_bank', [BankController::class, 'update_bank'])->name('update_bank');
        Route::post('/update-bank-status', [BankController::class, 'update_bank_status'])
            ->name('update_bank_status');
        Route::get('/staff_bank_details', [BankController::class, 'staff_bank_details'])->name('staff_bank_details');
        Route::get('staff_bank_details_data', [BankController::class, 'staff_bank_details_data'])->name('staff_bank_details_data');
        Route::get('/edit_staff_bank_details/{id}', [BankController::class, 'edit_staff_bank_details'])->name('edit_staff_bank_details');
        Route::post('delete_bank_details', [BankController::class, 'delete_bank_details'])->name('delete_bank_details');
        Route::post('/update_bank_details', [BankController::class, 'update_bank_details'])->name('update_bank_details');
        Route::post('/update_staff_bank_status', [BankController::class, 'update_staff_bank_status'])->name('update_staff_bank_status');
        //dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard/project-search', [AdminController::class, 'projectSearch'])->name('dashboard.project.search');
        Route::post('/reset_password_form', [AuthController::class, 'reset_password_form'])->name('reset_password_form');
        Route::get('/reset_password', [AdminController::class, 'reset_password'])->name('reset_password');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        //staff
        Route::get('/staff_table', [AdminController::class, 'staff_table'])->name('staff_table');
        Route::get('/staff_table_data', [AdminController::class, 'staff_table_data'])->name('staff_table_data');
        Route::get('/create_staff', [AdminController::class, 'create_staff'])->name('create_staff');
        Route::post('/add_staff', [AdminController::class, 'add_staff'])->name('add_staff');
        Route::get('/edit_staff/{id}', [AdminController::class, 'edit_staff'])->name('edit_staff');
        Route::post('/update_staff', [AdminController::class, 'update_staff'])->name('update_staff');
        Route::post('/delete_staff', [AdminController::class, 'delete_staff'])->name('delete_staff');
        Route::post('/toggle_status', [AdminController::class, 'toggle_status'])->name('toggle_status');
        //dashboard->task status
        Route::get('/today_tasks', [AdminController::class, 'today_tasks'])->name('today_tasks');
        Route::get('/today_tasks_data', [AdminController::class, 'today_tasks_data'])->name('today_tasks_data');
        Route::get('/task_description/{task_id}', [AdminController::class, 'task_description'])->name('task_description');
        Route::get('/today_complete', [AdminController::class, 'today_complete'])->name('today_complete');
        Route::get('/today_complete_data', [AdminController::class, 'today_complete_data'])->name('today_complete_data');
        Route::get('/task_history', [AdminController::class, 'task_history'])->name('task_history');
        Route::get('/task_history_data', [AdminController::class, 'task_history_data'])->name('task_history_data');
        Route::get('/pending_task', [AdminController::class, 'pending_task'])->name('pending_task');
        Route::get('/pending_task_data', [AdminController::class, 'pending_task_data'])->name('pending_task_data');
        Route::get('/create_role', [AdminController::class, 'create_role'])->name('create_role');
        Route::post('/add_role', [AdminController::class, 'add_role'])->name('add_role');
        Route::post('/delete_role', [AdminController::class, 'delete_role'])->name('delete_role');
        //craete project
        Route::get('/project_table', [AdminController::class, 'project_table'])->name('project_table');
        Route::get('/project_table_data', [AdminController::class, 'project_table_data'])->name('project_table_data');
        Route::get('/create_project', [AdminController::class, 'create_project'])->name('create_project');
        Route::post('/add_project', [AdminController::class, 'add_project'])->name('add_project');
        Route::get('/edit_project/{id}', [AdminController::class, 'edit_project'])->name('edit_project');
        Route::post('/update_project', [AdminController::class, 'update_project'])->name('update_project');
        Route::post('/delete', [AdminController::class, 'delete'])->name('delete');
        Route::post('/delete_project', [AdminController::class, 'delete_project'])->name('delete_project');
        //Intern
        Route::get('/student_dashboard', [AdminController::class, 'student_dashboard'])->name('student_dashboard');
        //course
        Route::get('/course', [AdminController::class, 'course'])->name('course');
        Route::get('/course_data', [AdminController::class, 'course_data'])->name('course_data');
        Route::post('/submit_course', [AdminController::class, 'submit_course'])->name('submit_course');
        Route::post('/delete_course', [AdminController::class, 'delete_course'])->name('delete_course');
        //topic
        Route::get('/topic/{id?}', [AdminController::class, 'topic'])->name('topic');
        Route::get('/topic_data/{id}', [AdminController::class, 'topic_data'])->name('topic_data');
        Route::post('/submit_topic', [AdminController::class, 'submit_topic'])->name('submit_topic');
        Route::post('/delete_topic', [AdminController::class, 'delete_topic'])->name('delete_topic');
        //chapter
        Route::get('/chapter/{id}', [AdminController::class, 'chapter'])->name('chapter');
        Route::get('/chapter_data/{id}', [AdminController::class, 'chapter_data'])->name('chapter_data');
        Route::post('/submit_chapter', [AdminController::class, 'submit_chapter'])->name('submit_chapter');
        Route::post('/delete_chapter', [AdminController::class, 'delete_chapter'])->name('delete_chapter');
        // Assign task
        Route::get('/student_tasks', [AdminController::class, 'student_tasks'])->name('student_tasks');
        Route::get('/student_tasks_data', [AdminController::class, 'student_tasks_data'])->name('student_tasks_data');
        Route::post('/assign_chapter', [AdminController::class, 'assign_chapter'])->name('assign_chapter');
        Route::post('/delete_student_task', [AdminController::class, 'delete_student_task'])->name('delete_student_task');
        Route::get('/intern_table', [AdminController::class, 'intern_table'])->name('intern_table');
        Route::get('/intern_table_data', [AdminController::class, 'intern_table_data'])->name('intern_table_data');
        Route::get('/student_task_view/{student_id}', [AdminController::class, 'student_task_view'])->name('student_task_view');
        Route::get('/student_task_view_data/{student_id}', [AdminController::class, 'student_task_view_data'])->name('student_task_view_data');
        Route::get('/create_intern', [AdminController::class, 'create_intern'])->name('create_intern');
        Route::post('/create_intern_form', [AdminController::class, 'create_intern_form'])->name('create_intern_form');
        Route::get('/edit_intern/{id}', [AdminController::class, 'edit_intern'])->name('edit_intern');
        Route::post('/update_intern', [AdminController::class, 'update_intern'])->name('update_intern');
        Route::post('/delete_intern', [AdminController::class, 'delete_intern'])->name('delete_intern');
        Route::post('/intern_toggle_status', [AdminController::class, 'intern_toggle_status'])->name('intern_toggle_status');
        Route::get('/intern_attendance', [AdminController::class, 'intern_attendance'])->name('intern_attendance');
        Route::get('/intern_attendance_data', [AdminController::class, 'intern_attendance_data'])->name('intern_attendance_data');
        //reminder
        Route::get('/reminder', [AdminController::class, 'reminder'])->name('admin.reminder');
        Route::get('/reminder_data', [AdminController::class, 'reminder_data'])->name('admin.reminder_data');
        Route::get('/create_reminder', [AdminController::class, 'create_reminder'])->name('create_reminder');
        Route::post('/add_reminder', [AdminController::class, 'add_reminder'])->name('add_reminder');
        Route::post('/delete_reminder', [AdminController::class, 'delete_reminder'])->name('delete_reminder');
        Route::post('/complete_reminder', [AdminController::class, 'complete_reminder'])->name('complete_reminder');
        // Route::get('/edit_reminder{id}', [AdminController::class,'edit_reminder'])->name('edit_reminder');
        // Route::post('/update_reminder', [AdminController::class, 'update_reminder'])->name('update_reminder');
        //module
        Route::get('/modules/{id}', [AdminController::class, 'modules'])->name('modules');
        Route::get('/modules_data/{id}', [AdminController::class, 'modules_data'])->name('modules_data');
        Route::post('/add_module', [AdminController::class, 'add_module'])->name('add_module');
        Route::post('/getModuleName', [AdminController::class, 'getModuleName'])->name('getModuleName');
        Route::get('/edit_module/{id}', [AdminController::class, 'edit_module'])->name('edit_module');
        Route::post('/update_module', [AdminController::class, 'update_module'])->name('update_module');
        Route::post('/delete_module', [AdminController::class, 'delete_module'])->name('delete_module');
        //task
        Route::get('/create_task/{project_id}', [AdminController::class, 'create_task'])->name('create_task');
        Route::get('/task/{id?}', [AdminController::class, 'task'])->name('task');
        Route::get('/task_data/{id?}', [AdminController::class, 'task_data'])->name('task_data');
        Route::post('/add_task', [AdminController::class, 'add_task'])->name('add_task');
        Route::post('/assigned_task', [AdminController::class, 'assigned_task'])->name('assigned_task');
        Route::get('/edit_task/{id}', [AdminController::class, 'edit_task'])->name('edit_task');
        Route::post('delete_task', [AdminController::class, 'delete_task'])->name('delete_task');
        Route::post('/update_task', [AdminController::class, 'update_task'])->name('update_task');
        Route::get('/task_view/{staff_id}', [AdminController::class, 'task_view'])->name('task_view');
        Route::get('/task_view_data/{staff_id}', [AdminController::class, 'task_view_data'])->name('task_view_data');
        Route::post('/verify_test_status', [AdminController::class, 'verify_test_status'])->name('verify_test_status');
        Route::get('/view_reopen_history/{task_id}', [AdminController::class, 'view_reopen_history'])->name('view_reopen_history');
        Route::get('/view_reopen_history_data/{task_id}', [AdminController::class, 'view_reopen_history_data'])->name('view_reopen_history_data');
        Route::post('/submit_reopen_status', [AdminController::class, 'submit_reopen_status'])->name('submit_reopen_status');
        Route::get('/view_hold_history/{task_id}', [AdminController::class, 'view_hold_history'])->name('view_hold_history');
        Route::get('/view_hold_history_data/{task_id}', [AdminController::class, 'view_hold_history_data'])->name('view_hold_history_data');
        //attendance history
        Route::get('/attendance_history', [AdminController::class, 'attendance_history'])->name('attendance_history');
        Route::get('/attendance_history_data', [AdminController::class, 'attendance_history_data'])->name('attendance_history_data');
        Route::get('/leave_request_table', [AdminController::class, 'leave_request_table'])->name('leave_request_table');
        Route::get('/leave_request_table_data', [AdminController::class, 'leave_request_table_data'])->name('leave_request_table_data');
        Route::post('/leave_reply', [AdminController::class, 'leave_reply'])->name('leave_reply');
        Route::get('/leave_request_history', [AdminController::class, 'leave_request_history'])->name('leave_request_history');
        Route::get('/leave_request_history_data', [AdminController::class, 'leave_request_history_data'])->name('leave_request_history_data');
        Route::get('/wfh_table', [AdminController::class, 'wfh_table'])->name('wfh_table');
        Route::get('/wfh_table_data', [AdminController::class, 'wfh_table_data'])->name('wfh_table_data');
        Route::post('/wfh_reply', [AdminController::class, 'wfh_reply'])->name('wfh_reply');
        Route::get('/wfh_history', [AdminController::class, 'wfh_history'])->name('wfh_history');
        Route::get('/wfh_history_data', [AdminController::class, 'wfh_history_data'])->name('wfh_history_data');
        Route::get('/permission_table', [AdminController::class, 'permission_table'])->name('permission_table');
        Route::get('/permission_table_data', [AdminController::class, 'permission_table_data'])->name('permission_table_data');
        Route::post('/permission_reply', [AdminController::class, 'permission_reply'])->name('permission_reply');
        Route::get('/permission_history', [AdminController::class, 'permission_history'])->name('permission_history');
        Route::get('/permission_history_data', [AdminController::class, 'permission_history_data'])->name('permission_history_data');
        Route::get('/feed_back', [AdminController::class, 'feed_back'])->name('feed_back');
        Route::get('/feed_back_data', [AdminController::class, 'feed_back_data'])->name('feed_back_data');
        Route::get('/seen_feed_back_data', [AdminController::class, 'seen_feed_back_data'])->name('seen_feed_back_data');
        Route::get('/view_feedback/{id}', [AdminController::class, 'view_feedback'])->name('view_feedback');
        //performance_tracker
        Route::get('/performance_tracker', [PerformanceController::class, 'performance_tracker'])->name('admin.performance_tracker');
        Route::get('/transaction_details', [PerformanceController::class, 'transaction_details'])->name('transaction_details');
        Route::post('/create_pps_transaction', [PerformanceController::class, 'create_pps_transaction'])->name('create_pps_transaction');
        Route::get('/transaction_data', [PerformanceController::class, 'transaction_data'])->name('transaction_data');
        Route::get('/pps', [PerformanceController::class, 'pps'])->name('pps');
        Route::get('/pps_data/{id}', [PerformanceController::class, 'pps_data'])->name('pps_data');
        //support
        Route::get('/common_request_table', [AdminController::class, 'common_request_table'])->name('common_request_table');
        Route::get('/common_request_table_data', [AdminController::class, 'common_request_table_data'])->name('common_request_table_data');
        Route::get('/common_request_history_table', [AdminController::class, 'common_request_history_table'])->name('common_request_history_table');
        Route::get('/common_request_history_data', [AdminController::class, 'common_request_history_data'])->name('common_request_history_data');
        Route::get('/personal_request_table', [AdminController::class, 'personal_request_table'])->name('personal_request_table');
        Route::get('/personal_request_table_data', [AdminController::class, 'personal_request_table_data'])->name('personal_request_table_data');
        Route::get('/personal_request_history_table', [AdminController::class, 'personal_request_history_table'])->name('personal_request_history_table');
        Route::get('/personal_request_history_data', [AdminController::class, 'personal_request_history_data'])->name('personal_request_history_data');
        Route::post('/remark_form', [AdminController::class, 'remark_form'])->name('remark_form');
        Route::post('/remark_personal', [AdminController::class, 'remark_personal'])->name('remark_personal');
        //Weekly Report
        Route::get('/weekly_report/{staff_id}', [AdminController::class, 'weekly_report'])->name('weekly_report');
        Route::get('/view_weekly_report/{staff_id}/{date}', [AdminController::class, 'view_weekly_report'])->name('view_weekly_report');
        Route::get('/staff_report', [AdminController::class, 'staff_report'])->name('staff_report');
        Route::get('/completed_task_history', [AdminController::class, 'completed_task_history'])->name('completed_task_history');
        Route::get('/hold_tasks', [AdminController::class, 'hold_tasks'])->name('admin.hold_tasks');
        Route::get('/admin_hold_tasks_data', [AdminController::class, 'admin_hold_tasks_data'])->name('admin_hold_tasks_data');
        Route::post('/update_profile', [AdminController::class, 'update_profile'])->name('update_profile');
        Route::get('today_present', [AdminController::class, 'today_present'])->name('today_present');
        Route::get('/today_in_progress', [AdminController::class, 'today_in_progress'])->name('today_in_progress');
        Route::get('/today_in_progress_data', [AdminController::class, 'today_in_progress_data'])->name('today_in_progress_data');
        Route::get('/in_progress/{project_id}', [AdminController::class, 'in_progress'])->name('in_progress');
        Route::get('/completed/{project_id}', [AdminController::class, 'completed'])->name('completed');
        Route::get('/hold/{project_id}', [AdminController::class, 'hold'])->name('hold');
        Route::get('/reopen/{staff_id}', [AdminController::class, 'reopen'])
            ->name('reopen');
        Route::get('/reopen_data/{staff_id}', [AdminController::class, 'reopen_data'])
            ->name('reopen_data');
        Route::post('/mark-feedback-seen', [AdminController::class, 'mark_feedback_seen'])->name('mark_feedback_seen');
        Route::get('seen_feedbacks', [AdminController::class, 'seen_feedbacks'])->name('seen_feedbacks');
        Route::get(
            'project_reopen/{projectId}',
            [AdminController::class, 'project_reopen']
        )->name('project_reopen');
        Route::get(
            'projectReopenData/{projectId}',
            [AdminController::class, 'projectReopenData']
        )->name('projectReopenData');
        Route::get('wfh_staff', [AdminController::class, 'wfh_staff'])->name('wfh_staff');
        Route::get('today_wfh_employee_data', [AdminController::class, 'today_wfh_employee_data'])->name('today_wfh_employee_data');
        Route::get('completed_staff/{staff_id}', [AdminController::class, 'completed_staff'])->name('completed_staff');
        Route::get('completed_staff_data/{staff_id}', [AdminController::class, 'completed_staff_data'])->name('completed_staff_data');
        Route::get('not_inprogress', [AdminController::class, 'not_inprogress'])->name('not_inprogress');
        Route::get('admin_not_inprogress_tasks_data', [AdminController::class, 'admin_not_inprogress_tasks_data'])->name('admin_not_inprogress_tasks_data');
        Route::post('inprogress_notification', [AdminController::class, 'inprogress_notification'])->name('inprogress_notification');
        Route::get('hold_task_staff', [AdminController::class, 'hold_task_staff'])->name('hold_task_staff');
        Route::get('pending_task_employee', [AdminController::class, 'pending_task_employee'])->name('pending_task_employee');
        Route::get('absent_staff', [AdminController::class, 'absent_staff'])->name('absent_staff');
        Route::get('today_absent_employee_data', [AdminController::class, 'today_absent_employee_data'])->name('today_absent_employee_data');
        Route::get('inactive_employees', [AdminController::class, 'inactive_employees'])->name('inactive_employees');
        Route::get('inactive_employee_data', [AdminController::class, 'inactive_employee_data'])->name('inactive_employee_data');
        Route::get('monthly_report', [AdminController::class, 'monthly_report'])->name('monthly_report');
        Route::get('monthly_project_report', [AdminController::class, 'monthly_project_report'])->name('monthly_project_report');
        Route::post('/assign-tester', [AdminController::class, 'assignTester'])->name('assign_tester');
        //Candidates
        Route::get('/edit_candidates/{id}', [HrController::class, 'edit_candidates'])->name('edit_candidates');
       Route::get('/candidates_form', [HrController::class, 'candidates_form'])->name('candidates_form');
        Route::get('/candidates', [HrController::class, 'candidates'])->name('admin.candidates');
        Route::get('/view_status/{id}', [HrController::class, 'view_status'])->name('view_status');
        // Route::get('view_doc/{id}', [AdminController::class, 'view_doc'])->name('view_doc');
        Route::get('admin/project-documents/{id?}', [ProjectServiceController::class, 'project_documents'])
            ->name('project.documents');
        Route::get('add_document/{id?}', [AdminController::class, 'add_document'])->name('add_document');
        Route::get('view_doc/{project_id?}', [AdminController::class, 'view_doc'])->name('view_doc');
        Route::post('store_document', [ProjectServiceController::class, 'store_document'])->name('store_document');
        Route::get('view_credentials/{project_id?}', [AdminController::class, 'view_credentials'])
            ->name('view_credentials');
        //credentials
        Route::get('upload_credentials/{id?}', [AdminController::class, 'upload_credentials'])->name('upload_credentials');
        Route::post('store_credential', [ProjectServiceController::class, 'store_credential'])->name('store_credential');
        Route::get('/credentials-data', [ProjectServiceController::class, 'credentialsData'])->name('credentials.data');
        Route::get('/edit_credential/{id}', [ProjectServiceController::class, 'edit_credential'])->name('edit_credential');
        Route::post('delete_document', [ProjectServiceController::class, 'delete_document'])->name('delete_document');
        Route::post('/update_credential/{id}', [ProjectServiceController::class, 'update_credential'])->name('update_credential');
        Route::post('delete_credentials', [ProjectServiceController::class, 'delete_credentials'])->name('delete_credentials');
        //bug
        Route::get('bug_report/{project_id}', [AdminBugController::class, 'bug_report'])->name('admin.bug_report');
        Route::get('add_bug/{project_id}', [AdminBugController::class, 'add_bug'])->name('admin.add_bug');
        Route::get('view_bug_details/{id}', [AdminBugController::class, 'view_bug_details'])->name('admin.view_bug_details');
        Route::get('admin_bug_report_data/{project_id}', [AdminBugController::class, 'admin_bug_report_data'])->name('admin_bug_report_data');
        Route::post('admin_create_bug', [AdminBugController::class, 'admin_create_bug'])->name('admin_create_bug');
        Route::post('/reopen-bug', [AdminBugController::class, 'reopenBug'])->name('admin_reopen_bug');
        Route::post('/update-bug-status', [AdminBugController::class, 'update_bug_status'])->name('admin_update_bug_status');
        Route::get('edit_bug/{id}', [AdminBugController::class, 'edit_bug'])->name('admin_edit_bug');
        Route::post('update_bug/{id}', [AdminBugController::class, 'update_bug'])->name('update_bug');
        Route::post('delete_bug', [AdminBugController::class, 'delete_bug'])->name('delete_bug');
        Route::get('getModuleBugs/{module_id}', [AdminBugController::class, 'getModuleBugs'])
            ->name('admin.get_module_bugs');


        //Pradeebha 
        Route::post('/add_popup_manager', [PopupController::class, 'add_popup_manager'])->name('add_popup_manager');
        Route::get('/getPopups', [PopupController::class, 'getPopups'])->name('getPopups');
        Route::post('/add_candidate', [CandidateController::class, 'add_candidate'])->name('add_candidate');
        Route::get('/candidate_list', [CandidateController::class, 'candidate_list'])->name('candidate_list');
        Route::post('/update_call_status', [CandidateController::class, 'update_call_status'])->name('update_call_status');
        Route::get('/view_status_data/{id}', [CandidateController::class, 'view_status_data'])->name('view_status_data');
        Route::post('/update_candidate', [CandidateController::class, 'update_candidate'])->name('update_candidate');
        Route::post('/update_popup_status', [PopupController::class, 'update_popup_status'])->name('update_popup_status');

        Route::get('/common_expenses', [ExpensesController::class, 'common_expenses'])->name('admin.common_expenses');
        Route::get('/project_expenses', [ExpensesController::class, 'project_expenses'])->name('admin.project_expenses');
        Route::post('/add_common_expenses',[ExpensesController::class,'add_common_expenses'])->name('add_common_expenses');
        Route::get('/common_expenses_data', [ExpensesController::class, 'common_expenses_data'])->name('common_expenses_data');
        Route::post('/add_project_expenses',[ExpensesController::class,'add_project_expenses'])->name('add_project_expenses');
        Route::get('/project_expenses_data', [ExpensesController::class, 'project_expenses_data'])->name('project_expenses_data');

    });
});
//Staff Routes
Route::prefix('staff')->group(function () {
    Route::get('/login', [StaffController::class, 'login'])->name('login');
    Route::post('/staff_login_sendotp', [AuthenticationController::class, 'staff_login_sendotp'])->name('staff_login_sendotp');
    Route::post('/staff_login_verifyotp', [AuthenticationController::class, 'staff_login_verifyotp'])->name('staff_login_verifyotp');
    Route::get('/forget_password', [StaffController::class, 'forget_password'])->name('forget_password');
    Route::get('/change_password', [StaffController::class, 'change_password'])->name('change_password');
    Route::post('/staff_forget_sendotp', [AuthenticationController::class, 'staff_forget_sendotp'])->name('staff_forget_sendotp');
    Route::post('/staff_forget_verifyotp', [AuthenticationController::class, 'staff_forget_verifyotp'])->name('staff_forget_verifyotp');
    Route::middleware('StaffLogin')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
        Route::get('/staff_reset_password', [StaffController::class, 'staff_reset_password'])->name('staff_reset_password');
        Route::post('/staff_reset_password_form', [AuthenticationController::class, 'staff_reset_password_form'])->name('staff_reset_password_form');
        Route::get('/profile', [StaffController::class, 'profile'])->name('profile');
        Route::get('/staff_logout', [AuthenticationController::class, 'staff_logout'])->name('staff_logout');

        Route::get('/table_mail', [StaffController::class, 'table_mail'])->name('table_mail');
        Route::get('/staff/mail_report_list', [StaffController::class, 'mail_report_list'])->name('mail_report_list');
        Route::get('/read_mail/{id}', [StaffController::class, 'read_mail'])->name('read_mail');
        Route::post('/store-communication-reply/{id}', [StaffController::class, 'store_communication_reply'])->name('store_communication_reply');
        // Route::get('/staff_task', [StaffController::class, 'staff_task'])->name('staff_task');


        //task
        Route::get('/staff_task', [StaffController::class, 'staff_task'])->name('staff_task');
        Route::get('/pending_task_table', [StaffController::class, 'pending_task_table'])->name('pending_task_table');
        Route::get('/pending_task_table_data', [StaffController::class, 'pending_task_table_data'])->name('pending_task_table_data');
        Route::get('/today_task', [StaffController::class, 'today_task'])->name('today_task');
        Route::get('/today_task_data', [StaffController::class, 'today_task_data'])->name('today_task_data');
        Route::get('/completed_task', [StaffController::class, 'completed_task'])->name('completed_task');
        Route::get('/completed_task_data', [StaffController::class, 'completed_task_data'])->name('completed_task_data');
        Route::get('/developer_completed_task', [StaffController::class, 'developer_completed_task'])->name('developer_completed_task');
        Route::get('/developer_task_description/{task_id}', [StaffController::class, 'developer_task_description'])->name('developer_task_description');
        Route::get('/developer_task_table_data', [StaffController::class, 'developer_task_table_data'])->name('developer_task_table_data');
        Route::get('/hold_tasks', [StaffController::class, 'hold_tasks'])->name('hold_tasks');
        Route::get('/hold_tasks_data', [StaffController::class, 'hold_tasks_data'])->name('hold_tasks_data');
        Route::get('/view_staff_hold_history/{task_id}', [StaffController::class, 'view_staff_hold_history'])->name('view_staff_hold_history');
        Route::get('/view_staff_hold_history_data/{task_id}', [StaffController::class, 'view_staff_hold_history_data'])->name('view_staff_hold_history_data');
        Route::get('/view_staff_reopen_history/{task_id}', [StaffController::class, 'view_staff_reopen_history'])->name('view_staff_reopen_history');
        Route::get('/view_staff_reopen_history_data/{task_id}', [StaffController::class, 'view_staff_reopen_history_data'])->name('view_staff_reopen_history_data');
        Route::get('/task_descriptions/{task_id}', [StaffController::class, 'task_descriptions'])->name('task_descriptions');
        Route::post('/verify_test_status', [StaffController::class, 'verify_test_status'])->name('task_verify_test_status');
        Route::post('/submit_reopen_status', [StaffController::class, 'submit_reopen_status'])->name('task_submit_reopen_status');
        Route::get('/testing_completed_tasks', [StaffController::class, 'testing_completed_tasks'])->name('testing_completed_tasks');
        Route::get('/testing_task_table_data', [StaffController::class, 'testing_task_table_data'])->name('testing_task_table_data');
        //support
        Route::get('/view_feedbacks/{id}', [StaffController::class, 'view_feedbacks'])->name('view_feedbacks');
        Route::get('/common_support', [StaffController::class, 'common_support'])->name('common_support');
        Route::get('/common_support_data', [StaffController::class, 'common_support_data'])->name('common_support_data');
        Route::get('/personal_request', [StaffController::class, 'personal_request'])->name('personal_request');
        Route::get('/personal_request_data', [StaffController::class, 'personal_request_data'])->name('personal_request_data');
        //attendance
        Route::get('/attendance_dashboard', [StaffController::class, 'attendance_dashboard'])->name('attendance_dashboard');
        Route::get('/attendance_dashboard_data', [StaffController::class, 'attendance_dashboard_data'])->name('attendance_dashboard_data');
        Route::get('/attendance', [StaffController::class, 'attendance'])->name('attendance');
        Route::post('/check_in', [StaffController::class, 'check_in'])->name('check_in');
        //wfh
        Route::get('/wfh', [StaffController::class, 'wfh'])->name('wfh');
        Route::get('/wfh_data', [StaffController::class, 'wfh_data'])->name('wfh_data');
        Route::post('/request_wfh', [StaffController::class, 'request_wfh'])->name('request_wfh');
        // leave_request
        Route::get('/leave_request', [StaffController::class, 'leave_request'])->name('leave_request');
        Route::get('/leave_request_data', [StaffController::class, 'leave_request_data'])->name('leave_request_data');
        Route::post('/request_leave', [StaffController::class, 'request_leave'])->name('request_leave');
        // permission
        Route::get('/permission', [StaffController::class, 'permission'])->name('permission');
        Route::get('/permission_data', [StaffController::class, 'permission_data'])->name('permission_data');
        Route::post('/request_permission', [StaffController::class, 'request_permission'])->name('request_permission');
        Route::get('/task_inprogress', [StaffController::class, 'task_inprogress'])->name('task_inprogress');
        Route::get('/task_inprogress_data', [StaffController::class, 'task_inprogress_data'])->name('task_inprogress_data');

        //add_request
        Route::post('/add_request', [StaffController::class, 'add_request'])->name('add_request');
        Route::post('/add_support', [StaffController::class, 'add_support'])->name('add_support');
        Route::post('/update_task_status', [StaffController::class, 'update_task_status'])->name('update_task_status');
        Route::post('/hold_status', [StaffController::class, 'hold_status'])->name('hold_status');
        Route::get('/daily_login', [StaffController::class, 'daily_login'])->name('daily_login');
        Route::post('/daily_login_form', [StaffController::class, 'daily_login_form'])->name('daily_login_form');
        Route::get('/final_logout', [StaffController::class, 'final_logout'])->name('final_logout');
        Route::post('/final_logout_form', [StaffController::class, 'final_logout_form'])->name('final_logout_form');
        Route::post('/resume_task', [StaffController::class, 'resume_task'])->name('resume_task');
        Route::post('/update_profile_staff', [StaffController::class, 'update_profile_staff'])->name('update_profile_staff');
        // reminder
        Route::get('/reminder', [StaffController::class, 'reminder'])->name('reminder');
        Route::get('/reminder_data', [StaffController::class, 'reminder_data'])->name('staff_reminder_data');
        Route::get('/create_reminder', [StaffController::class, 'create_reminder'])->name('staff_create_reminder');
        Route::post('/add_reminder', [StaffController::class, 'add_reminder'])->name('staff_add_reminder');
        Route::post('/delete_reminder', [StaffController::class, 'delete_reminder'])->name('staff_delete_reminder');
        Route::post('/staff_complete_reminder', [StaffController::class, 'staff_complete_reminder'])->name('staff_complete_reminder');
        // performance_tracker
        Route::get('/performance_tracker', [PerformanceTrackerController::class, 'performance_tracker'])->name('performance_tracker');
        Route::get('/performance_tracker_data', [PerformanceTrackerController::class, 'performance_tracker_data'])->name('performance_tracker_data');
        // report
        Route::get('/report', [StaffController::class, 'report'])->name('staff.report');
        Route::get('/report_data', [StaffController::class, 'report_data'])->name('report_data');
        Route::get('/view_report/{staff_id}/{date}', [StaffController::class, 'view_report'])->name('view_report');
        //break
        Route::post('/break_start', [StaffController::class, 'break_start'])->name('break_start');
        Route::post('/break_end', [StaffController::class, 'break_end'])->name('break_end');
        //    feedback_list
        Route::get('/feedback_list', [StaffController::class, 'feedback_list'])->name('feedback_list');
        Route::get('/feed_back_submit', [StaffController::class, 'feed_back_submit'])->name('feed_back_submit');
        Route::post('/add_feedback', [StaffController::class, 'add_feedback'])->name('add_feedback');
        Route::get('feedback_data', [StaffController::class, 'feedback_data'])->name('feedback_data');
        //bug_report
        Route::get('/bug_report/{project_id}', [BugController::class, 'bug_report'])->name('bug_report');
        Route::get('/add_bug/{project_id}', [StaffController::class, 'add_bug'])->name('add_bug');
        Route::post('create_bug', [BugController::class, 'create_bug'])->name('create_bug');
        Route::get('/bug_report_data/{project_id}', [BugController::class, 'bug_report_data'])->name('bug_report_data');
        Route::get('/view_bug_detail/{id}', [StaffController::class, 'view_bug_detail'])->name('view_bug_detail');
        Route::post('/reopen-bug', [BugController::class, 'reopenBug'])->name('reopen.bug');
        Route::post('/update-bug-status', [BugController::class, 'update_bug_status'])->name('update_bug_status');
        Route::get('/edit_bug', [StaffController::class, 'edit_bug'])->name('staff.edit_bug');
        Route::get('getModuleBugs/{module_id}', [BugController::class, 'getModuleBugs'])
            ->name('staff.get_module_bugs');
        //Break table
        Route::get('break_report', [StaffController::class, 'break_report'])->name('break_report');
        Route::get('/break_report_data', [StaffController::class, 'break_report_data'])
            ->name('break_report_data');
        //bank
        Route::get('bank_details', [StaffController::class, 'bank_details'])->name('bank_details');
        Route::post('add_kyc_details', [UserDetailsController::class, 'add_kyc_details'])->name('add_kyc_details');

        
        //pradeebha
        Route::post('/popupNoted/{id}', [PopupController::class, 'popupNoted'])->name('popupNoted');

        Route::post('/popupDone/{id}', [PopupController::class, 'popupDone'])->name('popupDone');
    });
});
//intern
Route::prefix('intern')->group(function () {
    Route::get('/login', [InternController::class, 'login'])->name('intern.login');
    Route::post('/intern_login_form', [InternController::class, 'intern_login_form'])->name('intern_login_form');
    Route::middleware('InternLogin')->group(function () {
        Route::get('/intern_logout', [InternController::class, 'intern_logout'])->name('intern_logout');
        Route::get('/intern_reset_password', [InternController::class, 'intern_reset_password'])->name('intern_reset_password');
        Route::post('/intern_reset_password_form', [InternController::class, 'intern_reset_password_form'])->name('intern_reset_password_form');
        Route::get('/dashboard', [InternController::class, 'dashboard'])->name('intern.dashboard');
        //Task
        Route::get('/intern_task', [InternController::class, 'intern_task'])->name('intern_task');
        Route::get('/inter_new_task', [InternController::class, 'inter_new_task'])->name('inter_new_task');
        Route::get('/intern_new_task_data', [InternController::class, 'intern_new_task_data'])->name('intern_new_task_data');
        Route::post('/update_student_task_status', [InternController::class, 'update_student_task_status'])->name('update_student_task_status');
        Route::get('/intern_task_description/{task_id}', [InternController::class, 'intern_task_description'])->name('intern_task_description');
        Route::get('/completed_task_intern', [InternController::class, 'completed_task_intern'])->name('completed_task_intern');
        Route::get('/completed_task_intern_data', [InternController::class, 'completed_task_intern_data'])->name('completed_task_intern_data');
        Route::get('/hold_tasks_intern', [InternController::class, 'hold_tasks_intern'])->name('hold_tasks_intern');
        Route::get('/hold_tasks_intern_data', [InternController::class, 'hold_tasks_intern_data'])->name('hold_tasks_intern_data');
        Route::post('/intern_resume_task', [InternController::class, 'intern_resume_task'])->name('intern_resume_task');
        //attendance
        Route::get('/attendance', [InternController::class, 'attendance'])->name('intern.attendance');
        Route::get('/attendance_data', [InternController::class, 'attendance_data'])->name('attendance_data');
        Route::post('/intern_check_in', [InternController::class, 'intern_check_in'])->name('intern_check_in');
        Route::post('/intern_check_out', [InternController::class, 'intern_check_out'])->name('intern_check_out');
    });
});
Route::prefix('hr')->group(function () {
    Route::get('/hr_login', action: [HrController::class, 'hr_login'])->name('hr_login');
    Route::post('/hr_login_sendotp', [AuthenticationController::class, 'hr_login_sendotp'])->name('hr_login_sendotp');
    Route::post('/hr_login_verifyotp', [AuthenticationController::class, 'hr_login_verifyotp'])->name('hr_login_verifyotp');
    Route::get('/forget_password', [HrController::class, 'forget_password'])->name('hr.forget_password');
    Route::get('/create_password', [HrController::class, 'create_password'])->name('hr.create_password');
    Route::get('/reset_password', [HrController::class, 'reset_password'])->name('hr.reset_password');
    Route::get('/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');
    // Route::get('/candidates_form', [HrController::class, 'candidates_form'])->name('candidates_form');
    // Route::get('/candidates', [HrController::class, 'candidates'])->name('hr.candidates');
    // Route::get('/edit_candidates', [HrController::class, 'edit_candidates'])->name('edit_candidates');
    Route::get('/follow_up', [HrController::class, 'follow_up'])->name('follow_up');
    Route::get('/candidate_follow_up', [HrController::class, 'candidate_follow_up'])->name('candidate_follow_up');
    Route::get('/follow_up_table', [HrController::class, 'follow_up_table'])->name('follow_up_table');
});
