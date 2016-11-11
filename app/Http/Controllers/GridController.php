<?php
namespace App\Http\Controllers;
use App\SchedulerEvent;
use Dhtmlx\Connector\GridConnector;

class GridController extends Controller
{
    public function data() {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(new SchedulerEvent(), "event_id", "start_date, end_date, event_name");
        $connector->render();
    }
}
