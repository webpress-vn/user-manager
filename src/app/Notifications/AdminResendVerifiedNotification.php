<?php

namespace VCComponent\Laravel\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use VCComponent\Laravel\User\Mail\V1\CustomEmail;

class AdminResendVerifiedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $token = ($notifiable->verify_token);

        return (new CustomEmail($notifiable))
            ->to($notifiable->email)
            ->subject('User Registered')
            ->line("Hi " . $notifiable->email . " Thank you for your registration.")
            ->line('<span style="color: #3490DC">Click on the button to verify your</span> ' . $notifiable->email . ' address and enter your Dashboard:')
            ->action('Verify my email address', asset("verify/{$notifiable->id}?token={$token}"))
            ->line("if not you ,You can click here to delete your email address from that account")
            ->actiondelete('Click here if not you', asset("verify-not-me/{$notifiable->id}?token={$token}"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
