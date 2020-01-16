<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
