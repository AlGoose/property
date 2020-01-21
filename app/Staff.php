<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public function entity()
    {
        return $this->morphTo();
    }
}
