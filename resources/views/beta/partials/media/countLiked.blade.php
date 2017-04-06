{{--
  -- how many percent a media been liked by all users (used in media list)
  -- PARAMS:
  -- $media => a media object
  --}}

<li class="tooltip" data-toggle="popover"  data-content="Likes">
  <i class="fa fa-thumbs-up"></i>{{ $media->likePercent }}%
</li>
