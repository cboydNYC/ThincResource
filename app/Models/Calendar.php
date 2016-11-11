<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class calendar extends Sximo  {
	
	protected $table = 'tb_milestones';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_milestones.* FROM tb_milestones  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_milestones.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
