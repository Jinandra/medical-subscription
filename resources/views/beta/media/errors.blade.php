{{--
  -- Display error messages of a form
  -- PARAMS:
  -- $defaultError => default error message
  --}}

@if (Session::get('status') !== null || count($errors) > 0)
  <div style="margin: 2em 0;">
    @if (Session::get('status')=='fail')
      <div class="alert alert-danger">
        <strong>Error!</strong> {{ $defaultError || 'please try again.' }}
      </div>
    @elseif (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            @if ($error !== 'validation.unique_media')
              <li>{{ $error }}</li>
            @endif
          @endforeach
        </ul>
      </div>
    @endif
  </div>
@endif

@if ( !is_null(Session::get('duplicate_media')) )
<?php $medium = Session::get('duplicate_media'); ?>
<script>
$(function () {
  var COLLECTIONS_MAPPING = {};
  @include('beta.collection.mapping_to_js', ['collections' => $collections, 'showMedia' => false]);

  var duplicate = {
    url: "{{ url('/media/'.$medium->id_media) }}",
    title: "{{ $medium->title }}",
    type: "{{ ucfirst($medium->type === 'text' ? 'document' : $medium->type) }}",
    likePercent: "{{ $medium->likePercent }}",
    countCollected: "{{ $medium->count_cd }}",
    createdAt: "{{ App\Util::ago($medium->created_at, false)}}"
  };
  <?php
    $startThumbnail = View::make('beta.partials.media.startThumbnail', ['media' => $medium]);
    $endThumbnail = View::make('beta.partials.media.endThumbnail');
    echo "duplicate.thumbnail = '".str_replace("\n", '', str_replace("'", "\'", $startThumbnail.$endThumbnail))."';";
  ?>
  var selectedTargets = [];
  @if ( !is_null(old('collections')) )
    selectedTargets = [
      @foreach (old('collections') as $c)
        {{ $c }},
      @endforeach
    ];
  @endif
  var targets = sortByStringField(
    Object.keys(COLLECTIONS_MAPPING).map(function (collectionId) {
      return $.extend(true, {}, COLLECTIONS_MAPPING[collectionId], {
        selected: selectedTargets.indexOf(COLLECTIONS_MAPPING[collectionId].id) !== -1
      });
    }), 'name'
  );

  var data = {
    mediumId: {{ $medium->id }},
    screenName: "{{ $medium->screen_name }}",
    duplicate: duplicate,
    targets: targets,
    onCreatedFolder: onCreatedFolder
  };
  ENFOLINK.modal.showDuplicateMedia(data);
});
</script>
<style>
  #enfolink-modal .tooltip { position: static; }
</style>
@endif
