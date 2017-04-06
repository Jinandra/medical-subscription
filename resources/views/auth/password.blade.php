@extends('beta.layout')

@section('title')
  Forgot Password | Enfolink
@stop

@section('content')

  <div class="container mt40">
    <div class="row clearfix mb20">
      <div class="col-sm-4 col-sm-offset-4">
        <form class="panel-box p30" method="POST" action="{{ url('/password/email') }}">
          {{ csrf_field() }}
          <div class="text-center">
            <h3 class="fz30 mb20">Reset Password</h3>
            @if (session('status'))
              <div class="alert alert-success">{{ session('status') }}</div>
            @endif
          </div>
          <div class="form">
            <div class="mb10">
              <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email address" class="mb0" />
              @if ($errors->has('email'))
                <small class="db txt-red fz12 mt10 mb20">{{ $errors->first('email') }}</small>
              @endif
            </div>
            <div class="text-center">
              <input type="submit" class="el-btn el-btn-lg el-btn-padding-md el-btn-green" value="Send Password Reset Link" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

@stop
