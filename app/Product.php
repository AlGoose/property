<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'article'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}