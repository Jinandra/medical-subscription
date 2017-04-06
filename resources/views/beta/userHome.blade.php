@extends('beta.userLayout')


@section('content')
<div class="col-sm-9 col-md-10 col-xs-9 right-column">
  <div class="content">
    <div class="headings nomarge">
      <h1 class="dib">Popular</h1>
      @include('beta.partials.media.filterPopularity')
      <?php /*  ?>
      @if(Auth::check())
        @include('beta.partials.media.filterCategory', ['categoryData' => isset($categoryData)? $categoryData : array()])
      @endif
      <?php /* */ ?>
    </div>
    <div class="container" id="ucodeAjax">
      @include('beta.partials.media.userHomeList', ['medias' => isset($medias) ? $medias : array()])
    </div>
  </div>
  <?php /*?><a href="" class="more">Watch more</a><?php */?>
</div>

@stop
