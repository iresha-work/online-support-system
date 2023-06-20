<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSupport extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ticket_has_supports';

    /**
     * Get the phone associated with the user.
     */
    public function ticket()
    {
        return $this->hasOne(Ticket::class,'ticket_id','id');
    }
}
