<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', ['as' => 'signin', 'uses' => 'CoreController@getSignIn']);

Route::get('forgot-password', ['as' => 'forgotPassword', 'uses' => 'CoreController@forgotPassword']);

Route::get('hehe', ['uses' => 'CoreController@addPermission']);

Route::group(['prefix' => 'auth'], function() {

    //user registration, login and email verification

    Route::get('signup', ['as' => 'signup', 'uses' => 'CoreController@getSignup']);

    Route::post('postLogin', ['as' => 'postLogin', 'uses' => 'UserController@postLogin']);

    Route::post('postRegister', ['as' => 'postRegister', 'uses' => 'UserController@postRegister']);

    Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);

    //facebook authentication

    Route::get('{provider}', ['uses' => 'UserController@redirectToProvider']);

    Route::get('{provider}/callback', ['uses' => 'UserController@handleProviderCallback']);

    Route::get('sign-in-as/{id}', ['as' => 'signInAsUser', 'uses' => 'CoreController@signInAsUser']);

});

Route::get('/verify/{email_code}', ['as' => 'verify', 'uses' => 'UserController@verifyEmail']);

Route::get('reschedule-booking/{token}/{date}', ['uses' => 'BookingController@waitingListReschedule']);

//non-protected routes

Route::get('{salon}/blog', ['as' => 'salonBlog', 'uses' => 'WebsiteController@getSalonBlog']);

Route::get('blog/{salon}/{url}', ['as' => 'getBlogPost', 'uses' => 'WebsiteController@getBlogPost']);

Route::get('online-booking/{salon}/{location?}', ['as' => 'clientBooking', 'uses' => 'WebsiteController@getClientBooking']);

Route::post('payment-paypal', ['as' => 'payWithPaypal', 'uses' => 'PaymentController@payWithPaypal']);

Route::get('payment-paypal-status/{id}', ['as' => 'paypalStatus', 'uses' => 'PaymentController@getPaymentStatus']);

Route::get('booking/payment/{id}', ['uses' => 'PaymentController@getBookingPayment']);

Route::post('payment-stripe', ['as' => 'stripePayment', 'uses' => 'PaymentController@payWithStripe']);

//protected routes

Route::get('facebook/share/{booking_id}/{unique_id}', ['as' => 'fbShare', 'uses' => 'BookingController@shareOnFacebook']);

