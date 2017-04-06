@extends('beta.userLayout')

@section('title')
Account | Enfolink
@stop

@section('content')

<div class="col-sm-9 col-md-10 col-xs-9 right-column">
    <div class="content">
        <div class="headings nomarge">
            <h1>Account</h1>
            <p></p>
        </div>
        <div class="action-row clearfix">
          <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#setting" aria-controls="setting" role="tab" data-toggle="tab">Setting</a></li>
            <li role="presentation"><a href="#invite" aria-controls="invite" role="tab" data-toggle="tab">Invite Colleagues</a></li>
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="setting">@include('beta.user.profile')</div>
            <div role="tabpanel" class="tab-pane fade" id="invite">@include('beta.user.invite')</div>
          </div>
        </div>
        <div id="overlay"></div>
    </div>
</div>



@stop
