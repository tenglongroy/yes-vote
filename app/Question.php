<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['vote_id', 'description', 'isMultiple', 'maxChoice'];
    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}
