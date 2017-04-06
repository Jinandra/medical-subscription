{{--
  -- PARAMS:
  -- $media => a media object
  --}}

<a href="{{ url('/media/'.$media->id.'/like') }}" class="likeCount">
  <i class="fa fa-thumbs-up"></i>
  <span>
  <?php
    if (isset($media)) {
      if ($media->count_like == 0) {
        echo '0';
      } else {
        echo $media->count_like;
      }
    } else {
      echo '0';
    }
  ?>
  </span>
</a>
