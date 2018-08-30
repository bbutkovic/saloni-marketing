<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreatedAccountVerify extends Notification
{
    use Queueable;
    
    private $email_code;
    private $user_password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email_code, $user_password)
    {
        $this->email_code = $email_code;
        $this->user_password = $user_password;
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
                    ->line('Your account has been created". To complete your registration, click on the button to verify your e-mail.')
                    ->line('Your password: ' . $this->user_password)
                    ->line('Do not forget to change your password after you log in')
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
