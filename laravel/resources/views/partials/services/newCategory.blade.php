<div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategory" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_new_category') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewCategory', 'id' => 'newCategory')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="category_name">{{ trans('salon.category_name') }}</label>
                        {{ Form::text('category_name', null, array('id' => 'categoryName', 'class' => 'form-control', 'required')) }}
                    </div>
                    <div class="form-group">
                        <label for="category_desc">{{ trans('salon.category_desc') }}</label>
                        {{ Form::textarea('category_desc', null, array('id' => 'categoryDesc', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="category_color">{{ trans('salon.category_color') }}</label>
                        <input type="text" id="spectrumCatColor" name="category_color" value="#6262b8">
                    </div>
                    <div class="form-group">
                        <label for="active_category">{{ trans('salon.active') }}</label>
                        <input type="checkbox" name="active_category" id="activeCategory" checked>
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

<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategory" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.edit_category') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewCategory', 'id' => 'editCategory')) }}
            {{ Form::hidden('category_id', null, array('id' => 'categoryId')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="category_name">{{ trans('salon.category_name') }}</label>
                        {{ Form::text('category_name', null, array('id' => 'categoryEditName', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="category_desc">{{ trans('salon.category_desc') }}</label>
                        {{ Form::textarea('category_desc', null, array('id' => 'categoryEditDesc', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="category_color">{{ trans('salon.category_color') }}</label>
                        <input type="text" id="spectrumEditCatColor" name="category_color" value="#6262b8">
                    </div>
                    <div class="form-group">
                        <label for="active_category">{{ trans('salon.active') }}</label>
                        <input type="checkbox" name="active_category" id="editActiveCategory">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button id="deleteCat" type="button" class="btn btn-danger" data-id="" onclick="deleteCategory()">{{ trans('salon.delete') }}</button>
                <button type="submit" class="btn btn-success">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>