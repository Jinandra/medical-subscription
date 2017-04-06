<?php $count = 0; ?>
@if (isset($medias) && count($medias) > 0)
  @foreach($medias as $row)
    @if(fmod($count,5) ==0)
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
                <div class="limit-text">
                  {{ description($row->description) }}
                </div>
              </span>
            </div>
          </li>
        </ul>
        <a href={{ url('/media/'.$row->id_media) }} class="video-wrap-link"></a>
      @include('beta.partials.media.endThumbnail')
      @include('beta.partials.media.thumbnailContent', ['media' => $row])
    </div>
    <?php $count++; ?>
  @endforeach
@else
  <p>No media at the moment</p>
@endif
</div>
<script type="text/javascript">
  $(document).ready(function () {
    initTooltip();
    $('.limit-text').limitText();
  });
</script>
