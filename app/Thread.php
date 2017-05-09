<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Thread extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'isComment', 'isAnonymous',
        'startTime', 'endTime', 'voteGap', 'code'];
    //protected $guarded = ['user_id'];   //inverse of fillable

    public function comments()
    {
        return $this->hasMany(Comment::class);  //hasMany('App\Comment');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function user()  //thread->user->name     $comment->thread->user->name
    {
        return $this->belongsTo(User::class);
    }

    public function addComment($body)
    {
        //$this->comments()->create(compact('body')); //can also set the id of the thread for me
        Comment::create([
            'body' => $body,
            'thread_id' => $this->id,
            'user_id' => auth()->user()->id
        ]);
    }


    /*
     * filter should be month and year only
     */
    public function scopeFiltertime($query, $filters)
    {
        if ($month = $filters['month']) {
            $query->whereMonth('created_at', Carbon::parse($month)->month);    //convert May=>5
        }
        if ($year = $filters['year']) {
            $query->whereYear('created_at', $year);
        }
        //$threads = $query->get();
    }

    public static function archives()
    {
        return static::selectRaw('year(created_at) as year, monthname(created_at) as month, count(*) published')
            ->groupBy('year', 'month')
            ->orderByRaw('created_at DESC')
            ->get()->toArray();
    }
}
