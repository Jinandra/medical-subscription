@extends('beta.layout')

@section('title')
  Account Activation | Enfolink
@stop

@section('content')
<div class="container">
  <div class="row">
    <div class=" col-xs-12 col-sm-10 col-sm-offset-1 right-column unregisterd">
      <div class="content">
        <div class="headings nomarge">
          <h1>Account Activation</h1>
        </div>
        <div class="accounts" style="margin: 2em 0">
          @if (!$withToken)
            <p>
              Please check your email for verification request.
            </p>
          @else
            @if ($activated)
              <p>
                Your request has been verified, please wait up to 24 hours while we review your application.
              </p>
            @else
              <p style="color: red;">Your token is invalid or already expired.</p>
            @endif
          @endif
        </div>
        <div id="overlay"></div>
      </div>
    </div>
  </div>
</div>
@stop

