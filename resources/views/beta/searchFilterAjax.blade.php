@if(array_key_exists('folders', $data) && count($data['folders']) > 0)
    <div id="search_folders" class="row">
    <?php $count = 0; ?>
    @foreach($data['folders'] as $folder)
      @include('beta.partials.search.folder', [
        'rowBreak' => fmod($count+1, 4) == 0
      ])
      <?php $count++; ?>
    @endforeach
    </div>
@endif
@if(array_key_exists('media', $data) && count($data['media']) > 0)
    <div id="search_media" class="row">
    <?php $count = 0; ?>
    @foreach($data['media'] as $media)
      @include('beta.partials.search.media', [
        'rowBreak' => fmod($count+1, 4) == 0
      ])
      <?php $count++; ?>
    @endforeach
    </div>
@endif
