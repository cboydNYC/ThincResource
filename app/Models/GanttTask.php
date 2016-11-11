<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class GanttTask extends Model
{
    protected $table = "gantt_tasks";
    public $primaryKey = "id";
    public $timestamps = false;
}
