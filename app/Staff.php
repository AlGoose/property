<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable=['name','phone','email'];
    public function entity()
    {
        return $this->morphTo();
    }
}
