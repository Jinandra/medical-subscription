{{--
  -- Show folder list for invite form
  -- PARAMS:
  -- $folder   => collection of folder object
  -- $prefixId => prefix name for id
  --}}
@include('beta.user.invite_collection', [
  'collection' => $folder,
  'prefixId' => $prefixId,
  'emptyMsg' => 'No folder.',
  'listTemplate' => 'beta.user.invite_folder_list'
])
