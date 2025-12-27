<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rijschool extends Model {
	
	protected $table = 'rijscholen';
	
	protected $fillable = [
		'email',
		'name',
		'cbr_url',
		'cbr_slug',
		'street',
		'postal_code',
		'city',
		'phone',
		'website',
		'address_raw',
		'rijschoolnummer',
		'kvk_nummer',
		'praktijkopleidingen',
		'theorieopleidingen',
		'beroepsopleidingen',
		'bijzonderheden',
		'exam_results',
		'coordinates',
		'cbr_modified_at',
		'crawled_at',
	];

	protected $casts = [
		'praktijkopleidingen' => 'array',
		'theorieopleidingen' => 'array',
		'beroepsopleidingen' => 'array',
		'bijzonderheden' => 'array',
		'exam_results' => 'array',
		'coordinates' => 'array',
		'cbr_modified_at' => 'datetime',
		'crawled_at' => 'datetime',
	];
	
}
