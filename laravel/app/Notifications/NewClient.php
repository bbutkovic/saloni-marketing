<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\Marketing\{Reminders,MarketingTemplate};
use App\Models\Salon\Vouchers;
use Illuminate\Support\Facades\URL;

use App\Repositories\InfoRepository;

class NewClient extends Notification
{
    use Queueable;

    public $location;

    protected $info_repo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($first_name,$last_name,$location)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->location = $location;
        $this->info_repo = new InfoRepository;
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
        $business_name = $this->location->location_name;
        $phone = $this->location->business_phone;
        $address = $this->location->address;
        $city = $this->location->city;
        $zip = $this->location->zip;
        $date = date('d.m.y');

        $reminder = Reminders::where('location_id',$this->location->id)->where('reminder_type',7)->first();
        $template = MarketingTemplate::where('location_id',$this->location->id)->where('id', $reminder->email_template)->first();

        $content_fields = [
            '[ClientFirstName]' => $this->first_name,
            '[ClientLastName]' => $this->last_name,
            '[CurrentDate]' => $date,
            '[BusinessName]' => $business_name,
            '[BusinessPhone]' => $phone,
            '[BusinessAddress]' => $address,
            '[BusinessCity]' => $city,
            '[BusinnessPostCode]' => $zip
        ];

        $content = $this->info_repo->swapFields($template->content, $content_fields);

        if($reminder->gift_voucher != null) {
            $voucher = Vouchers::find($reminder->gift_voucher);

            return (new MailMessage)
                ->subject($template->subject)
                ->from($this->location->email_address, $this->location->location_name)
                ->line($content)
                ->line(trans('salon.marketing_voucher', ['discount' => $voucher->discount]))
                ->line(trans('salon.marketing_voucher_code', ['code' => $voucher->code]))
                ->line(trans('salon.voucher_available_to'))
                ->markdown('vendor.notifications.email', ['content' => $content, 'location_name' => $this->location->location_name, 'logo' => URL::to('/').'/images/location-logo/'.$this->location->location_extras->location_photo]);

        } else {
            return (new MailMessage)
                ->subject($template->subject)
                ->from($this->location->email_address, $this->location->location_name)
                ->line($content)
                ->markdown('vendor.notifications.email', ['content' => $content, 'location_name' => $this->location->location_name, 'logo' => URL::to('/').'/images/location-logo/'.$this->location->location_extras->location_photo]);
        }
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
