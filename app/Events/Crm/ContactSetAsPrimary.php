<?php

declare(strict_types=1);

namespace App\Events\Crm;

use App\Models\Crm\Contact;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactSetAsPrimary
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The contact instance.
     *
     * @var \App\Models\Crm\Contact
     */
    public Contact $contact;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }
} 