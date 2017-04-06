{{--
  -- how many times a media been collected by all users (used in media list)
  -- PARAMS:
  -- $media => a media object
  --}}

<li class="tooltip" data-toggle="popover" data-content="Times Collected">
  <i class="fa fa-list-ul"></i>{{ $media->count_cd }}
</li>
