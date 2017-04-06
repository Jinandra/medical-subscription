@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')

<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left"></div>
  </div>
  <div class="clearfix"></div>

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Add New User</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <br>
        @if (Session::has('message'))
          <div role="alert" class="alert alert-success">{!! Session::get('message') !!}</div>
        @endif

        @if ($errors->has())
          <div role="alert" class="alert alert-danger">
            @foreach ($errors->all() as $error)
              <p>{!! $error !!}</p>
            @endforeach
          </div>
        @endif

        <form id="f1" method="post" action="{{ route('admin::user::create') }}" data-parsley-validate class="form-horizontal form-label-left">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="screen_name">Screen Name*</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              {!! Form::text('screen_name', Input::old('screen_name'), array('placeholder' => 'Screen Name','class' => 'form-control col-md-7 col-xs-12','required'=>'')) !!}
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first_name">First Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              {!! Form::text('first_name', Input::old('first_name'), array('placeholder' => 'First Name','class' => 'form-control col-md-7 col-xs-12','required'=>'')) !!}
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_name">Last Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              {!! Form::text('last_name', Input::old('last_name'), array('placeholder' => 'Last Name','class' => 'form-control col-md-7 col-xs-12')) !!}
            </div>
          </div> 

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email*</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              {!! Form::email('email', Input::old('email'), array('placeholder' => 'Email','class' => 'form-control col-md-7 col-xs-12','required'=>'')) !!}
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Role*</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <?php
                $regularUserRole         = \App\Models\Role::regularUser()->first();
                $paidUserRole            = \App\Models\Role::paidUser()->first();
                $administratorRole       = \App\Models\Role::administrator()->first();
                $masterAdministratorRole = \App\Models\Role::masterAdministrator()->first();
                $roleOptions = ['' => '--- Select Role ---'];
                $roleOptions["{$regularUserRole->name}"] = $regularUserRole->display_name;
                $roleOptions["{$paidUserRole->name}"] = $paidUserRole->display_name;
                if (Auth::user()->isMasterAdministrator()) {
                  $roleOptions["{$administratorRole->name}"] = $administratorRole->display_name;
                  $roleOptions["{$masterAdministratorRole->name}"] = $masterAdministratorRole->display_name;
                }
              ?>
              {!! Form::select('role', $roleOptions, Input::old('role'), array('class' => 'form-control col-md-7 col-xs-12') ) !!}
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password*</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="password" name="password" class='form-control col-md-7 col-xs-12' required>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirmation">Password Confirmation*</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="password" name="password_confirmation" class='form-control col-md-7 col-xs-12' required>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button type="reset" class="btn btn-primary">Cancel</button>
              <input type="submit" class="btn btn-success" name="submit" value="Submit">
            </div>
          </div>
          {{ csrf_field() }}

        </form>
      </div>
    </div>
  </div>
</div>

<div id='dialog-form'></div>
</div>

@include('admin.partial.footerjs')
@include('admin.partial.footer')
