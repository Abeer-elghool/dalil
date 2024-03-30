<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqSection extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    protected $table = 'mcq_section';

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }

    public function mcq()
    {
        return $this->belongsTo(Mcq::class, 'mcq_id', 'id');
    }
}
