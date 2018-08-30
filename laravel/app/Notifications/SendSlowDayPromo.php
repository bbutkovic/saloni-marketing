<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendSlowDayPromo extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($trans_subject, $trans_greeting, $trans_salutation, $promo_code_trans, $line)
    {
        $this->trans_subject = $trans_subject;
        $this->trans_greeting = $trans_greeting;
        $this->trans_salutation = $trans_salutation;
        $this->promo_code_trans = $promo_code_trans;
        $this->line = $line;
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
        return (new MailMessage)
                    ->subject($this->trans_subject)
                    ->greeting($this->trans_greeting)
                    ->line($this->line)
                    ->line($this->promo_code_trans)
                    ->salutation($this->trans_salutation);
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
