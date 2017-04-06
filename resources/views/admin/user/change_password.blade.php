@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')

<div class="right_col" role="main">
    
    <div class="page-title">
        <div class="title_left">            
            
            
        </div>      
    </div>
    <div class="clearfix"></div>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Change Password : {{ $user->first_name.' '.$user->last_name }} ({{ $user->screen_name }})</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <br>
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

                <form id="f1" method="post" action="{{URL::to('admin/user/change-password')}}" data-parsley-validate class="form-horizontal form-label-left">  
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">                           
                            <input type="password" name="password" class='form-control col-md-7 col-xs-12'>                         
                        </div>
                    </div>  

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirmation">Password Confirmation</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">                           
                            <input type="password" name="password_confirmation" class='form-control col-md-7 col-xs-12'>                            
                        </div>
                    </div>  
                    <div class="ln_solid"></div>
                    
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="reset" class="btn btn-primary">Cancel</button>
                            <input type="submit" class="btn btn-success" value="Submit" name="submit">
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $user->id }}">    
                    {{ csrf_field() }}                
                </form>

            </div>
        </div>
    </div>

</div>

<div id='dialog-form'></div>
    
</div>

@include('admin.partial.footerjs')

<script>
    $(document).ready(function () {
        
         
    });
</script>

@include('admin.partial.footer')
