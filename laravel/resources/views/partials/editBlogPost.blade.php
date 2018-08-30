<div class="modal fade" id="editBlogPostModal" tabindex="-1" role="dialog" aria-labelledby="editBlogPostModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.edit_blog_post') }}</h4>
            </div>
            {{ Form::open(array('route' => 'submitBlog', 'files' => 'true')) }}
            {{ Form::hidden('post_id', null, array('id' => 'blogPostId')) }}
            <div class="modal-body">
                <div class="row">
                    
                    <div class="form-group">
                        <label for="postTitle">{{ trans('salon.blog_post_title') }}</label>
                        {{ Form::text('post_title', null, array('id' => 'editPostTitle', 'class' => 'form-control')) }}
                    </div>
                    
                    <div class="form-group">
                        <label for="postDescription">{{ trans('salon.blog_post_description') }}*</label>
                        {{ Form::text('post_description', null, array('id' => 'editPostDesc', 'class' => 'form-control')) }}
                    </div>
                    
                    <div class="form-group">
                        <label for="featured_image">{{ trans('salon.featured_image_new') }}</label>
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">{{ trans('salon.featured_image_new') }}</span>
                                <input type="file" name="featured_image" id="featuredImageUpload">
                            </span>
                            <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="form-group">
                        <textarea id="summernote-edit" name="editordata"></textarea>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="submit" class="btn btn-primary submit-post-btn">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>