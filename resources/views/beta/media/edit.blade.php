@extends('beta.userLayout')

@section('content')
<?php
  /*if(old('create_type')) {
    $createType = old('create_type');
  } else {*/
    if($media['file_name']) {
      $createType = App\Models\Media::CREATE_TYPE_UPLOAD;
    } else {
      $createType = App\Models\Media::CREATE_TYPE_ONLINE;
    }
  //}
?>
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
  <div class="content">
    <div class="headings nomarge headings-no-border">
      <h1>Edit Media</h1>
      <p></p>
      <ul class="mt20 nav nav-tabs">
        @foreach(App\Models\Media::getAllCreateTypes() as $type=>$text)
          @if($type == $createType)
            <li role="presentation" class="media-type {{ ($type == $createType)?'active':'' }}">
              <a data-toggle="tab" class="media-type-link" href="#{{ $type }}">
              <!--<a href="{{url('contribute/addForm/'.$type)}}">-->
                <i class="fa {{ App\Models\Media::getShowTypeIconClass($type) }} mr5"></i>{{ $text }}
              </a>
            </li>
          @endif
        @endforeach
      </ul>
    </div>

    @include('beta.partials.modal')
    @include('beta.media.errors')

    @include('beta.media.form', [
      'formAction' => url('/contribute/'.$media['id'].'/update'),
      'media' => $media
    ])

  </div>
</div>
@stop
