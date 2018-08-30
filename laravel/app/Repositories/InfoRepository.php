<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\{Languages,Location,Countries,Salons};
use App\Role;

class InfoRepository {

    public function getCountryList() {
        
        try {
            
            $country_list = Countries::get();

            foreach($country_list as $country) {
                $country_array[0] = 'Select country';
                $country_array[$country->id] = $country->country_identifier;
            }

            return $country_array;
            
        } catch (Exception $e) {
            
            return array('status' => 0, 'message' => 'Error fetching countries data');
            
        }
    }

    public function getLanguageList() {
        
        try {
            
            $language_list = Languages::get();

            foreach($language_list as $language) {
                $language_array[$language->id] = $language->language_name;
            }

            return $language_array;
            
        } catch (Exception $e) {
            
            return array('status' => 0, 'message' => 'Error fetching languages data');
            
        }
    }
    
    public function getUserRoles() {
        
        $user_role = Auth::user()->roles[0]->id;
        
        $role_list = Role::where('id', '>', $user_role)->get();
        
        foreach($role_list as $role) {
            $role_arr[$role->id] = $role->name;
        }
        
        return $role_arr;
        
    }
    
    public function getLocationList($id) {
        
        $location_list = Location::where('salon_id', $id)->get();
        
        if($location_list->isEmpty()) {
            return ['status' => 0];
        }
        
        return ['status' => 1, 'location_list' => $location_list];
        
    }
    
    public function getWeekDays() {
        
        if(Auth::user()->salon->week_starting_on == 1) {
            $days = [
                '1' => [
                    'en' => 'Monday',
                    'name' => trans('salon.Monday'),
                ],
                '2' => [
                    'en' => 'Tuesday',
                    'name' => trans('salon.Tuesday'),
                ],
                '3' => [
                    'en' => 'Wednesday',
                    'name' => trans('salon.Wednesday'),
                ],
                '4' => [
                    'en' => 'Thursday',
                    'name' => trans('salon.Thursday'),
                ],
                '5' => [
                    'en' => 'Friday',
                    'name' => trans('salon.Friday'),
                ],
                '6' => [
                    'en' => 'Saturday',
                    'name' => trans('salon.Saturday'),
                ],
                '7' => [
                    'en' => 'Sunday',
                    'name' => trans('salon.Sunday'),
                ],
            ];
        } else {
            $days = [
                '0' => [
                    'en' => 'Sunday',
                    'name' => trans('salon.Sunday'),
                ],
                '1' => [
                    'en' => 'Monday',
                    'name' => trans('salon.Monday'),
                ],
                '2' => [
                    'en' => 'Tuesday',
                    'name' => trans('salon.Tuesday'),
                ],
                '3' => [
                    'en' => 'Wednesday',
                    'name' => trans('salon.Wednesday'),
                ],
                '4' => [
                    'en' => 'Thursday',
                    'name' => trans('salon.Thursday'),
                ],
                '5' => [
                    'en' => 'Friday',
                    'name' => trans('salon.Friday'),
                ],
                '6' => [
                    'en' => 'Saturday',
                    'name' => trans('salon.Saturday'),
                ],
            ];
        }
        
        
        return $days;
        
    }
    
    public function getSelectedDayName($salon, $days) {
        
        if($salon->week_starting_on == 1) {
            switch ($days) {
                case 1:
                    $day = 'Monday';
                    break;
                case 2:
                    $day = 'Tuesday';
                    break;
                case 3:
                    $day = 'Wednesday';
                    break;
                case 4:
                    $day = 'Thursday';
                    break;
                case 5:
                    $day = 'Friday';
                    break;
                case 6:
                    $day = 'Saturday';
                    break;
                case 7:
                    $day = 'Sunday';
                    break;
            }
              
        } else {
            switch ($days) {
                case 0:
                    $day = 'Sunday';
                    break;
                case 1:
                    $day = 'Monday';
                    break;
                case 2:
                    $day = 'Tuesday';
                    break;
                case 3:
                    $day = 'Wednesday';
                    break;
                case 4:
                    $day = 'Thursday';
                    break;
                case 5:
                    $day = 'Friday';
                    break;
                case 6:
                    $day = 'Saturday';
                    break;
            }
        }
        
        return $day;
        
    }
    
    public function getCurrencyList() {
        
        $currency = [
            '1' => [
                'name' => trans('salon.hrvatska_kuna'),
                'abbr' => 'HRK',
                ],
            '2' => [
                'name' => trans('salon.euro'),
                'abbr' => 'EUR',
            ]
        ];
        
        return $currency;
    }
    
