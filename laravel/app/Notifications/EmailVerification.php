<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerification extends Notification
{
    use Queueable;

    private $email_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email_code)
    {
        $this->email_code = $email_code;
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
        $url = url('/verify/'.$this->email_code);

        return (new MailMessage)
                    ->subject('Email verification')
                    ->greeting('Hello!')
                    ->line('You have successfully registered to "Saloni marketing". To complete your registration, click on the button to verify your e-mail.')
                    ->action('Verify e-mail', $url)
                    ->line('Thank you for using our application!')
                    ->markdown('vendor.notifications.email', ['location_name' => 'Saloni Marketing', 'logo' => null]);
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
