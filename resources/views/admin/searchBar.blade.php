{{--
  -- PARAMS:
  -- $inputName => name of input (default: s)
  -- $action => url for action (default: current url)
  -- $search => current search inputted (default: Input::get($inputName))
  -- $placeholder  => text for search placeholder (default "Search for...")
  --}}

<?php
  if ( !isset($inputName) ) {
    $inputName = 's';
  }
  if ( !isset($search) ) {
    $search = Input::get($inputName);
  }
?>
<div class="row">
  <div class="title_left">
    <div class="col-md-5 col-sm-5 col-xs-12 form-group">
      @if ($search)
        <h4>Filter by : <span style="background:#DBF500;padding:2px 3px">{{ $search }}</span></h4>
      @endif
    </div>
  </div>
  <div class="title_right">
    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
      <form name="f1" method="get" action="{{ isset($action) ? $action : '' }}">
        <div class="input-group">
          <input type="text" name="{{ $inputName }}" class="form-control" placeholder="{{ isset($placeholder) ? $placeholder : 'Search for...' }}" value="{{ $search }}">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
        </div>
      </form>
    </div>
  </div>
</div>
