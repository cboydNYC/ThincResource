<?php  namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use Validator, Input, Redirect ;
use Reports;

class ReportsController extends Controller {

    public function __construct()
    {
        parent::__construct();

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {

            $lang = (\Session::get('lang') != "" ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }

        $this->data['pageLang'] = 'en';
        if(\Session::get('lang') != '')
        {
            $this->data['pageLang'] = \Session::get('lang');
        }

    }

    public function getUpcomingDeadlines( $d )
    {
        $deadlines = \DB::select( \DB::raw("SELECT tb_projects.name, tb_projects.short_name, tb_projects.color, tb_project_milestones.priority, tb_project_milestones.status, tb_project_milestones.id, tb_project_milestones.title, tb_project_milestones.description, MONTHNAME(tb_project_milestones.start_date) AS start_month, tb_project_milestones.start_date, MONTHNAME(tb_project_milestones.end_date) AS end_month, tb_project_milestones.end_date from tb_project_milestones
INNER JOIN tb_projects on tb_project_milestones.project_id = tb_projects.id
WHERE (tb_project_milestones.status <> 3 AND tb_project_milestones.status <> 4) 
AND start_date >= curdate() AND (start_date <= (curdate() + interval 60 day))
ORDER BY start_date, name") );
        return $deadlines;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex()
    {
        if($this->access['is_view'] ==0)
            return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

        $this->data['access']		= $this->access;
        return view('reports.index',$this->data);
    }

    public function upcomingDeadlines( Request $request )
    {
        $this->data = array(
            'pageTitle' =>  "Upcoming Deadlines",
            'pageModule' =>  'reports',
            'pageNote'  =>  'Print Date: '.date("m/d/Y"),

        );
        $deadlines = $this->getUpcomingDeadlines(date('Y-m-d'));
        $colors = \DB::select( \DB::raw("SELECT * FROM tb_colors ORDER BY `order`") );
        //return View::make('result')->with(['info' => $info, 'error_code', 5]);

        return view('reports.upcomingdeadlines',$this->data)
            ->with('deadlines', $deadlines)
            ->with('colors', $colors)

            ->with('error_code', 5);
    }

    public function projectRoles( Request $request )
    {
        $this->data = array(
            'pageTitle' =>  "Project Roles",
            'pageModule' =>  'reports',
            'pageNote'  =>  'Print Date: '.date("m/d/Y"),

        );
        $projects = \DB::select( \DB::raw("SELECT tb_projects.id, tb_projects.name FROM tb_projects
INNER JOIN tb_project_staffing ON tb_project_staffing.project_id = tb_projects.id
WHERE tb_projects.isactive = 1 
GROUP BY tb_projects.name
ORDER BY `name`") );
        $roles = \DB::select( \DB::raw("SELECT tb_roles.id, tb_roles.role FROM tb_roles
INNER JOIN tb_project_staffing ON tb_project_staffing.role_id = tb_roles.id
GROUP BY tb_roles.role
ORDER BY tb_roles.order") );
        $people = \DB::select( \DB::raw("SELECT tb_staff.id, CONCAT(tb_staff.first_name, ' ', LEFT(tb_staff.last_name,1) )as 'name' FROM tb_staff
INNER JOIN tb_project_staffing ON tb_project_staffing.staff_id = tb_staff.id
GROUP BY tb_staff.first_name
ORDER BY tb_staff.first_name") );

        return view('reports.projectroles',$this->data)
            ->with('projects', $projects)
            ->with('people', $people)
            ->with('error_code', 5);
    }




    public function exportExcel( Request $request )
    {
        $curr_month = date('M', strtotime( 'monday this week' ));
        $curr_month_num = date('m', strtotime( 'monday this week' ));
        $monday = date( 'm-d-Y', strtotime( 'monday this week' ) );
        $end = date('Y-m-d', strtotime("+3 months", strtotime( $monday )));
        $ending_month = date('M', strtotime("+3 months", strtotime($end)));
        $ending_year = date('Y', strtotime("+3 months", strtotime($end)));
        $curr_year = date('Y', strtotime( 'monday this week' ));

        $this->data = array(
            'pageTitle' =>  "Resource Report (".$curr_month. " ". $curr_year." -  ".$ending_month. " ". $ending_year.")",
            'pageModule' =>  'reports',
            'pageNote'  =>  'Print Date: '.date("m/d/Y"),

        );

        return view('reports.excelExport',$this->data)
            ->with('curr_month', $curr_month)
            ->with('curr_month_num', $curr_month_num)
            ->with('curr_year', $curr_year)
            ->with('ending_month', $ending_month)
            ->with('monday', $monday)
            ->with('error_code', 5);
    }

}
