{{--
  -- short content after thumbnail
  -- PARAMS:
  -- $media => a media object
  -- $openNewTab => when clicked, will open in new tab/window ? (default=false)
  --}}
<p>
  <a href="{{ url('/media/'.$media->id_media) }}" {{ isset($openNewTab) && $openNewTab ? 'target="_blank"' : '' }} class="media-title">{{ $media->title }}</a>
</p>
<div class="txt-teal dib">{{ $media->screen_name }}</div> - {{ formatMediaType($media->type) }}
<ul class="listing clearfix">
  @include('beta.partials.media.countLiked', ['media' => $media])
  {{--
    --<li class="tooltip">
    --  <i class="fa fa-check-square"></i>99% <span class="tooltiptext tooltip-bottom tooltip-small">Verification</span>
    --</li>
    --}}
  @include('beta.partials.media.countCollected', ['media' => $media])
  <li class="calendar tooltip pull-right"><?php /*?><i class="fa fa-calendar"></i><?php */?>{{ App\Util::ago($media->created_at, false)}}</li>
</ul>
