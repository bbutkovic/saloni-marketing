<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

use App;
use App\User;
use App\Models\{Salons,CronjobChecks};
use App\Models\Salon\WeeklySchedule;
use App\Models\Booking\{Booking,Clients};
use App\Repositories\{InfoRepository,StaffRepository};
use App\Models\{Location,Languages};
use App\Models\Marketing\{Reminders,MarketingTemplate};
use App\Notifications\{StaffRosters,WaitingListNotification,AppointmentReminders,BirthdayNotification};
use Mockery\Exception;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //schedule for updating the weekly working hours for each staff member
        $schedule->call(function () {
            $salon_list = Salons::where('time_zone', '!=', null)->get();

            foreach($salon_list as $salon) {

                $current_date = new \DateTime("now", new \DateTimeZone($salon->time_zone));
                $cron_check = App\Models\CronjobChecks::where('salon_id', $salon->id)->where('cronjob_type', 'weekly_sch')->first();
                $current_day = $current_date->format('l');

                if($salon->week_starting_on != 1) {
                    $day_name = 'Sunday';
                } else {
                    $day_name = 'Monday';
                }

                if($current_day == $day_name) {

                    if(!$cron_check) {

                        $salon_staff = User::where('salon_id', $salon->id)->get();

                        foreach($salon_staff as $staff) {
                            if($staff->staff_hours->isNotEmpty()) {
                                if($staff->weekly_schedule->isNotEmpty()) {
                                    foreach($staff->weekly_schedule as $schedule) {
                                        $schedule->delete();
                                    }
                                }
                                $this->staff_repo->addToWeeklySchedule($staff);
                            }
                        }

                        $new_cron = new App\Models\CronjobChecks();
                        $new_cron->salon_id = $salon->id;
                        $new_cron->cronjob_type = 'weekly_sch';
                        $new_cron->finished = 1;
                        $new_cron->save();

                    } else {
                        if($cron_check) {
                            $cron_check->delete();
                        }
                    }
                }
            }
        })->hourly();
        
        $schedule->call(function () {
            
            $this->staff_repo = new StaffRepository;
            $this->info_repo = new InfoRepository;
            
            $salon_list = Salons::where('time_zone', '!=', null)->get();
            try {
                //do action based on salon timezone
                foreach ($salon_list as $salon) {

                    $cron_check = CronjobChecks::where('salon_id', $salon->id)->where('cronjob_type', 'weekly_to_email')->first();

                    $current_date = new \DateTime("now", new \DateTimeZone($salon->time_zone));
                    $salon_extras = $salon->salon_extras;
                    $current_day = $current_date->format('l');

                    //send notifications to clients about upcoming bookings
                    foreach($salon->locations as $location) {
                        $location = Location::find($location->id);
                        $reminder = Reminders::where('location_id', $location->id)->where('reminder_type', 1)->first();

                        if ($reminder != null && $reminder->reminder_status === 1) {
                            $booking_list = Booking::where('location_id', $location->id)->where('booking_date', '>=', $current_date->format('Y-m-d'))->groupBy('type_id')->get();

                            foreach ($booking_list as $booking) {
                                $time_diff = date('H', strtotime($reminder->send_before));
                                $booking_start = date('Y-m-d H:i', strtotime($booking->booking_date . ' ' . $booking->start));
                                $time_to_send = date('Y-m-d H:i', strtotime('-' . $time_diff . ' hour', strtotime($booking_start)));
                                $time_to_check = date('Y-m-d H:i', strtotime('+2 hour', strtotime($booking_start)));
                                $current_date_formatted = $current_date->format('Y-m-d H:i');
                                $reminder_check = CronjobChecks::where('salon_id', $location->salon_id)->where('cronjob_type', 'appointment_reminder')->where('client_id', $booking->client_id)->get();

                                if (count($reminder_check) < 1 && $current_date_formatted >= $time_to_send && $current_date_formatted < $time_to_check) {

                                    $booking->client->notify(new App\Notifications\AppointmentReminders($booking, $location));

                                    $cronjob = new CronjobChecks;
                                    $cronjob->salon_id = $location->salon_id;
                                    $cronjob->cronjob_type = 'appointment_reminder';
                                    $cronjob->client_id = $booking->client->id;
                                    $cronjob->finished = 1;
                                    $cronjob->save();

                                }

                            }
                        }
                    }

                    if ($salon_extras->email_staff_rosters == 1) {

                        $day_name = $this->info_repo->getSelectedDayName($salon, $salon_extras->email_day);

                        if ($current_day == $day_name && $current_date->format('H:i') >= $salon_extras->email_time) {

                            //if email wasnt ealready sent
                            if (!$cron_check) {

                                $salon_staff = User::where('salon_id', $salon->id)->get();

                                foreach ($salon_staff as $staff) {

                                    if ($staff->weekly_schedule->isNotEmpty() && $staff->staff_hours->isNotEmpty()) {
                                        $user_lang = $staff->language;
                                        $lang_name = Languages::find($user_lang)->language_iso;
                                        App::setLocale($lang_name);

                                        $date = trans('salon.date');
                                        $work_start = trans('salon.hours_start_time');
                                        $work_end = trans('salon.hours_end_time');
                                        $trans_sch = trans('salon.weekly_schedule');
                                        $trans_greeting = trans('salon.greeting') . $staff->user_extras->first_name;
                                        $trans_line = trans('salon.sch_line');
                                        $trans_salutation = trans('salon.salutation') . '</br>' . $salon->business_name;

                                        $user_arr = [];
                                        $user_arr[] = $staff;
                                        $user_schedule = $this->staff_repo->getWeeklySchedule($user_arr);

                                        $staff->notify(new StaffRosters($trans_sch, $trans_greeting, $trans_line, $trans_salutation, $date, $work_start, $work_end, $user_schedule));
                                    }

                                }

                                $new_cron = new CronjobChecks;
                                $new_cron->salon_id = $salon->id;
                                $new_cron->cronjob_type = 'weekly_to_email';
                                $new_cron->finished = 1;
                                $new_cron->save();

                            }

                        } else {
                            //delete cron check after day ends
                            if ($cron_check) {
                                $cron_check->delete();
                            }
                        }
                    }
                }

            }catch (Exception $exc) {
                Log::info($exc->getMessage());
            }
            
        })->hourly();

        $schedule->call(function () {

            try {

                $campaigns = App\Models\Salon\MarketingCampaign::all();

            } catch (Exception $exc) {
                Log::info($exc->getMessage());
            }

        })->weekly();

        //birthday check
        $schedule->call(function () {

            try {
                //do action based on salon timezone
                $clients = Clients::groupBy('email')->get();

                foreach($clients as $client) {
                    if($client->birthday != null) {
                        $birthday = date('Y-m-d', strtotime($client->birthday));
                        $client_location = Location::find($client->location_id);
                        $current_date = new \DateTime("now", new \DateTimeZone($client_location->salon->time_zone));
                        $curr_date_format = $current_date->format('Y-m-d');

                        if($curr_date_format === $birthday) {
                            $client->notify(new BirthdayNotification($client, $client_location));
                        }
                    }
                }

            } catch (Exception $exc) {
                Log::info($exc->getMessage());
            }

        })->daily();

        $schedule->call(function () {

            try {
                //do action based on salon timezone
                $clients = Clients::groupBy('email')->get();

                foreach($clients as $client) {
                    if($client->birthday != null) {
                        $birthday = date('Y-m-d', strtotime($client->birthday));
                        $client_location = Location::find($client->location_id);
                        $current_date = new \DateTime("now", new \DateTimeZone($client_location->salon->time_zone));
                        $curr_date_format = $current_date->format('Y-m-d');

                        if($curr_date_format === $birthday) {
                            $client->notify(new BirthdayNotification($client, $client_location));
                        }
                    }
                }

            } catch (Exception $exc) {
                Log::info($exc->getMessage());
            }

        })->daily();

        $schedule->call(function () {

            try {

                $this->client_repo = new App\Repositories\ClientRepository;
                $this->marketing_repo = new App\Repositories\MarketingRepository;

                $campaign_list = App\Models\Salon\MarketingCampaign::all();

                foreach($campaign_list as $campaign) {
                    if ($campaign->frequency !== 0) {

                        $location = $campaign->location;
                        $salon = Salons::find($location->salon_id);
                        $cronjob = App\Models\CronjobChecks::where('salon_id', $salon->id)->where('cronjob_type', 'campaign')->where('client_id', $campaign->id)->first();

                        if ($cronjob === null || ($cronjob != null && date('Y-m-d') >= date('Y-m-d', strtotime('+' . $campaign->campaign_frequency, strtotime(date('Y-m-d', strtotime($cronjob->created_at))))) && date('H:i') >= '12:00' && date('H:i') < '22:00')) {

                            $client_selection = $this->client_repo->getClientSelection($location, $campaign->clients_inactive, $campaign->clients_gender, $campaign->older_than, $campaign->with_label, $campaign->with_referral);

                            $send_campaign = $this->marketing_repo->sendCampaign($client_selection['clients'], $campaign);

                            if ($send_campaign['status'] === 1) {

                                if ($cronjob != null) {
                                    $cronjob->delete();
                                }

                                $new_cron = new App\Models\CronjobChecks();
                                $new_cron->salon_id = $salon->id;
                                $new_cron->cronjob_type = 'campaign';
                                $new_cron->client_id = $campaign->id;
                                $new_cron->finished = 1;
                                $new_cron->save();
                            }

                        }
                    }
                }

            } catch (Exception $exc) {
                Log::info($exc->getMessage());
            }

        })->hourly();

        $schedule->command('currency:update -o --force')->hourly();
    }



    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
