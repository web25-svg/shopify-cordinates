<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudgeEntry extends Model
{
    protected $fillable = ['judge_id','product_id','x','y'];

    public function final_entry()
    {
        return $this->belongsTo(FinalEntry::class, 'product_id');
    }
}
