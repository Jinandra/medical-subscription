@extends('beta.layout')

@section('title')
  Reset Password | Enfolink
@stop

@section('content')
  <div class="container mt40">
    <div class="row clearfix mb20">
      <div class="col-sm-4 col-sm-offset-4">
        <form class="panel-box p30" method="POST" action="{{ url('/password/reset') }}">
          {{ csrf_field() }}
          <input type="hidden" name="token" value="{{ $token }}">
          <div class="text-center">
            <h3 class="fz30 mb20">Reset Password</h3>
          </div>
          <div class="form">
            <div class="mb10">
              <input type="email" name="email" value="{{ $email or old('email') }}" class="form-control" placeholder="Email address" class="mb0" autofocus />
              @if ($errors->has('email'))
                <small class="db txt-red fz12 mt10 mb20">{{ $errors->first('email') }}</small>
              @endif
            </div>
            <div class="mb10">
              <input type="password" name="password" class="form-control" placeholder="Password" class="mb0" />
              @if ($errors->has('password'))
                <small class="db txt-red fz12 mt10 mb20">{{ $errors->first('password') }}</small>
              @endif
            </div>
            <div class="mb20">
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" class="mb0" />
              @if ($errors->has('password_confirmation'))
                <small class="db txt-red fz12 mt10 mb20">{{ $errors->first('password_confirmation') }}</small>
              @endif
            </div>
            <div class="text-center">
              <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="Reset Password" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!--
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Reset Password</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
              {{ csrf_field() }}
              <input type="hidden" name="token" value="{{ $token }}">
              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                <div class="col-md-6">
                  <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" autofocus>
                  @if ($errors->has('email'))
                    <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Password</label>
                <div class="col-md-6">
                  <input id="password" type="password" class="form-control" name="password">
                  @if ($errors->has('password'))
                    <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-6">
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                  @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                      <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-primary">
                    Reset Password
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  -->
@endsection
