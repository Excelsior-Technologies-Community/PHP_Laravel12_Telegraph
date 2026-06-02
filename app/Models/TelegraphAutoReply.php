<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegraphAutoReply extends Model
{
    protected $fillable = ['keyword', 'reply_text', 'match_type', 'is_active'];
}