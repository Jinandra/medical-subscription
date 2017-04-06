{{--
  -- Display list of collection/folder and its actions (send-to, copy-to, delete, bundle)
  -- PARAMS:
  -- $all => array of all collection (created, saved, category)
  -- $created => array of created collection (original_id IS NULL)
  -- $saved => array of saved collection (original_id IS NOT NULL)
  -- $categoried => array of collection from category (category_id IS NOT NULL)
  -- $pseudos => array of pseudo collection
  -- $currentPreview => selected collection to preview
  -- $pinneds => array of pinned collection
  --}}
@extends('beta.userLayout')

@section('content')
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
  <div class="headings nomarge">
    <h1>My Collection</h1>
    <p></p>
  </div>
  @include('beta.collection.actions')
  <div class="mt20 visible-xs"></div>
  <div class="content mt10">
    <div class="container">
      <div class="row row-md-gutter">
        <div class="col-md-3 collection-sidebar">
          @include('beta.collection.menu', [
            'all'     => $all,
            'created' => $created,
            'saved'   => $saved,
            'categoried' => $categoried,
            'pseudos' => $pseudos,
            'currentPreview' => isset($currentPreview) ? $currentPreview : null
          ])
        </div>
        <div class="col-md-9 collection-mainbar">

          <!-- START quick preview board -->
          <div class="collection-accordion-wrap collection-accordion-wrap-pinned active" id="quickBoard">
            <div class="collection-accordion-head top-section">
              <div class="accordion-toggle"></div>
              <div class="accordion-title txt-bold">Quick View <i class="fa fa-question-circle" data-toggle="popover"
                 data-content="Quick view of a collection"></i></div>
            </div>
            <div class="collection-accordion-content collection-grid-wrap collection-grid-wrap-1 row">
              <div id='preview'>
                @if (isset($currentPreview))
                  @include('beta.collection.content', [
                    'collection' => $currentPreview,
                    'sortByOptions' => [
                      array('value' => 'name',        'label' => 'Alphabetically'),
                      array('value' => 'updated_at',  'label' => 'Date modified')
                    ]
                  ])
                @else
                  @include('beta.collection.empty_preview')
                @endif
              </div>
            </div>
          </div>
          <!-- END quick preview board -->

          <!-- START pin board -->
          <div class="collection-accordion-wrap collection-accordion-wrap-pinned collection-pin-board active" id="pinBoard">
            <?php
              $gridView = Session::get('gridView');
              if (is_null($gridView) || $gridView == '') {
                $gridView = '2';  // default is 2
              }
            ?>
            <div class="collection-accordion-head top-section">
              <div class="accordion-toggle"></div>
              <div class="accordion-title txt-bold">Pin Board <i class="fa fa-question-circle" data-toggle="popover"
                  data-content="Pinned Collections"></i></div>
              <div class="tar pull-right view-grid-wrap">
                  <span class="txt-bold mr10">View by: </span>
                  <div class="view-grid-ico view-grid-ico-1 {{ $gridView === '1' ? 'active' : '' }}"></div>
                  <div class="view-grid-ico view-grid-ico-2 {{ $gridView === '2' ? 'active' : '' }}"></div>
                  <div class="view-grid-ico view-grid-ico-3 {{ $gridView === '3' ? 'active' : '' }}"></div>
              </div>
            </div>
            <div class="collection-accordion-content collection-grid-wrap collection-grid-wrap-{{ $gridView }} row mt10">
              @if (isset($pinneds) && count($pinneds) > 0)
                @foreach ($pinneds as $pinned)
                  @include('beta.collection.content', [
                    'collection' => $pinned,
                    'centerSelect' => true
                  ])
                @endforeach
              @else
                @include('beta.collection.empty_pin')
              @endif
            </div>
          </div>
          <!-- END pin board -->

        </div>
      </div>
    </div>
    <div id="overlay"></div>
  </div>
</div>


<style>
  .pin { opacity: .4; }
  .pin.pinned { opacity: 1; }
  .accordion-dropdown-ellipsis .disabled { opacity: .8; }
  .accordion-dropdown-elipsis .dropdown-menu { z-index: 1; }
  .collection-media-item a { color: #424242; }
  .collection-media-item a:hover { color: #009688; text-decoration: underline; }
  .select-all.active { font-weight: bold; text-decoration: underline; }
  .collection-grid-panel.panel-empty .collection-media-selector { display: none; }
  .menu-icon-pin a { color: #ddd; }
  .pinned .menu-icon-pin a { color: #17baa3; }
  .ul-limit-7 > ul > li { list-style-type: none; }
  .ul-limit-7 > ul > li > a { text-decoration: none; }
  .disabled {
    cursor: not-allowed;
    opacity: 0.3 !important;
    pointer-events: none !important;
}
</style>


@include('beta.collection.template_mine')
<script id="tmpl-empty-pin" type="text/x-handlebars-template">
  @include('beta.collection.empty_pin')
</script>
<script id="tmpl-empty-preview" type="text/x-handlebars-template">
  @include('beta.collection.empty_preview')
</script>
<script id="tmpl-select-all" type="text/x-handlebars-template">
  <div class="collection-media-selector @{{#if center}} tac @{{/if}} @{{#if hide}} hidden @{{/if}}">
    <span class="txt-bold">Select</span>
    <div class="txt-link select-all">All</div> |
    <div class="txt-link select-none">None</div>
  </div>
</script>

@include('beta.partials.modal')
<script>
  var COLLECTIONS_MAPPING = {}; // global collections mapping
  @include('beta.collection.mapping_to_js', ['collections' => $all->merge($categoried)]);
  @foreach ($pseudos as $pseudoCollection)
    COLLECTIONS_MAPPING['collection-{{ $pseudoCollection->name }}'] = {
      id: '{{ $pseudoCollection->name }}',
      name: '{{ $pseudoCollection->description }}',
      description: '{{ $pseudoCollection->description }}',
      original_id: null,
      category_id: null,
      media: [
        @foreach ($pseudoCollection->media() as $medium)
          { id: {{ $medium->id }}, title: '{{ $medium->title }}' },
        @endforeach
      ]
    };
  @endforeach

  window.COLLECTIONS_MAPPING = COLLECTIONS_MAPPING;
  window.CSRF_TOKEN = "{{ csrf_token() }}";
  window.COLLECTION_URL = "{{ url('collection') }}";
</script>
<script src="{{ URL::asset('resources/assets/js/collections.js') }}?v=5"></script>


@stop
