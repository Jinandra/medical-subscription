@extends('beta.layout')


@section('title')
  Ucode: {{ $ucode }}
@stop


@section('content')
  <div class="container">
    <div class="row">
      <div class=" col-xs-12 col-sm-10 col-sm-offset-1 right-column unregisterd">
        @include('beta.partials.ucode.item', ['ucode' => $ucode, 'media' => $media, 'auth' => false])
      </div>
    </div>
  </div>
@stop
