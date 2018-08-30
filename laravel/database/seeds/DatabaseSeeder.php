<?php

use Illuminate\Database\Seeder;
use App\Models\{Countries,Languages,Location,LocationExtras,Salons,BillingLocation,BillingSalon,LocalHours};
use App\Models\Salon\{Category,Group,SalonExtras,Service,ServiceDetails,ServiceStaff,StaffHours,SubCategory,WeeklySchedule,UserScheduleOptions,LoyaltyDiscounts};
use App\Models\Booking\{BookingFields,CalendarColors,CalendarOptions,CalendarSettings,BookingPolicy,CustomStyles};
use App\{User,Role,Permission};
use App\Models\Users\UserExtras;
use App\Models\Clients\{ClientSettings,ClientLabels,ClientReferrals};
use App\Models\Website\{WebsiteContent};

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //user roles
        $superadmin = new Role;
        $superadmin->name = 'superadmin';
        $superadmin->save();

        $salon_admin = new Role;
        $salon_admin->name = 'salonadmin';
        $salon_admin->save();

        $staff = new Role;
        $staff->name = 'staff';
        $staff->save();
        
        $trainee = new Role;
        $trainee->name = 'trainee';
        $trainee->save();
        
        $supervisor = new Role;
        $supervisor->name = 'supervisor';
        $supervisor->save();
        
        $reception = new Role;
        $reception->name = 'reception';
        $reception->save();

        $new_user = new Role;
        $new_user->name = 'user';
        $new_user->save();
        
        //permissions
        $perm = new Permission();
        $perm->name = 'manage-salon';
        $perm->display_name = 'Manage salon';
        $perm->description = 'Can edit salon settings';
        $perm->save();
        
        $perm2 = new Permission();
        $perm2->name = 'manage-locations';
        $perm2->display_name = 'Manage locations';
        $perm2->description = 'Can edit location settings and info';
        $perm2->save();
        
        $perm3 = new Permission();
        $perm3->name = 'manage-staff';
        $perm3->display_name = 'Manage staff';
        $perm3->description = 'Can manage staff';
        $perm3->save();
        
        $perm4 = new Permission();
        $perm4->name = 'view-rosters';
        $perm4->display_name = 'View rosters';
        $perm4->description = 'Can view staff rosters';
        $perm4->save();
        
        $perm5 = new Permission();
        $perm5->name = 'add-vacations';
        $perm5->display_name = 'Add vacations';
        $perm5->description = 'Can add staff vacations';
        $perm5->save();
        
        $perm6 = new Permission();
        $perm6->name = 'manage-booking';
        $perm6->display_name = 'Manage booking';
        $perm6->description = 'Can edit booking settings';
        $perm6->save();
        
        $perm7 = new Permission();
        $perm7->name = 'view-appointments';
        $perm7->display_name = 'View appointments';
        $perm7->description = 'Can view appointments';
        $perm7->save();
        
        $perm8 = new Permission();
        $perm8->name = 'manage-calendar';
        $perm8->display_name = 'Manage calendar';
        $perm8->description = 'Can manage calendar';
        $perm8->save();
        
        $perm9 = new Permission();
        $perm9->name = 'manage-clients';
        $perm9->display_name = 'Manage clients';
        $perm9->description = 'Can manage clients';
        $perm9->save();
        
        $perm10 = new Permission();
        $perm10->name = 'manage-website';
        $perm10->display_name = 'Manage website';
        $perm10->description = 'Can manage website';
        $perm10->save();
        
        $perm11 = new Permission();
        $perm11->name = 'manage-loyalty';
        $perm11->display_name = 'Manage loyalty';
        $perm11->description = 'Can manage loyalty';
        $perm11->save();

        //countries & lang
        $country_cro = new Countries;
        $country_cro->country_identifier = 'hr';
        $country_cro->country_local_name = 'Hrvatska';
        $country_cro->save();
        
        $country_en = new Countries;
        $country_en->country_identifier = 'uk';
        $country_en->country_local_name = 'United Kingdom';
        $country_en->save();
        
        $country_usa = new Countries;
        $country_usa->country_identifier = 'usa';
        $country_usa->country_local_name = 'United States';
        $country_usa->save();

        $lang_hr = new Languages;
        $lang_hr->language_iso = 'hr';
        $lang_hr->language_name = 'Croatian';
        $lang_hr->local_name = 'Hrvatski';
        $lang_hr->save();

        $lang_en = new Languages;
        $lang_en->language_iso = 'en';
        $lang_en->language_name = 'English';
        $lang_en->local_name = 'English';
        $lang_en->save();
        
        //accounts
        $superadmin = new User;
        $superadmin->email = 'super@admin.hr';
        $superadmin->password = Hash::make(123456);
        $superadmin->language = 1;
        $superadmin->email_verified = 1;
        $superadmin->save();
        $superadmin->attachRole(1);
        
        $superadmin_extras = new UserExtras;
        $superadmin_extras->user_id = $superadmin->id;
        $superadmin_extras->first_name = 'SuperAdmin';
        $superadmin_extras->last_name = 'SuperAdmin';
        $superadmin_extras->photo = '/images/user_placeholder.png';
        $superadmin_extras->save();
        
        $ncerovs = new User;
        $ncerovs->email = 'nikola.cerovski@gmail.com';
        $ncerovs->password = Hash::make(123456);
        $ncerovs->language = 1;
        $ncerovs->email_verified = 1;
        $ncerovs->salon_id = 2;
        $ncerovs->location_id = 1;
        $ncerovs->save();
        $ncerovs->attachRole(2);
        
        $ncerovs_extras = new UserExtras;
        $ncerovs_extras->user_id = $ncerovs->id;
        $ncerovs_extras->first_name = 'nc';
        $ncerovs_extras->last_name = 'nc';
        $ncerovs_extras->photo = '/images/user_placeholder.png';
        $ncerovs_extras->save();
        
        $ncerovs2 = new User;
        $ncerovs2->email = 'testuser@test.com';
        $ncerovs2->password = Hash::make(123456);
        $ncerovs2->language = 1;
        $ncerovs2->email_verified = 1;
        $ncerovs2->salon_id = 3;
        $ncerovs2->save();
        $ncerovs2->attachRole(2);
        
        $ncerovs2_extras = new UserExtras;
        $ncerovs2_extras->user_id = $ncerovs2->id;
        $ncerovs2_extras->first_name = 'nc';
        $ncerovs2_extras->last_name = 'nc';
        $ncerovs2_extras->photo = '/images/user_placeholder.png';
        $ncerovs2_extras->save();
        
        $user3 = new User;
        $user3->email = 'novi.korisnik@email.com';
        $user3->password = Hash::make(123456);
        $user3->language = 1;
        $user3->email_verified = 1;
        $user3->salon_id = 3;
        $user3->save();
        $user3->attachRole(2);
        
        $user3_extras = new UserExtras;
        $user3_extras->user_id = $user3->id;
        $user3_extras->first_name = 'Ruža';
        $user3_extras->last_name = 'Mirković';
        $user3_extras->photo = '/images/user_placeholder.png';
        $user3_extras->save();
        
        //set user permissions
        $role = Role::find(2);
        $permissions = Permission::all();
        foreach($permissions as $permission) {
            $role->attachPermission($permission->id);
        }

        //client labels
        $client_label1 = new ClientLabels;
        $client_label1->salon_id = 'all';
        $client_label1->name = 'Frequent cancels';
        $client_label1->color = '#EE1D24';
        $client_label1->save();
        
        $client_label2 = new ClientLabels;
        $client_label2->salon_id = 'all';
        $client_label2->name = 'Typically late';
        $client_label2->color = '#F87000';
        $client_label2->save();
        
        $client_label3 = new ClientLabels;
        $client_label3->salon_id = 'all';
        $client_label3->name = 'VIP';
        $client_label3->color = '#4206A9';
        $client_label3->save();
        
        //create salon
        $salon = new Salons;
        $salon->id = 2;
        $salon->business_name = 'Salon Ružica';
        $salon->website = '';
        $salon->country = 'hr';
        $salon->address = 'Matije Gupca 15';
        $salon->city = 'Zagreb';
        $salon->zip_code = '10000';
        $salon->time_format = 'time-24';
        $salon->time_zone = 'Europe/Zagreb';
        $salon->week_starting_on = 1;
        $salon->logo = '';
        $salon->contact_name = 'Marko Marić';
        $salon->business_phone = '01202020';
        $salon->mobile_phone = '912329239';
        $salon->email_address = 'ruzica@salonruzica.kom';
        $salon->currency = 'EUR';
        $salon->save();
        
        $salon_billing = new BillingSalon;
        $salon_billing->id = 2;
        $salon_billing->address = 'Matije Gupca 15';
        $salon_billing->city = 'Zagreb';
        $salon_billing->zip = '10000';
        $salon_billing->country = 'hr';
        $salon_billing->oib = '2133123113';
        $salon_billing->iban = '321121';
        $salon_billing->swift = '321312';
        $salon_billing->spent_sms = 0;
        $salon_billing->save(); 
        
        $salon_extras = new SalonExtras;
        $salon_extras->salon_id = 2;
        $salon_extras->email_staff_rosters = 0;
        $salon_extras->email_day = 'Monday';
        $salon_extras->email_time = '15:00';
        $salon_extras->save();

        $salon_payment1 = new \App\Models\Salon\SalonPaymentOptions;
        $salon_payment1->salon_id = 2;
        $salon_payment1->payment_gateway = 'paypal';
        $salon_payment1->status = 0;
        $salon_payment1->public_key = null;
        $salon_payment1->private_key = null;
        $salon_payment1->save();

        $salon_payment2 = new \App\Models\Salon\SalonPaymentOptions;
        $salon_payment2->salon_id = 2;
        $salon_payment2->payment_gateway = 'stripe';
        $salon_payment2->status = 0;
        $salon_payment2->public_key = null;
        $salon_payment2->private_key = null;
        $salon_payment2->save();

        $salon_payment3 = new \App\Models\Salon\SalonPaymentOptions;
        $salon_payment3->salon_id = 2;
        $salon_payment3->payment_gateway = 'wspay';
        $salon_payment3->status = 0;
        $salon_payment3->public_key = null;
        $salon_payment3->private_key = null;
        $salon_payment3->save();
        
        $loyalty_discounts = new LoyaltyDiscounts;
        $loyalty_discounts->salon_id = 2;
        $loyalty_discounts->discount = 5;
        $loyalty_discounts->points = 150;
        
        $loyalty_discounts1 = new LoyaltyDiscounts;
        $loyalty_discounts1->salon_id = 2;
        $loyalty_discounts1->discount = 10;
        $loyalty_discounts1->points = 200;
        
        $loyalty_discounts2 = new LoyaltyDiscounts;
        $loyalty_discounts2->salon_id = 2;
        $loyalty_discounts2->discount = 15;
        $loyalty_discounts2->points = 250;
        
        $location = new Location;
        $location->salon_id = 2;
        $location->time_format = 'time-24';
        $location->location_name = 'Lokacija 1';
        $location->address = 'Matije Gupca 15';
        $location->city = 'Zagreb';
        $location->zip = '10000';
        $location->country = 'hr';
        $location->save();
        
        $location_extras = new LocationExtras;
        $location_extras->location_id = 1;
        $location_extras->parking = null;
        $location_extras->credit_cards = null;
        $location_extras->accessible_for_disabled = null;
        $location_extras->wifi = null;
        $location_extras->pets = null;
        $location_extras->save();
        
        $hours = new LocalHours;
        $hours->location_id = 1;
        $hours->day = 1;
        $hours->dayname = 'Monday';
        $hours->status = 'on';
        $hours->start_time = '08:00';
        $hours->closing_time = '20:00';
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = 1;
        $hours->day = 2;
        $hours->dayname = 'Tuesday';
        $hours->status = 'on';
        $hours->start_time = '08:00';
        $hours->closing_time = '20:00';
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = 1;
        $hours->day = 3;
        $hours->dayname = 'Wednesday';
        $hours->status = 'on';
        $hours->start_time = '08:00';
        $hours->closing_time = '20:00';
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = 1;
        $hours->day = 4;
        $hours->dayname = 'Thursday';
        $hours->status = 'on';
        $hours->start_time = '08:00';
        $hours->closing_time = '20:00';
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = 1;
        $hours->day = 5;
        $hours->dayname = 'Friday';
        $hours->status = 'on';
        $hours->start_time = '08:00';
        $hours->closing_time = '20:00';
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = 1;
        $hours->day = 6;
        $hours->dayname = 'Saturday';
        $hours->status = 'on';
        $hours->start_time = '08:00';
        $hours->closing_time = '20:00';
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = 1;
        $hours->day = 7;
        $hours->dayname = 'Sunday';
        $hours->status = 'on';
        $hours->start_time = '08:00';
        $hours->closing_time = '20:00';
        $hours->save();
        
        $location_billing = new BillingLocation;
        $location_billing->id = 1;
        $location_billing->address = 'Matije Gupca 15';
        $location_billing->city = 'Zagreb';
        $location_billing->zip = '10000';
        $location_billing->country = 'hr';
        $location_billing->oib = '2133123113';
        $location_billing->iban = '321121';
        $location_billing->swift = '321312';
        $location_billing->spent_sms = 0;
        $location_billing->save(); 
        
        $salon_website_content = new WebsiteContent;
        $salon_website_content->salon_id = 2;
        $salon_website_content->save();
        
        //insert calendar styles
        $calendar_colors = new CalendarColors;
        $calendar_colors->salon_id = 2;
        $calendar_colors->save();
        
        $custom_styles = new CustomStyles;
        $custom_styles->salon_id = 2;
        $custom_styles->save();
        
        //insert client settings
        $client_settings = new ClientSettings;
        $client_settings->salon_id = 2;
        $client_settings->sms = 0;
        $client_settings->email = 0;
        $client_settings->viber = 0;
        $client_settings->facebook = 0;
        $client_settings->name_format = 'first_last';
        $client_settings->save();
        
        $client_referrals1 = new ClientReferrals;
        $client_referrals1->salon_id = 2;
        $client_referrals1->name = 'Email';
        $client_referrals1->save();
        
        $client_referrals2 = new ClientReferrals;
        $client_referrals2->salon_id = 2;
        $client_referrals2->name = 'Friend';
        $client_referrals2->save();
        
        $client_referrals3 = new ClientReferrals;
        $client_referrals3->salon_id = 2;
        $client_referrals3->name = 'Google';
        $client_referrals3->save();
        
        $client_referrals4 = new ClientReferrals;
        $client_referrals4->salon_id = 2;
        $client_referrals4->name = 'Newspaper';
        $client_referrals4->save();
        
        $client_referrals5 = new ClientReferrals;
        $client_referrals5->salon_id = 2;
        $client_referrals5->name = 'Other sources';
        $client_referrals5->save();
    
        //usluge
        $category = new Category;
        $category->location_id = 1;
        $category->name = 'Muškarci';
        $category->cat_color = '#270E71';
        $category->active = 1;
        $category->save();
        
        $category = new Category;
        $category->location_id = 1;
        $category->name = 'Žene';
        $category->cat_color = '#243E11';
        $category->active = 1;
        $category->save();
        
        $category = new Category;
        $category->location_id = 1;
        $category->name = 'Djeca';
        $category->cat_color = '#F74231';
        $category->active = 1;
        $category->save();
        
        $group = new Group;
        $group->category_id = 1;
        $group->name = 'Kosa';
        $group->active = 1;
        $group->save();
        
        $group = new Group;
        $group->category_id = 1;
        $group->name = 'Brada';
        $group->active = 1;
        $group->save();
        
        $group = new Group;
        $group->category_id = 2;
        $group->name = 'Kosa';
        $group->active = 1;
        $group->save();
        
        $group = new Group;
        $group->category_id = 1;
        $group->name = 'Nokti';
        $group->active = 1;
        $group->save();
        
        $group = new Group;
        $group->category_id = 3;
        $group->name = 'Glava';
        $group->active = 1;
        $group->save();
        
        $group = new SubCategory;
        $group->group_id = 5;
        $group->name = 'Kosa';
        $group->active = 1;
        $group->save();
        
        $service = new Service;
        $service->location_id = 1;
        $service->category = 1;
        $service->group = 1;
        $service->sub_group = null;
        $service->order = 1;
        $service->save();
        
        $service_details = new ServiceDetails;
        $service_details->service_id = 1;
        $service_details->name = 'Šišanje';
        $service_details->code = '1231321';
        $service_details->barcode = '132312';
        $service_details->service_length = '00:25:00';
        $service_details->base_price = '25';
        $service_details->available = 1;
        $service_details->save();
        
        $service = new Service;
        $service->location_id = 1;
        $service->category = 1;
        $service->group = 1;
        $service->sub_group = null;
        $service->order = 2;
        $service->save();
        
        $service_details = new ServiceDetails;
        $service_details->service_id = 2;
        $service_details->name = 'Pranje kose';
        $service_details->code = '42342';
        $service_details->barcode = '4234234';
        $service_details->service_length = '00:10:00';
        $service_details->base_price = '0';
        $service_details->available = 1;
        $service_details->save();
        
        $service = new Service;
        $service->location_id = 1;
        $service->category = 1;
        $service->group = 1;
        $service->sub_group = null;
        $service->order = 3;
        $service->save();
        
        $service_details = new ServiceDetails;
        $service_details->service_id = 3;
        $service_details->name = 'Frizura';
        $service_details->code = '654733';
        $service_details->barcode = '426543';
        $service_details->service_length = '00:10:00';
        $service_details->base_price = '0';
        $service_details->available = 1;
        $service_details->save();
        
        $service = new Service;
        $service->location_id = 1;
        $service->category = 1;
        $service->group = 2;
        $service->sub_group = null;
        $service->order = 1;
        $service->save();
        
        $service_details = new ServiceDetails;
        $service_details->service_id = 4;
        $service_details->name = 'Brijanje';
        $service_details->code = '345345';
        $service_details->barcode = '5345345';
        $service_details->service_length = '00:10:00';
        $service_details->base_price = '0';
        $service_details->available = 1;
        $service_details->save();
        
        //location staff
        $staff1 = new User;
        $staff1->email = 'zaposlenik1@ruzicasalon.com';
        $staff1->password = Hash::make(123456);
        $staff1->language = 1;
        $staff1->email_verified = 1;
        $staff1->salon_id = 2;
        $staff1->location_id = 1;
        $staff1->save();
        $staff1->attachRole(3);
        
        $staff1_extra = new UserExtras;
        $staff1_extra->user_id = 5;
        $staff1_extra->first_name = 'Ivana';
        $staff1_extra->last_name = 'Horvat';
        $staff1_extra->photo = '/images/user_placeholder.png';
        $staff1_extra->available_booking = 1;
        $staff1_extra->save();
        
        $staff2 = new User;
        $staff2->email = 'zaposlenik2@ruzicasalon.com';
        $staff2->password = Hash::make(123456);
        $staff2->language = 1;
        $staff2->email_verified = 1;
        $staff2->salon_id = 2;
        $staff2->location_id = 1;
        $staff2->save();
        $staff2->attachRole(3);
        
        $staff2_extras = new UserExtras;
        $staff2_extras->user_id = 6;
        $staff2_extras->first_name = 'Marija';
        $staff2_extras->last_name = 'Marič';
        $staff2_extras->photo = '/images/user_placeholder.png';
        $staff2_extras->available_booking = 1;
        $staff2_extras->save();
        
        $staff3 = new User;
        $staff3->email = 'zaposlenik3@ruzicasalon.com';
        $staff3->password = Hash::make(123456);
        $staff3->language = 1;
        $staff3->email_verified = 1;
        $staff3->salon_id = 2;
        $staff3->location_id = 1;
        $staff3->save();
        $staff3->attachRole(3);
        
        $staff3_extras = new UserExtras;
        $staff3_extras->user_id = 7;
        $staff3_extras->first_name = 'Ivica';
        $staff3_extras->last_name = 'Mirković';
        $staff3_extras->photo = '/images/user_placeholder.png';
        $staff3_extras->available_booking = 1;
        $staff3_extras->save();
        
        //staff to services
        $service_staff1 = new ServiceStaff;
        $service_staff1->location_id = 1;
        $service_staff1->service_id = 1;
        $service_staff1->user_id = 5;
        $service_staff1->save();
        
        $service_staff2 = new ServiceStaff;
        $service_staff2->location_id = 1;
        $service_staff2->service_id = 1;
        $service_staff2->user_id = 6;
        $service_staff2->save();
        
        $service_staff3 = new ServiceStaff;
        $service_staff3->location_id = 1;
        $service_staff3->service_id = 1;
        $service_staff3->user_id = 7;
        $service_staff3->save();
        
        $service_staff4 = new ServiceStaff;
        $service_staff4->location_id = 1;
        $service_staff4->service_id = 2;
        $service_staff4->user_id = 5;
        $service_staff4->save();
        
        $service_staff5 = new ServiceStaff;
        $service_staff5->location_id = 1;
        $service_staff5->service_id = 2;
        $service_staff5->user_id = 6;
        $service_staff5->save();
        
        $service_staff6 = new ServiceStaff;
        $service_staff6->location_id = 1;
        $service_staff6->service_id = 3;
        $service_staff6->user_id = 5;
        $service_staff6->save();
        
        $service_staff7 = new ServiceStaff;
        $service_staff7->location_id = 1;
        $service_staff7->service_id = 3;
        $service_staff7->user_id = 7;
        $service_staff7->save();
        
        $service_staff8 = new ServiceStaff;
        $service_staff8->location_id = 1;
        $service_staff8->service_id = 4;
        $service_staff8->user_id = 5;
        $service_staff8->save();
        
        //sch staff id 5
        $staff_hours1 = new StaffHours;
        $staff_hours1->staff_id = 5;
        $staff_hours1->day = 1;
        $staff_hours1->status = 1;
        $staff_hours1->week = 1;
        $staff_hours1->work_start = '08:00';
        $staff_hours1->work_end = '16:00';
        $staff_hours1->lunch_start = '13:00';
        $staff_hours1->lunch_end = '13:55';
        $staff_hours1->save();
        
        $staff_hours2 = new StaffHours;
        $staff_hours2->staff_id = 5;
        $staff_hours2->day = 2;
        $staff_hours2->status = 1;
        $staff_hours2->week = 1;
        $staff_hours2->work_start = '08:00';
        $staff_hours2->work_end = '16:00';
        $staff_hours2->lunch_start = '13:00';
        $staff_hours2->lunch_end = '13:55';
        $staff_hours2->save();
        
        $staff_hours3 = new StaffHours;
        $staff_hours3->staff_id = 5;
        $staff_hours3->day = 3;
        $staff_hours3->status = 1;
        $staff_hours3->week = 1;
        $staff_hours3->work_start = '08:00';
        $staff_hours3->work_end = '16:00';
        $staff_hours3->lunch_start = '13:00';
        $staff_hours3->lunch_end = '13:55';
        $staff_hours3->save();
        
        $staff_hours4 = new StaffHours;
        $staff_hours4->staff_id = 5;
        $staff_hours4->day = 4;
        $staff_hours4->status = 1;
        $staff_hours4->week = 1;
        $staff_hours4->work_start = '12:00';
        $staff_hours4->work_end = '20:00';
        $staff_hours4->lunch_start = '15:00';
        $staff_hours4->lunch_end = '15:55';
        $staff_hours4->save();
        
        $staff_hours5 = new StaffHours;
        $staff_hours5->staff_id = 5;
        $staff_hours5->day = 5;
        $staff_hours5->status = 1;
        $staff_hours5->week = 1;
        $staff_hours5->work_start = '08:00';
        $staff_hours5->work_end = '16:00';
        $staff_hours5->lunch_start = '13:00';
        $staff_hours5->lunch_end = '13:55';
        $staff_hours5->save();
        
        $staff_hours6 = new StaffHours;
        $staff_hours6->staff_id = 5;
        $staff_hours6->day = 6;
        $staff_hours6->status = 1;
        $staff_hours6->week = 1;
        $staff_hours6->work_start = '12:00';
        $staff_hours6->work_end = '20:00';
        $staff_hours6->lunch_start = '15:00';
        $staff_hours6->lunch_end = '15:55';
        $staff_hours6->save();
        
        $staff_hours7 = new StaffHours;
        $staff_hours7->staff_id = 5;
        $staff_hours7->day = 7;
        $staff_hours7->status = 1;
        $staff_hours7->week = 1;
        $staff_hours7->work_start = '08:00';
        $staff_hours7->work_end = '16:00';
        $staff_hours7->lunch_start = '13:00';
        $staff_hours7->lunch_end = '13:55';
        $staff_hours7->save();
        
        $staff_hour_options1 = new UserScheduleOptions;
        $staff_hour_options1->staff_id = 5;
        $staff_hour_options1->display_weeks = 1;
        $staff_hour_options1->week = 1;
        $staff_hour_options1->starting_date = '2018-04-25';
        $staff_hour_options1->end_date = '2100-01-01';
        $staff_hour_options1->save();
        
        //sch staff id 6
        $staff_hours = new StaffHours;
        $staff_hours->staff_id = 6;
        $staff_hours->day = 1;
        $staff_hours->status = 0;
        $staff_hours->week = 1;
        $staff_hours->save();
        
        $staff_hours = new StaffHours;
        $staff_hours->staff_id = 6;
        $staff_hours->day = 2;
        $staff_hours->status = 1;
        $staff_hours->week = 1;
        $staff_hours->work_start = '08:00';
        $staff_hours->work_end = '16:00';
        $staff_hours->lunch_start = '13:00';
        $staff_hours->lunch_end = '13:55';
        $staff_hours->save();
        
        $staff_hours = new StaffHours;
        $staff_hours->staff_id = 6;
        $staff_hours->day = 3;
        $staff_hours->status = 0;
        $staff_hours->week = 1;
        $staff_hours->save();
        
        $staff_hours = new StaffHours;
        $staff_hours->staff_id = 6;
        $staff_hours->day = 4;
        $staff_hours->status = 1;
        $staff_hours->week = 1;
        $staff_hours->work_start = '12:00';
        $staff_hours->work_end = '20:00';
        $staff_hours->lunch_start = '15:00';
        $staff_hours->lunch_end = '15:55';
        $staff_hours->save();
        
        $staff_hours = new StaffHours;
        $staff_hours->staff_id = 6;
        $staff_hours->day = 5;
        $staff_hours->status = 0;
        $staff_hours->week = 1;
        $staff_hours->save();
        
        $staff_hours = new StaffHours;
        $staff_hours->staff_id = 6;
        $staff_hours->day = 6;
        $staff_hours->status = 1;
        $staff_hours->week = 1;
        $staff_hours->work_start = '12:00';
        $staff_hours->work_end = '20:00';
        $staff_hours->lunch_start = '15:00';
        $staff_hours->lunch_end = '15:55';
        $staff_hours->save();
        
        $staff_hours = new StaffHours;
        $staff_hours->staff_id = 6;
        $staff_hours->day = 7;
        $staff_hours->status = 0;
        $staff_hours->week = 1;
        $staff_hours->save();
        
        $staff_hour_options = new UserScheduleOptions;
        $staff_hour_options->staff_id = 6;
        $staff_hour_options->display_weeks = 1;
        $staff_hour_options->week = 1;
        $staff_hour_options->starting_date = '2018-04-25';
        $staff_hour_options->end_date = '2100-01-01';
        $staff_hour_options->save();
        
        //sch staff id 7
        $staff_hours8 = new StaffHours;
        $staff_hours8->staff_id = 7;
        $staff_hours8->day = 1;
        $staff_hours8->status = 1;
        $staff_hours8->week = 1;
        $staff_hours8->work_start = '08:00';
        $staff_hours8->work_end = '16:00';
        $staff_hours8->lunch_start = '13:00';
        $staff_hours8->lunch_end = '13:55';
        $staff_hours8->save();
        
        $staff_hours9 = new StaffHours;
        $staff_hours9->staff_id = 7;
        $staff_hours9->day = 2;
        $staff_hours9->status = 0;
        $staff_hours9->week = 1;
        $staff_hours9->save();
        
        $staff_hours11 = new StaffHours;
        $staff_hours11->staff_id = 7;
        $staff_hours11->day = 3;
        $staff_hours11->status = 1;
        $staff_hours11->week = 1;
        $staff_hours11->work_start = '08:00';
        $staff_hours11->work_end = '16:00';
        $staff_hours11->lunch_start = '13:00';
        $staff_hours11->lunch_end = '13:55';
        $staff_hours11->save();
        
        $staff_hours12 = new StaffHours;
        $staff_hours12->staff_id = 7;
        $staff_hours12->day = 4;
        $staff_hours12->status = 0;
        $staff_hours12->week = 1;
        $staff_hours12->save();
        
        $staff_hours13 = new StaffHours;
        $staff_hours13->staff_id = 7;
        $staff_hours13->day = 5;
        $staff_hours13->status = 1;
        $staff_hours13->week = 1;
        $staff_hours13->work_start = '08:00';
        $staff_hours13->work_end = '16:00';
        $staff_hours13->lunch_start = '13:00';
        $staff_hours13->lunch_end = '13:55';
        $staff_hours13->save();
        
        $staff_hours14 = new StaffHours;
        $staff_hours14->staff_id = 7;
        $staff_hours14->day = 6;
        $staff_hours14->status = 0;
        $staff_hours14->week = 1;
        $staff_hours14->save();
        
        $staff_hours15 = new StaffHours;
        $staff_hours15->staff_id = 7;
        $staff_hours15->day = 7;
        $staff_hours15->status = 1;
        $staff_hours15->week = 1;
        $staff_hours15->work_start = '08:00';
        $staff_hours15->work_end = '16:00';
        $staff_hours15->lunch_start = '13:00';
        $staff_hours15->lunch_end = '13:55';
        $staff_hours15->save();
        
        $staff_hour_options2 = new UserScheduleOptions;
        $staff_hour_options2->staff_id = 7;
        $staff_hour_options2->display_weeks = 1;
        $staff_hour_options2->week = 1;
        $staff_hour_options2->starting_date = '2018-04-25';
        $staff_hour_options2->end_date = '2100-01-01';
        $staff_hour_options2->save();
        
        
    }
}
