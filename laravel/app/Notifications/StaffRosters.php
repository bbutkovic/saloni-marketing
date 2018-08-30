<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class StaffRosters extends Notification
{
    use Queueable;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($trans_sch, $trans_greeting, $trans_line, $trans_salutation, $date, $work_start, $work_end, $user_schedule)
    {
        $this->trans_sch = $trans_sch;
        $this->trans_greeting = $trans_greeting;
        $this->trans_line = $trans_line;
        $this->trans_salutation = $trans_salutation;
        $this->work_date = $date;
        $this->work_start = $work_start;
        $this->work_end = $work_end;
        $this->user_schedule = $user_schedule;
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
                    ->subject($this->trans_sch)
                    ->greeting($this->trans_greeting)
                    ->line($this->trans_line)
                    ->salutation($this->trans_salutation)
                    ->markdown('vendor.notifications.schedule', ['work_date' => $this->work_date, 'work_start' => $this->work_start, 'work_end' => $this->work_end, 'user_schedule' => $this->user_schedule]);
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
