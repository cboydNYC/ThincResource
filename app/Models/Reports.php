<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class reports extends Sximo  {

    protected $table = 'tb_reports';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public function days_in_month($month, $year)
    {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }


}
