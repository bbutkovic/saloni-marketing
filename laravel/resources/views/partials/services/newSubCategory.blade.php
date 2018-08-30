<div class="modal fade" id="newSubCategory" tabindex="-1" role="dialog" aria-labelledby="newSubCategory" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_new_subcategory') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewSubCategory', 'id' => 'addNewSubCategory')) }}
            {{ Form::hidden('group_id', null, array('id' => 'subGroupId')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="sub_category_name">{{ trans('salon.sub_category_name') }}</label>
                        {{ Form::text('sub_category_name', null, array('id' => 'subCategoryName', 'class' => 'form-control', 'required')) }}
                    </div>
                    <div class="form-group">
                        <label for="sub_category_desc">{{ trans('salon.sub_category_desc') }}</label>
                        {{ Form::textarea('sub_category_desc', null, array('id' => 'csubCategoryDesc', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="subgroup_color">{{ trans('salon.subgroup_color') }}</label>
                        <input type="text" id="spectrumSubGroupColor" name="subgroup_color" value="#6262b8">
                    </div>
                    <div class="form-group">
                        <label for="active_category">{{ trans('salon.active') }}</label>
                        <input type="checkbox" name="active_sub_category" id="activeSubCategory" checked>
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

<div class="modal fade" id="editSubCategory" tabindex="-1" role="dialog" aria-labelledby="editSubCategory" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.edit_subcategory') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewSubCategory', 'id' => 'editSubCategoryForm')) }}
            {{ Form::hidden('sub_category_id', null, array('id' => 'subCategoryId')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="category_name">{{ trans('salon.sub_category_name') }}</label>
                        {{ Form::text('sub_category_name', null, array('id' => 'subCategoryEditName', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="category_desc">{{ trans('salon.category_desc') }}</label>
                        {{ Form::textarea('sub_category_desc', null, array('id' => 'subCategoryEditDesc', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="subgroup_color">{{ trans('salon.subgroup_color') }}</label>
                        <input type="text" id="spectrumEditSubGroupColor" name="subgroup_color">
                    </div>
                    <div class="form-group">
                        <label for="active_category">{{ trans('salon.active') }}</label>
                        <input type="checkbox" name="active_sub_category" id="editActiveSubCategory">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button id="deleteSubCat" type="button" class="btn btn-danger" data-id="" onclick="deleteSubCategory()">{{ trans('salon.delete') }}</button>
                <button type="submit" class="btn btn-success">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>