@extends('prototype.userLayout')

@section('title')
  Bundle Page
@stop

@section('sidebar')
  @include('prototype.sidebar')
@stop

@section('content')
	<div class="col-sm-10">
		<h2>Bundle Page</h2>
		<form class="form-inline">
			<button type="button" class="btn btn-default">Search UCode</button>
			<div class="form-group">
				<input type="text" class="form-control" id="exampleInputName2" placeholder="Type UCode Here">
			</div>
		</form>
		<br/>
    <table class="table table-bordered" style="width:auto">
			<tr>
				<th>UCode Search Results</th>
				<th>Date Created</th>
				<th>Date Accessed</th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>

		<button type="button">DELETE</button>
		<button type="button">BUNDLE</button>
		<br/><br/>
		<table class="table table-bordered" style="width: auto;">
      <tr>
        <th>UCode</th>
        <th>Date Created</th>
        <th>Date Updated</th>
      </tr>
		@foreach($ucodes as $row)
			<tr>
				<td>
							<input type="checkbox" value="{{ $row->ucode }}">
							{{ $row->ucode }}
				</td>
				<td>{{ $row->created_at }}</td>
				<td>{{ $row->updated_at }}</td>
			</tr>
		@endforeach
		</table>
	</div>
@stop
