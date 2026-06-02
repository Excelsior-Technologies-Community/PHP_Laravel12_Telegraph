<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DefStudio\Telegraph\Models\TelegraphBot;

class TelegraphBotCommand extends Model
{
    protected $fillable = ['telegraph_bot_id', 'command', 'description'];

    public function bot()
    {
        return $this->belongsTo(TelegraphBot::class, 'telegraph_bot_id');
    }
}