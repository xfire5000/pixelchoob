<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Ticket as ModelsTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends ModelsTicket
{
    use HasFactory;

    /**
     * Retrieves the new messages.
     *
     * @return HasMany The new messages.
     */
    public function newMessages(): HasMany
    {
        return $this->messages()->whereNot('user_id', auth()->id())
            ->whereColumn('created_at', 'updated_at');
    }
}
