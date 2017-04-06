@extends('beta.userLayout')

@section('title')
  Media: {{ $media->title }}
@stop

@section('content')
  <div class="col-sm-9 col-md-10 col-xs-9 right-column">
    <div class="add-bundle-wrap-margin"></div>
    <div class="row">
      <div class="col-xs-12">
        @include('beta.partials.media.details', [
          'media' => $media,
          'collections' => isset($collections) ? $collections : [],
          'auth' => true
        ])
      </div>
    </div>
  </div>
@stop
