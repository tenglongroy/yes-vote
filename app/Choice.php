<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }
}
