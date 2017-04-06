{{--
  -- Display a collection name (with controls) in a menu
  -- PARAMS:
  -- $collection => collection object
  -- $selected => collection is selected on quick preview (default false)
  --}}
<?php $isSelected = isset($selected) && $selected; ?>
<div class="collection-menu-item {{ $isSelected ? 'selected' : '' }} {{ $collection->is_pin ? 'pinned' : '' }} collection-media-wrap" data-collection-id="{{ $collection->id }}" data-original-id="{{ $collection->original_id }}" data-category-id="{{ $collection->category_id }}">
  <label class="checkbox-default">
    <input type="checkbox" class="folder" value="{{ $collection->id }}" data-name="{{ $collection->name }}">
    <span class="ico-checkbox"></span>
  </label>
  <span class="collection-name">{{ $collection->name }}</span>
  <div class="menu-icon-info">
    <img src="{{ config('app.assets_path').'/images/ico-info.png' }}" alt=""  data-toggle="popover" class="info" data-content="{{ $collection->description }}">
  </div>
  <div class="menu-icon-pin" data-toggle="popover" data-content="{{ $collection->is_pin ? 'unpin' : 'pin' }}"><a class="pinning" href="{{ url('collection/pin').'?'.($collection->is_pin ? 'unpin=' : 'pin=').$collection->id }}"><i class="fa fa-thumb-tack"></i></a></div>
  <div class="accordion-dropdown-elipsis">
    <div class="dropdown pull-right">
      <div class="more-action" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v"></i>
      </div>
      <ul class="dropdown-menu pull-right" aria-labelledby="Menu Collection">
        <li><a href="{{ url('folder/'.$collection->id) }}" target="_blank">Preview</a></li>
        @if ( $collection->isOriginal() )
          <li><a href="#" class="btnEditFolder" data-id="{{ $collection->id }}">Edit</a></li>
        @else
          <li class="disabled"><a href="#">Edit</a></li>
        @endif
        <li><a href="#" class="btnDeleteFolder" data-id="{{ $collection->id }}">Delete</a></li>
      </ul>
    </div>
  </div>
</div>
