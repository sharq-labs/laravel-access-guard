<?php

namespace Sharqlabs\LaravelAccessGuard\Notifications;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

#[AllowDynamicProperties]
class ErrorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user access browser instance.
     */
    protected $userAccessBrowser;

    /**
     * Create a new notification instance.
     *
     * @param mixed $userAccessBrowser
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
            ->subject('Urgent: System Error Notification')
            ->greeting('Hello,')
            ->line('We have detected an error in the system that requires your attention.')
            ->line('**Error Details:**')
            ->line('- **Email:** ' . ($this->userAccessBrowser->userAccessRecord->email ?? 'N/A'))
            ->line('- **Domain:** ' . ($this->userAccessBrowser->userAccessRecord->domain ?? 'N/A'))
            ->line('- **Session IP:** ' . ($this->userAccessBrowser->session_ip ?? 'N/A'))
            ->line('- **Browser:** ' . ($this->userAccessBrowser->browser ?? 'N/A'))
            ->line('Please review the error details above and take necessary actions to resolve the issue.')
            ->action('View Error Logs', url('/admin/errors')) // Example URL for further actions
            ->line('If you need assistance, please contact support.')
            ->salutation('Best Regards,')
            ->salutation(config('app.name') . ' Support Team');
    }

}
