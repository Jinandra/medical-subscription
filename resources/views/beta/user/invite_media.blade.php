{{--
  -- Show media list for invite form
  -- PARAMS:
  -- $media    => collection of media object
  -- $prefixId => prefix name for id
  --}}
@include('beta.user.invite_collection', [
  'collection' => $media,
  'prefixId' => $prefixId,
  'emptyMsg' => 'No media.',
  'listTemplate' => 'beta.user.invite_media_list'
])
