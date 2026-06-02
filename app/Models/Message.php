<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DefStudio\Telegraph\Models\TelegraphChat;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'telegraph_chat_id',
        'message',
        'text',
        'file_type',
        'file_path',
        'direction'
    ];

    public function telegraphChat()
    {
        return $this->belongsTo(TelegraphChat::class, 'telegraph_chat_id');
    }
}