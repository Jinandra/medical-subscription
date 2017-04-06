@extends('prototype.userLayout')

@section('title')
  User Status
@stop

@section('content')
    <br/>
    <div class="container-fluid">
        <br/><br/>
        <h1 align="center">
        @if(Auth::user()->user_status == 'active')
            Your account is already activated.
        @endif

        @if(Auth::user()->user_status == 'pending')
            Your account is not activated yet.
        @endif

        @if(Auth::user()->user_status == 'blocked')
            Your account is Blocked.
        @endif        
        </h1>
    </div>
@stop
