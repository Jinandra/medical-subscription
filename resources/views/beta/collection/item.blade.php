{{--
  -- Display a media of a collection (1 row)
  -- PARAMS:
  -- $medium => media object
  -- $collection => media's collection object
  -- $can_delete => true if media is deletable (default true)
  --}}

<?php
if (!function_exists('is_pseudo_collection')) {
  function is_pseudo_collection ($collection) {
    return get_class($collection) === 'App\Models\PseudoCollection';
  }
}
?>

<div class="collection-media-item table-view">
  <label class="checkbox-default col-view">
    <input type="checkbox" name="" class="medium" value="{{ (is_pseudo_collection($collection) ? $collection->name : $collection->id).'-'.$medium->id }}" data-title="{{ $medium->title }}">
    <span class="ico-checkbox"></span>
  </label>
  <div class="col-view">
    <div class="collection-media-title"><a href="{{ url('media/'.$medium->id) }}" target="_blank">{{ $medium->title }}</a></div>
    <!--<div>{{ $medium->title }}</div>-->
  </div>
  <div class="col-view collection-icon-list">
    <?php
      $classType  = '';
      $mediumType = '';
        switch ($medium->type) {
        case 'website':
          $classType  = 'txt-blue';
          $mediumType = 'web';
          break;
        case 'video':
          $classType  = 'txt-red';
          $mediumType = 'vid';
          break;
        case 'image':
          $classType  = 'txt-orage';
          $mediumType = 'img';
          break;
        case 'text':
          $classType  = 'txt-green';
          $mediumType = 'doc';
          break;
        }
    ?>
    <small
      class="{{ $classType }} txt-type txt-bold"
      data-toggle="popover"
      data-content="{{ $medium->type }}"
    >
      {{ $mediumType }}
    </small>
    <div class="icon-info dib vam">
      <img src="{{ config('app.assets_path').'/images/ico-info.png' }}" alt=""  data-toggle="popover" class="info" data-content="{{ $medium->description }}">
    </div>
  </div>
  <div class="col-view accordion-dropdown-elipsis">
    <div class="dropdown pull-right">
      <div class="more-action" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v"></i>
      </div>
      <ul class="dropdown-menu pull-right" aria-labelledby="Menu Media">
        <li {{ !isset($can_delete) || $can_delete ? '' : 'class=disabled' }}>
          <a href="#" class="btnDeleteMedium" data-title="{{ $medium->title }}" data-id="{{ (is_pseudo_collection($collection) ? $collection->name : $collection->id).'-'.$medium->id }}">Delete</a>
        </li>
      </ul>
    </div>
  </div>
</div>
