<?php
Route::get('regenerateStaffUtilizationChart', 'DashboardController@regenerateStaffUtilizationChart');

Route::get('/MasterGantt', function () {
    return view('gantt/gantt');
});
Route::get('/GanttGrid', function () {
    return view('gantt/grid');
});
Route::get('/GanttScheduler', function () {
    return view('gantt/scheduler');
});

Route::match(['get', 'post'], '/grid_data', "GridController@data");
Route::match(['get', 'post'], '/gantt_data', "GanttController@data");
Route::match(['get', 'post'], '/scheduler_data', "SchedulerController@data");

Route::get('/upcomingDeadlines', 'ReportsController@upcomingDeadlines');
Route::get('/projectRoles', 'ReportsController@projectRoles');
Route::get('/exportExcel', 'ReportsController@exportExcel');

Route::get('calendar', 'CalendarController@getIndex');
Route::get('/calendar/UpcomingDeadlines', 'CalendarController@getUpcomingDeadlines');
Route::get('/modalcalendar', 'CalendarController@getModalIndex');
Route::get('calendar/showModal/{id}', 'CalendarController@getShowModal');

Route::get('/GenerateStaffEmail', 'EmailController@GenerateStaffEmail');

Route::match(['get', 'post'], '/gantt_data', "GanttController@data");
Route::group(['middleware' => 'auth'], function() {

//Route::get('/gantt', 'DashboardController@getGantt');
Route::match(['get', 'post'], '/gantt_data', "GanttController@data");

});

Route::match(['get', 'post'], 'staff/calendar/{id?}', 'StaffController@getCalendar');
Route::get('staff/schedule/{id?}', ['uses' =>'StaffController@getSchedule']);
Route::get('staff/assignments', ['uses' =>'StaffController@getAssignments']);
Route::get('staff/assignmentgantt', ['uses' =>'StaffController@getAssignmentGanttData']);


Route::match(['get', 'post'], 'staff/customshow/{id?}', function ($id) {

    $module = 'staffadmin';
    $info = App\Models\Staffadmin::makeInfo($module);

    $row = App\Models\Staffadmin::getRow($id);

    if($row){

        $this->data = array(
            'pageTitle'			=> 	$info['title'],
            'pageNote'			=>  $info['note'],
            'pageModule'		=> 'staffadmin',
            'row'               =>  $row
        );

        $deadlines = \DB::select( \DB::raw("SELECT tb_projects.name, tb_projects.short_name, tb_project_milestones.priority, tb_project_milestones.status, tb_project_milestones.id, tb_project_milestones.title, tb_project_milestones.description, tb_project_milestones.start_date, tb_project_milestones.end_date from tb_project_milestones
INNER JOIN tb_projects on tb_project_milestones.project_id = tb_projects.id
INNER JOIN tb_project_milestone_staff on tb_project_milestone_staff.milestone_id = tb_project_milestones.id
WHERE start_date <= (curdate() + interval 14 day) AND tb_project_milestone_staff.user_id = ".$id."
ORDER BY start_date") );

        $percentages = \DB::select( \DB::raw("SELECT tb_projects.id, tb_projects.name AS project, tb_projects.short_name, 
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_projects.id = tb_staff_group_assignments.project_id AND tb_staff_group_assignments.staff_id = ".$id.") AS 'hours_per_week', 
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = ".$id.") AS 'total_hours' FROM tb_staff_group_assignments
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
LEFT JOIN tb_projects on tb_staff_group_assignments.project_id = tb_projects.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = ".$id.") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_projects.id
UNION SELECT tb_staff_group_assignments.group_id, tb_staff_groups.group_name as project, tb_staff_groups.short_name,
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_groups.id = tb_staff_group_assignments.group_id AND tb_staff_group_assignments.staff_id = ".$id.") AS 'hours_per_week', 
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = ".$id.") AS 'total_hours' 
FROM tb_staff_groups 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.group_id=tb_staff_groups.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE NOT tb_staff_groups.project_specific AND (tb_staff.isactive = 1) AND tb_staff_group_assignments.mark_as_out = 0 AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_staff_groups.id") );

        $this->data['id'] = $id;
        $this->data['deadlines'] = $deadlines;
        $this->data['percentages'] = $percentages;

        //$this->data['access']		= $this->access;
        //$this->data['setting'] 		= $info['setting'];
        $this->data['fields'] 		= \AjaxHelpers::fieldLang($info['config']['grid']);
        $this->data['subgrid']		= (isset($info['config']['subgrid']) ? $info['config']['subgrid'] : array());
        return view('staffadmin.customview',$this->data)
            ->with('json_grouplist',json_encode($grouplist,JSON_NUMERIC_CHECK));

    } else {

        return response()->json(array(
            'status'=>'error',
            'message'=> \Lang::get('core.note_error')
        ));
    }
});

?>