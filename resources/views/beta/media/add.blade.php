@extends('beta.userLayout')

@section('content')
<?php
  if(old('create_type')) {
    $createType = old('create_type');
  }
?>
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
  <div class="content">
    <div class="headings nomarge headings-no-border">
      <h1>Add Media</h1>
      <p></p>
      <ul class="mt20 nav nav-tabs">
        <?php $count = 0; ?>
        @foreach(App\Models\Media::getAllCreateTypes() as $type=>$text)
        <li role="presentation" class="media-type {{ ((old('create_type') && old('create_type') == $type) || (!old('create_type') && $count == 0))?'active':'' }}">
          <a data-toggle="tab" class="media-type-link" href="#{{ $type }}">
          <!--<a href="{{url('contribute/addForm/'.$type)}}">-->
            <i class="fa {{ App\Models\Media::getShowTypeIconClass($type) }} mr5"></i>{{ $text }}
          </a>
        </li>
        <?php $count ++; ?>
        @endforeach
      </ul>
    </div>
    
    @include('beta.partials.modal')
    @include('beta.media.errors')

    @include('beta.media.form', [
      'formAction' => url('/contribute/add')
    ])

  </div>
</div>

@stop
