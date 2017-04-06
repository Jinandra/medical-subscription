@extends('beta.userLayout')

@section('title')
	Folder: {{ $folder_name }} @if($media <> "")({{ count($media) }} Media) @endif
@stop


@section('content')
  <div class="col-sm-9 col-md-10 col-xs-9 right-column">
    <div class="content">
      <div class="headings nomarge">
        <h1 class="dib">Folder: {{ $folder_name }} ({{ count($media)}} Media)</h1>
        <p></p>
      </div>
      <div class="container overflow-x-hidden">
          @if ($media != '' && count($media) > 0)
            @include('beta.partials.media.carousel', [
              'media' => $media,
              'fnOnClick' => 'showMedia'
            ])
          @else
            <h4>Media Not Found.</h4>
          @endif
      </div>
    </div>
    @if($media != "" && count($media) > 0)
      <div class="row">
        <div class="col-xs-12">
          <div class="content" id="mediaAjax">
            @include('beta.partials.media.details', [
              'media' => $media[0],
              'collections' => $collections,
              'auth' => true
            ])
          </div>
        </div>
      </div>
    @endif
@stop

@section('additionalScript')
<script type="text/javascript">
  function showMedia(id) {
    $.ajax({
      url: "<?php echo url('media/ajax')?>/"+ id,
      success: function(response) { //alert(response);
        $("#mediaAjax").html(response);
      }
    });
    return false;
  }
</script>
@stop
