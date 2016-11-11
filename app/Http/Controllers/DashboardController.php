<?php namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use App\Library\MyHelpers;
use Carbon\Carbon;

class DashboardController extends Controller {

	public function __construct()
	{
		parent::__construct();
        $this->data = array(
            'pageTitle' =>  CNF_APPNAME,
            'pageNote'  =>  'Welcome to Dashboard',
            
        );			
	}

    public function getStaffList(  )
    {
        $staff = \DB::select( \DB::raw("SELECT tb_staff.id, CONCAT(first_name, ' ', LEFT(last_name,1)) AS name
FROM tb_staff 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.staff_id =tb_staff.id
WHERE isactive=1
GROUP BY tb_staff.id
ORDER BY tb_staff.first_name") );
        return $staff;
    }

    public function getStaffWithAssignments( $d )
    {
        $staff = \DB::select( \DB::raw("SELECT tb_staff.id, CONCAT(first_name, ' ', LEFT(last_name,1)) AS name
FROM tb_staff 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.staff_id =tb_staff.id
WHERE isactive=1 AND tb_staff_group_assignments.staff_id AND tb_staff_group_assignments.mark_as_out = 0 AND ( ('".$d."' BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR ('".$d."' >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_staff.id 
ORDER BY tb_staff.first_name") );
        return $staff;
    }

    public function getStaffWithAssignmentsbyWeek( $d )
    {
        $staff = \DB::select( \DB::raw("SELECT tb_staff.id, CONCAT(first_name, ' ', LEFT(last_name,1)) AS name
FROM tb_staff 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.staff_id =tb_staff.id
WHERE tb_staff.isactive=1 AND tb_staff_group_assignments.staff_id AND tb_staff_group_assignments.mark_as_out = 0 
AND ( (STR_TO_DATE( concat( concat( date_format( ".$d." , '%Y' ) , WEEKOFYEAR( ".$d." ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( ".$d." , '%Y' ) , WEEKOFYEAR( ".$d." ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_staff.id
ORDER BY tb_staff.first_name") );
        return $staff;
    }

    public function getGroupList( $d )
    {
        $group = \DB::select( \DB::raw("SELECT tb_staff_groups.id, tb_staff_groups.group_name
FROM tb_staff_groups 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.group_id=tb_staff_groups.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE (tb_staff.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( ('".$d."' BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR ('".$d."' >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00' ))
GROUP BY tb_staff_groups.id
ORDER BY tb_staff_groups.group_name
") );
        return $group;
    }

    public function getAssignmentsGroupList( $d )
    {
        $group = \DB::select( \DB::raw("SELECT CONCAT('p',tb_staff_group_assignments.project_id) AS `key`, name, tb_projects.short_name
FROM tb_projects 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.project_id=tb_projects.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_projects.isactive = 1) AND tb_staff_group_assignments.mark_as_out = 0 AND (tb_staff_group_assignments.hours_per_week > 0) AND ( ('".$d."' BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR ('".$d."' >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_projects.id

UNION SELECT CONCAT('g',tb_staff_group_assignments.group_id) AS `key`, tb_staff_groups.group_name as name, tb_staff_groups.short_name
FROM tb_staff_groups 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.group_id=tb_staff_groups.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE NOT tb_staff_groups.project_specific AND (tb_staff.isactive = 1) AND tb_staff_group_assignments.mark_as_out = 0 AND (tb_staff_group_assignments.hours_per_week > 0) AND ( ('".$d."' BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR ('".$d."' >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )
GROUP BY tb_staff_groups.id
") );
        return $group;
    }

    public function getAssignmentsStaffList(  )
    {
        $staff = \DB::select( \DB::raw("SELECT tb_staff.id, CONCAT(first_name, ' ', LEFT(last_name,1)) AS name
FROM tb_staff 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.staff_id =tb_staff.id
WHERE isactive=1
GROUP BY tb_staff.id
ORDER BY tb_staff.first_name") );
        return $staff;
    }

    public function getProjectList( $d )
    {
        $project = \DB::select( \DB::raw("SELECT tb_projects.id, name, short_name
FROM tb_projects 
LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.project_id=tb_projects.id
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ('".$d."' >= tb_staff_group_assignments.start AND ('".$d."' < tb_staff_group_assignments.end OR tb_staff_group_assignments.end = '0000-00-00' ))
GROUP BY tb_projects.id
ORDER BY tb_projects.short_name
") );
        return $project;
    }

    public function getCompanyResourceUtilization( $d )
    {
        $groups = \DB::select( \DB::raw("SELECT tb_staff.id, tb_staff_groups.group_name, tb_staff_groups.color,  
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_staff_groups.id = tb_staff_group_assignments.group_id  ) AS 'hours_per_week', 
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments ) AS 'total_hours' 
FROM tb_staff_group_assignments
INNER JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
INNER JOIN tb_staff_groups on tb_staff_group_assignments.group_id = tb_staff_groups.id
WHERE tb_staff.isactive = 1 AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = \"0000-00-00\") )
GROUP BY tb_staff_groups.group_name") );
        return $groups;
    }

    public function getMaxHours( $d )
    {
        $max = \DB::select( \DB::raw("SELECT sum(hours_per_week)
FROM tb_staff_group_assignments
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
LEFT JOIN tb_projects on tb_staff_group_assignments.project_id = tb_projects.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ('".$d."' >= tb_staff_group_assignments.start AND ('".$d."' < tb_staff_group_assignments.end OR tb_staff_group_assignments.end = '0000-00-00' ))
GROUP by staff_id
ORDER BY hours_per_week DESC
LIMIT 1") );
        return $max;
    }

    public function getStaffHours( $d )
    {
        $hours = \DB::select( \DB::raw("SELECT tb_staff_group_assignments.staff_id, tb_projects.id AS project_id, tb_staff_group_assignments.group_id AS group_id, tb_staff_group_assignments.hours_per_week
FROM tb_staff_group_assignments
LEFT JOIN tb_projects on tb_staff_group_assignments.project_id = tb_projects.id
LEFT JOIN tb_staff_groups on tb_staff_group_assignments.group_id = tb_staff_groups.id
WHERE (tb_staff_group_assignments.project_id = 0 OR tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ('".$d."' >= tb_staff_group_assignments.start AND ('".$d."' < tb_staff_group_assignments.end OR tb_staff_group_assignments.end = '0000-00-00' ))
ORDER BY tb_staff_group_assignments.staff_id") );
    return $hours;
    }

    public function getProjectPercentages( $d )
    {
        $percentages = \DB::select( \DB::raw("SELECT tb_projects.id, tb_projects.name AS project, tb_projects.short_name, 
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments WHERE tb_projects.id = tb_staff_group_assignments.project_id  ) AS 'hours_per_week', 
(SELECT sum(tb_staff_group_assignments.hours_per_week) FROM tb_staff_group_assignments ) AS 'total_hours' FROM tb_staff_group_assignments
LEFT JOIN tb_staff on tb_staff_group_assignments.staff_id = tb_staff.id
LEFT JOIN tb_projects on tb_staff_group_assignments.project_id = tb_projects.id
WHERE (tb_staff_group_assignments.project_id <> 0) AND (tb_staff.isactive = 1) AND (tb_projects.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = \"0000-00-00\") )
GROUP BY tb_projects.id") );
        return $percentages;
    }

    public function getUpcomingDeadlines( $d )
    {
        $deadlines = \DB::select( \DB::raw("SELECT tb_projects.name, tb_projects.short_name, tb_project_milestones.priority, tb_project_milestones.status, tb_project_milestones.id, tb_project_milestones.title, tb_project_milestones.description, tb_project_milestones.start_date, tb_project_milestones.end_date from tb_project_milestones
INNER JOIN tb_projects on tb_project_milestones.project_id = tb_projects.id
WHERE start_date <= (curdate() + interval 14 day)
ORDER BY start_date") );
        return $deadlines;
    }

    public function getProjectStatuses( $d )
    {
        $status = \DB::select( \DB::raw("SELECT tb_statuses.id, tb_statuses.status FROM tb_statuses 
INNER JOIN tb_projects ON tb_projects.project_status = tb_statuses.id
INNER JOIN tb_staff_group_assignments ON tb_staff_group_assignments.project_id = tb_projects.id
WHERE tb_projects.isactive = 1 AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = \"0000-00-00\") )
GROUP BY status
ORDER BY status") );
        return $status;
    }

    public function regenerateStaffUtilizationChart( Request $request ) {
        $w = $_GET['week'];
        $y = $_GET['year'];
        $week_range = MyHelpers::getStartAndEndDate($w, $y);
        $week = date("m/d", strtotime($week_range[0]))." - ".date("m/d/Y", strtotime($week_range[1]));

        $staff = $this->getStaffWithAssignments(date('Y-m-d'));
        $staff_members = "";
        foreach ($staff as $s) {
            $staff_members .= $staff_members==""? "'" . $s->name . "'": ", '" . $s->name . "'" ;
        }

        $assgrouplist = "";
        $assignmentsgrouplist = $this->getAssignmentsGroupList( date('Y-m-d') );

        foreach ($assignmentsgrouplist as $group) {
            $group_data = "";

            foreach($staff as $s) {
                $ass_found = 0;
                $staff_assignments = \DB::select( \DB::raw("SELECT tb_staff.id, IF(tb_staff_group_assignments.project_id,CONCAT('p',tb_staff_group_assignments.project_id),CONCAT('g',tb_staff_group_assignments.group_id)) AS `key`, tb_staff_group_assignments.hours_per_week
                        FROM tb_staff
                        LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.staff_id = tb_staff.id
                        WHERE tb_staff.id = ".$s->id." AND hours_per_week
                        ") );

                foreach($staff_assignments AS $ass) {
                    if ($ass->key == $group->key) {
                        $ass_found = $ass->hours_per_week;
                    }
                }

                if ($ass_found) {
                    $group_data .= " ".$ass_found . ",";
                } else {
                    $group_data .= " 0,";
                }
            }
            $data = substr($group_data,0, strlen($group_data)-1 );

            $assgrouplist .= "{
                    name: '".$group->short_name."',
                    data: [".$data."]
                }," ;

        }

        echo json_encode(
            array("staff" => "[".$staff_members."]",
                "json_staff" => json_encode($staff_members,JSON_NUMERIC_CHECK),
                "assgrouplist" => $assgrouplist,
                "week_range" => $week
            )
        );

    }

	public function getIndex( Request $request )
	{

		$this->data['online_users'] = \DB::table('tb_users')->orderBy('last_activity','desc')->limit(10)->get(); 
		$this->data['active'] = '';

        $this->data['current_week_number'] = date("W");
        $this->data['current_year'] = date("Y");
        $this->data['current_week_range'] = MyHelpers::rangeWeek(date('Y-m-d'));
        $this->data['current_week_number_range'] = json_encode($this->data['current_week_number']-8, $this->data['current_week_number']+8);
        $this->data['current_week_options']="";

        //build date range options
        for ($x = $this->data['current_week_number']-8; $x <= $this->data['current_week_number']+8; $x++) {
            $week = MyHelpers::getStartAndEndDate($x, $this->data['current_year'] );
            //print_r($week);
            $this->data['current_week_options'].="<option value='".$x."'";
            if ($x == $this->data['current_week_number']) {
                $this->data['current_week_options'] .= " selected='selected'";
            }
            $this->data['current_week_options'] .= ">".date("m/d", strtotime($week[0]))." - ".date("m/d/Y", strtotime($week[1]))."</option>";
        }

        $groups = $this->getCompanyResourceUtilization( date('Y-m-d') ); //for Resource Utilization chart
        $projects = $this->getProjectPercentages( date('Y-m-d') ); //for project Percentages chart
        $max = $this->getMaxHours( date('Y-m-d') );
        $grouplist = $this-> getGroupList( date('Y-m-d') );
        $assignmentsgrouplist = $this->getAssignmentsGroupList( date('Y-m-d') );
        $projlist = $this->getProjectList( date('Y-m-d') );
        $staff = $this->getStaffWithAssignments( date('Y-m-d'));
        $projstatuses = $this->getProjectStatuses( date('Y-m-d'));
        $deadlines = $this->getUpcomingDeadlines(date('Y-m-d'));

		return view('dashboard.index',$this->data)
            ->with('staff',$staff)
            ->with('json_staff',json_encode($staff,JSON_NUMERIC_CHECK))
            ->with('grouplist',$grouplist)
            ->with('json_grouplist',json_encode($grouplist,JSON_NUMERIC_CHECK))
            ->with('projlist',$projlist)
            ->with('json_projlist',json_encode($projlist,JSON_NUMERIC_CHECK))
            ->with('assignmentsgrouplist',$assignmentsgrouplist)
            ->with('max',$max)
            ->with('groups',$groups)
            ->with('projects',$projects)
            ->with('projstatuses',$projstatuses)
            ->with('deadlines',$deadlines)
            ->with('week_range', MyHelpers::getStartAndEndDate($this->data['current_week_number'], $this->data['current_year'] ))
            ->with('json_groups',json_encode($groups,JSON_NUMERIC_CHECK));
	}

    public function getGantt( Request $request )
    {
        $staff = array();
        return view('dashboard.gantt',$this->data)
            ->with('staff',$staff);
    }
}