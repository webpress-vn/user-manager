<?php

namespace VCComponent\Laravel\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use VCComponent\Laravel\User\Mail\V1\CustomEmail;

class AdminResendPasswordNotification extends Notification
{
    use Queueable;
    protected $pass;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pass)
    {
        $this->pass = $pass;
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
            ->line("Hi " . $notifiable->email)
            ->line('<span style="color: #3490DC">This is your re-entered password</span> ' . $this->pass)
            ->action('Please click here to change the password', asset('login'));

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
