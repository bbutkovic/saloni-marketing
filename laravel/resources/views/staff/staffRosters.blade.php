@extends('main')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('content')
    <div id="location-options" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.work_schedule') }}</a></li>
                    <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.staff_holidays') }}</a></li>
                </ul>
                <div class="tab-content">
                    
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        @if($staff_roster != null)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover d-table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <?php $i = 0; ?>
                                                        @foreach($days as $day)
                                                        <th class="text-center">{{ trans('salon.'.$day) }} <br><small class="text-muted">{{ $week_dates[$i] }}</small></th>
                                                        <?php $i++; ?>
                                                        @endforeach
                                                        <th class="text-center">{{ trans('salon.hours') }}</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="staff-table">
                                                    @foreach($staff_roster as $schedule)
                                                        <tr class="staff-info">
                                                            <td>{{ $schedule['user']['first_name'] }} {{ $schedule['user']['last_name'] }}</td>
                                                            @if(count($schedule['schedule']) > 0)
                                                            @foreach($schedule['schedule'] as $work_hours)
                                                                @if(($work_hours['work_start'] != 'Not scheduled' && $work_hours['work_start'] != 'Nije postavljen') && $work_hours['work_start'] != 'vacation' && ($work_hours['work_start'] != '00:00' && $work_hours['work_end'] != '00:00'))
                                                                <td>{{ $work_hours['work_start'] }} to {{ $work_hours['work_end'] }}</td>
                                                                @elseif ($work_hours['work_start'] != 'Not scheduled' && $work_hours['work_start'] == 'vacation')
                                                                <td>{{ trans('salon.vacation') }}</td>
                                                                @elseif ($work_hours['work_start'] == '00:00' && $work_hours['work_end'] == '00:00')
                                                                <td>{{ trans('salon.not_scheduled') }}</td>
                                                                @else
                                                                <td>{{ trans('salon.not_scheduled') }}</td>
                                                                @endif
                                                            @endforeach
                                                            @else
                                                            <td>{{ trans('salon.not_scheduled') }}</td>
                                                            <td>{{ trans('salon.not_scheduled') }}</td>
                                                            <td>{{ trans('salon.not_scheduled') }}</td>
                                                            <td>{{ trans('salon.not_scheduled') }}</td>
                                                            <td>{{ trans('salon.not_scheduled') }}</td>
                                                            <td>{{ trans('salon.not_scheduled') }}</td>
                                                            <td>{{ trans('salon.not_scheduled') }}</td>
                                                            @endif
                                                            <td>{{ $schedule['hours'] }}</td>
                                                            <td class="user-options">
                                                                <a href="{{ route('getFullSchedule', [$schedule['user']['id'], 1]) }}">
                                                                    <i class="fa fa-edit table-edit"></i>
                                                                    Edit
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <h3 class="text-muted text-center">{{ trans('salon.schedule_not_defined') }}</h3>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        @include('partials.addStaffVacation')
                                        @if(Auth::user()->can('add-vacations'))<button type="button" class="btn btn-primary m-t m-b" data-toggle="modal" data-target="#addStaffVacation">{{ trans('salon.add_vacation') }}</button>@endif
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover d-table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">{{ trans('salon.staff_holidays') }}</th>
                                                        <th class="text-center">{{ trans('salon.note') }}</th>
                                                        <th class="text-center">{{ trans('salon.options') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="staff-table">
                                                    @foreach($user_list as $staff_member)
                                                        @foreach($staff_member->vacation as $vacation)
                                                        <tr class="staff-info">
                                                            @if($staff_member->vacation)
                                                                <td>{{ $staff_member->user_extras->first_name }} {{ $staff_member->user_extras->last_name }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($vacation->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($vacation->end_date)->format('d/m/Y') }}</td>
                                                                <td>{{ $vacation->note }}</td>
                                                                <td>
                                                                    <a href="{{ route('deleteVacation', $vacation->id) }}"><i class="fa fa-trash table-delete"></i></a>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
<script>
    var start = {{ Auth::user()->salon->week_starting_on }};
    if(start === 2) {
        week_start = 0;
    } else {
        week_start = 1;
    }
    $(".vacation-picker").each(function() {
        flatpickr(this, {
            enableTime: false,
            altInput: true,
            minDate: "today",
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            "locale": {
                "firstDayOfWeek": week_start
            }
        });
    });
</script>
@endsection