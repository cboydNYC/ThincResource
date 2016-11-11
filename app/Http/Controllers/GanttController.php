<?php
namespace App\Http\Controllers;
use App\GanttTask;
use App\GanttLink;
use Dhtmlx\Connector\GanttConnector;

class GanttController extends Controller
{
    public function data() {
        $connector = new GanttConnector(null, "PHPLaravel");
        $connector->render_links(new GanttLink(), "id", "source,target,type");
        $connector->render_table(new GanttTask(),"id","start_date,duration,text,progress,parent");
    }

}
