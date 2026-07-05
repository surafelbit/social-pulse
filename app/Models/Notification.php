<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'sender_id', 'type', 'reference_id', 'is_read'];

    // The person receiving the notification
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The person who triggered the action
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}