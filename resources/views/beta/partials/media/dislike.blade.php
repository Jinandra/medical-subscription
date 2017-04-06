{{--
  -- PARAMS:
  -- $media => a media object
  --}}

<a href="{{ url('/media/'.$media->id.'/dislike') }}" class="dislikeCount">
  <i class="fa fa-thumbs-down"></i>
  <span>
  <?php
    if (isset($media)) {
      if ($media->count_dislike == 0) {
        echo '0';
      } else {
        echo $media->count_dislike;
      }
    } else {
      echo '0';
    }
  ?>
  </span>
</a>
