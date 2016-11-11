<?php 
namespace App\Library;

class MyHelpers {

	static public function formatEmail( $email)
	{
		return '<a href="mailto:'.$email.'">'.$email.'</a>';
	}
    /*Format Date field*/

    static function formatDate($date)
    {
        return date('F j, Y', strtotime($date));
    }

    static function formatTime($date)
    {
        return date('g:i A', strtotime($date));
    }

    static public function rangeMonth($datestr) {
        date_default_timezone_set(date_default_timezone_get());
        $dt = strtotime($datestr);
        $res['start'] = date('Y-m-d', strtotime('first day of this month', $dt));
        $res['end'] = date('Y-m-d', strtotime('last day of this month', $dt));
        return $res;
    }

    static public function rangeWeek($datestr) {
        date_default_timezone_set(date_default_timezone_get());
        $dt = strtotime($datestr);
        $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
        $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
        return $res;
    }

    static public function getStartAndEndDate($week, $year)
    {

        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7*$week)+1-$day)*24*3600;
        $return[0] = date('Y-n-j', $time);
        $time += 6*24*3600;
        $return[1] = date('Y-n-j', $time);
        return $return;
    }

    static public function buttonActionNew( $module , $access , $id , $setting)
    {

        $html ='<div class=" action dropup" >';
        if($access['is_detail'] ==1) {
            if($setting['view-method'] != 'expand')
            {
                $onclick = " onclick=\"ajaxViewDetail('#".$module."',this.href); return false; \"" ;
                if($setting['view-method'] =='modal')
                    $onclick = " onclick=\"SximoModal(this.href,'View Detail'); return false; \"" ;
                $html .= '<a href="'.URL::to($module.'/show/'.$id).'" '.$onclick.' class="btn btn-xs btn-white tips" title="'.Lang::get('core.btn_view').'"><i class="fa fa-search"></i></a>';
            }
        }
        if($access['is_edit'] ==1) {
            $onclick = " onclick=\"ajaxViewDetail('#".$module."',this.href); return false; \"" ;
            if($setting['form-method'] =='modal')
                $onclick = " onclick=\"SximoModal(this.href,'Edit Form'); return false; \"" ;

            $html .= ' <a href="'.URL::to($module.'/update/'.$id).'" '.$onclick.'  class="btn btn-xs btn-white tips" title="'.Lang::get('core.btn_edit').'"><i class="fa  fa-edit"></i></a>';
        }
        $html .= '</div>';
        return $html;
    }

}