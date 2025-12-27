<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sign extends Model  {

	protected $table = 'signs';

	public $timestamps = false;

	protected $casts = [
		'practice_questions' => 'array',
	];

	public function category()
	{
		return $this->belongsTo(SignCategory::class, 'category_id');
	}

	public function getSlugAttribute()
	{
		$source = $this->description ?: $this->code;

		return Str::slug($source);
	}

	public function getUrlAttribute()
	{
		return url('/verkeersborden/' . $this->code . '-' . $this->slug);
	}
}
