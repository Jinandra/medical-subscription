@extends('beta.layout')


@section('title')
  Login | Enfolink
@stop

@section('content')
<div class="container mt40">
  <div class="row clearfix mb20">
    <div class="col-sm-6 col-sm-offset-3">

      <h3>Login</h3>
      <form method="post" action="{{ url('user/login') }}">
        <div>
          @if (count($errors) > 0 || Session::has('error_auth'))
            <div class="alert alert-danger">
              <p>Invalid username or password !</p>
            </div>
          @endif
          <div class="mb10">
            <input type="text" id="screenName" name="screen_name" class="form-control mb0" placeholder="Enter username" required />
            <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
          </div>
          <div class="mb10">
            <input type="password" id="password" name="password" class="form-control mb0" placeholder="Enter password" required />
            <!--<small class="db txt-red fz12 mt10 mb20">Password minimum lenght is 5 character</small>-->
          </div>
          <div class="mb10 mt10">
            <label class="checkbox-default mr5">
              <input id="remember" type="checkbox" name="">
              <span class="ico-checkbox"></span>
            </label>
            <label for="remember" class="mt5">Remember me</label>
          </div>
          <div class="text-center">
            {{ csrf_field() }}
            <input type="submit" class="el-btn el-btn-lg el-btn-green el-btn-padding-lg full" value="Login" />
          </div>
          <div class="mt20 text-center mb10">
            <a id="btn-reset-pass" href="{{ url('password/reset') }}">
              Forgot password?
            </a>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
@stop
