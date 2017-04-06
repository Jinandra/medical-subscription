@extends('prototype.userLayout')

@section('title')
	User Home Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
	<br/>
	<div class"row">
    <div class="col-sm-10">
			<h4>Featured</h4>
		</div>
		<div class="col-sm-2">
			<a class="btn btn-success pull-right" href="{{ url('/bundle/view') }}" role="button">CREATE BUNDLE</a>
		</div>
	</div>
	<br/>
  <div class="row placeholders">
 		@foreach($media as $row)     
			<div class="col-sm-3">
        <div class="thumbnail">
          <iframe style="width: 195px; height: 113px" src="https://www.youtube.com/embed/{{ $row->youtubeID  }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
          <div class="caption">
						<a class="btn btn-default btn-xs pull-right" href="{{ url('/bundle/'.$row->id.'/add') }}" role="button">            			<span class="glyphicon glyphicon-bold" aria-hidden="true"></span>
          	</a> 
						<button type="button" class="btn-xs btn btn-default pull-right">
							<span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
						</button>
						<button type="button" class="btn-xs btn btn-default pull-right">
                        <a href="{{ url('/user/'.$row->id.'/fav') }}">
						@if($row->fav)
						<span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
						@else
						<span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>
						@endif
						</a>
						</button>
            <p><a href="{{ url('/media/'.$row->id) }}">{{ $row->title }}</a>
            <br/>{{ $row->screen_name }}
            </p>
          </div>
        </div>
      </div>
    @endforeach
  </div>
      
	<h4>Most Popular 1 Week</h4>
  <div class="row">
  	@foreach($media as $row)
      <div class="col-sm-3">
        <div class="thumbnail">
          <iframe style="width: 195px; height: 113px" src="https://www.youtube.com/embed/{{ $row->youtubeID  }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
          <div class="caption">
          	<button type="button" class="btn btn-default btn-xs pull-right">                  <span class="glyphicon glyphicon-bold" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn-xs btn btn-default pull-right">
              <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
            </button>
            <button type="button" class="btn-xs btn btn-default pull-right">
            <a href="{{ url('/user/'.$row->id.'/fav') }}">
            @if($row->fav)            <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
            @else
            <span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>
            @endif
            </a>
            </button>
            <p><a href="{{ url('/media/'.$row->id) }}">{{ $row->title }}</a>
            <br/>{{ $row->screen_name }}
            </p>
					</div>
        </div>
      </div>
    @endforeach
	</div>

	<h4>Most Popular 1 Month</h4>
  <div class="row">
  	@foreach($media as $row)
      <div class="col-sm-3">
        <div class="thumbnail">
          <iframe style="width: 195px; height: 113px" src="https://www.youtube.com/embed/{{ $row->youtubeID  }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
          <div class="caption">
          	<button type="button" class="btn btn-default btn-xs pull-right">                  <span class="glyphicon glyphicon-bold" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn-xs btn btn-default pull-right">
              <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $row->description }}"></span>
            </button>
            <button type="button" class="btn-xs btn btn-default pull-right">
            <a href="{{ url('/user/'.$row->id.'/fav') }}">
            @if($row->fav)            <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
            @else
            <span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>
            @endif
            </a>
            </button>
            <p><a href="{{ url('/media/'.$row->id) }}">{{ $row->title }}</a>
            <br/>{{ $row->screen_name }}
            </p>
					</div>
        </div>
      </div>
    @endforeach
	</div>
@stop
