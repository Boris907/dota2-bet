<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Game extends Model
{

	public $timestamps = false;
	protected $fillable = [
	'title',
	];

	 public function services()
  {
    return $this->hasOne(Service::class);
  }

	public function user()
  {
    return $this->belongsTo(User::class);
  }
}
