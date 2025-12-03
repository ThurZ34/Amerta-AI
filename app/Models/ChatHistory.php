<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'conversation_id', 'role', 'message', 'image_path'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
