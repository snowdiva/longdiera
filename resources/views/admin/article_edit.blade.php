@extends('admin.base')

@section('title', '编辑文章')

@include('UEditor::head')

@section('breadcrumb')
    <li>
        <a href="/webmaster/article/index"> 小说管理 </a>
    </li>
    <li class="active"> 编辑小说 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong>编辑一本小说。
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-header text-primary"> 编辑一本小说 </h3>

            <form action="/webmaster/article/edit" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">标题(英文):</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="title" class="col-xs-12 col-sm-6" value="{{ $list->title }}" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">标题(中文名):</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="chinese_title" class="col-xs-12 col-sm-6" value="{{ $list->chinese_title }}" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">作品简介:</label>

                    <div class="col-xs-12 col-sm-9">
                        <textarea name="intro" rows="10" class="col-xs-12 col-sm-6">{{ $list->intro }}</textarea>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">作者:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="author" class="col-xs-12 col-sm-6" placeholder="留空则为'unknown'" value="{{ $list->author }}" />
                        </div>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">小说分类:</label>

                    <div class="col-xs-12 col-sm-9">
                        <select name="sort_id" class="select2">
                            @foreach($sort as $key => $value)
                                <option value="{{ $key }}" @if($key == $list->sort_id)selected="true"@endif>{{ $value['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">连载状态:</label>

                    <div class="col-xs-12 col-sm-9">
                        <select name="publish_status" class="select2">
                            <option value="0">连载中</option>
                            <option value="1" @if(1 == $list->sort_id)selected="true"@endif>已完结</option>
                        </select>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">封面图片:</label>
                    <div class="col-xs-12 col-sm-2">
                        <img style="width: 100%;height: auto" src="{{ $list->cover_url }}?{{ time() }}" alt="{{ $list->title }}">
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        {{--<div id="dropzone">--}}
                        {{--<div class="dropzone">--}}
                        {{--<div class="fallback">--}}
                        {{--<input name="cover" type="file" multiple="" />--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <input multiple="" type="file" name="cover" id="cover_input" />
                    </div>
                </div>

                {{--<div class="hr hr-dotted"></div>--}}

                {{--<div class="form-group">--}}
                {{--<label class="control-label col-xs-12 col-sm-3 no-padding-right">文章内容:</label>--}}

                {{--<div class="col-xs-12 col-sm-9">--}}
                {{--<div id="container" name="content" type="text/plain">--}}

                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="hr hr-dotted"></div>

                <div class="col-sm-4 col-sm-offset-3">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $list->id }}">
                    <input type="hidden" data-name="cover_js" name="cover" />
                    <a href="/webmaster/article/index" class="btn btn-default"> 取消 </a>
                    <button type="submit" class="btn btn-success"> 修改 </button>
                </div>

            </form>
        </div>

    </div>
    </div>

@endsection

@section('page_js')
    <script>
        {{--        window.UEDITOR_HOME_URL = '{{ route('save_image') }}'--}}
        
                jQuery(function($){
            {{--$( "#tabs" ).tabs();--}}

            {{--try {--}}
                {{--$(".dropzone").dropzone({--}}
                    {{--url:"{{ url('/webmaster/upload/cover_upload') }}",--}}
                    {{--paramName: "file", // The name that will be used to transfer the file--}}
                    {{--maxFilesize: 1, // MB--}}

{{--//                    method: 'get',--}}

                    {{--addRemoveLinks : true,--}}
                    {{--dictDefaultMessage :--}}
                            {{--'<span class="bigger-150 bolder"><i class="icon icon-caret-right red"></i> 拖拽图片</span>上传 <br /> \--}}
				            {{--<span class="smaller-80 grey">(或者 点击打开资源浏览器上传)</span><br />\--}}
				            {{--<i class="icon upload-icon icon-cloud-upload blue icon-3x"></i>'--}}
                    {{--,maxFiles: 3,--}}
                    {{--dictResponseError: 'Error while uploading file!',--}}

                    {{--//change the previewTemplate to use Bootstrap progress bars--}}
                    {{--previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"--}}
                    {{--,success: function(file, json) {--}}
                        {{--var _cover = $("[data-name=cover_js]").val()--}}
                        {{--_cover += json + ','--}}
                        {{--$("[data-name=cover_js]").val(_cover)--}}
                    {{--}--}}
                    {{--,error: function() {--}}
                        {{--// TODO::console error message.--}}
                    {{--}--}}
                {{--});--}}
            {{--} catch(e) {--}}
                {{--alert('Dropzone.js does not support older browsers!');--}}
            {{--}--}}

            $('#cover_input').ace_file_input({
                style:'well',
                btn_choose:'Drop files here or click to choose',
                btn_change:null,
                no_icon:'icon-cloud-upload',
                droppable:true,
                thumbnail:'small'//large | fit
                //,icon_remove:null//set null, to hide remove/reset button
                /**,before_change:function(files, dropped) {
						//Check an example below
						//or examples/file-upload.html
						return true;
					}*/
                /**,before_remove : function() {
						return true;
					}*/
                ,preview_error : function(filename, error_code) {
                    //name of the file that failed
                    //error_code values
                    //1 = 'FILE_LOAD_FAILED',
                    //2 = 'IMAGE_LOAD_FAILED',
                    //3 = 'THUMBNAIL_FAILED'
                    //alert(error_code);
                }

            }).on('change', function(){
                //console.log($(this).data('ace_input_files'));
                //console.log($(this).data('ace_input_method'));
            });

            {{--var ue=UE.getEditor('container', {--}}
            {{--toolbars: [--}}
            {{--['fullscreen', 'source', 'undo', 'redo', '|', 'bold', 'italic', 'underline', 'fontborder', 'skrikethrough', 'removeformat', 'formatmatch', '|', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']--}}
            {{--]--}}
            {{--})--}}
            {{--ue.ready(function(){--}}
            {{--ue.execCommand('serverparam', '_token', '{{ csrf_token() }}')--}}
            {{--})--}}
        })

    </script>
@endsection