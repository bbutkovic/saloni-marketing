<li>
    <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i><span class="nav-label m-l">{{ trans('menu.dashboard') }}</span></a>
</li>

@if(Auth::user()->salon != null)
    @if(Auth::user()->can('view-appointments') && Auth::user()->location != null)
    <li>
        <a href="{{ route('appointments') }}"><i class="fa fa-address-book"></i><span class="nav-label m-l">{{ trans('salon.appointments') }}</span></a>
    </li>
    @endif
    
    @if(Auth::user()->can('manage-salon'))
    <li>
        <a href="{{ route('salonInfo') }}"><i class="fa fa-briefcase"></i><span class="nav-label m-l">{{ trans('salon.my_salon') }}</span></a>
    </li>
    @endif
    
    @if(Auth::user()->can('manage-locations'))
    <li>
        <a href="#"><i class="fa fa-map-marker"></i><span class="nav-label m-l">{{ trans('salon.location_info') }}<span class="fa fa-angle-left arrow"></span></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="{{ route('locationInfo') }}"><span class="nav-label">{{ trans('salon.location_info_settings') }}</span></a></li>
            <li><a href="{{ route('salonServices') }}"><span class="nav-label">{{ trans('salon.location_services') }}</span></a></li>
        </ul>
    </li>
    @endif
    
    @if(Auth::user()->location != null)
        <li>
            <a href="#"><i class="fa fa-user"></i><span class="nav-label m-l">{{ trans('salon.staff_options') }}<span class="fa fa-angle-left arrow"></span></span></a>
            <ul class="nav nav-second-level collapse">
                @if(Auth::user()->can('manage-staff'))
                <li><a href="{{ route('staffGeneralSettings') }}"><span class="nav-label">{{ trans('salon.staff_general_settings') }}</span></a></li>
                <li><a href="{{ route('manageStaff') }}"><span class="nav-label">{{ trans('salon.manage_staff') }}</span></a></li>
                <li><a href="{{ route('staffSecurityLevels') }}"><span class="nav-label">{{ trans('salon.staff_levels') }}</span></a></li>
                @endif
                @if(Auth::user()->can('view-rosters'))
                <li><a href="{{ route('staffRosters') }}"><span class="nav-label">{{ trans('salon.staff_rosters') }}</span></a></li>
                @endif
            </ul>
        </li>

        @if(Auth::user()->can('manage-pos'))
        <li>
            <a href="#"><i class="fa fa-usd"></i><span class="nav-label m-l">POS<span class="fa fa-angle-left arrow"></span></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{{ route('billingInfo') }}"><span class="nav-label">{{ trans('salon.billing_info') }}</span></a></li>
                @if(Auth::user()->salon->country == 'hr')<li><a href="{{ route('posSettings') }}"><span class="nav-label">Fiskalizacija</span></a></li>@endif
                <li><a href="{{ route('getInvoices') }}"><span class="nav-label">{{ trans('salon.invoices') }}</span></a></li>
                <li><a href="{{ route('getChargingDevices') }}"><span class="nav-label">{{ trans('salon.charging_devices') }}</span></a></li>
            </ul>
        </li>
        @endif

        @if(Auth::user()->can('manage-booking'))
        <li>
            <a href="#"><i class="fa fa-book"></i><span class="nav-label m-l">{{ trans('salon.booking') }}<span class="fa fa-angle-left arrow"></span></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{{ route('onlineBooking') }}"><span class="nav-label">{{ trans('salon.online_booking') }}</span></a></li>
                <li><a href="{{ route('adminAddBooking') }}"><span class="nav-label">{{ trans('salon.create_booking') }}</span></a></li>
            </ul>
        </li>
        @endif
        
        @if(Auth::user()->can('manage-clients'))
        <li>
            <a href="{{ route('salonClients') }}"><i class="fa fa-users"></i><span class="nav-label m-l">{{ trans('salon.clients') }}</span></a>
        </li>
        @endif
        
        @if(Auth::user()->can('manage-calendar'))
        <li>
            <a href="{{ route('calendarSettings') }}"><i class="fa fa-calendar"></i><span class="nav-label m-l">{{ trans('salon.calendar_settings') }}</span></a>
        </li>
        @endif
        
        @if(Auth::user()->can('manage-website'))
        <li>
            <a href="#"><i class="fa fa-globe"></i><span class="nav-label m-l">{{ trans('salon.website') }}<span class="fa fa-angle-left arrow"></span></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{{ route('websiteSettings') }}"><span class="nav-label">{{ trans('salon.website_content_settings') }}</span></a></li>
                <li><a href="{{ route('websiteSliderSettings') }}"><span class="nav-label">{{ trans('salon.website_images') }}</span></a></li>
                <li><a href="{{ route('websiteTextboxSettings') }}"><span class="nav-label">{{ trans('salon.slider_promotions') }}</span></a></li>
            </ul>
        </li>
        <li>
            <a href="{{ route('manageBlog') }}"><i class="fa fa-pencil"></i><span class="nav-label m-l">{{ trans('salon.blog') }}</span></a>
        </li>
        @endif
        
        @if(Auth::user()->can('manage-loyalty'))
        <li>
            <a href="#"><i class="fa fa-percent"></i><span class="nav-label m-l">{{ trans('salon.loyalty_management') }}<span class="fa fa-angle-left arrow"></span></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{{ route('loyaltyManagement') }}"><span class="nav-label">{{ trans('salon.loyalty_management') }}</span></a></li>
                <li><a href="{{ route('happyHour') }}"><span class="nav-label">{{ trans('salon.happy_hour') }}</span></a></li>
                <li><a href="{{ route('giftVouchers') }}"><span class="nav-label">{{ trans('salon.gift_vouchers') }}</span></a></li>
            </ul>
        </li>

        @endif

        @if(Auth::user()->can('manage-marketing'))
        <li>
            <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label m-l">{{ trans('salon.marketing') }}<span class="fa fa-angle-left arrow"></span></span></a>
            <ul class="nav nav-second-level collapse">
                <li><a href="{{ route('marketingSettings') }}"><span class="nav-label">{{ trans('salon.autopilot_marketing') }}</span></a></li>
                <li><a href="{{ route('facebookCampaigns') }}"><span class="nav-label">Facebook marketing</span></a></li>
            </ul>
        </li>
        @endif
    
    @endif
@endif