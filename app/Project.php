<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Project extends Model
{
    use SoftDeletes;

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'date',
        'time',
        'tender_date',
        'work',
        'kladrId',
        'isTenderWon',
        'isClosed',
        'close_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function dealer_staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function customer_staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function opponents()
    {
        return $this->belongsToMany(Opponent::class)->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('count', 'price');
    }

    public function files()
    {
        return $this->belongsToMany(FileProject::class)->withTimestamps();
    }
}
