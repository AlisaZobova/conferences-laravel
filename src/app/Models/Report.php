<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'report_id', 'id');
    }
}
