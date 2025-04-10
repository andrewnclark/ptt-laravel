<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Crm\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSetAsPrimaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The contact that was set as primary.
     *
     * @var \App\Models\Crm\Contact
     */
    protected Contact $contact;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $companyName = $this->contact->company->name ?? 'Company';
        
        return (new MailMessage)
            ->subject("Primary Contact Changed for {$companyName}")
            ->greeting("Hello!")
            ->line("{$this->contact->full_name} has been set as the primary contact for {$companyName}.")
            ->line("Contact details:")
            ->line("Email: {$this->contact->email}")
            ->line("Phone: {$this->contact->phone}")
            ->action('View Contact', url("/admin/crm/contacts/{$this->contact->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $companyName = $this->contact->company->name ?? 'Company';
        
        return [
            'contact_id' => $this->contact->id,
            'contact_name' => $this->contact->full_name,
            'company_id' => $this->contact->company_id,
            'company_name' => $companyName,
            'message' => "Primary contact changed to {$this->contact->full_name} for {$companyName}",
            'type' => 'primary_contact_changed',
        ];
    }
} 