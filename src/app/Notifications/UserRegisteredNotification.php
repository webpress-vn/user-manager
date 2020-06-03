<?php

namespace VCComponent\Laravel\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use VCComponent\Laravel\User\Mail\V1\CustomEmail;

class UserRegisteredNotification extends Notification
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
        // dd($notifiable);

        $token = $notifiable->verify_token;


        return (new CustomEmail($notifiable))
            ->to($notifiable->email)
            ->subject('User Registered')
            ->line("Thank you for your registration.")
            ->line('<span style="color: #dc4d2f">Click on the button to verify your</span> ' . $notifiable->email . ' address and enter your Dashboard:')
            ->action('Verify my email address', asset("verify?id={$notifiable->id}&token={$token}"));
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
