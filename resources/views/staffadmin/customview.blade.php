<?php
use App\Library\MyHelpers;

$module = 'staffadmin';
$info = App\Models\Staffadmin::makeInfo($module);

$row = App\Models\Staffadmin::getRow($id);

if ($row) {

    $current_week_number = date("W");
    $current_year = date("Y");
    $current_week_range = MyHelpers::getStartAndEndDate($current_week_number, $current_year);
//$current_week_range = App\Library\MyHelpers::rangeWeek( date('Y-m-d') );
    $current_week_number_range = json_encode($current_week_number - 8, $current_week_number + 8);
    $current_week_options = "";

    $week_from_today = date("W", strtotime("+1 week"));
    $week_from_today_year = date("Y", strtotime("+1 week"));
    $next_week_range = MyHelpers::getStartAndEndDate($week_from_today, $week_from_today_year);

    //build date range options
    for ($x = $current_week_number - 8; $x <= $current_week_number + 8; $x++) {
        $week = MyHelpers::getStartAndEndDate($x, $current_year);
        //print_r($week);
        $current_week_options .= "<option value='" . $x . "'";
        if ($x == $current_week_number) {
            $current_week_options .= " selected='selected'";
        }
        $current_week_options .= ">" . date("m/d", strtotime($week[0])) . " - " . date("m/d/Y", strtotime($week[1])) . "</option>";
    }

    $this->data = array(
            'pageTitle' => $info['title'],
            'pageNote' => $info['note'],
            'pageModule' => 'staffadmin',
            'row' => $row
    );

    $projects = \DB::select(\DB::raw("SELECT tb_roles.role, tb_projects.name FROM tb_projects
INNER JOIN tb_project_staffing ON tb_project_staffing.project_id = tb_projects.id
INNER JOIN tb_roles ON tb_project_staffing.role_id = tb_roles.id
WHERE tb_project_staffing.staff_id = " . $id . " AND tb_projects.isactive = 1"));


    $assignments = \DB::select(\DB::raw("SELECT tb_projects.name FROM tb_projects
INNER JOIN tb_staff_group_assignments ON tb_staff_group_assignments.project_id = tb_projects.id
WHERE NOT ISNULL(tb_staff_group_assignments.project_id) AND tb_staff_group_assignments.staff_id = " . $id . " AND tb_projects.isactive = 1
GROUP BY tb_projects.name ORDER BY tb_projects.name"));

    $deadlines = \DB::select(\DB::raw("SELECT tb_projects.name, tb_projects.short_name, tb_project_milestones.priority, tb_project_milestones.status, tb_project_milestones.id, tb_project_milestones.title, tb_project_milestones.description, tb_project_milestones.start_date, tb_project_milestones.end_date from tb_project_milestones
INNER JOIN tb_projects on tb_project_milestones.project_id = tb_projects.id
INNER JOIN tb_project_milestone_staff on tb_project_milestone_staff.milestone_id = tb_project_milestones.id
WHERE start_date <= (curdate() + interval 14 day) AND tb_project_milestone_staff.user_id = " . $id . "
ORDER BY start_date"));

    $this_week = \DB::select(\DB::raw("SELECT tb_projects.id, tb_projects.name AS project, tb_projects.short_name,
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_projects.id = tb_staff_group_assignments.project_id AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (CURDATE( ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (CURDATE( ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'hours_per_week',
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (CURDATE( ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (CURDATE( ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'total_hours' FROM tb_staff_group_assignments
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
LEFT JOIN tb_projects on tb_staff_group_assignments.project_id = tb_projects.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (CURDATE( ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (CURDATE( ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_projects.id
UNION SELECT tb_staff_group_assignments.group_id, tb_staff_groups.group_name as project, tb_staff_groups.short_name,
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_groups.id = tb_staff_group_assignments.group_id AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (CURDATE( ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (CURDATE( ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'hours_per_week',
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (CURDATE( ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (CURDATE( ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') ) ) AS 'total_hours'
FROM tb_staff_groups
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.group_id = tb_staff_groups.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE NOT tb_staff_groups.project_specific AND (tb_staff.isactive = 1)
AND tb_staff_group_assignments.mark_as_out = 0
AND NOT ISNULL(tb_staff_group_assignments.hours_per_week)
AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.hours_per_week > 0)
AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (CURDATE( ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (CURDATE( ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_staff_groups.id"));
    //print_r($this_week); exit();

    $next_week = \DB::select(\DB::raw("SELECT tb_projects.id, tb_projects.name AS project, tb_projects.short_name,
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_projects.id = tb_staff_group_assignments.project_id AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'hours_per_week',
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'total_hours' FROM tb_staff_group_assignments
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
LEFT JOIN tb_projects on tb_staff_group_assignments.project_id = tb_projects.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_projects.id
UNION SELECT tb_staff_group_assignments.group_id, tb_staff_groups.group_name as project, tb_staff_groups.short_name,
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_groups.id = tb_staff_group_assignments.group_id AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'hours_per_week',
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') ) ) AS 'total_hours'
FROM tb_staff_groups
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.group_id = tb_staff_groups.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE NOT tb_staff_groups.project_specific AND (tb_staff.isactive = 1)
AND tb_staff_group_assignments.mark_as_out = 0
AND NOT ISNULL(tb_staff_group_assignments.hours_per_week)
AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.hours_per_week > 0)
AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +1 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_staff_groups.id"));

    $two_weeks = \DB::select(\DB::raw("SELECT tb_projects.id, tb_projects.name AS project, tb_projects.short_name,
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_projects.id = tb_staff_group_assignments.project_id AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'hours_per_week',
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'total_hours' FROM tb_staff_group_assignments
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
LEFT JOIN tb_projects on tb_staff_group_assignments.project_id = tb_projects.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_projects.id
UNION SELECT tb_staff_group_assignments.group_id, tb_staff_groups.group_name as project, tb_staff_groups.short_name,
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_groups.id = tb_staff_group_assignments.group_id AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )) AS 'hours_per_week',
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') ) ) AS 'total_hours'
FROM tb_staff_groups
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.group_id = tb_staff_groups.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE NOT tb_staff_groups.project_specific AND (tb_staff.isactive = 1)
AND tb_staff_group_assignments.mark_as_out = 0
AND NOT ISNULL(tb_staff_group_assignments.hours_per_week)
AND tb_staff_group_assignments.staff_id = " . $id . " AND (tb_staff_group_assignments.hours_per_week > 0)
AND (tb_staff_group_assignments.group_id <> 0) AND (tb_staff.id = " . $id . ") AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (DATE_ADD(CURDATE(), INTERVAL +2 WEEK) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_staff_groups.id"));

    $software = \DB::select(\DB::raw("SELECT tb_software.software, (SELECT tb_proficiency_levels.level FROM tb_proficiency_levels WHERE tb_staff_software.proficiency = tb_proficiency_levels.id ) as proficiency,  learn, note
FROM tb_software
LEFT JOIN tb_staff_software ON tb_staff_software.software_id = tb_software.id
WHERE tb_software.active = 1 AND (tb_staff_software.staff_id = ".$id." OR isnull(tb_staff_software.staff_id))
ORDER BY software"));

    $skills = \DB::select(\DB::raw("SELECT skill, (SELECT tb_skill_levels.level FROM tb_skill_levels WHERE tb_skill_levels.id = tb_staff_skills.proficiency) as proficiency, learn from tb_skills
LEFT JOIN tb_staff_skills ON tb_staff_skills.skill_id = tb_skills.id
WHERE (tb_staff_skills.staff_id = ".$id." OR isnull(tb_staff_skills.staff_id))
ORDER BY skill"));

    $notes = \DB::select(\DB::raw("SELECT note, dt_created FROM tb_staff_notes ORDER BY dt_created DESC"));

    //$this->data['id'] = $id;
    //$this->data['deadlines'] = $deadlines;
    //$this->data['percentages'] = $percentages;

    //$this->data['access']		= $this->access;
    //$this->data['setting'] 		= $info['setting'];
    //$this->data['fields'] 		= \AjaxHelpers::fieldLang($info['config']['grid']);
    //$this->data['subgrid']		= (isset($info['config']['subgrid']) ? $info['config']['subgrid'] : array());
    //return view('staffadmin.customview',$this->data)
    //->with('json_grouplist',json_encode($grouplist,JSON_NUMERIC_CHECK));

} else {

    return response()->json(array(
            'status' => 'error',
            'message' => \Lang::get('core.note_error')
    ));
}

?>

<div class="sbox">
    <div class="sbox-title">
        <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
            <small>{{ $pageNote }}</small>
            <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
               onclick="ajaxViewClose('#{{ $pageModule }}')">
                <i class="fa fa fa-times"></i></a>

        </h4>
    </div>

    <div class="sbox-content">

        <div class="tab-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab" aria-expanded="true">General</a></li>
                <li class=""><a href="#calendar" data-toggle="tab" aria-expanded="false">Calendar</a></li>
                <li class=""><a href="#description" data-toggle="tab" aria-expanded="false">Job Description</a></li>
                <li class=""><a href="#aspirations" data-toggle="tab" aria-expanded="false">Aspirations</a></li>
                <li class=""><a href="#software" data-toggle="tab" aria-expanded="false">Software & Skills</a></li>
                <li class=""><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
            </ul>
            <div class="tab-content">
                <!-- General -->
                <div class="tab-pane use-padding active" id="general">

                    <div class="row m-t">

                        <div class="col-md-6">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <th colspan="2">Details</th>
                                </tr>

                                <tr>
                                    <td width='30%'
                                        class='label-view text-right'>{{ SiteHelpers::activeLang('First Name', (isset($fields['first_name']['language'])? $fields['first_name']['language'] : array())) }}</td>
                                    <td>{{ $row->first_name}} </td>
                                </tr>

                                <tr>
                                    <td width='30%'
                                        class='label-view text-right'>{{ SiteHelpers::activeLang('Last Name', (isset($fields['last_name']['language'])? $fields['last_name']['language'] : array())) }}</td>
                                    <td>{{ $row->last_name}} </td>
                                </tr>

                                <tr>
                                    <td width='30%'
                                        class='label-view text-right'>{{ SiteHelpers::activeLang('Primary Group', (isset($fields['group_id']['language'])? $fields['group_id']['language'] : array())) }}</td>
                                    <td>{{ SiteHelpers::formatLookUp($row->group_id,'group_id','1:tb_staff_groups:id:group_name') }} </td>
                                </tr>

                                <tr>
                                    <td width='30%'
                                        class='label-view text-right'>{{ SiteHelpers::activeLang('Active', (isset($fields['isactive']['language'])? $fields['isactive']['language'] : array())) }}</td>
                                    <td>{{ SiteHelpers::formatLookUp($row->isactive,'isactive','1:tb_booleans:boo:value') }} </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">

                            <table class="table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <th colspan="2">Project Roles</th>
                                </tr>
                                @if ($projects)
                                    @foreach ($projects AS $project)
                                        <tr>
                                            <td width='30%' class='label-view text-right'> {{ $project->name }} </td>
                                            <td> {{ $project->role }} </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td width='30%' class='label-view text-right'> None</td>
                                        <td></td>
                                    </tr>
                                    @endif

                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <th colspan="2">Assignments</th>
                                </tr>
                                @if ($assignments)
                                    @foreach ($assignments AS $assignment)
                                        <tr>
                                            <td width='30%' class='label-view text-right'> {{ $assignment->name }} </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td width='30%' class='label-view text-right'> None</td>
                                        <td></td>
                                    </tr>
                                    @endif

                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row m-t">


                        <div class="col-lg-9">

                            <div class="row m-t">
                                <div class="col-lg-12">
                                    <div class="sbox">
                                        <div class="sbox-content">
                                            <div class="sbox-title">
                                                Week:&nbsp;<select
                                                        id="staff-utilization-select-week"><?php echo $current_week_options; ?></select>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="sbox">

                                                    <div class="sbox-content" id="project-percentages-this-week"
                                                         style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto">

                                                    </div>
                                                    <div class="sbox-content" id="project-percentages-this-week-table"
                                                         style="display:none; min-width: 310px; height: auto; max-width: 600px; margin: 0 auto">
                                                        <table style="width:100%">
                                                            <tr>
                                                                <th>Project/Group</th>
                                                                <th align='center'>Percent</th>
                                                                <th align='center'>Hours/Week</th>
                                                            </tr>
                                                            <?php
                                                            foreach ($this_week as $percentage) {
                                                                $hpw = ($percentage->hours_per_week / $percentage->total_hours) * 100;
                                                                echo("<tr><td><strong>" . $percentage->project . "</strong></td><td align='center'>" . number_format($hpw, 2) . "%</td><td align='center'>" . $percentage->hours_per_week . "</td></tr>");
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>

                                                </div><!-- /sbox -->
                                            </div><!-- </div class="col-lg-4"> -->

                                            <div class="col-lg-4">
                                                <div class="sbox">

                                                    <div class="sbox-content" id="project-percentages-next-week"
                                                         style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                                    <div class="sbox-content" id="project-percentages-next-week-table"
                                                         style="display:none; min-width: 310px; height: auto; max-width: 600px; margin: 0 auto">
                                                        <table style="width:100%">
                                                            <tr>
                                                                <th>Group</th>
                                                                <th align='center'>Percent</th>
                                                                <th align='center'>Hours/Week</th>
                                                            </tr>
                                                            <?php
                                                            foreach ($next_week as $percentage) {
                                                                $hpw = ($percentage->hours_per_week / $percentage->total_hours) * 100;
                                                                echo("<tr><td><strong>" . $percentage->project . "</strong></td><td align='center'>" . number_format($hpw, 2) . "%</td><td align='center'>" . $percentage->hours_per_week . "</td></tr>");
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>

                                                </div><!-- /sbox -->
                                            </div><!-- </div class="col-lg-4"> -->

                                            <div class="col-lg-4">
                                                <div class="sbox">

                                                    <div class="sbox-content" id="project-percentages-two-weeks"
                                                         style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                                    <div class="sbox-content" id="project-percentages-two-weeks-table"
                                                         style="display:none; min-width: 310px; height: auto; max-width: 600px; margin: 0 auto">
                                                        <table style="width:100%">
                                                            <tr>
                                                                <th>Group</th>
                                                                <th align='center'>Percent</th>
                                                                <th align='center'>Hours/Week</th>
                                                            </tr>
                                                            <?php
                                                            foreach ($two_weeks as $percentage) {
                                                                $hpw = ($percentage->hours_per_week / $percentage->total_hours) * 100;
                                                                echo("<tr><td><strong>" . $percentage->project . "</strong></td><td align='center'>" . number_format($hpw, 2) . "%</td><td align='center'>" . $percentage->hours_per_week . "</td></tr>");
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>

                                                </div><!-- /sbox -->
                                            </div><!-- </div class="col-lg-4"> -->

                                            <div class="col-lg-4">
                                                <div class="sbox">
                                                    <!--
                                                     <div class="sbox-title">
                                                         <span class="label label-warning pull-right">Resource Type Percentages</span>
                                                         <h5>Title</h5>
                                                     </div>-->
                                                    <div class="sbox-content" id="project-status-percentages"
                                                         style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto">

                                                    </div>

                                                </div><!-- /sbox -->
                                            </div><!-- </div class="col-lg-4"> -->

                                            <div id="staff-utilization-updated" class="m-t-md">
                                                <small class="pull-right">
                                                    <i class="fa fa-clock-o"> </i>
                                                    Updated
                                                    on <?php date_default_timezone_set('America/New_York'); echo date('m-d-Y h:i A'); ?>
                                                </small>
                                                <!--here-->
                                            </div>
                                        </div><!-- /sbox-content -->


                                    </div><!-- /sbox -->
                                </div><!-- </div class="col-lg-12"> -->
                            </div><!-- </div class="row-mt"> -->


                            <div class="row">

                            </div>

                        </div><!-- </div class="col-lg-9"> -->

                        <div class="col-lg-3" style="background-color:#ebebed; width:23.5%;">

                            <div class="" style="height: 425px;">

                                <h3>Upcoming Deadlines <span
                                            class="badge badge-info pull-right"><?php echo count($deadlines); ?></span>
                                </h3>

                                <?php
                                if ($deadlines) {
                                foreach ($deadlines AS $deadline) { ?>
                                <div class="feed-activity-list">
                                    <div class="feed-element">
                                        <?php
                                        echo "<div style='margin-bottom:5px;'><span class='black-block'>" . $deadline->name . "</span>";
                                        $members = \DB::select(\DB::raw("SELECT tb_staff.id, CONCAT(LEFT(tb_staff.first_name,1), LEFT(tb_staff.last_name,1)) AS initials, tb_staff_groups.color
                                                    FROM tb_project_milestone_staff
                                                    INNER JOIN tb_staff ON tb_project_milestone_staff.user_id=tb_staff.id
                                                    INNER JOIN tb_staff_groups ON tb_staff.group_id = tb_staff_groups.id
                                                    WHERE tb_project_milestone_staff.milestone_id=1"));
                                        foreach ($members AS $member) {
                                            echo "<span class='' style='width:30px; border-radius: 50%; margin:3px; padding:3px;color: #FFF; font-size:12px; font-weight:700; background-color:" . $member->color . "'>" . $member->initials . "</span>";
                                        }
                                        echo "</div>";
                                        ?>
                                        <div>
                                            <small <?php if ($deadline->priority == 1) {
                                                echo "style='font-weight:bold;'";
                                            } ?>><?php echo $deadline->title; ?></small><?php if ($deadline->priority == 1) {
                                                echo "<small> (high priority)</small>";
                                            } ?>
                                            <br/>
                                            <small class="text-muted" <?php if (($deadline->start_date <= date('Y-m-d') && ($deadline->end_date == "0000:00:00")) || ($deadline->end_date <= date('Y-m-d'))) {
                                                echo "style='color:red;'";
                                            } ?>><?php echo $deadline->end_date ? date("m/d", strtotime($deadline->start_date)) : date("m/d/Y", strtotime($deadline->start_date)); ?> <?php echo $deadline->end_date ? " - " . date("m/d/Y", strtotime($deadline->end_date)) : ""; ?></small><?php
                                            if (($deadline->start_date <= date('Y-m-d') && ($deadline->end_date == "0000:00:00")) || ($deadline->end_date <= date('Y-m-d'))) {
                                                echo " <small style='color: red; font-weight:bold;'>&larr; OVERDUE</small>";
                                            } elseif (($deadline->start_date <= date('Y-m-d')) && ($deadline->end_date > date('Y-m-d'))) {
                                                echo " <small style='font-weight:bold;'>&larr; DUE</small>";
                                            } ?>
                                        </div>

                                    </div>
                                    <hr style="height:20px; margin:0px; padding:0px;"/>
                                </div><!-- </div class="feed-activity-list">  -->
                            <?php }
                            } else {
                                echo "None";
                            } ?>

                            <!--
                                    <div class="m-t-md">
                                        <h4>Title</h4>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.
                                        </p>
                                        <div class="row m-t-sm">
                                            <div class="col-md-6">
                                                <span class="bar" style="display: none;">5,3,9,6,5,9,7,3,5,2</span><svg class="peity" height="16" width="32"><rect fill="#1ab394" x="0" y="7.111111111111111" width="2.3" height="8.88888888888889"/><rect fill="#d7d7d7" x="3.3" y="10.666666666666668" width="2.3" height="5.333333333333333"/><rect fill="#1ab394" x="6.6" y="0" width="2.3" height="16"/><rect fill="#d7d7d7" x="9.899999999999999" y="5.333333333333334" width="2.3" height="10.666666666666666"/><rect fill="#1ab394" x="13.2" y="7.111111111111111" width="2.3" height="8.88888888888889"/><rect fill="#d7d7d7" x="16.5" y="0" width="2.3" height="16"/><rect fill="#1ab394" x="19.799999999999997" y="3.555555555555557" width="2.3" height="12.444444444444443"/><rect fill="#d7d7d7" x="23.099999999999998" y="10.666666666666668" width="2.3" height="5.333333333333333"/><rect fill="#1ab394" x="26.4" y="7.111111111111111" width="2.3" height="8.88888888888889"/><rect fill="#d7d7d7" x="29.7" y="12.444444444444445" width="2.3" height="3.5555555555555554"/></svg>
                                                <h5><strong>169</strong> Posts</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="line" style="display: none;">5,3,9,6,5,9,7,3,5,2</span><svg class="peity" height="16" width="32"><polygon fill="#1ab394" points="0 15 0 7.166666666666666 3.5555555555555554 10.5 7.111111111111111 0.5 10.666666666666666 5.5 14.222222222222221 7.166666666666666 17.77777777777778 0.5 21.333333333333332 3.833333333333332 24.888888888888886 10.5 28.444444444444443 7.166666666666666 32 12.166666666666666 32 15"/><polyline fill="transparent" points="0 7.166666666666666 3.5555555555555554 10.5 7.111111111111111 0.5 10.666666666666666 5.5 14.222222222222221 7.166666666666666 17.77777777777778 0.5 21.333333333333332 3.833333333333332 24.888888888888886 10.5 28.444444444444443 7.166666666666666 32 12.166666666666666" stroke="#169c81" stroke-width="1" stroke-linecap="square"/></svg>
                                                <h5><strong>28</strong> Orders</h5>
                                            </div>
                                        </div>
                                    </div>-->
                                <!--
                                <div class="m-t-md">
                                    <h4>Title</h4>
                                    <div>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <span class="badge badge-primary">16</span>
                                                General topic
                                            </li>
                                            <li class="list-group-item">
                                                <span class="badge badge-info">12</span>
                                                The generated Lorem
                                            </li>
                                            <li class="list-group-item">
                                                <span class="badge badge-warning">7</span>
                                                There are many variations
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                -->

                            </div><!-- </div style="height: 1196px;"> -->

                        </div><!-- </div class="col-lg-3" style="background-color:#ebebed;"> -->

                    </div>

                </div>
                <!-- eo General -->

                <div class="tab-pane use-padding" id="calendar">
                    <!-- Calendar -->
                    <div class="row m-t">

                        <div class="col-md-12">
                            <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
                                <div class="dhx_cal_navline">
                                    <div class="dhx_cal_prev_button">&nbsp;</div>
                                    <div class="dhx_cal_next_button">&nbsp;</div>
                                    <div class="dhx_cal_today_button"></div>
                                    <div class="dhx_cal_date"></div>
                                    <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                                    <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                                    <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
                                </div>
                                <div class="dhx_cal_header">
                                </div>
                                <div class="dhx_cal_data">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- eo Calendar -->
                </div>
                <div class="tab-pane use-padding" id="description">
                    <div class="row m-t">
                        <div class="col-md-12">
                            {!!   $row->job_description !!}
                        </div>
                    </div>
                </div>
                <div class="tab-pane use-padding" id="aspirations">
                    {!!   $row->aspirations !!}
                </div>
                <div class="tab-pane use-padding" id="software">

                    <div class="row m-t">

                        <div class="col-md-6">
                            <?php use \App\Http\Controllers\StaffController;
                            //echo StaffController::displaysoftwarebystaff($id); ?>

                            <h3>Software</h3>
                            <table class="table table-bordered table-striped">
                                <thead class="no-border">
                                <tr>
                                    <th style="width:50%;">Software</th>
                                    <th>Proficiency</th>
                                    <th class="text-center">Want to Learn</th>
                                </tr>
                                </thead>
                                <tbody class="no-border-y">
                                @foreach ($software as $soft)
                                    <tr>
                                        <td style="width:30%;">{{ $soft->software }}</td>
                                        <td>{!!  $soft->proficiency ? $soft->proficiency : "<span style='color: #999'>N/A</span>" !!}</td>
                                        <td class="text-center"><?php if ($soft->learn == 1) { echo "Yes"; } else { if ($soft->learn == 0 && $soft->learn != Null) { echo "No"; } else { echo "<span style='color: #999'>N/A</span>"; } }; ?></td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3>Skills</h3>
                            <table class="table table-bordered table-striped">
                                <thead class="no-border">
                                <tr>
                                    <th style="width:50%;">Skill</th>
                                    <th>Proficiency</th>
                                    <th class="text-center">Want to Learn</th>
                                </tr>
                                </thead>
                                <tbody class="no-border-y">
                                @foreach ($skills as $skill)
                                    <tr>
                                        <td style="width:30%;">{{ $skill->skill }}</td>
                                        <td>{!!  $skill->proficiency ? $skill->proficiency : "<span style='color: #999'>N/A</span>" !!}</td>
                                        <td class="text-center"><?php if ($skill->learn == 1) { echo "Yes"; } else { if ($skill->learn == 0 && $skill->learn != Null) { echo "No"; } else { echo "<span style='color: #999'>N/A</span>"; } }; ?></td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
                <div class="tab-pane use-padding" id="notes">
                    <div class="row m-t">

                        <div class="col-md-12">
                            <h3>Notes</h3>
                            <table class="table table-bordered table-striped">

                                <tbody class="no-border-y">
                                @foreach ($notes as $note)
                                <tr>
                                    <td style="width:30%;">{{ $note->dt_created }} - {{ $note->note }}</td>
                                </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
        </div>


        <script>
            $(document).ready(function () {

                function getMonday(d) {
                    d = new Date(d);
                    var day = d.getDay(),
                            diff = d.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
                    var e = new Date(d.setDate(diff));
                    return (e.getMonth() + 1) + '/' + e.getDate()
                }

                function getNextMonday(d) {
                    d = new Date(d);
                    var day = (d.getDay() - 7),
                            diff = d.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
                    var e = new Date(d.setDate(diff));
                    return (e.getMonth() + 1) + '/' + e.getDate()
                }

                function getSecondMonday(d) {
                    d = new Date(d);
                    var day = (d.getDay() - 14),
                            diff = d.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
                    var e = new Date(d.setDate(diff));
                    return (e.getMonth() + 1) + '/' + e.getDate()
                }

                $('#project-percentages-this-week').highcharts({
                    credits: {
                        enabled: false
                    },
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: 0,
                        plotShadow: false
                    },
                    title: {
                        text: 'This Week (' + getMonday(new Date()) + ')'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Hours/week: <b>{point.x}</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                formatter: function () {
                                    return parseFloat(this.point.percentage).toFixed(1) + "%";
                                },
                                distance: -30,
                                style: {
                                    fontWeight: 'bold',
                                    color: 'white',
                                    textShadow: '0px 1px 2px black'
                                }
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Percentage',
                        innerSize: '50%',
                        data: [
                            <?php
                            foreach ($this_week as $percentage) {
                                if ($percentage->hours_per_week) {
                                    $hpw = ($percentage->hours_per_week / $percentage->total_hours) * 100;
                                    echo("{name: '" . $percentage->project . "', y: " . $hpw . ", x: '" . $percentage->hours_per_week . "'}, ");
                                }
                            }
                            ?>
                        ]
                    }]

                });

                $('#project-percentages-next-week').highcharts({
                    credits: {
                        enabled: false
                    },
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: 0,
                        plotShadow: false
                    },
                    title: {
                        text: 'Next Week (' + getNextMonday(new Date()) + ')'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Hours/week: <b>{point.x}</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                formatter: function () {
                                    return parseFloat(this.point.percentage).toFixed(1) + "%";
                                },
                                distance: -30,
                                style: {
                                    fontWeight: 'bold',
                                    color: 'white',
                                    textShadow: '0px 1px 2px black'
                                }
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Percentage',
                        innerSize: '50%',
                        data: [
                            <?php
                            foreach ($next_week as $percentage) {
                                $hpw = ($percentage->hours_per_week / $percentage->total_hours) * 100;
                                echo("{name: '" . $percentage->project . "', y: " . $hpw . ", x: '" . $percentage->hours_per_week . "'}, ");
                            }
                            ?>
                        ]
                    }]

                });

                $('#project-percentages-two-weeks').highcharts({
                    credits: {
                        enabled: false
                    },
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: 0,
                        plotShadow: false
                    },
                    title: {
                        text: 'Two Weeks (' + getSecondMonday(new Date()) + ')'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Hours/week: <b>{point.x}</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                formatter: function () {
                                    return parseFloat(this.point.percentage).toFixed(1) + "%";
                                },
                                distance: -30,
                                style: {
                                    fontWeight: 'bold',
                                    color: 'white',
                                    textShadow: '0px 1px 2px black'
                                }
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Percentage',
                        innerSize: '50%',
                        data: [
                            <?php
                            foreach ($two_weeks as $percentage) {
                                $hpw = ($percentage->hours_per_week / $percentage->total_hours) * 100;
                                echo("{name: '" . $percentage->project . "', y: " . $hpw . ", x: '" . $percentage->hours_per_week . "'}, ");
                            }
                            ?>
                        ]
                    }]

                });


                scheduler.config.xml_date = "%Y-%m-%d %H:%i";
                scheduler.config.prevent_cache = true;

                scheduler.config.lightbox.sections = [
                    {name: "description", height: 130, map_to: "text", type: "textarea", focus: true},
                    {name: "location", height: 43, type: "textarea", map_to: "details"},
                    {name: "time", height: 72, type: "time", map_to: "auto"}
                ];
                scheduler.config.first_hour = 4;
                scheduler.config.limit_time_select = true;
                scheduler.locale.labels.section_location = "Location";

                scheduler.init('scheduler_here', new Date(2010, 7, 1), "month");
                scheduler.setLoadMode("month");
                scheduler.load("./scheduler_data");

                var dp = new dataProcessor("./scheduler_data");
                dp.init(scheduler);

            });
        </script>

        <script type="text/javascript" charset="utf-8">


        </script>