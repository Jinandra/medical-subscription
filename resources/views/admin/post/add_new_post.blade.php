@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')
<style type="text/css">
    .foto-holder {
        cursor: pointer;
    }
    .foto-holder:hover {
        opacity: 0.8;
    }
</style>

<div class="right_col" role="main">

    <div class="page-title">
        <div class="title_left">
            <h3>Add New Post</h3>
            @if (Session::has('message'))
                <div role="alert" class="alert alert-success">
                    {{ Session::get('message') }}
                </div>
            @endif

            @if($errors->has())
                <div role="alert" class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="clearfix"></div>

    <form id="f1" method="post" action="{{URL::to('admin/post/add-new')}}" enctype="multipart/form-data" class="form-horizontal form-label-left">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>General</h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::text('title', Input::old('title'), array('placeholder' => 'Title','class' => 'form-control col-md-7 col-xs-12','required'=>'')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">Content</label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <div class="mokkiEditor damnEditor" >
                                    <textarea placeholder="Content" name="content" class="form-control col-md-7 col-xs-12 visual-editor">{{Input::old('content')}}</textarea>
                                    <div class="">
                                </div>
                            </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategori">Category</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">                            
                                {!! Form::select('category' , array('Page' => 'Page') , Input::old('category'), array('class' => 'form-control col-md-7 col-xs-12') ) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="foto">Cover Image</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label>

                                <div class="foto-holder"  style="width:200px; height:200px; border:1px solid #ccc">
                                <img src="" alt="" class="image-preview " style ="width:100%; height:100%; padding:5px">
                                <div class="plus" style="margin-left:40%; margin-top:25%; "></div>
                                </div>
                                <div></div>
                                <small>
                                    Image max file size <b>3 mb</b><br/>                                    
                                </small>
                                <input style="visibility: hidden;" type="file" name="image" class="form-control col-md-7 col-xs-12 image-input" onchange="readURL(this)">
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::select('post_status' , array('Publish' => 'Publish','Draft' => 'Draft'), Input::old('post_status'), array('class' => 'form-control col-md-7 col-xs-12') ) !!}
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                    </div>
                </div>
            </div>
        </div>

        <br/>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                &nbsp;&nbsp;
                <button type="reset" class="btn btn-danger">Cancel</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
            </div>
        </div>
        {{ csrf_field() }}
        <div class="form-group">
          <div class="col-xs-12">
            <a class="btn btn-default" href="{{ url('admin/post') }}">Back</a>
          </div>
        </div>
    </form>

</div>

@include('admin.partial.footerjs')
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script type="text/javascript">

    tinymce.init({
      selector: '.visual-editor',
      forced_root_block : false,
      force_br_newlines : true,
      force_p_newlines : false,
      height: 500,
      toolbar: [
        'undo redo | bold italic fontsizeselect | link unlink image | preview',
        'alignleft aligncenter alignright | forecolor backcolor'
      ]   ,
      plugins: [
        'advlist autolink lists link image preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
      ],
      content_css: [
        '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
        '//www.tinymce.com/css/codepen.min.css',
        '{{URL::asset("assets/gente-admin/css/custom-tiny-mce.css")}}'
      ],
      menu: {}
    });



    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                console.log(e);
                $(input).parent().find('.image-preview').attr('src', e.target.result);
                $(input).parent().find('.path-foto').val(e.target.result);

                $(input).parent().find('.plus').hide();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(function() {
        $('button[type=reset]').on('click', function (e) {
          if ( !confirm('Are you sure do you want to reset form data ?') ) {
            e.preventDefault();
          }
        });
    });

</script>
@include('admin.partial.footer')
