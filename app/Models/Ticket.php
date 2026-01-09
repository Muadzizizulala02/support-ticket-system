<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{

    use HasFactory;

    // so we can do mass assignment
    // can be assign using tinker
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
    ];


    // Define relationship to User
    // this means that each ticket belongs to a user
    public function user()

    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the replies for this ticket.
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
