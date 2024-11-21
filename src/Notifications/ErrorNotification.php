<?php

namespace Sharqlabs\LaravelAccessGuard\Notifications;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

#[AllowDynamicProperties] class ErrorNotification extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @param string $userAccessBrowser
     */
    public function __construct($userAccessBrowser)
    {
        $this->userAccessBrowser = $userAccessBrowser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Error Notification')
            ->greeting('Hello,')
            ->line('An error has occurred in the system:')
            ->line('Details:')
            ->line('Email: ' . ($this->userAccessBrowser->email ?? 'N/A'))
            ->line('Domain: ' . ($this->userAccessBrowser->domain ?? 'N/A'))
            ->line('Session Ip: ' . ($this->userAccessBrowser->session_ip ?? 'N/A'))
            ->line('browser: ' . ($this->userAccessBrowser ?? 'N/A'))
            ->line('expires at: ' . ($this->expires_at ?? 'N/A'))
            ->line('Thank you for using our application!');
    }
}
