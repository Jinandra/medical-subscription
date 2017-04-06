{{--
  -- Display a collection with its content (media list)
  -- PARAMS:
  -- $collection => collection object
  -- $centerSelect => true if placing select (all|none) at center (default false)
  --}}

<?php
if ( !function_exists('is_pseudo_collection') ) {
  function is_pseudo_collection ($collection) {
    return get_class($collection) === 'App\Models\PseudoCollection';
  }
}
if ( !function_exists('is_noop_collection') ) {
  // collection that we can't delete the media directly because it's collected automatically
  function is_noop_collection ($collection) {
    if (is_pseudo_collection($collection)) {
      if ($collection->name == \App\Models\PseudoCollection::ID_HISTORY || $collection->name == \App\Models\PseudoCollection::ID_CONTRIBUTED || $collection->name === \App\Models\PseudoCollection::ID_BASIC) {
        return true;
      } else {
        return false;
      }
    }
    if ( !$collection->isOriginal() ) { return true; }
    return false;
  }
}
if ( !function_exists('get_collection_id') ) {
  function get_collection_id ($collection) {
    return is_pseudo_collection($collection) ? $collection->name : $collection->id;
  }
}
?>

<?php
  $media   = is_pseudo_collection($collection) ? $collection->media() : $collection->media;
  $isEmpty = count($media) === 0;
?>
<div class="collection-grid-panel {{ $isEmpty ? 'panel-empty' : '' }}" data-collection-id="{{ get_collection_id($collection) }}">
  <div class="collection-accordion-head top-section">
    <div class="accordion-title">
      <div class="txt-bold">{{ is_pseudo_collection($collection) ? $collection->description : $collection->name }}</div>
      <div class="fz12">
        By 
        <?php
          if (is_pseudo_collection($collection)) {
            echo $collection->name === \App\Models\PseudoCollection::ID_BASIC ? "Enfolink" : Auth::user()->screen_name;
          } else if ($collection->isCategory()) {
            echo "Enfolink";
          } else {
            echo $collection->hasParent() ? $collection->parent->user->screen_name : $collection->user->screen_name;
          }
        ?>
      </div>
    </div>
    <div class="tar pull-right">
      <div class="accordion-icon-list">
        <div class="icon-info dib vam">
          <img src="{{ config('app.assets_path').'/images/ico-info.png' }}" alt=""  data-container="body" data-toggle="popover" class="info" data-content="{{ $collection->description }}">
        </div>
        <div class="icon-close dib pin {{ $collection->is_pin ? 'pinned' : '' }}" data-toggle="popover" data-content="{{ $collection->is_pin ? 'unpin' : 'pin' }}"><a class="pinning" href="{{ url('collection/pin').'?'.($collection->is_pin ? 'unpin=' : 'pin=').get_collection_id($collection) }}"><i class="fa fa-thumb-tack"></i></a>
        </div>
      </div>
      <div class="accordion-dropdown-elipsis">
        <div class="dropdown pull-right">
          <div class="more-action" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
          </div>
          <ul class="dropdown-menu pull-right" aria-labelledby="Menu Collection">
            @if ( !is_pseudo_collection($collection) )
              <li><a href="{{ url('folder/'.$collection->id) }}" target="_blank">Preview</a></li>
              @if ( $collection->isOriginal() )
                <li><a href="#" class="btnEditFolder" data-id="{{ $collection->id }}">Edit</a></li>
              @else
                <li class="disabled"><a href="#">Edit</a></li>
              @endif
              <li><a href="#" class="btnDeleteFolder" data-id="{{ $collection->id }}">Delete</a></li>
            @else
              <li class="disabled"><a href="#">Preview</a></li>
              <li class="disabled"><a href="#">Edit</a></li>
              <li class="disabled"><a href="#">Delete</a></li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="collection-media-selector {{ isset($centerSelect) && $centerSelect ? 'tac' : '' }}">
    <span class="txt-bold">Select</span>
    <div class="txt-link select-all">All</div> |
    <div class="txt-link select-none">None</div>
  </div>
  <div
    class="collection-media-wrap"
    data-original-id="{{ $collection->original_id }}"
    data-category-id="{{ $collection->category_id }}"
    data-id="{{ is_pseudo_collection($collection) ? $collection->name : $collection->id }}"
  >
    <div class="collection-media-item table-view empty-media" {!! $isEmpty ? 'style="display: block;"' : '' !!}>
      <div class="tac">
        <div>No media in the folder</div>
      </div>
    </div>
    @foreach ($media as $medium)
      @include('beta.collection.item', [
        'medium'     => $medium,
        'collection' => $collection,
        'can_delete' => !is_noop_collection($collection)
      ])
    @endforeach
  </div>
</div>
