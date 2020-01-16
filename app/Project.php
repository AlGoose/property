<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'customer',
        'opponents',
        'contacts',
        'date',
        'work',
        'products',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function opponents()
    {
        return $this->belongsToMany(Opponent::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
