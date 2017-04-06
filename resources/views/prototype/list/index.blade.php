@extends('prototype.userLayout')

@section('title')
  List Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
	<h2>LIST</h2>
	<div class="col-sm-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">History</h3>
      </div>
      <div class="panel-body">
			@if(!empty($history))
        @foreach($history as $row)
          <table class="table">
            <tr>
              <td><a href="/media/{{ $row['id'] }}">{{ $row['title'] }}</a></td>
            </tr>
          </table>
        @endforeach
        @endif

      </div>
    </div>
  </div>
	<div class="col-sm-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Favorite</h3>
			</div>
			<div class="panel-body">
				@if(!empty($media))
				@foreach($media as $row)
					<table class="table">
						<tr>
							<td><a href="/media/{{ $row->id }}">{{ $row->title }}</a></td>
						</tr>
					</table>
				@endforeach
				@endif
			</div>
		</div>	
	</div>
@stop
