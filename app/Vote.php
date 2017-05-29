<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'isComment', 'isAnonymous', 'isPublic',
        'startTime', 'endTime', 'voteGap', 'entryCode'];
    //protected $guarded = ['user_id'];   //inverse of fillable

    /**
     * The attributes that should be mutated to dates.
     * https://laracasts.com/discuss/channels/code-review/using-diffforhumans-on-other-timestamps-than-created-at
     * @var array
     */
    protected $dates = [
        'startTime',
        'endTime'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);  //hasMany('App\Comment');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function user()  //vote->user->name     $comment->vote->user->name
    {
        return $this->belongsTo(User::class);
    }

    public function selections()
    {
        return $this->hasMany(Selection::class);
    }

    public function addComment($body)
    {
        //$this->comments()->create(compact('body')); //can also set the id of the vote for me
        Comment::create([
            'body' => $body,
            'vote_id' => $this->id,
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
        //$votes = $query->get();
    }

    public static function archives()
    {
        return static::selectRaw('year(created_at) as year, monthname(created_at) as month, count(*) published')
            ->groupBy('year', 'month')
            ->orderByRaw('created_at DESC')
            ->get()->toArray();
    }
}
