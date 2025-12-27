<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SignCategory extends Model
{
	protected $table = 'signs_categories';

	public $timestamps = false;

	public function signs()
	{
		return $this->hasMany(Sign::class, 'category_id');
	}
}
