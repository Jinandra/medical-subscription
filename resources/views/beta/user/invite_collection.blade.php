{{--
  -- Show collection list (media/folder) for invite form
  -- PARAMS:
  -- $collection   => collection object
  -- $prefixId     => prefix name for id
  -- $emptyMsg     => empty message when collection is empty
  -- $listTemlate  => template name to render the list
  --}}
@if (count($collection) > 0)
  <div class="row">
    @if (count($collection) <= 10)
      <div class="col-xs-12">
        @include($listTemplate, ['collection' => $collection, 'prefixId' => $prefixId])
      </div>
    @elseif (count($collection) <= 20)
      <?php $limitString = 80; ?>
      <div class="col-xs-6">
        @include($listTemplate, ['collection' => $collection->slice(0, 10), 'prefixId' => $prefixId, 'limitString' => $limitString])
      </div>
      <div class="col-xs-6">
        @include($listTemplate, ['collection' => $collection->slice(10), 'prefixId' => $prefixId, 'limitString' => $limitString])
      </div>
    @else
      <?php
        $perRow = round(count($collection) / 3)+1;
        $limitString = 50;
      ?>
      <div class="col-xs-4">
        @include($listTemplate, ['collection' => $collection->slice(0, $perRow), 'prefixId' => $prefixId, 'limitString' => $limitString])
      </div>
      <div class="col-xs-4">
        @include($listTemplate, ['collection' => $collection->slice($perRow, $perRow), 'prefixId' => $prefixId, 'limitString' => $limitString])
      </div>
      <div class="col-xs-4">
        @include($listTemplate, ['collection' => $collection->slice($perRow*2), 'prefixId' => $prefixId, 'limitString' => $limitString])
      </div>
    @endif
  </div>
@else
  <p>{{ $emptyMsg }}</p>
@endif
