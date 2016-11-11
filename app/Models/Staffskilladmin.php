<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class staffskilladmin extends Sximo  {
	
	protected $table = 'tb_staff_skills';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_staff_skills.* FROM tb_staff_skills  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_staff_skills.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return " <p><br></p> ";
	}
	

}