Route::group(['middleware' => 'authUser'], function() {

    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'CoreController@getDashboard']);

    Route::post('submit-pin', ['as' => 'submitPin', 'uses' => 'UserController@submitPin']);

    Route::get('update-info', ['as' => 'update-info', 'uses' => 'CoreController@getAccountInfo']);

    Route::post('save-salon-user', ['as' => 'saveSalonUser', 'uses' => 'UserController@saveSalonUser']);

    Route::post('update-user-account', ['as' => 'updateUser', 'uses' => 'UserController@updateUserAccount']);

    Route::get('test', ['as' => 'testroute', 'uses' => 'CoreController@test']);

    Route::get('calendar/export', ['uses' => 'CalendarController@getExportToCal']);

    Route::post('account/delete', ['as' => 'deleteUserAccount', 'uses' => 'UserController@deleteUserAccount']);

    Route::post('social/award-points', ['as' => 'awardSocialPoints', 'uses' => 'LoyaltyController@awardSocialPoints']);

    //superadmin grupa
    Route::group(['middleware' => 'superadmin'], function() {

        Route::get('salon-user', ['as' => 'salonUserManagement', 'uses' => 'CoreController@getSalonUserManagement']);

        Route::get('salon/manage', ['as' => 'salonManagement', 'uses' => 'SalonController@getSalonManagement']);

        Route::get('salonadmin/signin/{id}', ['as' => 'signInAsAdmin', 'uses' => 'CoreController@signInAsAdmin']);

        Route::post('salon/delete', ['as' => 'deleteSalon', 'uses' => 'CoreController@deleteSalon']);

        Route::get('website-slider', ['as' => 'websiteSlider', 'uses' => 'WebsiteController@getWebsiteSliderSettings']);

        Route::post('website-slider/upload', ['as' => 'uploadSliderGlobalImages', 'uses' => 'WebsiteController@uploadGlobalSliderImages']);

        Route::post('website/slider/delete', ['uses' => 'WebsiteController@deleteSliderImage']);

    });

    Route::group(['middleware' => 'salonadmin'], function() {

       Route::post('location/change', ['uses' => 'CoreController@adminSwitchLocation']);

    });

    Route::group(['middleware' => 'client'], function() {

        Route::post('my-appointments/upcoming-bookings', ['uses' => 'ClientController@getUpcomingBookings']);

        Route::get('my-appointments', ['as' => 'clientAppointments', 'uses' => 'ClientController@getClientAppointments']);

        Route::get('wspay/response', ['as' => 'WSPayResponse', 'uses' => 'PaymentController@getWsPayResponse']);

        Route::get('privacy', ['as' => 'privacySettings', 'uses' => 'ClientController@getPrivacySettings']);

        Route::get('loyalty-status', ['as' => 'loyaltyStatus', 'uses' => 'ClientController@getLoyaltyStatus']);

        Route::post('privacy/update', ['uses' => 'ClientController@updatePrivacySettings']);

        Route::get('payment/wspay/{booking_id}', ['as' => 'getWsPayGateway', 'uses' => 'PaymentController@getWsPayGateway']);

    });

    Route::group(['middleware' => 'checkin'], function() {

        Route::group(['middleware' => 'manageSalon'], function() {

            Route::post('create-salon', ['as' => 'createSalon', 'uses' => 'SalonController@createSalon']);

            Route::get('salon-info', ['as' => 'salonInfo', 'uses' => 'SalonController@getSalonInfo']);

            Route::get('salon-services', ['as' => 'salonServices', 'uses' => 'SalonController@getSalonServices']);

            Route::post('save-service', ['as' => 'saveService', 'uses' => 'SalonController@saveService']);

            Route::post('update-salon', ['as' => 'updateSalon', 'uses' => 'SalonController@updateSalonInfo']);

            Route::post('add-new-field', ['as' => 'addNewField', 'uses' => 'SalonController@addNewField']);

            Route::get('get-field-info/{id}', ['uses' => 'SalonController@getFieldInfo']);

            Route::post('custom-fields/update', ['as' => 'editCustomField', 'uses' => 'SalonController@updateCustomField']);

            Route::post('field/delete', ['uses' => 'SalonController@deleteField']);

            Route::get('salon/payment-options', ['uses' => 'PaymentController@getPaymentOptions']);

            Route::post('salon/payment/update', ['uses' => 'PaymentController@updateSalonPayment']);

        });

        Route::group(['middleware' => 'manageLocations'], function() {

            Route::get('location-info', ['as' => 'locationInfo', 'uses' => 'SalonController@getLocationInfo']);

            Route::get('location-new', ['as' => 'newLocation', 'uses' => 'SalonController@newLocation']);

            Route::post('create-location', ['as' => 'createLocation', 'uses' => 'SalonController@createLocation']);

            Route::post('update-location', ['as' => 'updateLocation', 'uses' => 'SalonController@updateLocation']);

            Route::post('update-hours', ['as' => 'updateWorkingHours', 'uses' => 'SalonController@updateHours']);

            Route::post('update-billing-info', ['as' => 'postBillingInfo', 'uses' => 'SalonController@updateBilling']);

            Route::get('location/delete', ['uses' => 'SalonController@deleteLocation']);

            Route::post('location/category/new', ['as' => 'addNewCategory', 'uses' => 'SalonController@addNewCategory']);

            Route::post('location/subcategory/new', ['as' => 'addNewSubCategory', 'uses' => 'SalonController@addNewSubCategory']);

            Route::post('location/group/new', ['as' => 'addNewGroup', 'uses' => 'SalonController@addNewGroup']);

            Route::post('location/service/new', ['as' => 'addNewService', 'uses' => 'SalonController@addService']);

            Route::post('location/service/edit', ['as' => 'editService', 'uses' => 'SalonController@editService']);

            Route::post('location/staff/edit', ['as' => 'editServiceStaff', 'uses' => 'SalonController@editServiceStaff']);

            Route::post('location/images/upload', ['as' => 'uploadLocationImages', 'uses' => 'SalonController@uploadLocationImages']);

            Route::post('location/image/delete', ['uses' => 'SalonController@deleteLocationPhoto']);

            Route::post('location/services/import', ['as' => 'importServices', 'uses' => 'SalonController@importServices']);
        });

        Route::group(['middleware' => 'manageStaff'], function() {

            Route::get('staff/general-settings', ['as' => 'staffGeneralSettings', 'uses' => 'StaffController@getStaffSettings']);

            Route::get('staff/manage', ['as' => 'manageStaff', 'uses' => 'StaffController@getStaffManagement']);

            Route::get('staff/security', ['as' => 'staffSecurityLevels', 'uses' => 'StaffController@getSecurityManagement']);

            Route::post('profile/update', ['as' => 'updateUserProfile', 'uses' => 'StaffController@updateUserProfile']);

            Route::post('profile/update-avatar', ['as' => 'updateProfilePicture', 'uses' => 'UserController@updateUserPicture']);

            Route::post('profile/update/security' , ['as' => 'updateUserSecurity', 'uses' => 'StaffController@updateUserSecurity']);

            Route::post('profile/update/services' , ['as' => 'updateStaffServices', 'uses' => 'StaffController@updateUserServices']);

            Route::post('update/staff/settings', ['as' => 'updateGeneralStaffSettings', 'uses' => 'StaffController@updateStaffSettings']);

            Route::post('profile/set-staff-hours', ['as' => 'setStaffHours', 'uses' => 'StaffController@setStaffHours']);

            Route::get('profile/{id}/{active?}', ['as' => 'getFullSchedule', 'uses' => 'StaffController@viewProfile']);

            Route::get('delete-vacation/{id}', ['as' => 'deleteVacation', 'uses' => 'StaffController@deleteVacation']);

            Route::post('schedule/delete/confirm', ['uses' => 'StaffController@confirmScheduleDelete']);

            Route::post('services/update', ['uses' => 'StaffController@addServicesToStaff']);

        });

        Route::get('staff/rosters', ['middleware' => 'viewRosters', 'as' => 'staffRosters', 'uses' => 'StaffController@getRosterManagement']);

        Route::post('add-staff-vacation', ['middleware' => 'addVacations', 'as' => 'addStaffVacation', 'uses' => 'StaffController@addStaffVacation']);

        Route::get('booking/get/{id}', ['uses' => 'BookingController@getBooking']);


        Route::group(['middleware' => 'manageBooking'], function() {

            Route::get('booking-settings', ['as' => 'onlineBooking', 'uses' => 'BookingController@getBookingSettings']);

            Route::post('booking-settings/update-policies', ['as' => 'updateBookingPolicies', 'uses' => 'BookingController@updateBookingPolicies']);

            Route::post('booking/add-custom-fields', ['as' => 'addCustomFields', 'uses' => 'BookingController@addCustomFields']);

            Route::get('admin/booking', ['as' => 'adminAddBooking', 'uses' => 'BookingController@adminAddBooking']);

            Route::post('booking/new', ['as' => 'addBooking', 'uses' => 'BookingController@addNewBooking']);

            Route::post('booking/update-custom-styles', ['as' => 'updateCustomStyles', 'uses' => 'BookingController@updateCustomStyles']);

            Route::post('booking/edit', ['as' => 'editBookingInfo', 'uses' => 'BookingController@editBookingInfo']);

            Route::post('booking/add-note', ['as' => 'addClientNote', 'uses' => 'BookingController@addClientNote']);

            Route::post('booking/submit-staff', ['uses' => 'BookingController@submitStaff']);

            Route::post('booking/new-client', ['as' => 'addNewClientInfo', 'uses' => 'BookingController@addNewClientInfo']);

            Route::get('booking/get-client/{id}', ['uses' => 'BookingController@getClient']);

            Route::post('booking/create-invoice', ['uses' => 'PosController@createInvoice']);

        });

        Route::post('booking/status/update', ['uses' => 'CalendarController@updateBookingStatus']);

        Route::group(['middleware' => 'manageClients'], function() {

            Route::get('clients', ['as' => 'salonClients', 'uses' => 'ClientController@getSalonClients']);

            Route::post('clients/update', ['as' => 'updateClientSettings', 'uses' => 'ClientController@updateClientSettings']);

            Route::post('clients/label/save', ['as' => 'saveClientLabel', 'uses' => 'ClientController@saveClientLabel']);

            Route::post('clients/label/delete', ['as' => 'deleteClientLabel', 'uses' => 'ClientController@deleteClientLabel']);

            Route::post('clients/referral/save', ['as' => 'saveClientReferral', 'uses' => 'ClientController@saveClientReferral']);

            Route::post('clients/referral/delete', ['as' => 'deleteClientReferral', 'uses' => 'ClientController@deleteClientReferral']);

            Route::post('client/set/label', ['uses' => 'ClientController@setClientLabel']);

            Route::post('client/set/referral', ['uses' => 'ClientController@setClientReferral']);

            Route::get('client/profile/{id}', ['as' => 'viewClientProfile', 'uses' => 'ClientController@getClientProfile']);

            Route::post('client/profile/update', ['as' => 'updateClientInfo', 'uses' => 'ClientController@updateClientInfo']);

        });

        Route::get('appointments/{staff?}', ['middleware' => 'viewAppointments', 'as' => 'appointments', 'uses' => 'CalendarController@getAppointments']);

        Route::group(['middleware' => 'manageCalendar'], function() {

            Route::get('calendar/settings', ['as' => 'calendarSettings', 'uses' => 'CalendarController@getCalendarSettings']);

            Route::post('calendar/settings/update', ['as' => 'updateCalendar', 'uses' => 'CalendarController@updateCalendar']);

            Route::post('calendar/colors/update', ['as' => 'updateCalendarColors', 'uses' => 'CalendarController@updateCalendarColors']);

            Route::post('calendar/client-note', ['as' => 'clientNote', 'uses' => 'CalendarController@editClientNote']);

            Route::post('calendar/slowday', ['as' => 'activateSlowDayHour', 'uses' => 'CalendarController@setSlowDayHour']);

        });

        Route::group(['middleware' => 'manageWebsite'], function() {

           Route::get('website/settings', ['as' => 'websiteSettings', 'uses' => 'WebsiteController@websiteSettings']);

           Route::post('website/set-url', ['uses' => 'WebsiteController@setWebsiteUrl']);

           Route::post('website/content/update', ['uses' => 'WebsiteController@saveWebsiteContent']);

           Route::get('website/blog-settings', ['as' => 'manageBlog', 'uses' => 'WebsiteController@getWebsiteBlog']);

           Route::post('website/blog/submit', ['as' => 'submitBlog', 'uses' => 'WebsiteController@submitBlogPost']);

           Route::post('blog/post/delete', ['uses' => 'WebsiteController@deleteBlogPost']);

           Route::get('blog/{id}', ['uses' => 'WebsiteController@getBlogPostContent']);

           Route::post('website/social-links/save', ['as' => 'updateSocialLinks', 'uses' => 'WebsiteController@updateSocialLinks']);

           Route::post('website/slider-images/save', ['as' => 'uploadSliderImages', 'uses' => 'WebsiteController@uploadSliderImages']);

           Route::post('website/about-image/save', ['as' => 'uploadAboutImage', 'uses' => 'WebsiteController@uploadAboutImage']);

           Route::post('website/slider-image/delete', ['uses' => 'WebsiteController@deleteSliderImage']);

           Route::post('website/about-image/save', ['as' => 'saveAboutImage', 'uses' => 'WebsiteController@saveAboutImage']);

           Route::post('website/about-image/delete', ['uses' => 'WebsiteController@deleteAboutImage']);

           Route::post('website/slider-promo/update', ['as' => 'updateSliderPromo', 'uses' => 'WebsiteController@updateSliderPromo']);

           Route::post('website/update-slider', ['uses' => 'WebsiteController@updateSliderImages']);

        });

        Route::group(['middleware' => 'manageLoyalty'], function() {

           Route::get('loyalty-management', ['as' => 'loyaltyManagement', 'uses' => 'LoyaltyController@getLoyaltyManagement']);

           Route::post('loyalty-management/save', ['as' => 'saveLoyaltySettings', 'uses' => 'LoyaltyController@saveLoyaltySettings']);

           Route::post('loyalty-discounts/save', ['as' => 'saveLoyaltyDiscounts', 'uses' => 'LoyaltyController@saveLoyaltyDiscounts']);

           Route::post('loyalty/add-discounts', ['as' => 'addNewDiscounts', 'uses' => 'LoyaltyController@addNewDiscounts']);

           Route::post('loyalty/discount/delete', ['uses' => 'LoyaltyController@deleteDiscount']);

           Route::post('loyalty/happy-hour/update', ['as' => 'updateHappyHourSettings', 'uses' => 'LoyaltyController@updateHappyHourSettings']);

           Route::post('loyalty/voucher/new', ['as' => 'createNewVoucher', 'uses' => 'LoyaltyController@createNewVoucher']);

           Route::get('loyalty/voucher/delete/{id}', ['uses' => 'LoyaltyController@deleteVoucher']);

           Route::post('loyalty/voucher/update', ['uses' => 'LoyaltyController@updateVoucher']);

           Route::post('loyalty/change-type', ['uses' => 'LoyaltyController@changeLoyaltyProgram']);

           Route::get('gift-vouchers', ['as' => 'giftVouchers', 'uses' => 'LoyaltyController@getGiftVouchers']);

           Route::get('happy-hour', ['as' => 'happyHour', 'uses' => 'LoyaltyController@getHappyHour']);

           Route::post('loyalty/update/services', ['uses' => 'LoyaltyController@updateServicesPoints']);

        });

        Route::group(['middleware' => 'manageMarketing'], function() {

            Route::get('marketing', ['as' => 'marketingSettings', 'uses' => 'MarketingController@getMarketingSettings']);

            Route::get('marketing/get-fields/{field_id}', ['uses' => 'MarketingController@getAvailableFields']);

            Route::post('marketing/template/new', ['as' => 'addNewTemplate', 'uses' => 'MarketingController@createNewTemplate']);

            Route::get('marketing/template/{id}', ['uses' => 'MarketingController@getTemplate']);

            Route::post('marketing/template/edit', ['as' => 'editTemplate', 'uses' => 'MarketingController@editTemplate']);

            Route::post('template/delete', ['uses' => 'MarketingController@deleteTemplate']);

            Route::post('marketing/reminder/update', ['uses' => 'MarketingController@updateReminder']);

            Route::get('marketing/campaign/create', ['as' => 'campaignCreation', 'uses' => 'MarketingController@getNewCampaign']);

            Route::post('marketing/campaign/new', ['as' => 'addNewCampaign', 'uses' => 'MarketingController@createNewCampaign']);

            Route::get('marketing/campaign/{id}', ['as' => 'getCampaignEdit', 'uses' => 'MarketingController@getCampaignInfo']);

            Route::post('marketing/campaign/edit', ['as' => 'editCampaign', 'uses' => 'MarketingController@editCampaign']);

            Route::get('marketing/campaign-delete/{id}', ['uses' => 'MarketingController@deleteCampaign']);

            Route::get('marketing/campaign-send/{id}', ['uses' => 'MarketingController@sendCampaign']);

        });

        Route::group(['middleware' => 'managePos'], function() {

            Route::get('pos/billing-info', ['as' => 'billingInfo', 'uses' => 'PosController@getBillingInfo']);

            Route::get('pos/settings', ['as' => 'posSettings', 'uses' => 'PosController@getSettings']);

            Route::post('pos/settings/update', ['as' => 'updateFiskalSettings', 'uses' => 'PosController@updateFiskalSettings']);

            Route::get('pos/charging-devices', ['as' => 'getChargingDevices', 'uses' => 'PosController@getChargingDevices']);

            Route::post('pos/charging-devices/add', ['as' => 'addChargingDevice', 'uses' => 'PosController@addChargingDevice']);

            Route::post('pos/charging-device/delete', ['as' => 'deleteChargingDevice', 'uses' => 'PosController@deleteChargingDevice']);

            Route::get('pos/invoices', ['as' => 'getInvoices', 'uses' => 'PosController@getInvoices']);

            Route::get('pos/invoice/{id}', ['as' => 'getInvoice', 'uses' => 'PosController@getInvoice']);

            Route::get('create-pdf/{id}', ['as' => 'printInvoice', 'uses' => 'PosController@createInvoicePDF']);

        });

        Route::get('test-fb-api', ['uses' => 'FacebookController@testApi']);

        Route::get('campaigns/facebook', ['as' => 'facebookCampaigns', 'uses' => 'FacebookController@getFacebookCampaignManagement']);

        Route::post('campaigns/facebook/new', ['uses' => 'FacebookController@createFacebookCampaign']);

    });

});

