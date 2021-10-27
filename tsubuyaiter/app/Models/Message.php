<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $dates =  ['created_at', 'updated_at'];
    protected $fillable = ['id', 'user_id', 'message'];
}
