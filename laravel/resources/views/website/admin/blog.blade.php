@extends('main')

@section('styles')
{{ HTML::style('css/plugins/summernote/summernote.css') }}
{{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=944zbcd6b70j3spki3txrzecsz6n99ua5dapocup4abxci3c"></script>
{{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
@endsection

@section('scripts-footer')
{{ HTML::script('js/website/websiteSettings.js') }}
@endsection

@section('content')
    
    <div id="websiteOptions" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.blog_posts') }}</a></li>
                    <li id="tab-2-li"><a data-toggle="tab" href="#tab-2">{{ trans('salon.new_post') }}</a></li>
                </ul>
                <div class="tab-content">
                    
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <div class="row">
                                    <table class="table table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{ trans('salon.blog_post_title') }}</th>
                                                <th class="text-center">{{ trans('salon.date_submitted') }}</th>
                                                <th class="text-center">{{ trans('salon.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($blog_posts as $blog_post)
                                            <tr id="blogPost{{$blog_post->id}}">
                                                <td class="text-center"><a href="{{ route('getBlogPost',[$salon->unique_url, $blog_post->unique_url]) }}">{{ $blog_post->title }}</a></td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($blog_post->created_at)->formatLocalized('%A %d %B %Y') }}</td>
                                                <td class="text-center">
                                                    <a href="#" onclick="editBlogPost({{$blog_post->id}})">
                                                        <i class="fa fa-pencil table-profile"></i>
                                                    </a>
                                                    <a href="#" onclick="deleteBlogPost({{$blog_post->id}})">
                                                        <i class="fa fa-trash table-delete"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <div class="row">
                                    {{ Form::open(array('route' => 'submitBlog', 'files' => 'true')) }}
                                        <div class="form-group">
                                            <label for="postTitle">{{ trans('salon.blog_post_title') }}*</label>
                                            {{ Form::text('post_title', null, array('id' => 'postTitle', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="postDescription">{{ trans('salon.blog_post_description') }}*</label>
                                            {{ Form::text('post_description', null, array('id' => 'postDescription', 'class' => 'form-control', 'required')) }}
                                        </div>
                                                
                                        <div class="form-group">
                                            <label for="featured_image">{{ trans('salon.featured_image') }}*</label>
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">{{ trans('salon.select_featured_image') }}</span>
                                                    <input type="file" name="featured_image" id="featuredImageUpload">
                                                </span>
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                            </div> 
                                        </div>
                                                        
                                        <div class="form-group">
                                            <label for="summernote">{{ trans('salon.blog_post_content') }}*</label>
                                            <textarea id="summernote" name="editordata" required></textarea>
                                            <input name="image" type="file" id="upload" class="hidden" onchange="">
                                        </div>
                                        <button type="submit" class="btn btn-success m-t m-b submit-post-btn" disabled>{{ trans('salon.submit_blog') }}</button>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('partials.editBlogPost')
                </div>
            </div>
        </div>
    </div>
  
    <script>
        var prompt = '{{ trans('salon.are_you_sure') }}';
        var image_dim_not_valid = '{{ trans('salon.image_dim_not_valid') }}';
        
        $(document).ready(function() {
            tinymce.init({
                selector: 'textarea',
                setup: function (editor) {
                    editor.on('change', function (e) {
                        editor.save();
                    });
                },
                theme: "modern",
                paste_data_images: true,
                plugins: [
                  "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                  "searchreplace wordcount visualblocks visualchars code fullscreen",
                  "insertdatetime media nonbreaking save table contextmenu directionality",
                  "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                  if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                      var file = this.files[0];
                      var reader = new FileReader();
                      reader.onload = function(e) {
                        callback(e.target.result, {
                          alt: ''
                        });
                      };
                      reader.readAsDataURL(file);
                    });
                  }
                },
                templates: [{
                  title: 'Test template 1',
                  content: 'Test 1'
                }, {
                  title: 'Test template 2',
                  content: 'Test 2'
                }]
              });
            
            
        });

    </script>
@endsection

 