<li>
    <a href="{{ route('clientAppointments') }}"><i class="fa fa-address-book"></i><span class="nav-label m-l">{{ trans('salon.my_appointments') }}</span></a>
</li>

<li>
    <a href="{{ route('loyaltyStatus') }}"><i class="fa fa-percent"></i><span class="nav-label m-l">{{ trans('salon.loyalty_status') }}</span></a>
</li>

<li>
    <a href="{{ route('privacySettings') }}"><i class="fa fa-lock"></i><span class="nav-label m-l">{{ trans('salon.privacy_settings') }}</span></a>
</li>

{{--<li>
    <a href="#"><i class="fa fa-map-marker"></i><span class="nav-label m-l">{{ trans('salon.location_info') }}<span class="fa fa-angle-left arrow"></span></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="{{ route('locationInfo') }}"><span class="nav-label">{{ trans('salon.location_info_settings') }}</span></a></li>
        <li><a href="{{ route('salonServices') }}"><span class="nav-label">{{ trans('salon.location_services') }}</span></a></li>
    </ul>
</li>--}}
