<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Crm\Activity;
use App\Models\Crm\Contact;
use Illuminate\Support\Facades\Log;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return void
     */
    public function created(Contact $contact): void
    {
        Log::info('Contact created', [
            'contact_id' => $contact->id,
            'contact_name' => $contact->full_name,
            'company_id' => $contact->company_id,
        ]);

        try {
            $contact->recordActivity(
                Activity::TYPE_CREATED,
                "Contact {$contact->full_name} was created",
                [
                    'first_name' => $contact->first_name,
                    'last_name' => $contact->last_name,
                    'email' => $contact->email,
                    'company_id' => $contact->company_id,
                    'is_primary' => $contact->is_primary,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to record contact created activity', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the Contact "updated" event.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return void
     */
    public function updated(Contact $contact): void
    {
        // Only record activity if the model has dirty attributes
        if (empty($contact->getDirty())) {
            return;
        }

        Log::info('Contact updated', [
            'contact_id' => $contact->id,
            'contact_name' => $contact->full_name,
            'dirty' => $contact->getDirty(),
        ]);

        try {
            // Check if primary status changed
            if ($contact->isDirty('is_primary') && $contact->is_primary) {
                $contact->recordActivity(
                    Activity::TYPE_UPDATED,
                    "Contact {$contact->full_name} was set as primary contact",
                    [
                        'company_id' => $contact->company_id,
                        'changed_attributes' => [
                            'is_primary' => true,
                        ],
                    ]
                );
            } else {
                $contact->recordActivity(
                    Activity::TYPE_UPDATED,
                    "Contact {$contact->full_name} was updated",
                    [
                        'company_id' => $contact->company_id,
                        'changed_attributes' => $contact->getDirty(),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to record contact updated activity', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the Contact "deleted" event.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return void
     */
    public function deleted(Contact $contact): void
    {
        Log::info('Contact deleted', [
            'contact_id' => $contact->id,
            'contact_name' => $contact->full_name,
        ]);

        try {
            $contact->recordActivity(
                Activity::TYPE_DELETED,
                "Contact {$contact->full_name} was deleted",
                [
                    'company_id' => $contact->company_id,
                    'contact_data' => [
                        'id' => $contact->id,
                        'first_name' => $contact->first_name,
                        'last_name' => $contact->last_name,
                        'email' => $contact->email,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to record contact deleted activity', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the Contact "restored" event.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return void
     */
    public function restored(Contact $contact): void
    {
        Log::info('Contact restored', [
            'contact_id' => $contact->id,
            'contact_name' => $contact->full_name,
        ]);

        try {
            $contact->recordActivity(
                Activity::TYPE_RESTORED,
                "Contact {$contact->full_name} was restored",
                [
                    'company_id' => $contact->company_id,
                    'contact_data' => [
                        'id' => $contact->id,
                        'first_name' => $contact->first_name,
                        'last_name' => $contact->last_name,
                        'email' => $contact->email,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to record contact restored activity', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the Contact "force deleted" event.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return void
     */
    public function forceDeleted(Contact $contact): void
    {
        Log::info('Contact force deleted', [
            'contact_id' => $contact->id,
            'contact_name' => $contact->full_name,
        ]);

        try {
            $contact->recordActivity(
                Activity::TYPE_FORCE_DELETED,
                "Contact {$contact->full_name} was permanently deleted",
                [
                    'company_id' => $contact->company_id,
                    'contact_data' => [
                        'id' => $contact->id,
                        'first_name' => $contact->first_name,
                        'last_name' => $contact->last_name,
                        'email' => $contact->email,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to record contact force deleted activity', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
} 