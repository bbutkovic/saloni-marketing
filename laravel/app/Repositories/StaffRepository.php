<?php

namespace App\Repositories;

use Illuminate\Support\Facades\{Auth,Hash,Validator};
use App\Repositories\InfoRepository;
use App\Notifications\CreatedAccountVerify;
use App\Models\Salon\{SalonExtras,StaffHours,ScheduleChanges,CheckIn,StaffVacation,WeeklySchedule,UserScheduleOptions,Service,ServiceStaff};
use App\Models\Users\{UserExtras};
use App\Models\{Location,StaffServices,Salons,LocalHours};
use App\Models\Booking\Booking;
use App\{Role,User};
use Carbon\Carbon;
use DateTime;
use DB;

class StaffRepository {
    
    public function __construct() {
        return $this->info_repo = new InfoRepository;
    }
    
    public function addNewMember($data) {
        
        try {
            
            DB::beginTransaction();
            
            $user = new User;
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->language = Auth::user()->language;
            $user->email_verified = 0;
            $user->salon_id = Auth::user()->salon_id;
            $user->location_id = $data['location'];
            $user->remember_token = substr(md5(rand()), 0, 40);
            $user->save();
            
            $user->attachRole($data['role']);
            
            $user_extras = new UserExtras;
            $user_extras->user_id = $user->id;
            $user_extras->first_name = $data['first_name'];
            $user_extras->last_name = $data['last_name'];
            $user_extras->photo = '/images/user_placeholder.png';
            $user_extras->available_booking = 1;
            $user_extras->save();
            
            DB::commit();
            
            $user->notify(new CreatedAccountVerify($user->remember_token, $data['password']));
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function updateProfile($data) {

        try {

            $user_extras = UserExtras::where('user_id', $data['user_id'])->first();
            
            if(isset($data['staff_photo']) && $data['staff_photo'] != 'undefined') {
                $mime_type = $data['staff_photo']->getClientOriginalExtension();
                $image_name = substr(md5(rand()), 0, 15) . '.' . $mime_type;
                $data['staff_photo']->move(public_path() . '/images/profile/', $image_name);
            }

            $user_extras->first_name = $data['first_name'];
            $user_extras->last_name = $data['last_name'];
            $user_extras->birthday = date('Y-m-d', strtotime($data['birthday']));
            $user_extras->phone_number = $data['phone'];
            $user_extras->address = $data['address'];
            $user_extras->city = $data['city'];
            $user_extras->photo = isset($image_name) ? '/images/profile/'.$image_name : '/images/user_placeholder.png';
            $user_extras->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function updateSecurity($data, $user) {
        
        try {
            
            if(isset($data['email'])) {
                $user->email = $data['email'];
            }
            
            if(isset($data['password']) && isset($data['password_confirmation'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function updateStaffSettings($data) {

        try {

            $salon = Salons::find(Auth::user()->salon_id);
            $salon_extras = SalonExtras::where('salon_id', $salon->id)->first();
            $salon_extras->email_staff_rosters = $data['email_rosters'];
            $salon_extras->email_day = $data['weekday'];
            $salon_extras->email_time = $data['time'];
            $salon_extras->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }
    
    public function saveSchedule($user, $data) {

        try {
            
            $first_date = key($data['schedule']);
            
            foreach($data['schedule'] as $key=>$schedule_data) {

                $starting_date = date('Y-m-d', strtotime($key));
                
                if($data['repeatFor'] != 0) {
                    $end_date = date('Y-m-d', strtotime('+'.$data['repeatFor'].' week', strtotime($first_date)));
                } else {
                    $end_date = '2100-01-01';
                }
    
                $options_new = new UserScheduleOptions;
                $options_new->staff_id = $data['uid'];
                $options_new->display_weeks = $data['repeatWeeks'];
                $options_new->starting_date = $key;
                $options_new->end_date = $end_date;
                
                //get week
                $week_count = UserScheduleOptions::where('staff_id', $data['uid'])->max('week');
                if($week_count != null) {
                    $week = $week_count + 1;
                } else {
                    $week = 1;
                }

                $options_new->week = $week;
                $options_new->save();
                
                $days_array = $this->info_repo->getWeekDays();
                
                foreach($schedule_data as $week_sch) {
                    
                    $day_id = 1;
                    
                    for($i = 0; $i<7; $i++) {

                        if($user->salon->week_starting_on == 2 && $i == 0) {
                            $day_id = 0;
                        }
                        
                        $work_start = $week_sch[$i]['work_start'];
                        $work_end = $week_sch[$i]['work_end'];
                        $lunch_start = $week_sch[$i]['lunch_start'];
                        $lunch_end = $week_sch[$i]['lunch_end'];

                        $schedule = new StaffHours;
                        $schedule->staff_id = $data['uid'];
                        $schedule->day = $day_id;
                        $schedule->week = $week;
                        
                        if($week_sch[$i]['working'] === '1') {

                            if($lunch_start != 0 && $lunch_end != 0 && ($work_start > $work_end || $lunch_start > $lunch_end || $lunch_start < $work_start)) {
                                return ['status' => 0, 'message' => trans('salon.hours_error')];
                            }
                            
                            $schedule->status = 1;
                            $schedule->work_start = $work_start;
                            $schedule->work_end = $work_end;
                            $schedule->lunch_start = $lunch_start;
                            $schedule->lunch_end = $lunch_end;
                            
                        } else {

                            $schedule->status = 0;
                            $schedule->work_start = null;
                            $schedule->work_end = null;
                            $schedule->lunch_start = null;
                            $schedule->lunch_end = null;
                        }
                        
                        $day_id++;
                        
                        $schedule->save();

                    }
                }
            }
            
            $this->addToWeeklySchedule($user);
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function addTime($hours, $salon_id) {
        
        if($hours['work_start'] != null) {
            $work_start = date('H:i', strtotime($hours['work_start']));
        } else {
            $work_start = trans('salon.not_scheduled');
        }
        
        if($hours['work_end'] != null) {
            $work_end = date('H:i', strtotime($hours['work_end']));
        } else {
            $work_end = trans('salon.not_scheduled');
        }
        
        if($hours['lunch_start'] != null && $hours['lunch_start'] != 0) {
            $lunch_start = date('H:i', strtotime($hours['lunch_start']));
        } else {
            $lunch_start = trans('salon.not_scheduled');
        }
        
        if($hours['lunch_end'] != null && $hours['lunch_end'] != 0) {
            $lunch_end = date('H:i', strtotime($hours['lunch_end']));
        } else {
            $lunch_end = trans('salon.not_scheduled');
        }
        
        $salon = Salons::find($salon_id);
        
        if($salon->time_format == 'time-ampm') {
            if($work_start != 'Not scheduled' || $work_end != 'Not scheduled' || $lunch_start != 'Not scheduled' || $lunch_end != 'Not scheduled') {
                $work_start = date('h:i A', strtotime($work_start));
                $work_end = date('h:i A', strtotime($work_end));
                $lunch_start = date('h:i A', strtotime($lunch_start));
                $lunch_end = date('h:i A', strtotime($lunch_end));
            }
        }
        
        $time = [
            'start' => $work_start,
            'end' => $work_end,
            'lunch_start' => $lunch_start,
            'lunch_end' => $lunch_end
        ];
        
        return $time;
        
    }
    
    public function createSchedule($user, $duration = null, $option = null, $day_diff = null) {
        
        $salon_id = $user->salon_id;
        
        if($user->schedule_options != null) {

            if($user->salon->week_starting_on == 1) {
                $local = 'N';
                $current_day_check = 1;
            } else {
                $local = 'w';
                $current_day_check = 0;
            }
            
            $schedule_arr = [];
            
            foreach($user->schedule_options as $sch_opt) {
                $week = $sch_opt->week;
                $week_repeats = $sch_opt->display_weeks;
                $staff_hours = $user->staff_hours;
                
                $hours_array = [];
                
                foreach($staff_hours as $staff_hour) {
                    if($staff_hour->week === $week) {
                        $hours_array[] = [
                            'day' => $staff_hour->day,
                            'work_start' => $staff_hour->work_start,
                            'work_end' => $staff_hour->work_end,
                            'lunch_start' => $staff_hour->lunch_start,
                            'lunch_end' => $staff_hour->lunch_end
                        ];
                    }
                }
                
                $schedule_arr[$week] = ['start_date' => $sch_opt->starting_date,'end_date' => $sch_opt->end_date, 'hours' => $hours_array];
            }

            $start_date = date('Y-m-d');

            if($week_repeats == 2) {
                $start_date_1 = $user->schedule_options[0]->starting_date;
                $end_date_1 = $user->schedule_options[0]->end_date;
                $start_date_2 = $user->schedule_options[1]->starting_date;
                $end_date_2 = $user->schedule_options[1]->end_date;
            } else if ($week_repeats == 3) {
                $start_date_1 = $user->schedule_options[0]->starting_date;
                $end_date_1 = $user->schedule_options[0]->end_date;
                $start_date_2 = $user->schedule_options[1]->starting_date;
                $end_date_2 = $user->schedule_options[1]->end_date;
                $start_date_3 = $user->schedule_options[2]->starting_date;
                $end_date_3 = $user->schedule_options[2]->end_date;
            } else if ($week_repeats == 4) {
                $start_date_1 = $user->schedule_options[0]->starting_date;
                $end_date_1 = $user->schedule_options[0]->end_date;
                $start_date_2 = $user->schedule_options[1]->starting_date;
                $end_date_2 = $user->schedule_options[1]->end_date;
                $start_date_3 = $user->schedule_options[2]->starting_date;
                $end_date_3 = $user->schedule_options[2]->end_date;
                $start_date_4 = $user->schedule_options[3]->starting_date;
                $end_date_4 = $user->schedule_options[3]->end_date;
            }
            $end_date = null;

            foreach ($schedule_arr as $schedule) {
                if($end_date) {
                    if ($schedule['end_date'] > $end_date) {
                        $end_date = $schedule['end_date'];
                    }
                } else {
                    $end_date = $schedule['end_date'];
                }
            }

            if($end_date === '2100-01-01') {
                $end_date = date('Y-m-d', strtotime('+12 month', strtotime($start_date)));
            }
            
            if($duration) {
                $end_date = date('Y-m-d', strtotime($duration, strtotime($start_date)));
            }

            if($option) {

                $date_difference = 6 - $day_diff;
                $i = 0;
                $past_date = date('Y-m-d', strtotime('-'.$date_difference.' day', strtotime($start_date)));
                while($i < $date_difference) {
                    $past_dayname = date('l', strtotime($past_date));
                    $past_dayofweek = date($local, strtotime($past_date));
                    
                    $past_work_start =  trans('salon.not_scheduled');
                    $past_work_end = trans('salon.not_scheduled');
                    $past_lunch_start = trans('salon.not_scheduled');
                    $past_lunch_end = trans('salon.not_scheduled');
                    
                    
                    $time = [
                        'start' => $past_work_start,
                        'end' => $past_work_end,
                        'lunch_start' => $past_lunch_start,
                        'lunch_end' => $past_lunch_end
                    ];
                    
                    
                    $schedule_initial[] = [
                        'date' => [
                            'user_id' => $user->id,
                            'date' => $past_date,
                            'dayname' => $past_dayname,
                            'dayofweek' => $past_dayofweek
                        ],
                        'timetable' => $time
                    ];
                    
                    $i++;
                    
                    $past_date = date('Y-m-d', strtotime('+1 day', strtotime($past_date)));
                }
            }

            $ref_date = $user->schedule_options[0]->starting_date;

            while($start_date <= $end_date) {

                $dayofweek = date($local, strtotime($start_date));

                $dayname = date('l', strtotime($start_date));

                $str_date = $user->schedule_options[0]->starting_date;

                $time = [
                    'start' => trans('salon.not_scheduled'),
                    'end' => trans('salon.not_scheduled'),
                    'lunch_start' => trans('salon.not_scheduled'),
                    'lunch_end' => trans('salon.not_scheduled')
                ];

                if($week_repeats == 1) {
                    foreach($staff_hours as $hour) {
                        if($hour->day == $dayofweek) {
                            $time = $this->addTime($hour, $salon_id);
                        }
                    }

                } else if($week_repeats == 2) {
                    
                    $curr_week = 1;
                    
                    while($str_date <= $start_date) {

                        $current_day = date($local, strtotime($str_date));
                        
                        if($current_day == $current_day_check && $str_date != $ref_date) {
                            if($curr_week == 1) {
                                $curr_week = 2;
                            } else {
                                $curr_week = 1;
                            }
                        }
                        
                        $str_date = date('Y-m-d', strtotime('+1 day', strtotime($str_date)));
                    }
                    
                    foreach($schedule_arr[$curr_week]['hours'] as $hours) {
                        if($curr_week == 1 && $start_date >= $start_date_1 && $start_date <= $end_date_1 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                        
                        if ($curr_week == 2 && $start_date >= $start_date_2 && $start_date <= $end_date_2 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                    }
                    
                } else if($week_repeats == 3) {

                    $curr_week = 1;
                    
                    while($str_date <= $start_date) {

                        $current_day = date($local, strtotime($str_date));

                        if($current_day == $current_day_check && $str_date != $ref_date) {
                            if($curr_week == 1) {
                                $curr_week = 2;
                            } else if ($curr_week == 2) {
                                $curr_week = 3;
                            } else {
                                $curr_week = 1;
                            }
                        }
                        
                        $str_date = date('Y-m-d', strtotime('+1 day', strtotime($str_date)));
                    }
                    
                    
                    foreach($schedule_arr[$curr_week]['hours'] as $hours) {
                        if($curr_week == 1 && $start_date >= $start_date_1 && $start_date <= $end_date_1 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                        
                        if ($curr_week == 2 && $start_date >= $start_date_2 && $start_date <= $end_date_2 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                        
                        if ($curr_week == 3 && $start_date >= $start_date_3 && $start_date <= $end_date_3 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                    }

                } else if($week_repeats == 4) {
                    
                    $curr_week = 1;
                    
                    while($str_date <= $start_date) {

                        $current_day = date($local, strtotime($str_date));
                        
                        if($current_day == $current_day_check && $str_date != $ref_date) {
                            if($curr_week == 1) {
                                $curr_week = 2;
                            } else if ($curr_week == 2) {
                                $curr_week = 3;
                            } else if ($curr_week == 3) {
                                $curr_week = 4;
                            } else {
                                $curr_week = 1;
                            }
                        }
                        
                        $str_date = date('Y-m-d', strtotime('+1 day', strtotime($str_date)));
                    }

                    foreach($schedule_arr[$curr_week]['hours'] as $hours) {
                        if($curr_week == 1 && $start_date >= $start_date_1 && $start_date <= $end_date_1 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                        
                        if ($curr_week == 2 && $start_date >= $start_date_2 && $start_date <= $end_date_2 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                        
                        if ($curr_week == 3 && $start_date >= $start_date_3 && $start_date <= $end_date_3 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                        
                        if ($curr_week == 4 && $start_date >= $start_date_4 && $start_date <= $end_date_4 && $hours['day'] == $dayofweek) {
                            $time = $this->addTime($hours, $salon_id);
                        }
                    }
                    
                }
                
                $schedule_initial[] = [
                    'date' => [
                        'user_id' => $user->id,
                        'date' => $start_date,
                        'dayname' => $dayname,
                        'dayofweek' => $dayofweek
                    ],
                    'timetable' => $time
                ];
                
                $start_date = date('Y-m-d', strtotime('+1 day', strtotime($start_date)));
            }

            $changed_schedule = $this->checkForScheduleChanges($schedule_initial, $user);
            
            $final_schedule = $this->checkVacations($user, $changed_schedule);
            
            return $final_schedule;
            
        }

    }
    
    public function updateUserLocation($user, $location_id) {
        $user->location_id = $location_id;
        if($user->save()) {
            return ['status' => 1];
        }
    }
    
    public function updateSchedule($data) {
        
        if($user = User::find($data['uid'])) {
            
            $check_affected_bookings = $this->checkBookings($user, $data);
            
            if($check_affected_bookings['status'] === 1) {
                return ['status' => 0, 'message' => trans('salon.bookings_affected'), 'bookings' => $check_affected_bookings['bookings']];   
            }
            
            $updated_sch = $this->updateTimes($user, $data);

            if($updated_sch['status'] === 1) {
                $this->updateWeekly($user, $data);
            }
            
            return ['status' => 1, 'dates' => $updated_sch['updated_days']];
    
        }
        return ['status' => 0];
    }
    
    public function checkBookings($user, $data) {
        
        $selected_date = date('Y-m-d', strtotime($data['selected_date']));
        $end_date = date('Y-m-d', strtotime($data['end_date']));
        $affected_bookings = [];
        $salon = Salons::find($user->salon_id);
        
        while($selected_date <= $end_date) {
            
            $booking_list = Booking::where('staff_id', $user->id)->where('booking_date', $selected_date)->groupBy('type_id')->get();
            
            if($booking_list->isNotEmpty()) {
                foreach($booking_list as $booking) {
                    if($booking->start < $data['work_start'] || $booking->booking_end > $data['work_end'] || !($booking->booking_end <= $data['lunch_start'] || $booking->start >= $data['lunch_end'])) {
                        
                        if($salon->time_format != 'time-24') {
                            $start_time = date('H:i A', strtotime($booking->start));
                            $end_time = date('H:i A', strtotime($booking->booking_end));
                        } else {
                            $start_time = date('H:i', strtotime($booking->start));
                            $end_time = date('H:i', strtotime($booking->booking_end));
                        }
                        
                        $affected_bookings[] = [
                            'id' => $booking->id,
                            'service' => $booking->service->service_details->name,
                            'booking_date' => $booking->booking_date,
                            'start' => $start_time,
                            'end' => $end_time,
                        ];
                    }
                }
            }
            
            $selected_date = date('Y-m-d', strtotime('+1 day', strtotime($selected_date)));
            
        }
        
        if(count($affected_bookings) > 0) {
            return ['status' => 1, 'bookings' => $affected_bookings];
        } else {
            return ['status' => 0];
        }
        
    }
    
    public function updateWeekly($user, $data) {
        
        $weekly_schedule = WeeklySchedule::where('user_id', $user->id)->get();
        
        try {
            
            $selected_date = date('Y-m-d', strtotime($data['selected_date']));
            $end_date = date('Y-m-d', strtotime($data['end_date']));

            while($selected_date <= $end_date) {
            
                $weekly_sch = WeeklySchedule::where('user_id', $user->id)->where('schedule_date', $selected_date)->first();
                
                if($weekly_sch != null) {
                    $weekly_sch->work_start = $data['work_start'];
                    $weekly_sch->work_end = $data['work_end'];
                    $weekly_sch->save();
                }
                
                $selected_date = date('Y-m-d', strtotime('+1 day', strtotime($selected_date)));
                
            }
            
            return ['status' => 1];
            
        }catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function checkForScheduleChanges($schedule, $user) {
        
        if(isset($user->changes)) {
            foreach($schedule as $key=>$val) {
                foreach($user->changes as $change) {
                    if($val['date']['date'] === $change->start_date) {
                        $time = $this->addTime($change, $user->salon_id);
                        $val['timetable'] = $time;
                    }
                }
                $new_schedule[] = $val;
            }
            return $new_schedule;
        } else {
            return $schedule;
        }
        
    }
    
    public function updateTimes($user, $data) {
        
        try {
            
            $this->deletePreviousChanges($user, $data);
            
            $selected_date = date('Y-m-d', strtotime($data['selected_date']));
            $end_date = date('Y-m-d', strtotime($data['end_date']));
            $updated_dates = [];
            
            while($selected_date <= $end_date) {
                
                if($data['working_status'] === 'on') {
                    $work_start = $data['work_start'];
                    $work_end = $data['work_end'];
                    $lunch_start = $data['lunch_start'];
                    $lunch_end = $data['lunch_end'];
                } else {
                    $work_start = null;
                    $work_end = null;
                    $lunch_start = null;
                    $lunch_end = null;
                }
                
                $schedule_change = new ScheduleChanges;
                $schedule_change->staff_id = $data['uid'];
                $schedule_change->location_id = $user->location_id;
                $schedule_change->type = 1;
                $schedule_change->start_date = $selected_date;
                $schedule_change->end_date = $end_date;
                $schedule_change->work_start = $work_start;
                $schedule_change->work_end = $work_end;
                $schedule_change->lunch_start = $lunch_start;
                $schedule_change->lunch_end = $lunch_end;
                $schedule_change->save();
                
                $updated_dates[] = [
                    'date' => $selected_date,
                    'start' => $data['work_start'],
                    'end' => $data['work_end'],
                    'lunch_start' => $data['lunch_start'],
                    'lunch_end' => $data['lunch_end'],
                ];
                
                $selected_date = date('Y-m-d', strtotime('+1 day', strtotime($selected_date)));
            }
            
            return ['status' => 1, 'updated_days' => $updated_dates];
            
        } catch (Exception $exc) {
            return ['status' => 0, $exc->getMessage()];
        }
    }
    
    public function deletePreviousChanges($user, $data) {
        foreach($user->changes as $change) {
            if($change->start_date == $data['selected_date']) {
                $delete_sch_change = ScheduleChanges::where('type', 1)->first();
                $delete_sch_change->delete();
            }
        }
    }
    
    public function getStaffHours($staff) {
        foreach($staff as $user) {
            if(!$user->staff_hours->isEmpty()) {
                $employee_hours[] = [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->user_extras->first_name . ' ' . $user->user_extras->last_name,
                        'hours' => '30'
                    ],
                    'staff_hours' => $this->createSchedule($user)
                ];
            } else {
                $employee_hours[] = [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->user_extras->first_name . ' ' . $user->user_extras->last_name,
                        'hours' => '0'
                    ],
                    'staff_hours' => null,
                ];
            }
        }
        return $employee_hours;
    }
    
    public function addVacation($data) {
        
        Validator::make($data, StaffVacation::$vacation_rules)->validate();
        
        try {
            $vacation = new StaffVacation;
            $vacation->user_id = $data['select_staff'];
            $vacation->start_date = $data['vacation_start'];
            $vacation->end_date = $data['vacation_end'];
            $vacation->note = $data['note'];
            $vacation->save();
            
            return ['status' => 1];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function checkVacations($user, $changed_schedule) {

        if($user->vacation) {
            foreach($changed_schedule as $schedule) {
                foreach($user->vacation as $user_vacation) {
                    if(($schedule['date']['date'] >= $user_vacation->start_date) && ($schedule['date']['date'] <= $user_vacation->end_date)) {
                        $time = [
                            'start' => 'vacation',
                            'end' => 'vacation',
                            'lunch_start' => 'vacation',
                            'lunch_end' => 'vacation'
                        ];
                        
                        $schedule['timetable'] = $time;
                    } else {
                        $time = [
                            'start' => $schedule['timetable']['start'],
                            'end' => $schedule['timetable']['end'],
                            'lunch_start' => $schedule['timetable']['lunch_start'],
                            'lunch_end' => $schedule['timetable']['lunch_end']
                        ];
                        
                        $schedule['timetable'] = $time;
                    }
                }
                $vacation_schedule[] = $schedule;
            }
        
            return $vacation_schedule;
        }
        return $changed_schedule;
    }
    
    public function deleteVacation($id) {
        
        $vacation = StaffVacation::find($id);
        
        if($vacation) {
            $vacation->delete();
            return ['status' => 1];
        }
        
    }
    
    public function addToWeeklySchedule($user) {

        try {
            
            $weekly_schedule = [];
            
            $start_date = date('Y-m-d');
            
            if($user->salon->week_starting_on == 1) {
                $dayofweek = date('N', strtotime($start_date));
                $day_diff = 7 - $dayofweek;
            } else {
                $dayofweek = date('w', strtotime($start_date));
                $day_diff = 6 - $dayofweek;
            }

            $duration = '+' . $day_diff .  ' day';
            
            $option = true;
            
            if($user->schedule_options != null) {
                $user_sch[] = [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->user_extras->first_name . ' ' . $user->user_extras->last_name,
                    ],
                    'schedule' => $this->createSchedule($user, $duration, $option, $day_diff),
                ];
            }
            
            
            $stored_schedule = $this->storeWeeklySchedule($user_sch);
            
            return $stored_schedule;
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function storeWeeklySchedule($user_sch) {
        
        DB::beginTransaction();
        
        foreach($user_sch as $schedule) {
            $user_id = $schedule['user']['id'];
            $db_schedule = WeeklySchedule::where('user_id', $user_id)->get();
            if($db_schedule->isEmpty()) {
                foreach($schedule['schedule'] as $date_info) {
                    $new_sch = new WeeklySchedule;
                    $new_sch->user_id = $user_id;
                    $new_sch->schedule_date = $date_info['date']['date'];
                    $new_sch->work_start = $date_info['timetable']['start'];
                    $new_sch->work_end = $date_info['timetable']['end'];
                    $new_sch->save();
                }
            }
        }
        
        DB::commit();
        
        return ['status' => 1];
    }
    
    public function validateDate($date, $format = 'H:i') {
        
        $d = DateTime::createFromFormat($format, $date);
        
        return $d && $d->format($format) == $date;
        
    }
    
    public function getWeeklySchedule($staff) {
        
        $stored_sch = [];
        $schedule = [];
        foreach($staff as $user) {
            $stored_sch = WeeklySchedule::where('user_id', $user->id)->get();
            $counted_hours = 0;
            foreach($stored_sch as $count_hours) {
                if($this->validateDate($count_hours['work_start'])) {
                    $time = new Carbon($count_hours['work_start']);
                    $shift_end_time = new Carbon($count_hours['work_end']);
                    $counted_hours += $time->diff($shift_end_time)->format('%H:%i:%s');;
                }
            }
            
            $schedule[] = [
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->user_extras->first_name,
                    'last_name' => $user->user_extras->last_name,
                ],
                'schedule' => $stored_sch,
                'hours' => $counted_hours
            ];
        }
        
        return $schedule;
        
    }
    
    public function deleteWeeklySchedule($user) {
        
        $schedule_changes = ScheduleChanges::where('staff_id', $user->id)->get();
        $schedule = StaffHours::where('staff_id', $user->id)->get();
        $weekly_schedule = WeeklySchedule::where('user_id', $user->id)->get();
        $schedule_options = UserScheduleOptions::where('staff_id', $user->id)->get();
        
        try {
            
            foreach($schedule_changes as $change) {
                $change->delete();
            }
            
            foreach($schedule as $hour) {
                $hour->delete();
            }
            
            foreach($schedule_options as $sch_opt) {
                $sch_opt->delete();
            }
        
            foreach($weekly_schedule as $schedule) {
                $schedule->delete();
            }
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0];
        }

    }
    
    public function getCalendarDays() {
        
        $salon = Salons::where('id', Auth::user()->salon_id)->first();
        
        $weekdays = [];
        
        if($salon->week_starting_on == 1) {
            $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        } else {
            $weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        }
        
        return $weekdays;
        
    }
    
    public function unsetLocation($id) {
        
        $users = User::where('location_id', $id)->get();
        
        if($users->isNotEmpty()) {
            
            $location = Location::where('salon_id', Auth::user()->salon_id)->first();
            
            if($location != null) {
                foreach($users as $user) {
                    $user->location_id = $location->id;
                    $user->save();
                }
            } else {
                foreach($users as $user) {
                    $user->location_id = null;
                    $user->save();
                }
            }
        }
        
        return ['status' => 1];
    }
    
    public function getScheduleMessage($week_counter, $repeat_weeks) {
        
        if($week_counter === 1) {
            $index = 0;
        } else if ($week_counter === 2) {
            $index = 1;
        } else if ($week_counter === 3) {
            $index = 2;
        } else if ($week_counter === 4) {
            $index = 3;
        }
        
        $dates = [];
        $display_weeks = $repeat_weeks[0]->display_weeks;
        $check_loop = $display_weeks - $week_counter;
        $starting_date = date('Y-m-d', strtotime($repeat_weeks[$index]->starting_date));
        $end_date = date('Y-m-d', strtotime('+'.$check_loop. ' week', strtotime($starting_date)));
        
        while($starting_date < $end_date) {
            $starting_date = date('Y-m-d', strtotime('+1 week', strtotime($starting_date)));
            $dates[] = $starting_date;
        }
        
        return $dates;
        
    }
    
    public function assignLocationToAdmin($location_id) {
        $user = Auth::user();
        if($user->hasRole('salonadmin')) {
            $user->location_id = $location_id;
            $user->save();
            return ['status' => 1];
        }
    
    }
    
    public function getLocationSchedule($location_id, $duration) {
        
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime($duration, strtotime($start_date)));
        $location = Location::find($location_id);
        $salon = Salons::find($location->salon_id);
        $local_hours = LocalHours::where('location_id', $location_id)->get();

        if($salon->week_starting_on == 1) {
            $local = 'N';
        } else {
            $local = 'w';
        }
        
        while($start_date <= $end_date) {
            
            $dayname = date('l', strtotime($start_date));
            $dayofweek = date($local, strtotime($start_date));
            
            foreach($local_hours as $hour) {
                if($hour->dayname == $dayname && $hour->status == 'on') {
                    $time = [
                        'start' => $hour->start_time,
                        'end' => $hour->closing_time,
                        'lunch_start' => null,
                        'lunch_end' => null
                    ];
                } else if($hour->dayname == $dayname && $hour->status != 'on') {
                    $time = [
                        'start' => null,
                        'end' => null,
                        'lunch_start' => null,
                        'lunch_end' => null
                    ];
                }
            }
            
            $schedule_initial[] = [
                'date' => [
                    'date' => $start_date,
                    'dayname' => $dayname,
                    'dayofweek' => $dayofweek
                ],
                'timetable' => $time
            ];
            
            $start_date = date('Y-m-d', strtotime('+1 day', strtotime($start_date)));
            
        }

        return $schedule_initial;
        
    }
    
    public function addServicesToStaff($staff, $data) {
        
        $staff_services = ServiceStaff::where('user_id', $staff->id)->get();
        
        try {
            foreach($data['services'] as $service) {
                
                $check_service = ServiceStaff::where('user_id', $staff->id)->where('service_id', $service['service'])->first();
                
                if($check_service === null && $service['value'] === 'true') {
                    $new_staff_service = new ServiceStaff;
                    $new_staff_service->location_id = $staff->location_id;
                    $new_staff_service->service_id = $service['service'];
                    $new_staff_service->user_id = $staff->id;
                    $new_staff_service->save();
                    
                } else if ($check_service != null && $service['value'] != 'true') {
                    $check_service->delete();
                }

            }
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            
            return ['status' => 0, 'message' => $exc->getMessage()];
            
        }
        
    }

}

