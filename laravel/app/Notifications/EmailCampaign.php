<?php

namespace App\Notifications;

use App\Models\Salons;
use App\Repositories\InfoRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class EmailCampaign extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($client, $campaign)
    {
        $this->client = $client;
        $this->campaign = $campaign;
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

        $location = $this->client->client_locations;

        $business_name = $location->location_name;
        $phone = $location->business_phone;
        $address = $location->address;
        $city = $location->city;
        $zip = $location->zip;
        $date = date('d.m.y');

        $content_fields = [
            '[ClientFirstName]' => $this->client->first_name,
            '[ClientLastName]' => $this->client->last_name,
            '[CurrentDate]' => $date,
            '[BusinessName]' => $business_name,
            '[BusinessPhone]' => $phone,
            '[BusinessAddress]' => $address,
            '[BusinessCity]' => $city,
            '[BusinessPostCode]' => $zip,
        ];

        $content = $this->info_repo->swapFields($this->campaign->content, $content_fields);

        if($this->campaign->gift_voucher != null) {

            $voucher = Vouchers::find($this->campaign->gift_voucher);

            return (new MailMessage)
                ->subject($this->campaign->subject)
                ->from($location->email_address, $location->location_name)
                ->line($content)
                ->line(trans('salon.marketing_voucher', ['discount' => $voucher->discount]))
                ->line(trans('salon.marketing_voucher_code', ['code' => $voucher->code]))
                ->line(trans('salon.voucher_available_to'))
                ->markdown('vendor.notifications.email', ['content' => $content, 'location_name' => $location->location_name, 'logo' => URL::to('/').'/images/location-logo/'.$location->location_extras->location_photo]);

        } else {
            return (new MailMessage)
                ->subject($this->campaign->subject)
                ->from($this->location->email_address, $this->location->location_name)
                ->line($content)
                ->markdown('vendor.notifications.email', ['content' => $content, 'location_name' => $location->location_name, 'logo' => URL::to('/').'/images/location-logo/'.$location->location_extras->location_photo]);
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
