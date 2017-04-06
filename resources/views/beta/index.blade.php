@extends('beta.layout')

@section('title')
  Home Page | Enfolink
@stop

@section('content')
<?php if (isset($_GET['ucodeNotFound'])) { ?>
  <!-- Modal confrmation -->
  <div id="modalNotification" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sorry</h4>
        </div>
        <div class="modal-body">
          <p>{{$_GET['ucodeNotFound']}} </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<div class="container">
  <div class="row">
    <div class=" col-xs-12 col-sm-10 col-sm-offset-1 right-column unregisterd">
      <div class="content">
        <div class="headings nomarge">
          <h1 class="dib">Popular</h1>
          @include('beta.partials.media.filterPopularity')
        </div>
        <div class="container" id="ucodeAjax">
          @if (count($medias) === 0)
            <p>No media the moment.</p>
          @else
            <?php $count = 0; ?>
            @foreach($medias as $row)
              @if(fmod($count,5) ==0 && $count !=0)
                </div>
              @endif
              @if($count == 0 || fmod($count,5) == 0)
                <div class="row" >
              @endif
              <div class="col-sm-3 col-md-3 col-xs-12 column-box">
                @include('beta.partials.media.startThumbnail', ['media' => $row])
                  <ul class="view-listing">
                    <li>
                      <div class="tooltip">
                        <i class="fa fa-info-circle"></i>
                        <div class="tooltiptext tooltip-bottom @if(fmod($count+1, 5) == 0) pos-right @endif">
                          <div class="limit-text">
                            {{ description($row->description) }}
                          </p>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <a href={{ url('/media/'.$row->id_media) }} class="video-wrap-link"></a>
                @include('beta.partials.media.endThumbnail')
                @include('beta.partials.media.thumbnailContent', ['media' => $row])
              </div>
              <?php $count++; ?>
            @endforeach
          @endif
        </div>
      </div>
      <?php /*?><a href="" class="more">Watch more</a><?php */?>
    </div>
    <div id="overlay"></div>
  </div>
</div>
@stop


@section('footer')
<script type="text/javascript">

  $(document).ready(function () {
    $('.limit-text').limitText();

    @if (isset($_GET['ucodeNotFound']))
      $(document).ready(function () {
        $('#modalNotification').modal('show');
      });
    @endif
  });
</script>
@stop
