<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;

class CalendarController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();
	public $module = 'Projectdeadlinestaffadmin';
	static $per_page = '10';

	public function __construct()
	{
        parent::__construct();
		$this->model = new Calendar();
        $this->modelview = new  \App\Models\Projectdeadlinestaffadmin();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'projectdeadlinestaffadmin',
			'return'	=> self::returnUrl()
		);
		
	}

	public function getIndex( Request $request )
	{

        if($this->access['is_view'] ==0)
            return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

        $this->data['access']		= $this->access;
        $this->data['deadline_types'] = \DB::table('tb_deadline_types')->select('tb_deadline_types.deadline_type','tb_deadline_types.color')->join('tb_project_milestones','tb_project_milestones.deadline_type','=', 'tb_deadline_types.id')->orderBy('tb_deadline_types.deadline_type')->distinct()->get();

        return view('calendar.index',$this->data);

	}

    public function getModalIndex( Request $request )
    {

        if($this->access['is_view'] ==0)
            return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

        $this->data['access']		= $this->access;
        $this->data['deadline_types'] = \DB::table('tb_deadline_types')->select('tb_deadline_types.deadline_type','tb_deadline_types.color')->join('tb_project_milestones','tb_project_milestones.deadline_type','=', 'tb_deadline_types.id')->orderBy('tb_deadline_types.deadline_type')->distinct()->get();

        //return view('calendar.index',$this->data);
        return view('calendar.modalindex',$this->data);

    }

    function getJsondata( Request $request)
    {
        if (is_null($request->get('start')) || is_null($request->get('end'))) {
            die("Please provide a date range.");
        }

        //$results = $this->model->getRows( $params = array() );
        $sql = "Select calendar.id, tb_eventtypes.`eventtype` as 'type',calendar.title, calendar.start, calendar.end, calendar.description, tb_eventtypes.color 
FROM calendar INNER JOIN tb_eventtypes ON calendar.`eventtype` = tb_eventtypes.`id`";

        //holidays
        $sql .= " UNION Select tb_holidays.id, tb_eventtypes.`eventtype` as 'type', tb_holidays.title, tb_holidays.start, tb_holidays.end, tb_holidays.description, tb_eventtypes.color 
FROM tb_holidays INNER JOIN tb_eventtypes ON tb_eventtypes.`id` = 6";

        //birthdays
        $sql .= " UNION Select tb_user_profile.id, tb_eventtypes.`eventtype` as 'type', concat(tb_users.`first_name`,' ',tb_users.`last_name`) as 'title', concat(YEAR(now()),\"-\",DATE_FORMAT(tb_user_profile.dob,'%m') ,\"-\",DATE_FORMAT(tb_user_profile.dob,'%d')) as `start`, NULL as `end`, concat(tb_users.`first_name`,' ',tb_users.`last_name`) as 'description', tb_eventtypes.color 
FROM tb_user_profile INNER JOIN tb_users ON tb_user_profile.`user_id` = tb_users.`id` INNER JOIN tb_eventtypes ON tb_eventtypes.`id` = 4 WHERE tb_user_profile.dob <> '0000-00-00'";

        $result = \DB::select($sql);

        $results['rows'] =  $result;
        $results['total'] = count($result);
        $data = array();
        foreach($results['rows'] as $row)
        {
            $data[] = array(
                'color'	=> $row->color,
                'id'	=> $row->id,
                'title'	=> $row->title,
                'start'	=> $row->start,
                'end'	=> $row->end,
                'description'	=> $row->description
            );
        }

        return json_encode($data);
    }

    function getUpcomingDeadlines( Request $request)
    {
        if (is_null($request->get('start')) || is_null($request->get('end'))) {
            die("Please provide a date range.");
        }

        //$results = $this->model->getRows( $params = array() );
        $sql = "SELECT tb_project_milestones.id, tb_deadline_types.`deadline_type` as `type`, tb_projects.short_name as `project`, tb_project_milestones.title, tb_project_milestones.start_date as `start`, tb_project_milestones.end_date as `end`, tb_project_milestones.description, tb_deadline_types.color, tb_priorities.value as `priority`, tb_milestone_status.value as `status` 
FROM tb_project_milestones 
INNER JOIN tb_projects on tb_project_milestones.project_id = tb_projects.id
INNER JOIN tb_deadline_types ON tb_project_milestones.`deadline_type` = tb_deadline_types.`id`
INNER JOIN tb_priorities ON tb_project_milestones.priority = tb_priorities.priority
INNER JOIN tb_milestone_status ON tb_project_milestones.status = tb_milestone_status.status
WHERE tb_projects.isactive = 1 AND tb_project_milestones.isactive = 1
ORDER BY tb_project_milestones.start_date";

        $result = \DB::select($sql);
        $results['rows'] =  $result;
        $results['total'] = count($result);

        $data = array();
        foreach($results['rows'] as $row)
        {
            $project = $row->project . ": ". $row->title;
            if($row->priority == "High") { $project .= " (".$row->priority.")"; }
            if($row->status == "Complete") { $project .= " (".$row->status.")"; }
            $data[] = array(
                'color'	        => $row->color,
                'id'	        => $row->id,
                'title'	        => $project,
                'start'	        => $row->start,
                'end'	        => $row->end == "0000-00-00" ? "" : $row->end,
                'description'	=> $row->description
            );
        }


        return json_encode($data);
    }

	function getUpdate(Request $request, $id = null)
	{
	
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				
				
		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('calendar'); 
		}

		
		$this->data['id'] = $id;
		return view('calendar.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('calendar'); 
		}
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('calendar.view',$this->data);	
	}

	public function getShowModal( $id = null)
	{

		if($this->access['is_detail'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', Lang::get('core.note_restric'))->with('msgstatus','error');

		//$row = $this->model->getRow($id);
        $row = \DB::table('tb_project_milestones')
            ->select('tb_deadline_types.color', 'tb_projects.name as project', 'tb_clients.name as client', 'tb_project_milestones.title', 'tb_project_milestones.description', 'tb_project_milestones.start_date', 'tb_project_milestones.end_date','tb_deadline_types.deadline_type', 'tb_milestone_status.value as status', 'tb_priorities.value as priority')
            ->join('tb_projects', 'tb_project_milestones.project_id', '=', 'tb_projects.id')
            ->join('tb_clients', 'tb_projects.company_id', '=', 'tb_clients.id')
            ->join('tb_deadline_types', 'tb_project_milestones.deadline_type', '=', 'tb_deadline_types.id')
            ->join('tb_priorities', 'tb_project_milestones.priority', '=', 'tb_priorities.priority')
            ->join('tb_milestone_status', 'tb_project_milestones.status', '=', 'tb_milestone_status.status')
            ->where('tb_project_milestones.id',$id)->first();

        if($row){
			$this->data['row'] =  $row;
            //get people

            $row2 = \DB::table('tb_project_milestone_staff')
                ->select('tb_staff.first_name', 'tb_staff.last_name')
                ->join('tb_staff', 'tb_project_milestone_staff.user_id', '=', 'tb_staff.id')
                ->where('tb_project_milestone_staff.milestone_id',$id)->get();
            $this->data['people'] = $row2;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_project_milestones');
		}

		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('calendar.modalview',$this->data);
	}

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_calendar');
			
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'calendar/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'calendar?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('id') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('calendar')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}	

	function postSavedrop( Request $request)
	{
		$data = $this->validatePost('calendar');
		$ID = $this->model->insertRow($data , $request->get('id'));
		return 'success';
		
	}		

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('id')) >=1)
		{
			$this->model->destroy($request->input('id'));
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('id'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('calendar')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('calendar')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}			


}