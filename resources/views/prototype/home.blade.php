@extends('prototype.layout')

@section('title')
	Home Page
@stop

@section('content')
<br/><br/><br/><br/><br/>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3 col-md-offset-1">
			<h4>Featured</h4>
		</div>
	</div>
	<div class="row">
		<?php $i = 0; ?>
		@foreach($featured as $row)
			@if ($i == 0)
			<div class="col-sm-2 col-md-offset-1">
			@else
			<div class="col-sm-2">
			@endif
				<div class="thumbnail">
					<iframe style="width: 195px; height: 113px" src="https://www.youtube.com/embed/{{ $row->youtubeID  }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
					<div class="caption">
						<span class="glyphicon glyphicon-info-sign pull-right" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
						<p>{{ $row->title }}
						<br/>{{ $row->screen_name }}
						</p>
					</div>
				</div>
			</div>
			<?php $i++; ?>
		@endforeach
	</div>

	<div class="row">
    <div class="col-md-3 col-md-offset-1">
      <h4>Most Popular 1 week</h4>
    </div>
  </div>
	<div class="row">
    <?php $i = 0; ?>
    @foreach($pop1weeks as $row)
      @if ($i == 0)
      <div class="col-sm-2 col-md-offset-1">
      @else
      <div class="col-sm-2">
      @endif
        <div class="thumbnail">
          <iframe style="width: 195px; height: 113px" src="https://www.youtube.com/embed/{{ $row->youtubeID  }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
          <div class="caption">
            <span class="glyphicon glyphicon-info-sign pull-right" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
						<p>{{ $row->title }}
            <br/>{{ $row->screen_name }}
            </p>
          </div>
        </div>
      </div>
      <?php $i++; ?>
    @endforeach
  </div>

	<div class="row">
    <div class="col-md-2 col-md-offset-1">
      <h4>Most Popular 1 Month</h4>
    </div>
  </div>
	<div class="row">
    <?php $i = 0; ?>
    @foreach($pop1month as $row)
      @if ($i == 0)
      <div class="col-sm-2 col-md-offset-1">
      @else
      <div class="col-sm-2">
      @endif
        <div class="thumbnail">
          <iframe style="width: 195px; height: 113px" src="https://www.youtube.com/embed/{{ $row->youtubeID  }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
          <div class="caption">
            <span class="glyphicon glyphicon-info-sign pull-right" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
						<p>{{ $row->title }}
            <br/>{{ $row->screen_name }}
            </p>
          </div>
        </div>
      </div>
      <?php $i++; ?>
    @endforeach
  </div>
</div>
@stop
