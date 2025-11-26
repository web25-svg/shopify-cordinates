<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalEntry extends Model
{
    protected $fillable = ['player_id','product_id','order_id','customer_email','x','y','amount_paid'];

    public function judge_entries()
    {
        return $this->hasMany(JudgeEntry::class, 'product_id');
    }
}
