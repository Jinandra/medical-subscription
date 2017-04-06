@extends('beta.userLayout')

@section('title')
  Ucode: {{ $ucode }}
@stop

@section('content')
  <div class="col-sm-9 col-md-10 col-xs-9 right-column">
    @include('beta.partials.ucode.item', ['ucode' => $ucode, 'media' => $media, 'auth' => true])
  </div>
@stop
