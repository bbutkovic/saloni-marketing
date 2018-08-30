<div class="modal fade" id="newGroupModal" tabindex="-1" role="dialog" aria-labelledby="newGroup" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_new_group') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewGroup', 'id' => 'addNewGroupCategory')) }}
            {{ Form::hidden('category_id', null, array('id' => 'groupCatId')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="group_name">{{ trans('salon.group_name') }}</label>
                        {{ Form::text('group_name', null, array('id' => 'groupName', 'class' => 'form-control', 'required')) }}
                    </div>
                    <div class="form-group">
                        <label for="group_desc">{{ trans('salon.group_desc') }}</label>
                        {{ Form::textarea('group_desc', null, array('id' => 'groupDesc', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="group_color">{{ trans('salon.group_color') }}</label>
                        <input type="text" id="spectrumGroupColor" name="group_color" value="#6262b8">
                    </div>
                    <div class="form-group">
                        <label for="active_group">{{ trans('salon.active') }}</label>
                        <input type="checkbox" name="active_group" id="activeGroup" checked>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div class="modal fade" id="editGroupModal" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.edit_group') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewGroup', 'id' => 'editGroupCategory')) }}
            {{ Form::hidden('group_id', null, array('id' => 'groupId')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="group_name">{{ trans('salon.group_name') }}</label>
                        {{ Form::text('group_name', null, array('id' => 'editGroupName', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="category_desc">{{ trans('salon.category_desc') }}</label>
                        {{ Form::textarea('group_desc', null, array('id' => 'editGroupDesc', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="group_color">{{ trans('salon.group_color') }}</label>
                        <input type="text" id="spectrumEditGroupColor" name="group_color">
                    </div>
                    <div class="form-group">
                        <label for="active_category">{{ trans('salon.active') }}</label>
                        <input type="checkbox" name="active_group" id="editActiveGroup">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button id="deleteGroupButton" type="button" class="btn btn-danger" data-id="" onclick="deleteGroup()">{{ trans('salon.delete') }}</button>
                <button type="submit" class="btn btn-success">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>