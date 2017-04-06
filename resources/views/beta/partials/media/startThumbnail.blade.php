{{--
  -- opening div tag for media thumbnail (you need to call @include('beta.partials.media.endThumbnail') in the end to close the div tag)
  -- PARAMS:
  -- $media => a media object
  -- $attributes => additional attributes
  -- $style => additional style
  --}}

<?php $attrs = isset($attributes) ? $attributes : ''; ?>
<?php $styl  = isset($style) ? $style : ''; ?>
<div class="video-wrap" style="background-image: url('{{ App\Models\Media::thumbnailUrl($media) }}');{!! $styl !!}" {!! $attrs !!}>
