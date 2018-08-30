<div class="row text-center">
    <label for="repeatWeeks">{{ trans('salon.select_shifts_desc') }}</label>
    <select id="repeatWeeks" name="repeat_for" class="form-control select-hours-type">
        <option value="default" selected disabled>{{ trans('salon.repeat_every') }}...</option>
        <option value="1">{{ trans('salon.work_every_week') }}</option>
        <option value="2">{{ trans('salon.work_two_weeks') }}</option>
        <option value="3">{{ trans('salon.work_three_weeks') }}</option>
        <option value="4">{{ trans('salon.work_four_weeks') }}</option>
    </select>
</div>