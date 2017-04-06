{{--
  -- Display collections name on left menu
  -- PARAMS:
  -- $all => array of all collection (created, saved, category)
  -- $created => array of created collection (original_id IS NULL)
  -- $saved => array of saved collection (original_id IS NOT NULL)
  -- $categoried => array of collection from category (category_id IS NOT NULL)
  -- $pseudos => array of pseudo collection object
  -- $currentPreview => selected collection on current preview
  --}}

<?php
$preview = isset($currentPreview) ? $currentPreview : null;
if ( !function_exists('is_collection_selected') ) {
  function is_collection_selected ($collectionId, $selectedCollection) {
    if ( is_null($selectedCollection) || !isset($selectedCollection->id) ) { return false; }
    return $collectionId == $selectedCollection->id;
  }
}
if ( !function_exists('create_pseudo_menu_item') ) {
  function create_pseudo_menu_item($collection, $selectedCollection) {
    echo '<div class="collection-menu-item'.(is_collection_selected($collection->id, $selectedCollection) ? ' selected ': '').($collection->is_pin ? ' pinned ' : '').'" data-collection-id="'.$collection->name.'">';
    echo '<span class="collection-name">'.$collection->description.'</span>';
    echo '<div class="menu-icon-pin" data-toggle="popover" data-content="'.($collection->is_pin ? 'unpin' : 'pin').'"><a class="pinning" href="'.(url('collection/pin').'?'.($collection->is_pin ? 'unpin=' : 'pin=').$collection->name).'"><i class="fa fa-thumb-tack"></i></a></div>';
    echo '</div>';
  }
}
?>
<div id="collection-sidebar">
  <div class="static-folder">
    @foreach ($pseudos as $collection)
      {{ create_pseudo_menu_item($collection, $preview) }}
    @endforeach
  </div>
  <div class="collection-menu-header">
    <i class="fa fa-folder"></i> My Folder
  </div>

  <ul class="nav nav-tabs nav-tabs-small" role="tablist">
    <li role="presentation" class="active"><a href="#folders-all" aria-controls="folders-all" role="tab" data-toggle="tab">All</a></li>
    <li role="presentation"><a href="#folders-created" aria-controls="folders-created" role="tab" data-toggle="tab">Created</a></li>
    <li role="presentation"><a href="#folders-saved" aria-controls="folders-saved" role="tab" data-toggle="tab">Saved</a></li>
    <li role="presentation"><a href="#folders-categoried" aria-controls="folders-categoried" role="tab" data-toggle="tab">Categories</a></li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="folders-all">
      <div class="collection-folder-sidebar all-folder">
        @foreach ($all as $collection)
          @include('beta.collection.mine', [
            'collection' => $collection,
            'selected' => is_collection_selected($collection->id, $preview)
          ])
        @endforeach
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="folders-created">
      <div class="collection-folder-sidebar created-folder">
        @foreach ($created as $collection)
          @include('beta.collection.mine', [
            'collection' => $collection,
            'selected' => is_collection_selected($collection->id, $preview)
          ])
        @endforeach
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="folders-saved">
      <div class="collection-folder-sidebar saved-folder">
        @foreach ($saved as $collection)
          @include('beta.collection.mine', [
            'collection' => $collection,
            'selected' => is_collection_selected($collection->id, $preview)
          ])
        @endforeach
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="folders-categoried">
      <div class="collection-folder-sidebar categoried-folder">
        @foreach ($categoried as $collection)
          @include('beta.collection.mine', [
            'collection' => $collection,
            'selected' => is_collection_selected($collection->id, $preview)
          ])
        @endforeach
      </div>
    </div>
  </div>
</div>
