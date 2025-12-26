<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rijschool extends Model {
	
	protected $table = 'rijscholen';
	
	protected $fillable = ['email'];
	
}