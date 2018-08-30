<?php

namespace App\Notifications;

use App\Models\Booking\Booking;
use App\Models\Location;
use App\Models\Marketing\MarketingTemplate;
use App\Models\Marketing\Reminders;
use App\Models\Salon\Vouchers;
use App\Models\Salons;
use App\Repositories\InfoRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class WaitingListNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking, $dates)
    {
        $this->booking = $booking;
        $this->dates = $dates;
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
        $location = Location::find($this->booking->location_id);
        $salon = Salons::find($location->salon_id);

        $business_name = $location->location_name;
        $phone = $location->business_phone;
        $address = $location->address;
        $city = $location->city;
        $zip = $location->zip;
        $date = date('d.m.y');

        $reminder = Reminders::where('location_id', $location->id)->where('reminder_type', 3)->first();
        $template = MarketingTemplate::where('location_id', $location->id)->where('id', $reminder->email_template)->first();

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
        $dates = implode(', ', $this->dates);

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
            '[LoyaltyPoints]' => $loyalty_points,
            '[AvailableRescheduleDates]' => $dates
        ];

        $content = $this->info_repo->swapFields($template->content, $content_fields);

        if($reminder->gift_voucher != null) {

            $voucher = Vouchers::find($reminder->gift_voucher);

            return (new MailMessage)
                ->subject($template->subject)
                ->from($location->email_address, $location->location_name)
                ->line($content)
                ->line(trans('salon.marketing_voucher', ['discount' => $voucher->discount]))
                ->line(trans('salon.marketing_voucher_code', ['code' => $voucher->code]))
                ->line(trans('salon.voucher_available_to'))
                ->markdown('vendor.notifications.email', ['content' => $content, 'location_name' => $location->location_name, 'logo' => URL::to('/').'/images/location-logo/'.$location->location_extras->location_photo]);

        } else {
            return (new MailMessage)
                ->subject($template->subject)
                ->from($location->email_address, $location->location_name)
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
