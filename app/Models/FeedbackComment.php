<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackComment extends Model
{
    protected $fillable = [
        'feedback_id',
        'user_id',
        'comment',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
