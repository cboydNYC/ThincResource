<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use \App\Http\Controllers\StaffsoftwareadminController;
use App\Models\Staffadmin;
use App\Models\Staffsoftwareadmin;
use Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;


class StaffController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	static $per_page	= '50';
    public $module = 'staffsoftwareadmin';

	public function __construct() 
	{
		parent::__construct();
        $this->model = new Staffsoftwareadmin();
        $this->modelview = new  \App\Models\Staffsoftwareadmin();
        $this->info = $this->model->makeInfo( $this->module );
        $this->access = $this->model->validAccess( $this->info['id'] );
	}

    public static function displaysoftwarebystaff( $staff_id )
    {

        $model  = new Staffsoftwareadmin();
        $info = $model::makeInfo('staffsoftwareadmin');

        $data = array(
            'pageTitle'	=> 	$info['title'],
            'pageNote'	=>  $info['note']
        );

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $params = array(
            'page'		=> $page ,
            'limit'		=>  (isset($_GET['rows']) ? filter_var($_GET['rows'],FILTER_VALIDATE_INT) : 10 ) ,
            'sort'		=> 'software' ,
            'order'		=> 'asc',
            'params'	=> '',
            'global'	=> 1
        );

        $result = $model::getRows( $params );
        $data['subtableGrid'] 	= $info['config']['grid'];

        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($result['rows'], $result['total'], $params['limit']);
        $pagination->setPath('');
        $data['i']			= ($page * $params['limit'])- $params['limit'];
        $data['pagination'] = $pagination;
        $data['rowData'] 	= $result['rows'];

        return view('staffadmin.public.softwaretable',$data);

    }

    public function getCalendar( Request $request )
    {

        if($this->access['is_view'] ==0)
            return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

        $this->data['access']		= $this->access;

        $staff = \DB::table('tb_staff')
            ->where('tb_staff.id',Request::segment(3))->first();

        $this->data = array(
            'pageTitle'	=> 	$this->info['title'],
            'pageNote'	=>  $this->info['note'],
            'pageModule'=> 'staffadmin',
            'return'	=> self::returnUrl()
        );

        return view('staffadmin.calendar',$this->data)
            ->with("id",Request::segment(3))
            ->with("first_name",$staff->first_name);

    }

    public function getSchedule($id)
    {

        $sql = "SELECT tb_projects.short_name, tb_projects.color, tb_staff_groups.short_name as group_name, tb_staff_group_assignments.id, tb_staff_group_assignments.mark_as_out, tb_staff_group_assignments.mark_as_travel, tb_staff_group_assignments.start, tb_staff_group_assignments.end, tb_staff_group_assignments.hours_per_week
FROM tb_staff_group_assignments
LEFT JOIN tb_projects ON tb_staff_group_assignments.project_id = tb_projects.id
LEFT JOIN tb_staff_groups ON tb_staff_group_assignments.group_id = tb_staff_groups.id
WHERE staff_id=".$id;

        $result = \DB::select($sql);

        $results['rows'] =  $result;
        $results['total'] = count($result);
        $data = array();

        foreach($results['rows'] as $row)
        {
            if($row->short_name != "") {
                $assignment = $row->short_name;
            } elseif($row->group_name != "") {
                $assignment = $row->group_name;
            }
            $assignment .= " (".$row->hours_per_week . " hours)";

            $data[] = array(
                'color'	=> $row->color,
                'id'	=> $row->id,
                'title'	=> $assignment,
                'start'	=> $row->start,
                'end'	=> $row->end,
                'description'	=> $row->hours_per_week . " hours"
            );
        }

        return json_encode($data);
    }

    public function getAssignments( Request $request )
    {
        $this->module = 'staffadmin';
        $this->model = new Staffadmin();
        $this->modelview = new  \App\Models\Staffadmin();
        $this->info = $this->model->makeInfo( $this->module );

        if($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

        $this->data['access']		= $this->access;

        $staff = \DB::table('tb_staff')
            ->select('tb_staff_groups.group_name','tb_staff.first_name', 'tb_staff.last_name')
            ->join("tb_staff_groups","tb_staff_groups.id", "=", "tb_staff.group_id")
            ->orderBy('tb_staff_groups.group_name', 'asc')
            ->orderBy('tb_staff.first_name', 'asc')
            ->orderBy('tb_staff.last_name', 'asc')->get();

        $this->data = array(
            'pageTitle'	=> 	$this->info['title'],
            'pageNote'	=>  $this->info['note'],
            'pageModule'=> 'staffadmin',
            'return'	=> self::returnUrl()
        );

        return view('staffadmin.assignments',$this->data)
            ->with("staff", $staff);

    }

    public function getAssignmentGanttData(  )
    {

        $staff = \DB::table('tb_staff')
            ->select('tb_staff.id', 'tb_staff_groups.group_name','tb_staff.first_name', 'tb_staff.last_name')
            ->join("tb_staff_groups","tb_staff_groups.id", "=", "tb_staff.group_id")
            ->orderBy('tb_staff_groups.group_name', 'asc')
            ->orderBy('tb_staff.first_name', 'asc')
            ->orderBy('tb_staff.last_name', 'asc')->get();

        return view('staffadmin.assignmentgantt',$this->data)
            ->with("staff", $staff);

    }


}