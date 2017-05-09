<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}
