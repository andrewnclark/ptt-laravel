<?php

declare(strict_types=1);

namespace App\Listeners\Crm;

use App\Events\Crm\ContactSetAsPrimary;
use App\Models\Crm\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactSetAsPrimaryNotification;

class HandleContactSetAsPrimary implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\Crm\ContactSetAsPrimary  $event
     * @return void
     */
    public function handle(ContactSetAsPrimary $event): void
    {
        $contact = $event->contact;
        $company = $contact->company;
        
        Log::info('Contact set as primary', [
            'contact_id' => $contact->id,
            'contact_name' => $contact->full_name,
            'company_id' => $contact->company_id,
            'company_name' => $company->name ?? 'Unknown',
        ]);

        // 1. Create additional activity for the company entity
        if ($company) {
            try {
                $company->recordActivity(
                    Activity::TYPE_UPDATED,
                    "Primary contact changed to {$contact->full_name}",
                    [
                        'contact_id' => $contact->id,
                        'contact_name' => $contact->full_name,
                        'previous_primary' => $this->getPreviousPrimaryContactInfo($contact)
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Failed to record company activity for primary contact change', [
                    'company_id' => $company->id,
                    'contact_id' => $contact->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // 2. Update internal cache or computed fields if needed
        $this->updateRelatedRecords($contact);
        
        // 3. Send notifications about the primary contact change
        $this->sendNotifications($contact);
    }

    /**
     * Get information about the previously selected primary contact
     *
     * @param \App\Models\Crm\Contact $newPrimaryContact
     * @return array
     */
    protected function getPreviousPrimaryContactInfo($newPrimaryContact): array
    {
        $previousPrimary = $newPrimaryContact->company->contacts()
            ->where('id', '!=', $newPrimaryContact->id)
            ->where('is_primary', false)  // Now false, but was previously true
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if (!$previousPrimary) {
            return ['info' => 'No previous primary contact found'];
        }
        
        return [
            'id' => $previousPrimary->id,
            'name' => $previousPrimary->full_name,
            'email' => $previousPrimary->email
        ];
    }
    
    /**
     * Update any related records that rely on primary contact status
     *
     * @param \App\Models\Crm\Contact $contact
     * @return void
     */
    protected function updateRelatedRecords($contact): void
    {
        // If you have any related models that need to be updated when
        // a primary contact changes, handle that here.
        // For example, updating opportunities or tasks
        
        // Example (commented out as it depends on your data model):
        /*
        // Update any opportunities that rely on primary contact information
        $contact->company->opportunities()
            ->where('contact_id', null)
            ->update(['contact_id' => $contact->id]);
            
        // Update any open tasks to assign to the new primary contact
        $contact->company->tasks()
            ->whereNull('assigned_to')
            ->update(['contact_id' => $contact->id]);
        */
    }
    
    /**
     * Send notifications about the primary contact change
     *
     * @param \App\Models\Crm\Contact $contact
     * @return void
     */
    protected function sendNotifications($contact): void
    {
        // Check if the Notification class exists before attempting to use it
        if (!class_exists(ContactSetAsPrimaryNotification::class)) {
            Log::info('ContactSetAsPrimaryNotification class not found, skipping notifications');
            return;
        }
        
        try {
            // Example of sending to admin users (adjust based on your User model)
            // $adminUsers = \App\Models\User::where('role', 'admin')->get();
            // Notification::send($adminUsers, new ContactSetAsPrimaryNotification($contact));
            
            // Or notify the company's account manager if you have such a relationship
            if ($contact->company && isset($contact->company->account_manager)) {
                Notification::send(
                    $contact->company->account_manager,
                    new ContactSetAsPrimaryNotification($contact)
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send primary contact change notification', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
} 