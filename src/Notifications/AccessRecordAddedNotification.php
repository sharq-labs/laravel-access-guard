<?php

namespace Sharqlabs\LaravelAccessGuard\Notifications;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

#[AllowDynamicProperties]
class AccessRecordAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user access browser instance.
     */
    protected $userAccessBrowser;

    /**
     * Create a new notification instance.
     */
    public function __construct($userAccessBrowser)
    {
        $this->userAccessBrowser = $userAccessBrowser;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Access Record Added')
            ->greeting('Hello,')
            ->line('A new access record has been added.')
            ->line('Details:')
            ->line('Email: ' . ($this->userAccessBrowser->record->email ?? 'N/A'))
            ->line('Domain: ' . ($this->userAccessBrowser->record->domain ?? 'N/A'))
            ->line('Session Ip: ' . ($this->userAccessBrowser->session_ip ?? 'N/A'))
            ->line('Browser: ' . ($this->userAccessBrowser->browser ?? 'N/A'))
            ->line('Expires At: ' . ($this->userAccessBrowser->expires_at ?? 'N/A'))
            ->line('Thank you for using our application!');
    }
}
