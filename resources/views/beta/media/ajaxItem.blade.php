{{--
  -- Params:
  -- $media => array of media of current ucode
  -- $auth  => true if user authenticated, default to false
  --}}

@include('beta.partials.media.details', ['media' => $media[0], 'auth' => isset($auth) ? $auth : false])
