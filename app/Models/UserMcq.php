<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMcq extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function mcq()
    {
        return $this->belongsTo(Mcq::class, 'mcq_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user_mcq_answers()
    {
        return $this->hasMany(UserMcqAnswer::class, 'user_mcq_id', 'id');
    }
}
