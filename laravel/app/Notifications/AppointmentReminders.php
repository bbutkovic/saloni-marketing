<?php

namespace App\Notifications;

use App\Models\Salons;
use App\Repositories\InfoRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Marketing\{MarketingTemplate,Reminders};
use Illuminate\Support\Facades\URL;
use App\Models\Booking\Booking;

class AppointmentReminders extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking, $location)
    {
        $this->booking = $booking;
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

        $salon = Salons::find($this->location->salon_id);

        $business_name = $this->location->location_name;
        $phone = $this->location->business_phone;
        $address = $this->location->address;
        $city = $this->location->city;
        $zip = $this->location->zip;
        $date = date('d.m.y');

        $reminder = Reminders::where('location_id', $this->location->id)->where('reminder_type', 1)->first();
        $template = MarketingTemplate::where('location_id', $this->location->id)->where('id', $reminder->email_template)->first();
        $staff_list = [];
        $service_list = [];

        if($this->booking->type === 'multiple') {
            $booking_list = Booking::where('type_id', $this->booking->type_id)->get();
            foreach($booking_list as $booking_single) {
                $staff_list[] = $booking_single->staff->user_extras->first_name . ' ' . $booking_single->staff->user_extras->last_name;
                $service_list[] = $booking_single->service->service_details->name;
            }
        } else {
            $staff_list[] = $this->booking->staff->user_extras->fist_name . ' ' . $this->booking->staff->user_extras->last_name;
            $service_list[] = $this->booking->service->service_details->name;
        }

        $staff = implode(', ', $staff_list);
        $services = implode(', ', $service_list);
        $loyalty_points = $this->booking->client->loyalty_points;
        $price = $this->booking->pricing->price . ' ' . $salon->currency;

        $content_fields = [
            '[ClientFirstName]' => $this->booking->client->first_name,
            '[ClientLastName]' => $this->booking->client->last_name,
            '[AppointmentDate]' => date('F j, Y', strtotime($this->booking->booking_date)),
            '[AppointmentTime]' => $this->booking->start,
            '[Price]' => $price,
            '[StaffList]' => $staff,
            '[ServiceList]' => $services,
            '[CurrentDate]' => $date,
            '[BusinessName]' => $business_name,
            '[BusinessPhone]' => $phone,
            '[BusinessAddress]' => $address,
            '[BusinessCity]' => $city,
            '[BusinessPostCode]' => $zip,
            '[LoyaltyPoints]' => $loyalty_points
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