    public function getCurrentWeek($sid) {
        
        if($salon = Salons::find($sid)) {
            
            $week_array = [];
            
            $first_day = $salon->week_starting_on;
            
            $current_date = date('d.m.Y.');
            
            if($first_day == 2) {
                $week_days_array = array(trans('salon.Monday'), trans('salon.Tuesday'), trans('salon.Wednesday'), trans('salon.Thursday'), trans('salon.Friday'), trans('salon.Saturday'), trans('salon.Sunday'));
                
                $weekday = date('N', strtotime($current_date));
                
                if($weekday == 1) {
                    for($i = 1; $i<=7; $i++) {
                        $current_date = date('d.m.Y.', strtotime('+1 day', strtotime($current_date)));
                        $week_array[] = [
                            'date' => date('d.m.Y', strtotime($current_date)),
                            'day' => $week_days_array[$i -1]
                        ];
                    }
                } else {
                    $day_diff = $weekday - 1;
                    
                    for($i = 1; $i < $weekday; $i++) {
                        
                    }
                    
                }
                
                return $week_array;
            } else {
                $week_days_array = array(trans('salon.Sunday'), trans('salon.Monday'), trans('salon.Tuesday'), trans('salon.Wednesday'), trans('salon.Thursday'), trans('salon.Friday'), trans('salon.Saturday'));
                
                $weekday = date('w', strtotime($current_date));
                
                return $weekday;
                
            }
            
        }
        
        return ['status' => 0];
    }
    
    public function getTimeZones() {
        static $regions = array(
            \DateTimeZone::AMERICA,
            \DateTimeZone::ASIA,
            \DateTimeZone::AUSTRALIA,
            \DateTimeZone::EUROPE,
        );
    
        $timezones = array();
        foreach( $regions as $region )
        {
            $timezones = array_merge( $timezones, \DateTimeZone::listIdentifiers( $region ) );
        }
    
        $timezone_offsets = array();
        foreach( $timezones as $timezone )
        {
            $tz = new \DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new \DateTime);
        }
    
        ksort($timezone_offsets);
    
        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate( 'H:i', abs($offset) );
    
            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
    
            $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
        }
    
        return $timezone_list;
    }
    
    public function getHoursList($salon_id) {
        $salon = Salons::find($salon_id);
        
        $hours_list = [];
        
        if($salon->time_format === 'time-24') {
            $time_format = "H:i";
        } else {
            $time_format = "g:i a";
        }
        
        $start = "00:00";
        $end = "23:59";
        
        $start_time = strtotime($start);
        $end_time = strtotime($end);
        $current_time = $start_time;
        
        while($current_time <= $end_time){
          $hours_list[date("H:i", $current_time)] = date($time_format, $current_time);
          $current_time = strtotime('+15 minutes', $current_time);
        }
    
        return $hours_list;
        
    }
    
    public function getBusinessTypes() {
        
        $business_types = [
            '1' => trans('salon.business_hairsalon'),
            '2' => trans('salon.business_nailsalon'),
            '3' => trans('salon.business_beautysalon'),
            '4' => trans('salon.business_massagesalon'),
        ];
        
        return $business_types;
        
    }
    
    public function getWeekDates($salon_id) {
        
        $salon = Salons::find($salon_id);
        $week_dates = [];
        
        if($salon->week_starting_on === '1') {
            $day = date('N') - 1;
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $date_format = 'd-m-Y';
        } else if ($salon->week_starting_on === '2') {
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $date_format = 'm-d-Y';
        }
        
        $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));

        while($week_start <= $week_end) {
            $week_date = date($date_format, strtotime($week_start));
            $week_dates[] = $week_date;
            
            $week_start = date('Y-m-d', strtotime('+1 day', strtotime($week_start)));
        }
        
        return $week_dates;

    }

    public function swapFields($content, $fields) {

        foreach($fields as $key=>$field) {
            if(strpos($content, $key)) {
                $content = str_replace($key, $field, $content);
            }
        }

        return $content;

    }

    public function getMonthList() {

        return [
           1 => trans('salon.january'),
           2 => trans('salon.february'),
           3 => trans('salon.march'),
           4 => trans('salon.april'),
           5 => trans('salon.may'),
           6 => trans('salon.june'),
           7 => trans('salon.july'),
           8 => trans('salon.august'),
           9 => trans('salon.september'),
           10 => trans('salon.october'),
           11 => trans('salon.november'),
           12 => trans('salon.december')
        ];

    }

}