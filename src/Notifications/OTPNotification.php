<?php

namespace Sharqlabs\LaravelAccessGuard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OTPNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $otp;

    /**
     * Create a new notification instance.
     *
     * @param string $otp
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your One-Time Password (OTP) for Secure Access')
            ->greeting('Hello!')
            ->line('You are receiving this email because you requested access to your account.')
            ->line('Your One-Time Password (OTP) is:')
            ->line("**{$this->otp}**")
            ->line('This OTP is valid for a limited time only. Please do not share it with anyone.')
            ->line('If you did not request this OTP, please ignore this email or contact support immediately.')
            ->salutation('Best Regards,')
            ->salutation(config('app.name') . ' Team');    }
}
