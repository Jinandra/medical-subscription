@extends('beta.userLayout')

@section('title')
  Learn | Enfolink
@stop

@section('content')
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
  <div class="content">
    <div class="headings nomarge">
      <h1 class="dib">Learn</h1>
      <p></p>
    </div>
    <div class="container container-listing-vertical">
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
                <div class="tooltip tooltip-toggle">
                  <i class="fa fa-info-circle"></i>
                  <span class="tooltiptext tooltip-bottom @if($count == 5) pos-right @endif">
                    <div class="limit-text">{{ description($row->description) }}</div>
                  </span>
                </div>
              </li>
              <li>
                <a  class="bundle @if($row->id_media_bundle_cart) active @endif" href="{{ url('bundle/'.$row->id_media.'/add') }}">
                  <img src="{{ config('app.assets_path') }}/images/bundle.png" alt="add to bundle" title="add to bundle" />
                </a>
              </li>
              <li>
                <a id="hrefBtnBookmark" href="{{ url('user/'.$row->id.'/fav') }}" class="set-bookmark set-bookmark-listing @if($row->fav) active @endif"><i class="fa fa-bookmark" ></i></a>
              </li>
            </ul>
            <a href={{ url('/media/'.$row->id_media) }} class="video-wrap-link"></a>
          @include('beta.partials.media.endThumbnail')
          @include('beta.partials.media.thumbnailContent', ['media' => $row, 'openNewTab' => true])
        </div>
        <?php $count++; ?>
      @endforeach
    </div>
  </div>
</div>
<script>
  $(document).ready(function () {
    $('.limit-text').limitText();
    $('.view-listing .bundle').bundleButton();
    $('.set-bookmark').bookmarkButton();
  });
</script>
@stop
