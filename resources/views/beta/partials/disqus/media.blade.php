{{--
  -- PARAMS:
  -- $media => media of disucssion
  --}}

@include('beta.partials.disqus', ['identifier' => '/media/'.$media->id])
