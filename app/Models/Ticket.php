<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * Get the supports for the ticket.
     */
    public function supports()
    {
        return $this->hasMany(TicketSupport::class,'ticket_id' , 'id')->orderBy('id' , 'desc');
    }

}
