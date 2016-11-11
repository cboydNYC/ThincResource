<?php  namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use Validator, Input, Redirect ;

class EmailController extends Controller {

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

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function GenerateWeeklyEmails( Request $request )
    {
        $staff = \DB::select( \DB::raw("SELECT id, first_name, last_name FROM tb_staff WHERE isactive = 1 AND weekly_email = 1 ORDER BY first_name, last_name") );
        return $staff;
        foreach ($staff as $s) {
            //generate email for each member of the staff

        }

    }

    public function GenerateStaffEmail( Request $request )
    {
        $this->data['days'] = 14;
        $staff = \DB::select( \DB::raw("SELECT id, first_name, last_name FROM tb_staff WHERE isactive = 1 AND weekly_email = 1 AND id = ".$request->id) );
        $deadlines = \DB::select( \DB::raw("SELECT name, short_name, tb_project_milestones.title, tb_project_milestones.description, tb_project_milestones.start_date, tb_project_milestones.end_date, tb_project_milestones.priority, tb_statuses.status 
FROM tb_projects
INNER JOIN tb_staff_group_assignments ON tb_staff_group_assignments.project_id = tb_projects.id
INNER JOIN tb_project_milestones ON tb_project_milestones.project_id = tb_projects.id
INNER JOIN tb_statuses ON tb_project_milestones.status = tb_statuses.id
WHERE tb_projects.isactive = 1 AND tb_staff_group_assignments.staff_id = 1 AND tb_project_milestones.isactive = 1
AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = \"0000-00-00\") )
AND tb_project_milestones.start_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL ".$this->data['days']." DAY)
ORDER BY start_date"));


        $temp = "";

        return view('emails.weekly',$this->data)
            ->with('staff',$staff)
            ->with('deadlines',$deadlines);

    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index( Request $request )
    {


    }



}
