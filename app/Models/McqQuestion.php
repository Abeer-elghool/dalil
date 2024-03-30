<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqQuestion extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    protected $table = 'mcq_question';

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function mcq()
    {
        return $this->belongsTo(Mcq::class, 'mcq_id', 'id');
    }
}