//ajax routes

Route::group(['prefix' => 'ajax'], function() {

   Route::get('switch-lang/{id}', ['as' => 'switch-lang', 'uses' => 'CoreController@switchLanguage']);

   Route::post('addNewUser', ['as' => 'addNewUser', 'uses' => 'UserController@addNewUser']);

   Route::post('set-hours', ['as' => 'setOpenHours', 'uses' => 'SalonController@setHours']);

   Route::post('change-permission', ['as' => 'changePermission', 'uses' => 'UserController@changePermission']);

   Route::get('deleteService/{id}', ['uses' => 'SalonController@deleteService']);

   Route::get('deleteUser/{id}', ['as' => 'deleteUser', 'uses' => 'CoreController@deleteUser']);

   Route::post('addNewStaff', ['as' => 'addNewStaff', 'uses' => 'StaffController@addNewStaff']);

   Route::get('booking/{uid}/{val}', ['uses' => 'StaffController@changeBookingStatus']);

   Route::get('update-role/{uid}/{role_id}', ['uses' => 'StaffController@updateRole']);

   Route::get('update-location/{uid}/{location_id}', ['uses' => 'StaffController@updateUserLocation']);

   Route::post('update-schedule', ['uses' => 'StaffController@updateUserSchedule']);

   Route::get('delete-schedule/{id}', ['uses' => 'StaffController@deleteSchedule']);

   Route::get('location-hours/{id}', ['uses' => 'StaffController@getLocationHours']);

   Route::post('admin/calendar/week', ['uses' => 'BookingController@getWeekCalendar']);

   Route::get('service/staff', ['uses' => 'BookingController@getServiceStaff']);

   Route::get('get-salon-data', ['uses' => 'SalonController@getSalonData']);

   Route::get('location/{id}/services', ['uses' => 'SalonController@getLocationServices']);

   Route::post('staff/schedule', ['uses' => 'BookingController@getStaffDates']);

   Route::post('schedule/get-schedule', ['uses' => 'BookingController@getStaffHours']);

   Route::get('update/{id}/{action}', ['uses' => 'CalendarController@updateBookingStatus']);

   Route::post('reschedule/booking', ['uses' => 'BookingController@rescheduleBooking']);

   Route::get('delete-field/{id}', ['uses' => 'BookingController@deleteCustomField']);

   Route::get('get-booking/{id}', ['uses' => 'BookingController@getBookingInfo']);

   Route::get('clients/{location_id}', ['uses' => 'BookingController@getClients']);

   Route::get('category/{id}', ['uses' => 'SalonController@getCategoryInfo']);

   Route::get('category/delete/{id}', ['uses' => 'SalonController@deleteCategory']);

   Route::get('group/{id}', ['uses' => 'SalonController@getGroupInfo']);

   Route::get('group/delete/{id}', ['uses' => 'SalonController@deleteGroup']);

   Route::get('subcategory/{id}', ['uses' => 'SalonController@getSubcategoryInfo']);

   Route::get('subcategory/delete/{id}', ['uses' => 'SalonController@deleteSubcategory']);

   Route::get('get-group/{id}', ['uses' => 'SalonController@getGroupList']);

   Route::get('get-subgroup/{id}', ['uses' => 'SalonController@getSubGroupList']);

   Route::get('service/{id}', ['uses' => 'SalonController@getServiceInfo']);

   Route::get('get-service-staff/{id}', ['uses' => 'SalonController@serviceStaff']);

   Route::post('change-order', ['uses' => 'SalonController@changeServiceOrder']);

   Route::get('services/{location_id}/{cat_id}', ['uses' => 'BookingController@getServices']);

   Route::post('booking/redeem-code', ['uses' => 'BookingController@redeemCode']);

   Route::post('booking/calc-points', ['uses' => 'BookingController@calculatePoints']);

   Route::get('unique-codes', ['uses' => 'SalonController@getUniqueCodes']);

   Route::get('fetch-blogposts/{salon_id}/{page_number}', ['uses' => 'WebsiteController@loadBlogPosts']);

   Route::get('vouchers/get', ['uses' => 'LoyaltyController@getVouchers']);

   Route::get('client-booking/categories/{location}', ['uses' => 'BookingController@clientGetCategoryList']);

   Route::post('booking/submit-services', ['as' => 'submitSelectedServices', 'uses' => 'BookingController@submitServices']);

   Route::get('booking-fields/{location}', ['uses' => 'BookingController@getClientFields']);

   Route::post('booking/submit', ['as' => 'clientConfirmBooking', 'uses' => 'BookingController@clientConfirmBooking']);

   Route::post('happy-hour/change-status', ['uses' => 'LoyaltyController@changeHappyHourStatus']);

   Route::get('calendar/{booking_id}/links', ['uses' => 'CalendarController@getCalendarLinks']);

   Route::post('custom-fields/status', ['as' => 'updateDisplayFields', 'uses' => 'BookingController@updateDisplayFields']);

   Route::get('get-service-groups/{id}', ['uses' => 'SalonController@getServiceGroups']);

   Route::post('change-stats-date', ['uses' => 'CoreController@changeStatsDate']);

});

//salon website

Route::get('{salon_name}/privacy-policy', ['as' => 'privacyPolicy', 'uses' => 'CoreController@getPrivacyPolicy']);

Route::get('privacy-policy', ['as' => 'privacyPolicyAPP', 'uses' => 'CoreController@getPrivacyPolicyAPP']);

Route::get('{salon_name}', ['as' => 'salonWebsite', 'uses' => 'WebsiteController@getSalonWebsite']);

Route::get('{salon_name}/{location_name}', ['uses' => 'WebsiteController@getLocationWebsite']);

Route::get('online/booking/complete', ['uses' => 'BookingController@getBookingComplete']);