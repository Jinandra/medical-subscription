@extends((isset($auth) && $auth)? 'beta.userLayout' : 'beta.layout')

@section('title')
  Media: {{ $media->title }}
@stop

@section('content')
  <div class="container">
    <div class="row">
      <div class=" col-xs-12 col-sm-10 col-sm-offset-1 right-column unregisterd">
        <div class="add-bundle-wrap-margin"></div>
        <div class="row">
          <div class="col-xs-12">
            @include('beta.partials.media.details', [
              'media' => $media,
              'auth' => false
            ])
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
