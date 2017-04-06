{{--
  -- Listing media used in home page in user layout
  -- PARAMS:
  -- $medias => array of media object
  --}}

@if(isset($medias) && count($medias) > 0)
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
              <span class="tooltiptext tooltip-bottom @if(fmod($count+1, 5) == 0) pos-right @endif">
                <div class="limit-text">{{ description($row->description) }}</div>
              </span>
            </div>
          </li>
          <li>
            <a data-toggle="popover" data-content="{{ $row->id_media_bundle_cart ? 'Remove from bundle' : 'Add to bundle' }}" class="bundle @if($row->id_media_bundle_cart) active @endif" href="{{ url('bundle/'.$row->id_media.'/add') }}"><img src="{{ config('app.assets_path') }}/images/bundle.png" alt="Bundle" title="Bundle" /></a>
          </li>
          <li>
            <a data-toggle="popover" data-content="{{ $row->fav ? 'Remove from bookmark' : 'Add to bookmark' }}" href="{{ url('user/'.$row->id.'/fav') }}" class="set-bookmark set-bookmark-listing @if($row->fav) active @endif"><i class="fa fa-bookmark" ></i></a>
          </li>
        </ul>
        <a href={{ url('/media/'.$row->id_media) }} class="video-wrap-link"></a>
      @include('beta.partials.media.endThumbnail')
      @include('beta.partials.media.thumbnailContent', ['media' => $row])
    </div>
    <?php $count++; ?>
  @endforeach
  </div> {{-- last close .row --}}
  <script>
    $(document).ready(function () {
      initTooltip();  // force close opened popover that losing DOM
      $('.limit-text').limitText();
      $('.view-listing .bundle').bundleButton();
      $('.set-bookmark').bookmarkButton();
    });
  </script>
@else
  <p>No media at the moment</p>
@endif
<script>
  $(document).ready(function () {
    hidePopOver();
  });
</script>
