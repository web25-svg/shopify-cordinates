<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempEntry extends Model
{
    protected $fillable = ['session_token','product_id','x','y'];
}
