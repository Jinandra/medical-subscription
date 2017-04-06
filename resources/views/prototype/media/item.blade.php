@extends('prototype.userLayout')

@section('title')
  User Home Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
	<h2></h2>
	<br/>
	<div class="row">
		<div class="col-sm-7 col-sm-offset-2">
			<div class="thumbnail">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $media[0]->youtubeID }}?showinfo=0" frameborder="0" allowfullscreen></iframe>
				<div class="caption">
					<a class="btn btn-default btn-lg pull-right" href="{{ url('/bundle/'.$media[0]->id.'/add') }}" role="button">	
						<span class="glyphicon glyphicon-bold" aria-hidden="true"></span>
					</a>	
					<button type="button" class="btn btn-default btn-lg pull-right">
						<span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ $media[0]->description }}"></span>
          </button>
					<button type="button" class="btn btn-default btn-lg pull-right">
						<a href="/user/{{ $media[0]->id }}/fav">
						@if($media[0]->fav)
						<span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
						@else
						<span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>
						@endif
          </a>
					</button>
					<button type="button" class="btn btn-default btn-lg pull-right">
            @if($media[0]->like)
						<a href="/media/{{ $media[0]->id }}/dislike">
						<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
          	</a>
						@else
						<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
						@endif
					</button>
					<button type="button" class="btn btn-default btn-lg pull-right">
						@if($media[0]->dislike)
						<a href="/media/{{ $media[0]->id }}/like">
						<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
						</a>
						@else
						<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
						@endif
					</button>
					<p><a href="/media/{{ $media[0]->id }}">{{ $media[0]->title }}</a>
					<br/>{{ $media[0]->screen_name }}
				</div>
			</div>
		</div>
	</div>
@stop
